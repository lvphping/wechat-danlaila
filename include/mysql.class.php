<?php
// +----------------------------------------------------------------------
// | phpWeChat MySQL操作类 Last modified 2016/7/5 16:14
// +----------------------------------------------------------------------
// | Copyright (c) 2009-2016 phpWeChat http://www.phpwechat.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 骑马的少年 <phpwechat@126.com> <http://www.phpwechat.com>
// +----------------------------------------------------------------------
namespace phpWeChat;

class Mysql
{
	public static $mDbLink;
	public static $mQuery;
	public static $mSql;
	private static $mSearch = array('/union(\s*(\/\*.*\*\/)?\s*)+select/i', '/load_file(\s*(\/\*.*\*\/)?\s*)+\(/i', '/into(\s*(\/\*.*\*\/)?\s*)+outfile/i');
	private static $mReplace = array('union &nbsp; select', 'load_file &nbsp; (', 'into &nbsp; outfile');
	private static $mRs;

	public static function connect($hostName,$userName,$userPwd,$dataBase,$charset='utf8')
	{
		self::$mDbLink=mysqli_connect($hostName,$userName,$userPwd,$dataBase);
		(!self::$mDbLink || !is_object(self::$mDbLink)) && fatal_error(mysqli_connect_error(),mysqli_connect_errno());
		mysqli_set_charset(self::$mDbLink,$charset);
		if(self::mysqlVersion()>'5.0.1')
		{
			mysqli_query(self::$mDbLink,"SET character_set_connection=".$charset.",character_set_results=".$charset.",character_set_client=binary,sql_mode = ''",MYSQLI_STORE_RESULT);
			mysqli_query(self::$mDbLink,"SET sql_mode=''",MYSQLI_STORE_RESULT);
		}
		return self::$mDbLink;
	}

	public static function query($sql)
	{
		self::$mRs=mysqli_query(self::$mDbLink,$sql,MYSQLI_STORE_RESULT); //当服务器内存过小时 ，用 MYSQLI_USE_RESULT 代替 2016/7/5

		if(!self::$mRs)
		{
			fatal_error(mysqli_error(self::$mDbLink),mysqli_errno(self::$mDbLink));
		}

		self::$mQuery++;
		self::$mSql=$sql;
		return self::$mRs;
	}

	public static function getLastSql() //获取最后一条执行的SQL语句
	{
		return self::$mSql;
	}

	public static function getMaxfield($filed='id',$table='')
	{
		$r=self::fetchOne("SELECT {$table}.{$filed} FROM {$table} ORDER BY {$table}.`{$filed}` DESC LIMIT 0,1");
		return $r[$filed];
	}

	
	public static function getCount($table)
	{
		$r=self::fetchOne("SELECT COUNT(*) AS ct FROM {$table}");
		return $r['ct'];
	}

	public static function getMinfield($filed='id',$table)
	{
		$r=self::fetchOne("SELECT {$table}.{$filed} FROM {$table} ORDER BY {$table}.`{$filed}` ASC LIMIT 0,1");
		return $r[$filed];
	}
	
	public static function fetchOne($sql)
	{
		self::$mRs=self::query($sql);
		$data=filter_pass(mysqli_fetch_assoc(self::$mRs));
		mysqli_free_result(self::$mRs);
		return $data;
	}

	public static function fetchAll($sql)
	{
		self::$mRs=self::query($sql);
		$result=array();
		while($rows=mysqli_fetch_assoc(self::$mRs))
		{
			$result[]=filter_pass($rows);
		}
		
		mysqli_free_result(self::$mRs);
		return $result; 
	}

	public static function lastInsertId()
	{
		if(($insertid=mysqli_insert_id(self::$mDbLink))>0)
		{
			return $insertid;
		}
		else //如果 AUTO_INCREMENT 的列的类型是 BIGINT，则 mysql_insert_id() 返回的值将不正确.
		{
			$result=self::fetchOne('select LAST_INSERT_ID() as insertId');
			return $result['insertId'];
		}
	}

	public static function insert($tbName,$vArray,$replace=false,$isDelay=false)
	{
		$vArray=self::escape($vArray);
		$tb_fields=self::getFields($tbName);
		
		foreach($vArray as $key => $value)
		{
			if(in_array($key,$tb_fields))
			{
				$fileds[]='`'.$key.'`';
				$values[]=is_string($value)?'\''.$value.'\'':$value;
			}
		}

		if($fileds)
		{
			$fileds=implode(',',$fileds);
			$fileds=str_replace('\'','`',$fileds);
			$values=implode(',',$values);
			$sql=$replace?"replace ".($isDelay?'DELAYED':'')." into {$tbName}({$fileds}) values ({$values})":"insert into {$tbName}({$fileds}) values ({$values})";

			self::query($sql);
			return $isDelay?true:self::lastInsertId();
		}
		else return false;
	}

	public static function update($tbName, $array, $where = '')
	{
		$array=self::escape($array);
		if($where)
		{
			$tb_fields=self::getFields($tbName);
			
			$sql = '';
			foreach($array as $k=>$v)
			{
				if(in_array($k,$tb_fields))
				{
					$k=str_replace('\'','',$k);
					$sql .= ", `$k`='$v'";
				}
			}
			$sql = substr($sql, 1);
			
			if($sql)
			{
				$sql = "UPDATE $tbName SET $sql WHERE $where";
			}
			else 
			{
				return true;
			}
		}
		else
		{
			$sql = "REPLACE INTO $tbName(`".implode('`,`', array_keys($array))."`) VALUES('".implode("','", $array)."')";
		}

		return self::query($sql);
	}
	
	public static function mysqlDelete($tbName,$idArray,$filedName='id')
	{
		$idwhere=is_array($idArray)?implode(',',$idArray):intval($idArray);
		$where=is_array($idArray)?"{$tbName}.{$filedName} in ({$idwhere})":" {$tbName}.{$filedName}={$idwhere}";
		return self::query("delete from {$tbName} where {$where}");
	}

	public static function getFields($table)
	{
		$fields=array();
		$result=self::fetchAll("SHOW COLUMNS FROM {$table}");
		foreach($result as $val)
		{
			$fields[]=$val['Field'];
		}
		return $fields;
	}

	public static function getTableStatus($dataBase)
	{
		$status=array();
		$r=self::fetchAll("SHOW TABLE STATUS FROM `".$dataBase."`"); /////// SHOW TABLE STATUS的性质与SHOW TABLE类似，不过，可以提供每个表的大量信息。
		foreach($r as $v)
		{
			$status[]=$v;
		}
		return $status;
	}

	public static function getOneTableStatus($table)
	{
		return self::fetchOne("SHOW TABLE STATUS LIKE '$table'");
	}

	public static function showCreateSql($table)
	{
		$tables=self::getTables();

		if(in_array($table,$tables))
		{
			$r=self::fetchOne("SHOW CREATE TABLE `$table`");
			return $r['Create Table'];
		}
		else
		{
			return '';
		}
	}

	public static function createFields($tbName,$fieldName,$size=0,$type='VARCHAR')
	{		
		if($size)
		{
			self::query("ALTER TABLE {$tbName} ADD `$fieldName` {$type}( {$size} )  NOT NULL");
		}
		else 
		{
			self::query("ALTER TABLE {$tbName} ADD `$fieldName` MEDIUMTEXT  NOT NULL");
		}
		return true;
	}

	public static function getTables()
	{
		$tables=array();
		$r=self::fetchAll("SHOW TABLES");
		foreach($r as $v)
		{
			foreach($v as $v_)
			{
				$tables[]=$v_;
			}
		}
		return $tables;
	}

	public static function escape($str)
	{
		if(!is_array($str)) 
		{
			return str_replace(array('\n', '\r'), array(chr(10), chr(13)),mysqli_real_escape_string(self::$mDbLink,preg_replace(self::$mSearch,self::$mReplace, $str)));
		}

		foreach($str as $key=>$val) 
		{
			$str[$key] = self::escape($val);
		}

		return $str;
	}

	public static function dropTable($tbName)
	{
		return self::query("DROP TABLE IF EXISTS {$tbName}");
	}

	public static function mysqlVersion()
	{
		return mysqli_get_server_info(self::$mDbLink);
	}

	public static function mysqlClose()
	{
		if(self::$mDbLink && is_object(self::$mDbLink))
		{
			return mysqli_close(self::$mDbLink);
		}
		return true;
	}

	public static function createIndexTable()
	{
		global $PW;
		
		$index_tables=self::getTables();

		for($i=1;$i<=$PW['cache_mysql_db'];$i++)
		{
			if(!in_array(DB_PRE.'cache_'.$i,$index_tables))
			{
				self::query("CREATE TABLE `".DB_PRE."cache_".$i."`(`name` char(16) NOT NULL,`value` text NOT NULL,`expire` char(10) NOT NULL,UNIQUE KEY `name` (`name`)) ENGINE=MyISAM DEFAULT CHARSET=utf8");
			}
		}

		return true;
	}
}
?>