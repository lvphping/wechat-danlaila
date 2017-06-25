<?php
// +----------------------------------------------------------------------
// | phpWeChat 微信端管理配置入口文件 Last modified 2016/4/26 14:07
// +----------------------------------------------------------------------
// | Copyright (c) 2009-2016 phpWeChat http://www.phpwechat.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 骑马的少年 <phpwechat@126.com> <http://www.phpwechat.com>
// +----------------------------------------------------------------------
use Wechat\WeChatManage;
use phpWeChat\CaChe;
use phpWeChat\Config;
use phpWeChat\Module;
use phpWeChat\MySql;

!defined('IN_MANAGE') && exit('Access Denied!');

$mod='wechat';
$file=@return_edefualt(str_callback_w($_GET['file']),'core');
$action=@return_edefualt(str_callback_w($_GET['action']),'subscribereply');

switch($action)
{
	case 'subscribereply':
		if(isset($dosubmit))
		{
			Config::setConfig($mod,$config);
			operation_tips('参数配置成功！','?mod=wechat&file=core&action=subscribereply');
		}

		include_once parse_admin_tlp($file.'-'.$action,'wechat');
		break;
	case 'autoreply':
		if(isset($dosubmit))
		{
			Config::setConfig($mod,$config);
			operation_tips('参数配置成功！','?mod=wechat&file=core&action=autoreply');
		}

		include_once parse_admin_tlp($file.'-'.$action,'wechat');
		break;
	case 'helpreply_add':
		if(isset($dosubmit))
		{
			$info['material_type']=$config['wechat_help_msg_type'];
			$info['content']=$config['wechat_help_msg_text'];
			WeChatManage::autoReplyAdd($info);
		
			Config::setConfig($mod,$config);
			operation_tips('参数配置成功！','?mod=wechat&file=core&action=helpreply');
		}

		include_once parse_admin_tlp($file.'-'.$action,'wechat');
		break;
	case 'helpreply':
		if(isset($job))
		{
			switch($job)
			{
				case 'delete':
					WeChatManage::autoReplyDelete($ids);
					operation_tips('删除操作成功！');
					break 2;
			}
		}
		$type=array('media'=>'图文','text'=>'文本','image'=>'图片','voice'=>'语音','video'=>'视频');
		$data=WeChatManage::autoReplyList(20);
		include_once parse_admin_tlp($file.'-'.$action,'wechat');
		break;
	case 'load_tmplmsg':
		WeChatManage::tmplmsgLoad();
		operation_tips('模板消息同步成功！','?mod=wechat&file=core&action=tmplmsg');
		break;
	case 'masssend':
		if(isset($dosubmit))
		{
			$op=WeChatManage::massSend($wechat_masssend_msg_type,$wechat_masssend_msg_value,$wechat_masssend_msg_text);
			if($op>0)
			{
				operation_tips('消息群发成功！','?mod=wechat&file=core&action=masssend_list');
			}
			else
			{
				operation_tips('操作失败 ['.$op.']！','','error');
			}
		}
		include_once parse_admin_tlp($file.'-'.$action,'wechat');
		break;
	case 'masssend_list':
		if(isset($job))
		{
			switch($job)
			{
				case 'delete':
					WeChatManage::massSendDelete($ids);
					operation_tips('删除操作成功！');
					break 2;
			}
		}
		$type=array('media'=>'图文','text'=>'文本','image'=>'图片','voice'=>'语音','video'=>'视频');
		$data=WeChatManage::massSendList(20);
		include_once parse_admin_tlp($file.'-'.$action,'wechat');
		break;

	case 'youaskservice_synchronism':
		$op=WeChatManage::youaskserviceSynchronism();

		if($op>0)
		{
			operation_tips('操作成功！','?mod=wechat&file=core&action=youaskservice');
		}
		else
		{
			operation_tips('操作失败 ['.$op.']！','','error');
		}
		break;
	case 'youaskservice':
		if(isset($job))
		{
			switch($job)
			{
				case 'delete':
					WeChatManage::kfDelete($ids);
					operation_tips('删除操作成功！');
					break 2;
			}
		}
		$data=WeChatManage::kfList(20);
		include_once parse_admin_tlp($file.'-'.$action,'wechat');
		break;
	case 'youaskservice_onlie':
		$data=WeChatManage::kfOnlineList();
		include_once parse_admin_tlp($file.'-'.$action,'wechat');
		break;
	case 'youaskservice_add':
		if(isset($dosubmit))
		{
			$op=WeChatManage::youaskserviceAdd($info);
			if($op>0)
			{
				operation_tips('客服添加成功！','?mod=wechat&file=core&action=youaskservice');
			}
			else
			{
				if(isset($PW['wechat_errcode'][abs($op)]))
				{
					operation_tips($PW['wechat_errcode'][abs($op)],'','error');
				}
				else
				{
					operation_tips('操作失败 ['.$op.']！','','error');
				}
			}
		}
		include_once parse_admin_tlp($file.'-'.$action,'wechat');
		break;
	case 'tmplmsg':
		if(isset($job))
		{
			switch($job)
			{
				case 'delete':
					$op=WeChatManage::tmplmsgDelete($template_ids);
					if($op>0)
					{
						operation_tips('模板删除成功！');
					}
					else
					{
						operation_tips('操作失败 ['.$op.']！','','error');
					}
					
					break 2;
			}
		}
		$data=WeChatManage::tmplmsgList(5);

		if(!$data)
		{
			$output=WeChatManage::tmplmsgLoad();

			if(!$output['errcode'] && $output['template_list'])
			{
				operation_tips('正在同步微信模板数据...','?mod=wechat&file=core&action=tmplmsg');
			}
			else
			{
				if($output['errcode'])
				{
					operation_tips_nourl($output['errmsg'],'error');
				}
				else
				{
					operation_tips_nourl('暂无远程模板素材','error');
				}
			}
			
		}
		include_once parse_admin_tlp($file.'-'.$action,'wechat');
		break;
	case 'ucenter':
		if(isset($job))
		{
			switch($job)
			{
				case 'refresh':
					WeChatManage::refreshFans($ids);
					operation_tips('粉丝数据刷新完毕！','?mod=wechat&file=core&action=ucenter');
					break 2;
				case 'setgroup':
					WeChatManage::setUserGroup($ids,intval($gotogroupid));
					operation_tips('用户转移分组成功！');
					break 2;
			}
		}

		$groupid=isset($groupid)?intval($groupid):0;
		$data=WeChatManage::userList($groupid,20);
		include_once parse_admin_tlp($file.'-'.$action,'wechat');
		break;
	case 'fans_action':
		if(isset($job))
		{
			switch($job)
			{
				case 'excel':
					WeChatManage::fansActionExcel($ids);
					break 2;
				case 'delete':
					WeChatManage::fansActionDelete($ids);
					operation_tips('粉丝行为记录删除成功！');
					break 2;
			}
		}

		$MsgType=array('event'=>'事件','image'=>'发送图片','link'=>'发送链接','location'=>'发送地理位置','text'=>'发送信息','shortvideo'=>'发送小视频','video'=>'发送视频','voice'=>'发送音频');
		$Event=array('subscribe'=>'关注','unsubscribe'=>'取消关注','LOCATION'=>'实时发送地理位置','CLICK'=>'点击菜单','VIEW'=>'跳转链接','SCAN'=>'场景扫描','scancode_waitmsg'=>'扫码带提示','scancode_push'=>'扫码推事件','pic_sysphoto'=>'系统拍照','pic_weixin'=>'相册发图','pic_photo_or_album'=>'拍照或相册发图','location_select'=>'发送位置');

		$data=WeChatManage::fansActionList($openid);
		include_once parse_admin_tlp($file.'-'.$action,'wechat');
		break;
	case 'load_fans':
		if(!isset($next_openid))
		{
			operation_tips('准备工作结束，开始同步粉丝数据...','?mod=wechat&file=core&action=load_fans&next_openid=');
		}
		else
		{
			if(isset($count) && $count==0)
			{
				operation_tips('粉丝数据同步完毕！','?mod=wechat&file=core&action=ucenter');
			}
			else
			{
				$data=WeChatManage::fansSynchronism($next_openid);
				
				operation_tips('正在同步粉丝数据...','?mod=wechat&file=core&action=load_fans&count='.$data['count'].'&next_openid='.$data['next_openid']);
			}
		}
		break;
	case 'groups':
		if(isset($dosubmit))
		{
			$op=WeChatManage::groupsAdd($info);
			if($op)
			{
				operation_tips('操作成功！','?mod=wechat&file=core&action=groups');
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
				case 'edit':
					WeChatManage::groupsEdit($ids,$names);
					operation_tips('分组编辑成功！');
					break 2;
				case 'delete':
					WeChatManage::groupsDelete($ids);
					operation_tips('分组删除成功！');
					break 2;
			}
		}
		$data=WeChatManage::groupList();

		if(!$data)
		{
			WeChatManage::groupsSynchronism();
			operation_tips('正在同步微信分组...','?mod=wechat&file=core&action=groups');
		}
		include_once parse_admin_tlp($file.'-'.$action,'wechat');
		break;
	case 'load_groups':
		$op=WeChatManage::groupsSynchronism();

		if($op)
		{
			operation_tips('分组同步成功！');
		}
		else
		{
			operation_tips('操作失败 ['.$op.']！','','error');
		}

		break;
	case 'sceneqrcode':
		if(isset($dosubmit))
		{
			$op=WeChatManage::sceneqrcodeAdd($info);
			if($op)
			{
				operation_tips('操作成功！','?mod=wechat&file=core&action=sceneqrcode');
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
				case 'edit':
					WeChatManage::sceneqrcodeEdit($ids,$scene_names,$keywords);
					operation_tips('二维码编辑成功！');
					break 2;
				case 'delete':
					WeChatManage::sceneqrcodeDelete($ids);
					operation_tips('二维码删除成功！');
					break 2;
			}
		}
		$data=WeChatManage::sceneqrcodeList();
		include_once parse_admin_tlp($file.'-'.$action,'wechat');
		break;
	case 'sceneqrcode_create':
		WeChatManage::sceneqrcodeCreate($id);
		operation_tips('操作成功！','?mod=wechat&file=core&action=sceneqrcode');
		break;
	case 'sceneqrcode_view':
		WeChatManage::sceneqrcodeView($id);
		break;
	case 'custommenu':
		$id=intval($id);

		if(isset($dosubmit))
		{
			if($info['typeid']==6)
			{
				$info['url']=format_url(U(MOD,'auth20')).'&redirect_url='.urlsafe_b64encode($info['redirect_url']);
			}
			
			if($id)
			{
				$op=WeChatManage::menuEdit($info,$id);
			}
			else
			{
				$op=WeChatManage::menuAdd($info);
			}
			
			if($op)
			{
				operation_tips('操作成功！','?mod=wechat&file=core&action=create_menu');
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
				case 'edit':
					WeChatManage::menuSimpleEdit($ids,$names,$orderbys);
					operation_tips('菜单编辑成功！','?mod=wechat&file=core&action=create_menu');
					break 2;
				case 'delete':
					WeChatManage::menuDelete($ids);
					operation_tips('菜单删除成功！','?mod=wechat&file=core&action=create_menu');
					break 2;
			}
		}
		
		$eventname=array("scancode_waitmsg"=>'扫码带提示',
						"scancode_push"=>'扫码推事件',
						"pic_sysphoto"=>'系统拍照发图',
						"pic_photo_or_album"=>'拍照或者相册发图',
						"pic_weixin"=>'微信相册发图',
						"location_select"=>'发送位置'
		);

		$data=array();

		if($id)
		{
			$data=WeChatManage::getMenu($id);
		}
		include_once parse_admin_tlp($file.'-'.$action,'wechat');
		break;
	case 'create_menu':
		$op=WeChatManage::createMenu();
		if(!$op)
		{
			operation_tips('生成微信菜单成功，请重新关注微信公共号后查看效果！','?mod=wechat&file=core&action=custommenu');
		}
		else
		{
			operation_tips('操作失败 ['.$op.']！','','error');
		}
		break;
}
?>