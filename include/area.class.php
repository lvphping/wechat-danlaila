<?php
// +----------------------------------------------------------------------
// | phpWeChat 地区操作类 Last modified 2016-04-06 11:49
// +----------------------------------------------------------------------
// | Copyright (c) 2009-2016 phpWeChat http://www.phpwechat.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 骑马的少年 <phpwechat@126.com> <http://www.phpwechat.com>
// +----------------------------------------------------------------------
namespace phpWeChat;

class Area
{
	private static $mProTable='province';
	private static $mCityTable='city';
	private static $mAreaTable='area';
	
	/**
	 *	省份操作方法
	 *
	 */
	public static function proList() 
	{
		return MySql::fetchAll("SELECT * FROM `".DB_PRE.self::$mProTable."` ORDER BY `".DB_PRE.self::$mProTable."`.`id` ASC");
	}

	public static function proDelete($ids=array())
	{
		$ids=is_array($ids)?array_map('intval',$ids):array(intval($ids));
		return MySql::mysqlDelete(DB_PRE.self::$mProTable,$ids);
	}

	public static function proRefresh($ids,$names)
	{
		$ids=is_array($ids)?array_map('intval',$ids):array(intval($ids));
		foreach($ids as $id)
		{
			MySql::update(DB_PRE.self::$mProTable,array('name'=>$names[intval($id)]),'id='.intval($id));
		}

		return true;
	}

	public static function proAdd($info)
	{
		if(!trim($info['name']))
		{
			return -1;
		}

		return MySql::insert(DB_PRE.self::$mProTable,pw_strip_tags($info));
	}

	public static function getPro($id,$f='') 
	{
		$r=MySql::fetchOne("SELECT * FROM `".DB_PRE.self::$mProTable."` WHERE `".DB_PRE.self::$mProTable."`.`id`=".intval($id));
	
		if(!$f)
		{
			return $r;
		}

		return isset($r[$f])?$r[$f]:$r;
	}

	/**
	 *	城市操作方法
	 *
	 */
	public static function cityList($proid=0) 
	{	
		$proid=intval($proid);
		if(!$proid)
		{
			return MySql::fetchAll("SELECT * FROM `".DB_PRE.self::$mCityTable."` ORDER BY `".DB_PRE.self::$mCityTable."`.`proid` ASC,`".DB_PRE.self::$mCityTable."`.`id` ASC");
		}
		else
		{
			return MySql::fetchAll("SELECT * FROM `".DB_PRE.self::$mCityTable."` WHERE `".DB_PRE.self::$mCityTable."`.`proid`=$proid ORDER BY `".DB_PRE.self::$mCityTable."`.`id` ASC");
		}
	}

	public static function getCity($id,$f='') 
	{
		$r=MySql::fetchOne("SELECT * FROM `".DB_PRE.self::$mCityTable."` WHERE `".DB_PRE.self::$mCityTable."`.`id`=".intval($id));
	
		if(!$f)
		{
			return $r;
		}

		return isset($r[$f])?$r[$f]:$r;
	}

	public static function cityAdd($info)
	{
		$info['spell']=preg_replace('/[^a-z0-9_]/i','',$info['spell']);

		if(!$info['spell'])
		{
			return -1;
		}
		
		if(MySql::fetchOne("SELECT * FROM ".DB_PRE.self::$mCityTable." WHERE `spell`='".$info['spell']."'"))
		{
			return -2;
		}

		$info['initial']=strtoupper(substr($info['spell'],0,1));
		return MySql::insert(DB_PRE.self::$mCityTable,pw_strip_tags($info));
	}

	public static function cityEdit($info,$id)
	{
		$info['spell']=preg_replace('/[^a-z0-9_]/i','',$info['spell']);

		if(!$info['spell'])
		{
			return -1;
		}
		
		$r=MySql::fetchOne("SELECT * FROM ".DB_PRE.self::$mCityTable." WHERE `spell`='".$info['spell']."'");

		if($r && $r['id']!=intval($id))
		{
			return -2;
		}

		$info['initial']=strtoupper(substr($info['spell'],0,1));

		return MySql::update(DB_PRE.self::$mCityTable,pw_strip_tags($info),'id='.intval($id));
	}

	public static function cityDelete($ids=array())
	{
		$ids=is_array($ids)?array_map('intval',$ids):array(intval($ids));
		return MySql::mysqlDelete(DB_PRE.self::$mCityTable,$ids);
	}

	/**
	 *	区域操作方法
	 *
	 */
	public static function areaList($cityid=0,$parentid=0) 
	{	
		$cityid=intval($cityid);
		$parentid=intval($parentid);

		if(!$cityid)
		{
			return MySql::fetchAll("SELECT * FROM `".DB_PRE.self::$mAreaTable."` WHERE	`".DB_PRE.self::$mAreaTable."`.`parentid`=$parentid ORDER BY `".DB_PRE.self::$mAreaTable."`.`id` ASC");
		}
		else
		{
			return MySql::fetchAll("SELECT * FROM `".DB_PRE.self::$mAreaTable."` WHERE `".DB_PRE.self::$mAreaTable."`.`parentid`=$parentid AND `".DB_PRE.self::$mAreaTable."`.`cityid`=$cityid ORDER BY `".DB_PRE.self::$mAreaTable."`.`id` ASC");
		}
	}

	public static function getArea($id,$f='') 
	{
		$r=MySql::fetchOne("SELECT * FROM `".DB_PRE.self::$mAreaTable."` WHERE `".DB_PRE.self::$mAreaTable."`.`id`=".intval($id));
	
		if(!$f)
		{
			return $r;
		}

		return isset($r[$f])?$r[$f]:$r;
	}

	public static function areaAdd($info)
	{
		$info['cityid']=intval($info['cityid']);
		if(!$info['cityid'])
		{
			return -1;
		}

		$info['proid']=self::getCity($info['cityid'],'proid');
		return MySql::insert(DB_PRE.self::$mAreaTable,pw_strip_tags($info));
	}

	public static function areaEdit($info,$id)
	{
		return MySql::update(DB_PRE.self::$mAreaTable,pw_strip_tags($info),'id='.intval($id));
	}

	public static function areaDelete($ids=array())
	{
		$ids=is_array($ids)?array_map('intval',$ids):array(intval($ids));
		return MySql::mysqlDelete(DB_PRE.self::$mAreaTable,$ids);
	}

}
?>