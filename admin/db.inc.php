<?php
// +----------------------------------------------------------------------
// | phpWeChat 数据库管理入口文件 Last modified 22016-04-11 21:44
// +----------------------------------------------------------------------
// | Copyright (c) 2009-2016 phpWeChat http://www.phpwechat.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 骑马的少年 <phpwechat@126.com> <http://www.phpwechat.com>
// +----------------------------------------------------------------------
use phpWeChat\dbBak;
use phpWeChat\MySql;

!defined('IN_MANAGE') && exit('Access Denied!');
$action=@return_edefualt(str_callback_w($_GET['action']),'export');

include_once PW_ROOT.'include/dbbak.class.php';
switch($action)
{
	case 'export':
		if(isset($dosubmit)) 
		{
			if((!isset($name) ||!$name) && (!isset($page) || !$page))
			{
				operation_tips('请选择要备份的数据表！','','error');
			}

			if(isset($name) && $name)
			{
				cache_write('bakup_table.cache_tmp'.$_userid.'.php',$name,PW_ROOT.'data/tmp/');
			}
			else
			{
				$name=cache_read('bakup_table.cache_tmp'.$_userid.'.php',PW_ROOT.'data/tmp/');
			}

			$sql='';
			$tableid=isset($tableid)?$tableid-1:0;
			$tablecounts=count($name);
			$start=isset($start)?intval($start):0;

			for($i=$tableid;$i<$tablecounts && strlen($sql)<$sizelimit*1000;$i++)
			{				
				if(!$start)
				{
					$r=MySql::fetchOne("SHOW CREATE TABLE `{$name[$i]}`"); 
					$sql.="DROP TABLE IF EXISTS `{$name[$i]}`;\n";
					$sql.=$r['Create Table'].";\n";
				}

				$numrows=$offset=5;

				while(strlen($sql)<$sizelimit*1000 && $numrows==$offset)  
				{
					if(preg_match('/^'.DB_PRE.'cache_([0-9]+)$/i', $name[$i]) || $names[$i]==DB_PRE.'counts')
					{
						$tmp_r=array();
					}
					else
					{
						$tmp_r=MySql::fetchAll("SELECT * FROM `{$name[$i]}` LIMIT {$start},{$offset}");

						if($tmp_r)foreach($tmp_r as $val_r)
						{
							foreach($val_r as $t_k => $t_v)
							{
								$val_r[$t_k]='\''.mysqli_real_escape_string(MySql::$mDbLink,$t_v).'\'';
							}
							$sql.="REPLACE INTO `{$name[$i]}` VALUES(".implode(',',str_replace(SITE_URL,'#website_url#',$val_r)).");\n";
						}
					}
					$numrows=count($tmp_r);
					$start+=$offset;
					$startrow=$start;
				}
				$start=0;
			}

			if(trim($sql))
			{
				$tableid=$i;
				$page=isset($page)?$page:1;
				$rand=isset($rand)?$rand:mt_rand(10000,99999);	
				@file_put_contents(PW_ROOT.'data/bakup/database_'.strtolower(PHPWECHAT_VERSION).strtolower(PHPWECHAT_RELEASE).'_bakup_'.date('Ymd').'_'.$rand.'_'.$page.'.sql',$sql);
				operation_tips('卷号为'.date('Ymd').'_'.$rand.'_'.$page.'的备份文件写入成功!',ADMIN_FILE.'?mod=&file=db&action=export&dosubmit=1&tableid='.$tableid.'&page='.($page+1).'&rand='.$rand.'&sizelimit='.$sizelimit.'&start='.$startrow);
			}
			else
			{
				cache_delete('bakup_table.cache_tmp'.$_userid.'.php',PW_ROOT.'data/tmp/');
				operation_tips('数据库备份成功!',ADMIN_FILE.'?mod=&file=db&action=export');
			}
		}
		include_once parse_admin_tlp($file.'-'.$action);
		break;
	case 'import':
		if(isset($dosubmit))
		{
			if(isset($mytime) && $mytime && $volume && $no)
			{
				$filepath=PW_ROOT.'data/bakup/database_'.strtolower(PHPWECHAT_VERSION).strtolower(PHPWECHAT_RELEASE).'_bakup_'.$mytime.'_'.$volume.'_'.$no.'.sql';
				if(file_exists($filepath))
				{
					$sqls=file_get_contents($filepath);
					$sqls=explode(";\n",trim($sqls));
					foreach($sqls as $sql)
					{
						MySql::query(str_replace('#website_url#',SITE_URL,$sql),true);
					}
					operation_tips('卷'.$volume.'_'.$no.' 导入成功, 程序自动继续导入...',ADMIN_FILE.'?mod=&file=db&action=import&dosubmit=1&mytime='.$mytime.'&volume='.$volume.'&no='.($no+1));
				}
				else
				{
					operation_tips('数据库还原成功!',ADMIN_FILE.'?mod=&file=db&action=import');
				}
					
			}
			else
			{
				$r=explode('_',$filename);
				if(strtolower($r[1])!=strtolower(PHPWECHAT_VERSION).strtolower(PHPWECHAT_RELEASE))
				{
					operation_tips('数据备份文件与当前系统版本不一致, 无法导入!','','error');
				}
				operation_tips('准备工作结束，开始还原数据...',ADMIN_FILE.'?mod=&file=db&action=import&dosubmit=1&mytime='.$r[3].'&volume='.$r[4].'&no=1');
			}		
		}
		$result=$tarray=array();
		foreach(glob(PW_ROOT.'data/bakup/*.sql') as $k => $v)
		{
			$tarray=explode('_',substr(strrchr($v,'/'),1));
			$tarray=explode('.',$tarray[4]);
			$key=$tarray[0];
			$result[$k]['filename']=substr(strrchr($v,'/'),1);
			$result[$k]['filesize']=round(filesize($v)/1024,2).' KB';
			$result[$k]['mtime']=date('Y-m-d H:i:s',filemtime($v));
			$result[$k]['volume']=$key;
		}

		ksort($result);
		include_once parse_admin_tlp($file.'-'.$action);
		break;
	case 'bakdelete':
		if(!$filename)
		{
			operation_tips('请选择要删除的备份文件!','','error');
		}

		$filename=PW_ROOT.'data/bakup/'.$filename;
		if(is_file($filename))
		{
			@unlink($filename);
			operation_tips('操作成功!');
		}
		else
		{
			operation_tips('操作失败 [-1]!','','error');
		}
		break;
	case 'bakdown':
		$filepath=PW_ROOT.'data/bakup/'.$filename;
		$filesize=sprintf("%u", filesize($filepath));
		$filetype=get_fileext($filename);
		if(ob_get_length() !== false) @ob_end_clean();
		header('Pragma: public');
		header('Last-Modified: '.gmdate('D, d M Y H:i:s') . ' GMT');
		header('Cache-Control: no-store, no-cache, must-revalidate');
		header('Cache-Control: pre-check=0, post-check=0, max-age=0');
		header('Content-Transfer-Encoding: binary');
		header('Content-Encoding: none');
		header('Content-type: '.$filetype);
		header('Content-Disposition: attachment; filename="'.$filename.'"');
		header('Content-length: '.$filesize);
		readfile($filepath);
		exit;
		break;
	case 'repair':
		if(isset($operation))
		{
			$manage=$operation=='repair'?'修复':'优化';
			if(!$name)
			{
				operation_tips('请选择要'.$manage.'的数据表!','','error');
			}
				
			foreach($name as $table)
			{
				MySql::query("{$operation} TABLE `{$table}`");
			}
			operation_tips('数据表'.$manage.'完毕!');
		}
		include_once parse_admin_tlp($file.'-'.$action);
		break;
}
?>