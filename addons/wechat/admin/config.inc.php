<?php
// +----------------------------------------------------------------------
// | phpWeChat 微信端管理配置入口文件 Last modified 2016-04-18 17:04
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
use phpWeChat\Upload;

!defined('IN_MANAGE') && exit('Access Denied!');

$mod='wechat';
$file=@return_edefualt(str_callback_w($_GET['file']),'config');
$action=@return_edefualt(str_callback_w($_GET['action']),'config');

switch($action)
{
	case 'material':
		$data=WeChatManage::materialList(25);
		include_once parse_admin_tlp($file.'-'.$action,$mod);
		break;
	case 'material_synchronism':
		if(!isset($offset))
		{
			MySql::query("TRUNCATE TABLE `".DB_PRE."wechat_material`");
			operation_tips('准备工作结束，开始同步图文素材...','?mod=wechat&file=config&action=material_synchronism&offset=0');
		}
		else
		{
			if(isset($item_count) && $item_count==0)
			{
				operation_tips('图文素材数据同步完毕！','?mod=wechat&file=config&action=material');
			}
			else
			{
				$data=WeChatManage::mediaMaterialSynchronism($offset,20);
				if(is_array($data))
				{
					operation_tips('正在同步图文素材数据...','?mod=wechat&file=config&action=material_synchronism&item_count='.$data['item_count'].'&offset='.($offset+min(20,$data['item_count'])));
				}
				else
				{
					operation_tips('微信接口异常 ['.$data.']，操作中断！','?mod=wechat&file=config&action=material','error');
				}
			}
		}
		break;
	case 'material_delete':
		$type=!empty($type)?$type:'Media';

		$op=WeChatManage::deleteMaterial($id,$type);

		if($op>0)
		{
			operation_tips('操作成功！');
		}
		else
		{
			operation_tips('操作失败 ['.$op.']！','','error');
		}
		break;
	case 'material_edit':
		if(isset($dosbumit))
		{
			$op=WeChatManage::editMediaMaterial($Title,$Author,$Description,$PicUrl,$thumb_media_id,$content,$url,$id);

			if($op>0)
			{
				operation_tips('操作成功！');
			}
			else
			{
				operation_tips('操作失败 ['.$op.']！','','error');
			}
		}

		$imageJuqeryList=WeChatManage::imageMaterialJqueryList($page,100);

		$parentdata=WeChatManage::getMaterial($id);
		$childdata=WeChatManage::getMutiMaterial($parentdata['id']);

		include_once parse_admin_tlp($file.'-'.$action,'wechat');
		break;
	case 'material_post':
		if(isset($dosbumit))
		{
			$op=WeChatManage::postMediaMaterial($Title,$Author,$Description,$PicUrl,$thumb_media_id,$content,$url);

			if($op>0)
			{
				operation_tips('操作成功！');
			}
			else
			{
				operation_tips('操作失败 ['.$op.']！','','error');
			}
		}

		$imageJuqeryList=WeChatManage::imageMaterialJqueryList($page,100);

		include_once parse_admin_tlp($file.'-'.$action,'wechat');
		break;
	case 'images_material_synchronism':
		if(!isset($offset))
		{
			MySql::query("TRUNCATE TABLE `".DB_PRE."wechat_image_material`");
			operation_tips('准备工作结束，开始同步图片素材...','?mod=wechat&file=config&action=images_material_synchronism&offset=0');
		}
		else
		{
			if(isset($item_count) && $item_count==0)
			{
				operation_tips('图片素材数据同步完毕！','?mod=wechat&file=config&action=material_image');
			}
			else
			{
				$data=WeChatManage::materialSynchronism($offset,20,'image');
				if(is_array($data))
				{
					operation_tips('正在同步图片素材数据...','?mod=wechat&file=config&action=images_material_synchronism&item_count='.$data['item_count'].'&offset='.($offset+min(20,$data['item_count'])));
				}
				else
				{
					operation_tips('微信接口异常 ['.$data.']，操作中断！','?mod=wechat&file=config&action=material_image','error');
				}
			}
		}
		break;
	case 'voice_material_synchronism':
		if(!isset($offset))
		{
			MySql::query("TRUNCATE TABLE `".DB_PRE."wechat_voice_material`");
			operation_tips('准备工作结束，开始同步语音素材...','?mod=wechat&file=config&action=voice_material_synchronism&offset=0');
		}
		else
		{
			if(isset($item_count) && $item_count==0)
			{
				operation_tips('语音素材数据同步完毕！','?mod=wechat&file=config&action=material_voice');
			}
			else
			{
				$data=WeChatManage::materialSynchronism($offset,20,'voice');
				if(is_array($data))
				{
					operation_tips('正在同步语音素材数据...','?mod=wechat&file=config&action=voice_material_synchronism&item_count='.$data['item_count'].'&offset='.($offset+min(20,$data['item_count'])));
				}
				else
				{
					operation_tips('微信接口异常 ['.$data.']，操作中断！','?mod=wechat&file=config&action=material_voice','error');
				}
			}
		}
		break;
	case 'video_material_synchronism':
		if(!isset($offset))
		{
			MySql::query("TRUNCATE TABLE `".DB_PRE."wechat_video_material`");
			operation_tips('准备工作结束，开始同步视频素材...','?mod=wechat&file=config&action=video_material_synchronism&offset=0');
		}
		else
		{
			if(isset($item_count) && $item_count==0)
			{
				operation_tips('视频素材数据同步完毕！','?mod=wechat&file=config&action=material_video');
			}
			else
			{
				$data=WeChatManage::materialSynchronism($offset,20,'video');
				if(is_array($data))
				{
					operation_tips('正在同步视频素材数据...','?mod=wechat&file=config&action=video_material_synchronism&item_count='.$data['item_count'].'&offset='.($offset+min(20,$data['item_count'])));
				}
				else
				{
					operation_tips('微信接口异常 ['.$data.']，操作中断！','?mod=wechat&file=config&action=material_video','error');
				}
			}
		}
		break;
	case 'material_image':
		if(isset($dosubmit))
		{
			$op=WeChatManage::imageMaterialUpload();

			if($op>0)
			{
				exit('<script language="javascript" type="text/javascript">alert("图片素材上传成功！");self.parent.location.reload(true);</script>');
			}
			else
			{
				exit('<script language="javascript" type="text/javascript">alert("图片素材上传失败['.$op.']！");</script>');
			}
		}
		$data=WeChatManage::imagesMaterialList(25);
		include_once parse_admin_tlp($file.'-'.$action,'wechat');
		break;
	case 'material_voice':
		if(isset($dosubmit))
		{
			$op=WeChatManage::voiceMaterialUpload();

			if($op>0)
			{
				operation_tips('语音素材上传成功！','?mod=wechat&file=config&action=material_voice');
			}
			else
			{
				operation_tips('接口异常 ['.$op.']，操作中断！','?mod=wechat&file=config&action=material_voice','error');
			}
		}
		$data=WeChatManage::voiceMaterialList(25);
		include_once parse_admin_tlp($file.'-'.$action,'wechat');
		break;
	case 'material_video':
		$data=WeChatManage::videoMaterialList(25);
		include_once parse_admin_tlp($file.'-'.$action,'wechat');
		break;
	case 'config':
		if(isset($dosubmit))
		{
			Config::setConfig($mod,$info);
			operation_tips('参数配置成功！','?mod=wechat&file=config&action=interface');
		}

		include_once parse_admin_tlp($file.'-'.$action,'wechat');
		break;
	case 'interface':
		if(!$PW['wechat_token'])
		{
			operation_tips('请先设置微信号配置信息！','','error');
		}
		include_once parse_admin_tlp($file.'-'.$action,'wechat');
		break;
	case 'wepay':
		if(isset($dosubmit))
		{
			$wechat_apiclient_cert=Upload::pemUpload('wechat_apiclient_cert','upload/pem/wepay/cert/');
			$info['wechat_apiclient_cert']=is_pem($wechat_apiclient_cert)?$wechat_apiclient_cert:$info['wechat_apiclient_cert'];
			
			$wechat_apiclient_key=Upload::pemUpload('wechat_apiclient_key','upload/pem/wepay/cert/');
			$info['wechat_apiclient_key']=is_pem($wechat_apiclient_key)?$wechat_apiclient_key:$info['wechat_apiclient_key'];

			Config::setConfig($mod,$info);
			operation_tips('微信支付参数配置成功！','?mod=wechat&file=config&action=wepay');
		}

		include_once parse_admin_tlp($file.'-'.$action,'wechat');
		break;
}
?>