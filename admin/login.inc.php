<?php
// +----------------------------------------------------------------------
// | phpWeChat 管理员登陆入口文件 Last modified 2016-03-28 9:16
// +----------------------------------------------------------------------
// | Copyright (c) 2009-2016 phpWeChat http://www.phpwechat.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 骑马的少年 <phpwechat@126.com> <http://www.phpwechat.com>
// +----------------------------------------------------------------------
use Admin\Admin;
use phpWeChat\Captcha;

!defined('IN_MANAGE') && exit('Access Denied!');
$action=@return_edefualt(str_callback_w($_GET['action']),'login');

switch($action)
{
	case 'login':
		if(isset($dosubmit))
		{
			if(!Captcha::checkCaptcha($captchacode))
			{
				Admin::adminLoginLog(0,'验证码不正确');
				operation_tips('验证码不正确！','','error');
			}
			
			$admininfo=Admin::adminLogin($username,$password);
			if(!is_array($admininfo))
			{
				switch($admininfo)
				{
					case -1:
						operation_tips('管理员不存在！','','error');
						break;
					case -2:
						operation_tips('管理员密码不正确！','','error');
						break;
					case -3:
						operation_tips('管理员账号被锁定！','','error');
						break;
				}
			}

			$_SESSION['adminuserid']=$admininfo['userid'];
			die_to_url(PW_PATH.ADMIN_FILE,'top.');
		}
		include_once parse_admin_tlp('login');
		break;
	case 'logout':
			Admin::adminLogout();
			operation_tips('登出成功！',PW_PATH.ADMIN_FILE.'?file=login&action=login');
			break;
}
?>