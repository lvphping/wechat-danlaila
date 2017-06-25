<?php
// +----------------------------------------------------------------------
// | phpWeChat 模块配置操作类 Last modified 2016-03-27 15:29
// +----------------------------------------------------------------------
// | Copyright (c) 2009-2016 phpWeChat http://www.phpwechat.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 骑马的少年 <phpwechat@126.com> <http://www.phpwechat.com>
// +----------------------------------------------------------------------

namespace phpWeChat;

class Config
{
	private static $mConfigTable='config';
	
	public static function loadConfig()
	{
		$_config=array();

		$r=MySql::fetchAll("SELECT * FROM ".DB_PRE.self::$mConfigTable." WHERE 1");

		foreach($r as $_r)
		{
			$_selfconfig=$_r['config']?array_map('base64_decode',json_decode($_r['config'],true)):array(); //array_map('base64_decode',json_decode($_selfconfig,true))
			if($_selfconfig)
			{
				$_config=array_merge($_config,$_selfconfig);
			}
		}

		return $_config;
	}

	public static function getConfig($mod='system')
	{
		$r=MySql::fetchOne("SELECT * FROM `".DB_PRE.self::$mConfigTable."` WHERE `mod`='".preg_replace('/[^a-z0-9_]/i','',$mod)."'");;
		return $r?array_map('base64_decode',json_decode($r['config'],true)):array();
	}

	public static function setConfig($mod='system',$setting=array())
	{
		$mod=pw_md5($mod);
		$_config=self::getConfig($mod);
		if(!$_config)
		{
			MySql::insert(DB_PRE.self::$mConfigTable,array('mod'=>$mod,'config'=>json_encode(array_map('base64_encode',$setting))),true);
		}
		else
		{
			$setting=array_merge(array_map('addslashes',$_config),$setting);

			MySql::update(DB_PRE.self::$mConfigTable,array('config'=>json_encode(array_map('base64_encode',$setting))),'`mod`=\''.preg_replace('/[^a-z0-9_]/i','',$mod).'\'');
		}

		return true;
	}
}
?>