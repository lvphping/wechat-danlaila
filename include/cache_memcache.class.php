<?php
// +----------------------------------------------------------------------
// | phpWeChat MySql缓存操作类 Last modified 2016-04-06 20:08
// +----------------------------------------------------------------------
// | Copyright (c) 2009-2016 phpWeChat http://www.phpwechat.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 骑马的少年 <phpwechat@126.com> <http://www.phpwechat.com>
// +----------------------------------------------------------------------
namespace phpWeChat;

class Cache
{
	static public function get($name)
    {
		global $PW;

		$memhost=explode("\r\n",$PW['memcache_host']);
		$deptb=get_hash(strlen($name),$PW['cache_mysql_db']);
		
		$memObj= new \Memcache;
		$memObj->connect($memhost[$deptb], $PW['memcache_port'], $PW['memcache_timeout']);

        return pw_stripslashes(string2array($memObj->get(pw_md5($name))));
    }

    static public function set($name, $value, $ttl = 0)
    {
		global $PW;

		$ttl=$ttl?$ttl:$PW['cache_ttl'];
		$ttl=intval($ttl);
		$value=var_export(pw_addslashes($value),true);
		
		$memhost=explode("\r\n",$PW['memcache_host']);
		$deptb=get_hash(strlen($name),$PW['cache_mysql_db']);
		
		$memObj= new \Memcache;
		$memObj->connect($memhost[$deptb], $PW['memcache_port'], $PW['memcache_timeout']);

        return $memObj->set(pw_md5($name), $value, 0, $ttl);
    }

    static public function rm($name)
    {
		global $PW;

		$memhost=explode("\r\n",$PW['memcache_host']);
		$deptb=get_hash(strlen($name),$PW['cache_mysql_db']);
		
		$memObj= new \Memcache;
		$memObj->connect($memhost[$deptb], $PW['memcache_port'], $PW['memcache_timeout']);

        return $memObj->delete(pw_md5($name));
    }

    static public function clear($moduleid=0)
    {
		global $PW;

		$memhost=explode("\r\n",$PW['memcache_host']);
		$memObj= new \Memcache;

		foreach($memhost as $host)
		{
			$memObj->connect($host, $PW['memcache_port'], $PW['memcache_timeout']);
			$memObj->flush();
		}
		return true;
    }
}
?>