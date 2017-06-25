<?php
// +----------------------------------------------------------------------
// | phpWeChat Ip操作类 Last modified 2016 -03 -24 10:46
// +----------------------------------------------------------------------
// | Copyright (c) 2009-2016 phpWeChat http://www.phpwechat.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 骑马的少年 <phpwechat@126.com> <http://www.phpwechat.com>
// +----------------------------------------------------------------------
namespace phpWeChat;

class Ip
{
	private static $mDbTable='ip';
	
	/**
		函数：public static function ip2area($ip='')  $ip 指具体IP地址
		功能：获取IP对应的实际地址
	*/

	public static function ip2area($ip='') 
	{
		if(!self::isIp($ip)) 
		{
			return 'Unknown';
		}
		
		$ipdata=$ip2area='';
		$ipdata=MySql::fetchOne("SELECT * FROM `".DB_PRE.self::$mDbTable."` WHERE `ip`='$ip'");
		if($ipdata)
		{
			$ip2area=$ipdata['city'].' '.$ipdata['isp'];
		}
		else
		{
			$ipcon=strip_tags(file_get_contents('http://www.ip138.com/ips138.asp?ip='.$ip.'&action=2'),'<li>');
			preg_match('/本站主数据：(.*?)<\/li>/is',iconv("GBK","UTF-8//IGNORE",$ipcon),$matches);

			$ip2area=explode(' ',$matches[1]);
			MySql::insert(DB_PRE.self::$mDbTable,array('ip'=>$ip,'city'=>$ip2area[0],'isp'=>$ip2area[sizeof($ip2area)-1],'lastmodified'=>CLIENT_TIME),true);
			$ip2area=$ip2area[0].' '.$ip2area[sizeof($ip2area)-1];
		}
		return $ip2area;
	}

	/**
		函数：public static function isIp($ip='')  $ip 指具体IP地址
		功能：判断 $ip 是否是有效IP
	*/

	public static function isIp($ip='')
	{
		$ip2arr = explode('.',$ip);
		for($i=0;$i<count($ip2arr);$i++)
		{  
			if($ip2arr[$i]>255)
			{  
				return false;
			}  
		}  
		return preg_match('/^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}$/',$ip);  
	}

	/**
		函数：public static function hideIp($ip,$key)  $ip 指具体IP地址， $key 指IP地址的第几段
		功能：判断 $ip 是否是有效IP
	*/
	public static function hideIp($ip='',$key=4)
	{
		if(!self::isIp($ip) || $key>4)
		{
			return 'Unkonwn';
		}

		$ip2arr=explode(".",$ip);
		$ip2arr[$key-1]='*';
		return implode(".",$ip2arr);
	}


	/**
		函数：public static function getIp($ishide)  $ishide 为 0时表示不隐藏IP任何字段，为1时，隐藏IP部分字段作为隐私保护
		功能：获取IP
	*/

	public static function getIp($ishide=0)
	{
		$ip='Unknown Ip';

		if(!empty($_SERVER['HTTP_CLIENT_IP']))
		{
			$ip=self::isIp($_SERVER['HTTP_CLIENT_IP'])?$_SERVER['HTTP_CLIENT_IP']:$ip;
		}
		elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
		{
			$ip=self::isIp($_SERVER['HTTP_X_FORWARDED_FOR'])?$_SERVER['HTTP_X_FORWARDED_FOR']:$ip;
		}
		else
		{
			$ip=self::isIp($_SERVER['REMOTE_ADDR'])?$_SERVER['REMOTE_ADDR']:$ip;
		}

		return $ishide && self::isIp($ip)?self::hideIp($ip,3):$ip;
	}
}
?>