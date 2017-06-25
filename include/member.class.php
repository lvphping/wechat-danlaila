<?php
// +----------------------------------------------------------------------
// | phpWeChat 会员操作类 Last modified 2016/5/25 21:33
// +----------------------------------------------------------------------
// | Copyright (c) 2009-2016 phpWeChat http://www.phpwechat.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 骑马的少年 <phpwechat@126.com> <http://www.phpwechat.com>
// +----------------------------------------------------------------------
namespace phpWeChat;

class Member
{
	private static $mMemberTable='member';
	private static $mMemberCacheTable='member_cache';
	private static $mMemberLevelTable='member_level';
	private static $mAddressTable='member_address';
	private static $mBlockedAccountTable='member_blocked_account';

	public static $mPageString='';

	public static function load()
	{
		if(!MySql::fetchOne("SELECT * FROM `".DB_PRE.self::$mMemberCacheTable."`"))
		{
			MySql::query("SET SQL_MODE =  'NO_AUTO_VALUE_ON_ZERO'");
			MySql::query("REPLACE INTO  `".DB_PRE.self::$mMemberCacheTable."` SELECT * FROM  `".DB_PRE.self::$mMemberTable."`");
		}
	}

	/*
		会员地址操作
	*/

	public static function addressList() 
	{
		return MySql::fetchAll("SELECT * FROM `".DB_PRE.self::$mAddressTable."` WHERE 1 ORDER BY `default` DESC,`id` ASC");
	}

	public static function addressAdd($info)
	{
		MySql::query("UPDATE ".DB_PRE.self::$mAddressTable." SET `default`=0 WHERE userid=".intval($info['userid']));

		$info['default']=1;
		MySql::insert(DB_PRE.self::$mAddressTable,$info,true);

		return true;
	}

	public static function addressDelete($ids)
	{
		$ids=is_array($ids)?array_map('intval',$ids):array(intval($ids));
		if(!$ids)
		{
			return -1;
		}
		
		return MySql::mysqlDelete(DB_PRE.self::$mAddressTable,$ids,'id');
	}

	public static function addressEdit($info,$id)
	{
		return MySql::update(DB_PRE.self::$mAddressTable,$info,'`id`='.intval($id));
	}
	
	public static function getAddress($id=0,$f='*')
	{
		$r=MySql::fetchOne("SELECT * FROM `".DB_PRE.self::$mAddressTable."` WHERE `id`=".intval($id));

		return $f=='*'?$r:$r[$f];
	}
	
	public static function myAddressList($userid=0) 
	{
		return MySql::fetchAll("SELECT * FROM `".DB_PRE.self::$mAddressTable."` WHERE `userid`=".intval($userid)." ORDER BY `default` DESC,`id` ASC");
	}

	public static function getDefaultAddress($userid=0)
	{
		return MySql::fetchOne("SELECT * FROM `".DB_PRE.self::$mAddressTable."` WHERE `userid`=".intval($userid)." ORDER BY  `default` DESC LIMIT 0,1");
	}

	public static function deleteMyAddress($id,$userid)
	{
		return MySql::query("DELETE FROM ".DB_PRE.self::$mAddressTable." WHERE userid=".intval($userid)." AND id=".intval($id));
	}

	public static function setMyDefaultAddress($id,$userid)
	{
		MySql::query("UPDATE ".DB_PRE.self::$mAddressTable." SET `default`=0 WHERE userid=".intval($userid));
		MySql::query("UPDATE ".DB_PRE.self::$mAddressTable." SET `default`=1 WHERE userid=".intval($userid)." AND id=".intval($id));
		return true;
	}

	/*
		会员等级操作
	*/
	public static function levelList() 
	{
		return MySql::fetchAll("SELECT * FROM `".DB_PRE.self::$mMemberLevelTable."` WHERE 1 ORDER BY `levelid` ASC");
	}

	public static function levelAdd($info)
	{
		return MySql::insert(DB_PRE.self::$mMemberLevelTable,$info,true);
	}

	public static function levelDelete($levelids)
	{
		$levelids=is_array($levelids)?array_map('intval',$levelids):array(intval($levelids));
		if(!$levelids)
		{
			return -1;
		}
		
		return MySql::mysqlDelete(DB_PRE.self::$mMemberLevelTable,$levelids,'levelid');
	}

	public static function levelEdit($info,$levelid)
	{
		return MySql::update(DB_PRE.self::$mMemberLevelTable,$info,'`levelid`='.intval($levelid));
	}
	
	public static function levelGet($levelid=0,$f='*')
	{
		$r=MySql::fetchOne("SELECT * FROM `".DB_PRE.self::$mMemberLevelTable."` WHERE `levelid`=".intval($levelid));

		return $f=='*'?$r:$r[$f];
	}

	/*
		会员操作
	*/
	public static function memberList($username,$pagesize=20)
	{	
		$where='1';
		$where.=$username?' AND username = \''.$username.'\'':'';

		$orderby='`userid` DESC';

		$result=DataList::getList(DB_PRE.self::$mMemberTable,$where,$orderby,max(isset($_GET['page'])?intval($_GET['page']):1,1),intval($pagesize),0,'right');

		self::$mPageString=DataList::$mPageString;

		return $result;
	}

	static public function getUserByUserName($username,$f='*')
	{
		if(!is_username($username) || !trim($username))
		{
			return 0;
		}

		$r=MySql::fetchOne("SELECT * FROM `".DB_PRE.self::$mMemberCacheTable."` WHERE `username`='".trim($username)."'");

		if(!$r)
		{
			$r=MySql::fetchOne("SELECT * FROM `".DB_PRE.self::$mMemberTable."` WHERE `username`='".trim($username)."'");
		}

		if(!$r)
		{
			return 0;
		}

		return $f=='*'?$r:$r[$f];
	}

	static public function getUserByTelephone($telephone,$f='*')
	{
		if(!is_telephone($telephone) || !trim($telephone))
		{
			return 0;
		}

		$r=MySql::fetchOne("SELECT * FROM `".DB_PRE.self::$mMemberCacheTable."` WHERE `telephone`='".trim($telephone)."'");

		if(!$r)
		{
			$r=MySql::fetchOne("SELECT * FROM `".DB_PRE.self::$mMemberTable."` WHERE `telephone`='".trim($telephone)."'");
		}

		if(!$r)
		{
			return 0;
		}

		return $f=='*'?$r:$r[$f];
	}

	static public function getUserByEmail($email,$f='*')
	{
		if(!is_email($email) || !trim($email))
		{
			return 0;
		}

		$r=MySql::fetchOne("SELECT * FROM `".DB_PRE.self::$mMemberCacheTable."` WHERE `email`='".trim($email)."'");

		if(!$r)
		{
			$r=MySql::fetchOne("SELECT * FROM `".DB_PRE.self::$mMemberTable."` WHERE `email`='".trim($email)."'");
		}

		if(!$r)
		{
			return 0;
		}

		return $f=='*'?$r:$r[$f];
	}

	static public function getUserByOpenId($openid,$f='*')
	{
		if(!trim($openid))
		{
			return 0;
		}

		$r=MySql::fetchOne("SELECT * FROM `".DB_PRE.self::$mMemberCacheTable."` WHERE `openid`='".trim($openid)."'");

		if(!$r)
		{
			$r=MySql::fetchOne("SELECT * FROM `".DB_PRE.self::$mMemberTable."` WHERE `openid`='".trim($openid)."'");
		}

		if(!$r)
		{
			return 0;
		}

		return $f=='*'?$r:$r[$f];
	}
	
	static public function getUserByUserId($userid,$f='*')
	{
		if(!trim($userid))
		{
			return 0;
		}

		$r=MySql::fetchOne("SELECT * FROM `".DB_PRE.self::$mMemberCacheTable."` WHERE `userid`=".intval($userid));

		if(!$r)
		{
			$r=MySql::fetchOne("SELECT * FROM `".DB_PRE.self::$mMemberTable."` WHERE `userid`=".intval($userid));
		}

		if(!$r)
		{
			return 0;
		}

		return $f=='*'?$r:$r[$f];
	}

	public static function createRandAccount()
	{
		global $PW,$_userid;

		$info=array();

		if(!$_SESSION['openid'])
		{
			return false;
		}

		if($_userid && $_SESSION['openid'])
		{
			if(WECHAT_WESHOP_DISTRIBUTION_OPEN && WECHAT_WESHOP_AGENT_CONDITION==0 && !$PW['memberlogin']['isagent'])
			{
				self::memUpdate($_userid,array('isagent'=>1));
			}

			if(!$PW['memberlogin']['openid'])
			{
				self::memUpdate($_userid,array('openid'=>$_SESSION['openid']));
			}
			
			return $_userid;
		}

		$data=self::getUserByOpenId($_SESSION['openid']);

		if(!$data)
		{
			$info['username']='u'.CLIENT_TIME.mt_rand(100,999);
			do
			{
				$info['username']='u'.CLIENT_TIME.mt_rand(100,999);
			}while(self::getUserByUserName($info['username']));

			$info['userpwd']=md5(mt_rand(100000,999999));
			$info['regtime']=$info['logintime']=CLIENT_TIME;
			$info['regip']=$info['loginip']=CLIENT_IP;
			$info['openid']=$_SESSION['openid'];
			$info['levelid']=-1;
			$info['parentagent']=intval($_SESSION['invitecode']);

			if(WECHAT_WESHOP_DISTRIBUTION_OPEN && WECHAT_WESHOP_AGENT_CONDITION==0)
			{
				$info['isagent']=1;
			}
			else
			{
				$info['isagent']=0;
			}

			$userid=MySql::insert(DB_PRE.self::$mMemberTable,$info,true);
			$info['userid']=$userid;
			MySql::insert(DB_PRE.self::$mMemberCacheTable,$info,true);

			set_cookie('userid',$userid);
			return $userid;
		}

		return false;
	}

	public static function register($info)
	{
		$info=array_map('trim',$info);

		if(!is_username($info['username']))
		{
			return -1;
		}

		if(self::getUserByUserName($info['username']))
		{
			return -2;
		}

		if(!is_pwd($info['userpwd']))
		{
			return -3;
		}
		
		if($info['email'] && !is_email($info['email']))
		{
			return -4;
		}

		if($info['email'] && self::getUserByEmail($info['email']))
		{
			return -5;
		}

		if($info['telephone'] && !is_telephone($info['telephone']))
		{
			return -6;
		}
		
		if($info['telephone'] && self::getUserByTelephone($info['telephone']))
		{
			return -7;
		}
		
		$info['userpwd']=md5($info['userpwd']);
		$info['regtime']=CLIENT_TIME;
		$info['regip']=CLIENT_IP;

		$userid=MySql::insert(DB_PRE.self::$mMemberTable,$info,true);
		$info['userid']=$userid;
		$info['openid']=$_SESSION['openid'];
		MySql::insert(DB_PRE.self::$mMemberCacheTable,$info,true);
		
		return $userid;
	}

	public static function resetPassword($userid,$password)
	{
		MySql::update(DB_PRE.self::$mMemberTable,array('userpwd'=>md5(trim($password))),'`userid`='.intval($userid));
		MySql::update(DB_PRE.self::$mMemberCacheTable,array('userpwd'=>md5(trim($password))),'`userid`='.intval($userid));
		return intval($userid);
	}

	public static function memUpdate($userid,$info)
	{
		MySql::update(DB_PRE.self::$mMemberTable,$info,'`userid`='.intval($userid));
		MySql::update(DB_PRE.self::$mMemberCacheTable,$info,'`userid`='.intval($userid));
		return intval($userid);
	}

	/*
		会员冻结金额
	*/
	public static function memBlockAccount($userid=0)
	{
		$userid=intval($userid);

		$r=MySql::fetchOne("SELECT SUM(account) AS sum_account FROM `".DB_PRE.self::$mBlockedAccountTable."` WHERE `userid`=$userid");

		return $r['sum_account']?$r['sum_account']:'0.00';
	}
}