<?php
// +----------------------------------------------------------------------
// | phpWeChat 会员系统管理配置入口文件 Last modified 2016/5/25 21:49
// +----------------------------------------------------------------------
// | Copyright (c) 2009-2016 phpWeChat http://www.phpwechat.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 骑马的少年 <phpwechat@126.com> <http://www.phpwechat.com>
// +----------------------------------------------------------------------
use phpWeChat\Member;

!defined('IN_MANAGE') && exit('Access Denied!');

$mod='member';
$file=@return_edefualt(str_callback_w($_GET['file']),'member');
$action=@return_edefualt(str_callback_w($_GET['action']),'member');

switch($action)
{
	/**
	 * 会员管理操作
	 */
	case 'member':
		$username=htmlspecialchars(trim($username));
		$data=Member::memberList($username,20);
		include_once parse_admin_tlp($file.'-'.$action,'member');
		break;
	/**
	 * 会员等级操作
	 */
	case 'level':
		if(isset($dosubmit))
		{
			if($levelid)
			{
				$op=Member::levelEdit($info,$levelid);
			}
			else
			{
				$op=Member::levelAdd($info);
			}

			if($op>0)
			{
				operation_tips('商品分类'.($levelid?'编辑':'添加').'成功！','?mod=member&file=member&action=level');
			}
			else
			{
				operation_tips('操作失败 ['.$op.']！','','error');
			}
		}

		if(isset($job))
		{
			switch($job)
			{
				case 'delete':
					Member::levelDelete($levelids);
					operation_tips('商品分类删除成功！');
					break 2;
			}
		}

		$data=array();

		if($levelid)
		{
			$data=Member::levelGet($levelid);
		}
		include_once parse_admin_tlp($file.'-'.$action,$mod);
		break;
}
?>