<?php
// +----------------------------------------------------------------------
// | phpWeChat 管理员登陆入口文件 Last modified 2016-03-28 20:02
// +----------------------------------------------------------------------
// | Copyright (c) 2009-2016 phpWeChat http://www.phpwechat.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 骑马的少年 <phpwechat@126.com> <http://www.phpwechat.com>
// +----------------------------------------------------------------------

use Admin\Admin;
use phpWeChat\Module;

define('IN_MANAGE',true);
require dirname(__FILE__).'/include/common.inc.php';

/**
 * 初始化URL参数
 */

unset($_userid,$_username,$_password,$_lastlogintime,$_roleid);

$file=@return_edefualt(str_callback_w($_GET['file']),'index');
$action=@return_edefualt(str_callback_w($_GET['action']),'');
$mod=@return_edefualt(str_callback_w($_GET['mod']),'admin');

define('MOD',$mod);
define('FILE',$file);
define('ACTION',$action);

if((!isset($_SESSION['adminuserid']) || !$_SESSION['adminuserid']) && $file!='login') //用户未登录 并且 file不是登陆入口，后者防止死跳转.
{
	die_to_url(ADMIN_FILE.'?file=login&action=login','top.');
}
else 
{
	if(isset($_SESSION['adminuserid']) && $_SESSION['adminuserid'])
	{
		@extract(Admin::getAdmin($_SESSION['adminuserid']),EXTR_PREFIX_ALL,'');
		if(!$_userid || !$_status)
		{
			Admin::adminLogout();
		}
	}

	$admin_file_path=($mod=='admin'?'':'addons/'.$mod.'/').'admin/'.$file.'.inc.php';

	$mod_parent=Module::getModule($mod);
	
	if($mod_parent['parentkey'])
	{
		$admin_file_path='addons/'.Module::getModuleByKey($mod_parent['parentkey'],'folder').'/'.$admin_file_path;
	}

	if(is_file(PW_ROOT.$admin_file_path))
	{
		$_privileges=json_decode(Admin::getRole($_roleid,'privileges'),true);

		if($_roleid!=-1)
		{
			$_m=$mod=='admin'?'system':$mod;
			$_a=$action;

			if(!in_array($_a,array('','login','logout','main','menu')) && (!isset($_privileges[$_m][$_a]) || !$_privileges[$_m][$_a]))
			{
				exit('No operation permissions.');
			}
		}
		include_once PW_ROOT.$admin_file_path;
	}
	else
	{	
		fatal_error('File '.PW_ROOT.$admin_file_path.' not exists!',1001);
	}
}
?>