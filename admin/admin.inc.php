<?php
// +----------------------------------------------------------------------
// | phpWeChat 管理员管理文件 Last modified 2016/5/16 20:13
// +----------------------------------------------------------------------
// | Copyright (c) 2009-2016 phpWeChat http://www.phpwechat.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 骑马的少年 <phpwechat@126.com> <http://www.phpwechat.com>
// +----------------------------------------------------------------------

use Admin\Admin;

!defined('IN_MANAGE') && exit('Access Denied!');
$action=@return_edefualt(str_callback_w($_GET['action']),'login');

switch($action)
{
	case 'role':
		if($_roleid!=-1)
		{
			exit('Access Denied!');
		}

		if(isset($dosubmit))
		{
			if(!$info['name'])
			{
				operation_tips('请输入角色名称！','','error');
			}
			$info['privileges']=json_encode($privileges);
			Admin::roleAdd($info);
			operation_tips('操作成功！','?mod=&file=admin&action=role');
		}

		if(isset($job))
		{
			switch($job)
			{
				case 'edit':
					Admin::roleEdit($roleids,$names,$privileges);
					operation_tips('批量编辑成功！');
					break 2;
			}
		}
		$data=Admin::roleList();
		include_once parse_admin_tlp($file.'-'.$action);
		break;
	case 'roleid_delete':
		if($_roleid!=-1)
		{
			exit('Access Denied!');
		}

		$op=Admin::roleDelete($roleid);

		if($op>0)
		{
			operation_tips('操作成功！','?mod=&file=admin&action=role');
		}
		else
		{
			operation_tips('操作失败 ['.$op.']！','','error');
		}
		break;
	case 'manage':
		$roleid=intval($roleid);
		$data=Admin::adminList($roleid,20);
		include_once parse_admin_tlp($file.'-'.$action);
		break;
	case 'delete':
		if($_roleid==-1 && $_userid!=$userid)
		{
			Admin::adminDelete($userid);
			operation_tips('操作成功！','?mod=&file=admin&action=manage');
		}
		else
		{
			operation_tips('您无权进行此项操作 [-999]！','','error');
		}
	case 'setstatus':
		if($_roleid==-1 && $_userid!=$userid)
		{
			Admin::adminSetStatus($userid,$status);
			operation_tips('操作成功！','?mod=&file=admin&action=manage');
		}
		else
		{
			operation_tips('您无权进行此项操作 [-999]！','','error');
		}
		break;
	case 'add':
		if($_roleid!=-1)
		{
			exit('Access Denied!');
		}

		if(isset($dosubmit))
		{
			$op=Admin::adminAdd($info);
			if($op>0)
			{
				operation_tips('管理员添加成功！','?mod=&file=admin&action=manage');
			}
			else
			{
				operation_tips('操作失败 ['.$op.']！','?mod=&file=admin&action=add','error');
			}
		}
		include_once parse_admin_tlp($file.'-'.$action);
		break;
	case 'edit':
		if(isset($dosubmit))
		{
			$op=Admin::adminEdit($info,$userid);
			if($op>0)
			{
				operation_tips('管理员编辑成功！','?mod=&file=admin&action=manage');
			}
			else
			{
				operation_tips('操作失败 ['.$op.']！','','error');
			}
		}

		if($_roleid==-1)
		{
			$info=Admin::getAdmin($userid);
		}
		else
		{
			$info=Admin::getAdmin($_userid);
		}

		include_once parse_admin_tlp($file.'-'.$action);
		break;
}
?>