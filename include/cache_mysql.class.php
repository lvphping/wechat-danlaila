<?php
// +----------------------------------------------------------------------
// | phpWeChat MySql缓存操作类 Last modified 2016-04-06 19:04
// +----------------------------------------------------------------------
// | Copyright (c) 2009-2016 phpWeChat http://www.phpwechat.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 骑马的少年 <phpwechat@126.com> <http://www.phpwechat.com>
// +----------------------------------------------------------------------
namespace phpWeChat;

class Cache
{
	static private $mCacheTable='cache';

	static public function set($name, $value, $ttl = 0)
    {
		global $PW;

		$ttl=intval($ttl)?intval($ttl):$PW['cache_ttl'];
		$deptb=get_hash(strlen($name),$PW['cache_mysql_db'])+1;

		$name=pw_md5($name);
		$expire=CLIENT_TIME+$ttl;

		$value=addslashes(var_export(pw_addslashes($value),true));
		
		MySql::query("REPLACE INTO ".DB_PRE.self::$mCacheTable."_".$deptb."(`name`,`value`,`expire`) VALUES('{$name}','{$value}',{$expire})",true);
		
		return true;
	}

	static public function get($name)
	{
		global $PW;
		$deptb=get_hash(strlen($name),$PW['cache_mysql_db'])+1;

		$r=MySql::fetchOne("SELECT `value`,`expire` FROM ".DB_PRE.self::$mCacheTable."_".$deptb." WHERE `name`='".pw_md5($name)."'");
		
		if($r)
		{
			if(CLIENT_TIME > $r['expire'])
			{
				self::rm($name);
			}
			return pw_stripslashes(string2array($r['value']));
		}
		else
		{
			return '';
		}

		return '';
	}

	static public function rm($name)
    {
		global $PW;
		$deptb=get_hash(strlen($name),$PW['cache_mysql_db'])+1;

		$name=pw_md5($name);
		return MySql::query("DELETE FROM `".DB_PRE.self::$mCacheTable."_".$deptb."` WHERE `name`='$name' AND `expire`<".CLIENT_TIME,true);
    }

    static public function clear()
    {	
		global $PW;

		rmdirs(SYSTEM_ROOT.'data/cache_page/');

		for($i=1;$i<=$PW['cache_mysql_db'];$i++)
		{
			MySql::query("TRUNCATE TABLE `".DB_PRE.self::$mCacheTable."_".$i."`",true);
		}
		return true;
    }
}