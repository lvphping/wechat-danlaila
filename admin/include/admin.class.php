<?php
// +----------------------------------------------------------------------
// | phpWeChat 管理员操作类 Last modified 2016-03-24 17:17
// +----------------------------------------------------------------------
// | Copyright (c) 2009-2016 phpWeChat http://www.phpwechat.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 骑马的少年 <phpwechat@126.com> <http://www.phpwechat.com>
// +----------------------------------------------------------------------
namespace Admin;
use phpWeChat\DataList;
use phpWeChat\MySql;

class Admin
{
	private static $mAdminTable='admin';
	private static $mAdminRoleTable='admin_role';
	private static $mAdminLogTable='admin_log';
	public static $mPageString='';

	public static function adminList($roleid=0,$pagesize=10) 
	{
		global $_userid,$_roleid;

		$roleid=intval($roleid);
		$pagesize=intval($pagesize);
		
		if($_roleid==-1)
		{
			$where=$roleid?'roleid='.$roleid:'1';
		}
		else
		{
			$where='userid='.$_userid;
		}
		
		$orderby='userid ASC';

		$result=DataList::getList(DB_PRE.self::$mAdminTable,$where,$orderby,max(isset($_GET['page'])?intval($_GET['page']):1,1),intval($pagesize),0,'right');

		self::$mPageString=DataList::$mPageString;

		return $result;
	}

	public static function adminNoLimitList($roleid=0) 
	{
		return MySql::fetchAll("SELECT * FROM `".DB_PRE.self::$mAdminTable."` WHERE	`roleid`=".intval($roleid)."  ORDER BY `userid` ASC");
	}

	public static function adminDelete($userid)
	{
		global $_roleid,$_userid;

		$userid=intval($userid);

		if($_roleid!=-1 || $_userid==$userid)
		{
			return -999;
		}

		return MySql::mysqlDelete(DB_PRE.self::$mAdminTable,$userid,'userid');
	}

	public static function adminEdit($info,$userid=0)
	{
		global $_roleid;

		$userid=$_roleid==-1?intval($userid):$_userid;

		if(!$info['password'])
		{
			unset($info['password']);
		}
		else
		{
			$info['password']=md5($info['password']);
		}

		$r=self::getAdminByUserName($info['username']);

		if($r && $r['userid']!=$userid)
		{
			return -888;
		}

		return MySql::update(DB_PRE.self::$mAdminTable,$info,'userid='.$userid);
	}

	public static function adminSetStatus($userid,$status)
	{
		global $_roleid,$_userid;
		
		if($_roleid!=-1 || $_userid==$userid)
		{
			return -999;
		}

		return MySql::update(DB_PRE.self::$mAdminTable,array('status'=>$status),'userid='.intval($userid));
	}

	public static function adminAdd($info)
	{
		global $_roleid;
		
		if($_roleid!=-1)
		{
			return -999;
		}

		$info['password']=md5($info['password']);
		$info['lastlogintime']=CLIENT_TIME;

		$r=self::getAdminByUserName($info['username']);

		if($r)
		{
			return -888;
		}

		return MySql::insert(DB_PRE.self::$mAdminTable,$info,true);
	}

	public static function roleList()
	{
		return MySql::fetchAll("SELECT * FROM `".DB_PRE.self::$mAdminRoleTable."` ORDER BY `roleid` ASC");
	}

	public static function getRole($roleid,$f='') 
	{
		$r=MySql::fetchOne("SELECT * FROM `".DB_PRE.self::$mAdminRoleTable."` WHERE `".DB_PRE.self::$mAdminRoleTable."`.`roleid`=".intval($roleid));
	
		if(!$f)
		{
			return $r;
		}

		return isset($r[$f])?$r[$f]:$r;
	}

	public static function roleAdd($info)
	{
		return MySql::insert(DB_PRE.self::$mAdminRoleTable,$info,true);
	}

	public static function roleEdit($roleids,$names,$privileges)
	{
		$roleids=array_map('intval',$roleids);

		foreach($roleids as $roleid)
		{
			$info=array();

			$info['name']=$names[$roleid];
			$info['privileges']=json_encode($privileges[$roleid]);

			MySql::update(DB_PRE.self::$mAdminRoleTable,$info,'roleid='.$roleid);
		}

		return true;
	}

	public static function roleDelete($roleid)
	{
		global $_roleid;

		$roleid=intval($roleid);

		if($_roleid!=-1)
		{
			return -999;
		}
		
		if(self::getAdminByRoleId($roleid))
		{
			return -777;
		}

		return MySql::mysqlDelete(DB_PRE.self::$mAdminRoleTable,$roleid,'roleid');
	}

	/**
		getAdmin($userid) $userid后台用户ID 
		功能：获取管理员信息
	*/
	public static function getAdmin($userid)
	{
		return MySql::fetchOne("SELECT * FROM `".DB_PRE.self::$mAdminTable."` WHERE `userid`=".intval($userid));
	}

	public static function getAdminByUserName($username)
	{
		return MySql::fetchOne("SELECT * FROM `".DB_PRE.self::$mAdminTable."` WHERE `username`='".str_callback_w($username)."'");
	}

	public static function getAdminByRoleId($roleid)
	{
		return MySql::fetchOne("SELECT * FROM `".DB_PRE.self::$mAdminTable."` WHERE `roleid`=".intval($roleid));
	}
	
	/**
		adminLogin($username,$password) $username后台用户名 $password后台用户密码
		功能：后台管理登陆检查 
		返回：
		-1：用户名不存在
		-2：用户密码不正确
		-3：管理账户被锁定
		成功返回管理员用户信息数组
	*/
	public static function adminLogin($username,$password)
	{
		$username=str_callback_w($username);
		$password=str_callback_w($password);

		if(!$username)
		{
			self::adminLoginLog(0,'用户名未输入');
			return -1;
		}

		if(!$password)
		{
			self::adminLoginLog(0,'用户密码未输入');
			return -2;
		}

		$r=MySql::fetchOne("SELECT * FROM `".DB_PRE.self::$mAdminTable."` WHERE `username`='$username'");

		if(!$r)
		{
			self::adminLoginLog(0,'用户名不存在('.$username.')');
			return -1;
		}

		if($r['password']!=md5($password))
		{
			self::adminLoginLog($r['userid'],'用户密码不正确('.$password.')');
			return -2;
		}

		if(!$r['status'])
		{
			self::adminLoginLog($r['userid'],'管理账号被锁定');
			return -3;
		}
		
		MySql::update(DB_PRE.self::$mAdminTable,array('lastlogintime'=>CLIENT_TIME),'userid='.$r['userid']);
		self::adminLoginLog($r['userid'],'登陆成功');
		return $r;
	}
	
	/**
		adminLogout()
		功能：后台管理登出
	*/
	public static function adminLogout()
	{
		if(isset($_SESSION['adminuserid']))
		{
			self::adminLoginLog($_SESSION['adminuserid'],'登出成功');
			$_SESSION['adminuserid']=0;
			unset($_SESSION['adminuserid']);
		}

		return true;
	}

	/**
		adminLoginLog($userid,$event) $userid后台用户ID  $event登陆事件
		功能：后台管理登陆日志
	*/
	public static function adminLoginLog($userid,$event='')
	{
		return ADMIN_LOG?MySql::insert(DB_PRE.self::$mAdminLogTable,array('event'=>$event,'userid'=>$userid,'logdate'=>CLIENT_TIME,'logip'=>CLIENT_IP)):true;
	}
}
?>