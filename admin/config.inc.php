<?php
// +----------------------------------------------------------------------
// | phpWeChat 后台系统配置入口文件 Last modified 2016-04-06 18:04
// +----------------------------------------------------------------------
// | Copyright (c) 2009-2016 phpWeChat http://www.phpwechat.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 骑马的少年 <phpwechat@126.com> <http://www.phpwechat.com>
// +----------------------------------------------------------------------
use phpWeChat\Area;
use phpWeChat\Config;
use phpWeChat\Email;
use phpWeChat\MySql;

!defined('IN_MANAGE') && exit('Access Denied!');
$mod='system';
$file=@return_edefualt(str_callback_w($_GET['file']),'config');
$action=@return_edefualt(str_callback_w($_GET['action']),'base');

switch($action)
{
	case 'base':
		if(isset($dosubmit))
		{
			$info['site_url']=substr($info['site_url'],-1)=='/'?$info['site_url']:$info['site_url'].'/';
			$info['site_url']=substr($info['site_url'],0,7)=='http://'?$info['site_url']:'http://'.$info['site_url'];
			Config::setConfig($mod,$info);
			operation_tips('参数配置成功！');
		}
		
		include_once parse_admin_tlp($file.'-'.$action);
		break;
	case 'upload':
		if(isset($dosubmit))
		{
			Config::setConfig($mod,$info);
			operation_tips('参数配置成功！');
		}
		include_once parse_admin_tlp($file.'-'.$action);
		break;
	case 'email':
		if(isset($dosubmit))
		{
			if($test_email)
			{
				Email::set($info['mail_server'], $info['mail_port'], $info['mail_user'], $info['mail_pwd'], $info['mail_type']);
				$send_result=Email::send($test_email, 'DeDeChat邮件配置测试', '这是一封测试邮件。用于检测DeDeChat邮件参数配置是否成功。', $info['mail_user']);
				if(!$send_result)
				{
					operation_tips('参数配置不正确，邮件无法正常发送['.Email::$mError[1].']','','error','5000');
				}
			}
			Config::setConfig($mod,$info);
			operation_tips('参数配置成功！');
		}
		include_once parse_admin_tlp($file.'-'.$action);
		break;
	case 'sms':
		if(isset($dosubmit))
		{
			if($test_mobile)
			{
				$send_result=send_checkcode_sms($test_mobile,mt_rand(1000,9999),$info);
				if($send_result!=2)
				{
					operation_tips('参数配置不正确，短信无法正常发送[错误代码：'.$send_result[0].']','','error','5000');
				}
			}
			Config::setConfig($mod,$info);
			operation_tips('参数配置成功！');
		}
		include_once parse_admin_tlp($file.'-'.$action);
		break;
	case 'pro':
		if(isset($job))
		{
			switch($job)
			{
				case 'refresh':
					Area::proRefresh($ids,$names);
					operation_tips('操作成功！');
					break 2;
				case 'delete':
					Area::proDelete($ids);
					operation_tips('操作成功！');
					break 2;
			}
		}
		include_once parse_admin_tlp($file.'-'.$action);
		break;
	case 'pro_add':
		if(isset($dosubmit))
		{
			$op=Area::proAdd($info);
			
			if($op>0)
			{
				operation_tips('操作成功！','?mod=&file=config&action=pro');
			}
			else
			{
				operation_tips('操作失败 ['.$op.']！','','error');
			}
		}
		include_once parse_admin_tlp($file.'-'.$action);
		break;
	case 'city':
		if(isset($job))
		{
			switch($job)
			{
				case 'delete':
					Area::cityDelete($ids);
					operation_tips('操作成功！');
					break 2;
			}
		}
		$data=Area::cityList(0);
		include_once parse_admin_tlp($file.'-'.$action);
		break;
	case 'city_add':
		if(isset($dosubmit))
		{
			$op=Area::cityAdd($info);
			
			if($op>0)
			{
				operation_tips('操作成功！');
			}
			else
			{
				operation_tips('操作失败 ['.$op.']！','','error');
			}
		}
		include_once parse_admin_tlp($file.'-'.$action);
		break;
	case 'city_edit':
		if(isset($dosubmit))
		{
			$op=Area::cityEdit($info,$id);
			
			if($op>0)
			{
				operation_tips('操作成功！','?file=config&action=city');
			}
			else
			{
				operation_tips('操作失败 ['.$op.']！','','error');
			}
		}

		$_data=Area::getCity($id);
		include_once parse_admin_tlp($file.'-'.$action);
		break;
	case 'area':
		if(isset($job))
		{
			switch($job)
			{
				case 'delete':
					Area::areaDelete($ids);
					operation_tips('操作成功！');
					break 2;
			}
		}
		include_once parse_admin_tlp($file.'-'.$action);
		break;
	case 'area_add':
		if(isset($dosubmit))
		{
			$op=Area::areaAdd($info);
			
			if($op>0)
			{
				operation_tips('操作成功！','?mod=&file=config&action=area&pro=1&city='.$city);
			}
			else
			{
				operation_tips('操作失败 ['.$op.']！','','error');
			}
		}
		include_once parse_admin_tlp($file.'-'.$action);
		break;
	case 'area_edit':
		$_data=Area::getArea($id);

		if(isset($dosubmit))
		{
			$op=Area::areaEdit($info,$id);
			
			if($op>0)
			{
				operation_tips('操作成功！','?mod=&file=config&action=area&pro=1&parentid='.$_data['parentid'].'&city='.$_data['cityid']);
			}
			else
			{
				operation_tips('操作失败 ['.$op.']！','','error');
			}
		}

		include_once parse_admin_tlp($file.'-'.$action);
		break;
	case 'cache':
		if(isset($dosubmit))
		{
			MySql::createIndexTable();

			$info['memcache_host']=trim($info['memcache_host']);
			Config::setConfig($mod,$info);
			operation_tips('参数配置成功！');
		}

		include_once parse_admin_tlp($file.'-'.$action);
		break;
	case 'safety':
		if(isset($dosubmit))
		{
			if(is_writeable(PW_ROOT.$PW['admin_file']) && $PW['admin_file']!=trim($info['admin_file']))
			{
				@rename(PW_ROOT.$PW['admin_file'],PW_ROOT.trim($info['admin_file']));
			}

			$info['cookie_domain']=$info['cookie_domain']?(substr($info['cookie_domain'],0,1)=='.'?$info['cookie_domain']:'.'.$info['cookie_domain']):'';
			$info['cookie_pre']=preg_replace('/[^A-Z0-9_]/i','',strtoupper($info['cookie_pre']));
			Config::setConfig($mod,$info);
			operation_tips('参数配置成功！');
		}

		include_once parse_admin_tlp($file.'-'.$action);
		break;
}
?>