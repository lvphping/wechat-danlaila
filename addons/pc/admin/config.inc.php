<?php
// +----------------------------------------------------------------------
// | phpWeChat 网站PC端管理配置入口文件 Last modified 2016-04-07 13:20
// +----------------------------------------------------------------------
// | Copyright (c) 2009-2016 phpWeChat http://www.phpwechat.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 骑马的少年 <phpwechat@126.com> <http://www.phpwechat.com>
// +----------------------------------------------------------------------
use phpWeChat\Config;

!defined('IN_MANAGE') && exit('Access Denied!');

$mod='pc';
$file=@return_edefualt(str_callback_w($_GET['file']),'config');
$action=@return_edefualt(str_callback_w($_GET['action']),'base');

switch($action)
{
	case 'base':
		if(isset($dosubmit))
		{
			$info['pc_site_url']=substr($info['pc_site_url'],-1)=='/'?$info['pc_site_url']:$info['pc_site_url'].'/';
			$info['pc_site_url']=substr($info['pc_site_url'],0,7)=='http://'?$info['pc_site_url']:'http://'.$info['pc_site_url'];
			Config::setConfig($mod,$info);
			operation_tips('参数配置成功！');
		}
		
		include_once parse_admin_tlp($file.'-'.$action,'pc');
		break;
	case 'template':
		if(isset($dosubmit))
		{
			Config::setConfig($mod,$info);
			operation_tips('参数配置成功！');
		}
		$previews=glob(substr(dirname(__FILE__),0,-5).'template/*/preview.gif');
		
		include_once parse_admin_tlp($file.'-'.$action,'pc');
		break;
}
?>