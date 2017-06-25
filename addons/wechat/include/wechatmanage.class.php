<?php
// +----------------------------------------------------------------------
// | phpWeChat 微信操作类 Last modified 2016/4/21
// +----------------------------------------------------------------------
// | Copyright (c) 2009-2016 phpWeChat http://www.phpwechat.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 骑马的少年 <phpwechat@126.com> <http://www.phpwechat.com>
// +----------------------------------------------------------------------

namespace WeChat;
use phpWeChat\DataInput;
use phpWeChat\DataList;
use phpWeChat\MySql;
use phpWeChat\Upload;

class WeChatManage
{
	static private $mAutoReplyTable='wechat_auto_reply';
	static private $mMaterialTable='wechat_material';
	static private $mMassSendTable='wechat_masssend';
	static private $mKfTable='wechat_kf';
	static private $mImageMaterialTable='wechat_image_material';
	static private $mVoiceMaterialTable='wechat_voice_material';
	static private $mVideoMaterialTable='wechat_video_material';
	static private $mEmojitTable='wechat_emoji';
	static private $mGroupsTable='wechat_groups';
	static private $mFansTable='wechat_fans';
	static private $mTemplateTable='wechat_template';
	static private $mFansActionTable='wechat_request';
	static private $mQrcodeTable='wechat_qrcode';
	static private $mMenuTable='wechat_menu';
	public static $mPageString='';
	
	/*
		消息群发
	*/
	static public function massSendList($pagesize=10)
	{
		$where='1';
		$orderby='id DESC';

		$result=DataList::getList(DB_PRE.self::$mMassSendTable,$where,$orderby,max(isset($_GET['page'])?intval($_GET['page']):1,1),intval($pagesize),0,'right');

		self::$mPageString=DataList::$mPageString;

		return $result;
	}

	static public function massSend($wechat_masssend_msg_type='',$wechat_masssend_msg_value='',$wechat_masssend_msg_text='',$tag_id=-1)
	{
		$wechat_masssend_msg_text=strip_tags($wechat_masssend_msg_text,'<a>');

		$info=array();
		$info['sendtime']=CLIENT_TIME;
		$info['tag_id']=$tag_id;
		$info['is_to_all']=$tag_id==-1?1:0;
		$info['msgtype']=$wechat_masssend_msg_type;
		$info['media_id']=$wechat_masssend_msg_value;
		$info['content']=$wechat_masssend_msg_text;
		
		$url='https://api.weixin.qq.com/cgi-bin/message/mass/sendall?access_token='.WECHAT_ACCESS_TOKEN;
		$data=array();

		switch($wechat_masssend_msg_type)
		{
			case 'media':
				if($tag_id>-1)
				{
					$data=array('filter'=>array('is_to_all'=>false,'tag_id'=>$tag_id),'mpnews'=>array('media_id'=>$wechat_masssend_msg_value),'msgtype'=>'mpnews');
				}
				else
				{
					$data=array('filter'=>array('is_to_all'=>true),'mpnews'=>array('media_id'=>$wechat_masssend_msg_value),'msgtype'=>'mpnews');
				}
				break;
			case 'image':
				if($tag_id>-1)
				{
					$data=array('filter'=>array('is_to_all'=>false,'tag_id'=>$tag_id),'image'=>array('media_id'=>$wechat_masssend_msg_value),'msgtype'=>'image');
				}
				else
				{
					$data=array('filter'=>array('is_to_all'=>true),'image'=>array('media_id'=>$wechat_masssend_msg_value),'msgtype'=>'image');
				}
				break;
			case 'video':
				if($tag_id>-1)
				{
					$data=array('filter'=>array('is_to_all'=>false,'tag_id'=>$tag_id),'mpvideo'=>array('media_id'=>$wechat_masssend_msg_value),'msgtype'=>'mpvideo');
				}
				else
				{
					$data=array('filter'=>array('is_to_all'=>true),'mpvideo'=>array('media_id'=>$wechat_masssend_msg_value),'msgtype'=>'mpvideo');
				}
				break;
			case 'voice':
				if($tag_id>-1)
				{
					$data=array('filter'=>array('is_to_all'=>false,'tag_id'=>$tag_id),'voice'=>array('media_id'=>$wechat_masssend_msg_value),'msgtype'=>'voice');
				}
				else
				{
					$data=array('filter'=>array('is_to_all'=>true),'voice'=>array('media_id'=>$wechat_masssend_msg_value),'msgtype'=>'voice');
				}
				break;
			case 'text':
				if($tag_id>-1)
				{
					$data=array('filter'=>array('is_to_all'=>false,'tag_id'=>$tag_id),'text'=>array('content'=>urlencode($wechat_masssend_msg_text)),'msgtype'=>'text');
				}
				else
				{
					$data=array('filter'=>array('is_to_all'=>true),'text'=>array('content'=>urlencode($wechat_masssend_msg_text)),'msgtype'=>'text');
				}
				break;
		}

		$output=(array)json_decode(http_request($url,urldecode(json_encode($data))));
		
		if($output['errcode'])
		{
			$info['errcode']=(0-$output['errcode']);
			$info['msg_id']=$output['msg_id'];
			MySql::insert(DB_PRE.self::$mMassSendTable,$info,true);
			return 0-$output['errcode'];
		}
		else
		{
			$info['errcode']=0;
			$info['msg_id']=$output['msg_id'];
			return MySql::insert(DB_PRE.self::$mMassSendTable,$info,true);
		}
	}

	static public function getMassSend($id='')
	{
		$id=intval($id);
		
		return MySql::fetchOne("SELECT * FROM `".DB_PRE.self::$mMassSendTable."` WHERE `id`=".$id);
	}

	static public function massSendDelete($ids)
	{
		$ids=is_array($ids)?array_map('intval',$ids):array(intval($ids));
		if(!$ids)
		{
			return -1;
		}
		
		$url='https://api.weixin.qq.com/cgi-bin/message/mass/delete?access_token='.WECHAT_ACCESS_TOKEN;

		foreach($ids as $id)
		{
			$massinfo=self::getMassSend($id);
			$output=(array)json_decode(http_request($url,'{"msg_id":"'.$massinfo['msg_id'].'"}'));

			if($output['errcode'])
			{
				return 0-$output['errcode'];
			}
			else
			{
				MySql::mysqlDelete(DB_PRE.self::$mMassSendTable,$id);
			}
		}

		return true;
	}

	/*
		在线客服 
	*/
	static public function kfList($pagesize=10)
	{
		$where='1';
		$orderby='id DESC';

		$result=DataList::getList(DB_PRE.self::$mKfTable,$where,$orderby,max(isset($_GET['page'])?intval($_GET['page']):1,1),intval($pagesize),0,'right');

		self::$mPageString=DataList::$mPageString;

		return $result;
	}

	static public function kfOnlineList()
	{
		$url='https://api.weixin.qq.com/cgi-bin/customservice/getonlinekflist?access_token='.WECHAT_ACCESS_TOKEN;
		$output=(array)json_decode(http_request($url));

		if($output['errcode'])
		{
			return 0-$output['errcode'];
		}
		else
		{
			return 	$output['kf_online_list'];
		}
	}

	static public function youaskserviceSynchronism()
	{
		MySql::query("TRUNCATE TABLE `".DB_PRE.self::$mKfTable."`");

		$url='https://api.weixin.qq.com/cgi-bin/customservice/getkflist?access_token='.WECHAT_ACCESS_TOKEN;
		$output=(array)json_decode(http_request($url));
		
		if($output['errcode'])
		{
			return 0-$output['errcode'];
		}
		else
		{
			foreach($output['kf_list'] as $r)
			{
				$r=(array)$r;
				MySql::insert(DB_PRE.self::$mKfTable,$r,true);
			}
		}

		return true;
	}

	static public function youaskserviceAdd($info)
	{
		$data=array();
		$info['kf_account']='kf'.$info['kf_id'].'@'.WECHAT_NO;
		$info['password']=md5($info['password']);

		$data['kf_account']=urlencode($info['kf_account']);
		$data['nickname']=urlencode($info['kf_nick']);
		$data['password']=$info['password'];

		$url='https://api.weixin.qq.com/customservice/kfaccount/add?access_token='.WECHAT_ACCESS_TOKEN;
		$output=(array)json_decode(http_request($url,urldecode(json_encode($data))));

		if($output['errcode'])
		{
			return 0-$output['errcode'];
		}
		else
		{
			$imgurl='http://api.weixin.qq.com/customservice/kfaccount/uploadheadimg?access_token='.WECHAT_ACCESS_TOKEN.'&kf_account='.$data['kf_account'];

			if (class_exists('CURLFile')) 
			{
				$field = array('media' => new \CURLFile(PW_ROOT.$info['kf_headimgurl']));
			} 
			else 
			{
				$field = array('media' => '@' . PW_ROOT.$info['kf_headimgurl']);
			}
			$imgoutput=(array)json_decode(http_media_request($imgurl,$field));

			return MySql::insert(DB_PRE.self::$mKfTable,$info,true);
		}
	}

	static public function getKf($id='')
	{
		$id=intval($id);
		
		return MySql::fetchOne("SELECT * FROM `".DB_PRE.self::$mKfTable."` WHERE `id`=".$id);
	}

	static public function kfDelete($ids)
	{
		$ids=is_array($ids)?array_map('intval',$ids):array(intval($ids));
		if(!$ids)
		{
			return -1;
		}
		
		foreach($ids as $id)
		{
			$kfinfo=self::getKf($id);
			$url='https://api.weixin.qq.com/customservice/kfaccount/del?access_token='.WECHAT_ACCESS_TOKEN.'&kf_account='.$kfinfo['kf_account'];

			$output=(array)json_decode(http_request($url));

			if($output['errcode'])
			{
				return 0-$output['errcode'];
			}
			else
			{
				MySql::mysqlDelete(DB_PRE.self::$mKfTable,$id);
			}
		}

		return true;
	}

	/*
		自动回复
	*/
	static public function autoReplyList($pagesize=10)
	{
		$where='1';
		$orderby='id DESC';

		$result=DataList::getList(DB_PRE.self::$mAutoReplyTable,$where,$orderby,max(isset($_GET['page'])?intval($_GET['page']):1,1),intval($pagesize),0,'right');

		self::$mPageString=DataList::$mPageString;

		return $result;
	}

	static public function autoReplyAdd($info)
	{
		if($info['material_type']=='text')
		{
			unset($info['media_id']);
		}
		else
		{
			unset($info['content']);
		}

		return MySql::insert(DB_PRE.self::$mAutoReplyTable,$info,true);
	}

	static public function autoReplyDelete($ids)
	{
		$ids=is_array($ids)?array_map('intval',$ids):array(intval($ids));
		if(!$ids)
		{
			return -1;
		}
		
		MySql::mysqlDelete(DB_PRE.self::$mAutoReplyTable,$ids);
		return true;
	}

	static public function getAutoReply($media_id='')
	{
		$media_id=str_callback_w($media_id);
		
		return MySql::fetchOne("SELECT * FROM `".DB_PRE.self::$mAutoReplyTable."` WHERE `media_id`='".$media_id."'");
	}

	/*
		模板消息
	*/
	static public function tmplmsgList($pagesize=10)
	{
		$where='1';
		$orderby='primary_industry ASC,deputy_industry ASC';

		$result=DataList::getList(DB_PRE.self::$mTemplateTable,$where,$orderby,max(isset($_GET['page'])?intval($_GET['page']):1,1),intval($pagesize),0,'right');

		self::$mPageString=DataList::$mPageString;

		return $result;
	}
	
	static public function getTmplMsg($template_id='')
	{
		$template_id=str_callback_w($template_id);
		
		return MySql::fetchOne("SELECT * FROM `".DB_PRE.self::$mTemplateTable."` WHERE `template_id`='".$template_id."'");
	}
	
	static public function tmplmsgLoad()
	{
		$url='https://api.weixin.qq.com/cgi-bin/template/get_all_private_template?access_token='.WECHAT_ACCESS_TOKEN;
		$output=(array)json_decode(http_request($url));

		$data=(array)$output['template_list'];
		
		if($data)
		{
			MySql::query("TRUNCATE `".DB_PRE.self::$mTemplateTable."`");
			foreach($data as $r)
			{
				$r=(array)$r;

				if($r)
				{
					MySql::insert(DB_PRE.self::$mTemplateTable,$r,true);
				}
			}
		}
		return $output;
	}
	 
	static public function tmplmsgDelete($template_ids=array())
	{
		$template_ids=str_callback_w($template_ids);
		$template_ids=is_array($template_ids)?$template_ids:array($template_ids);
		
		if(!$template_ids)
		{
			return -1;
		}
		
		foreach($template_ids as $template_id)
		{
			$url='https://api,weixin.qq.com/cgi-bin/template/del_private_template?access_token='.WECHAT_ACCESS_TOKEN;
			$output=(array)json_decode(http_request($url,'{“template_id”=”'.$template_id.'”}'));

			if($output['errmsg']=='ok')
			{
				MySql::query("DELETE FROM `".DB_PRE.self::$mTemplateTable."` WHERE `template_id`='".$template_id."'");
			}
			else
			{
				return -2;
			}
		}
		return true;
	}
	
	/*
		菜单
	*/
	static public function menuList($parentid=0)
	{
		return MySql::fetchAll("SELECT * FROM `".DB_PRE.self::$mMenuTable."` WHERE `parentid`=".intval($parentid)." ORDER BY `orderby` ASC,`id` DESC LIMIT 0,5");
	}

	static public function getMenu($id,$f='*')
	{
		$r=MySql::fetchOne("SELECT * FROM `".DB_PRE.self::$mMenuTable."` WHERE `id`=".intval($id));
		return $f=='*'?$r:$r[$f];
	}

	static public function menuAdd($info)
	{
		return MySql::insert(DB_PRE.self::$mMenuTable,$info,true);
	}

	static public function menuEdit($info,$id)
	{
		return MySql::update(DB_PRE.self::$mMenuTable,$info,'id='.intval($id));
	}

	static public function menuDelete($ids)
	{
		$ids=is_array($ids)?array_map('intval',$ids):array(intval($ids));
		if(!$ids)
		{
			return -1;
		}
		
		MySql::mysqlDelete(DB_PRE.self::$mMenuTable,$ids);
		return true;
	}

	static public function menuSimpleEdit($ids,$names,$orderbys)
	{
		$ids=is_array($ids)?array_map('intval',$ids):array(intval($ids));
		if(!$ids)
		{
			return -1;
		}
		
		foreach($ids as $id)
		{
			MySql::update(DB_PRE.self::$mMenuTable,array('name'=>$names[$id],'orderby'=>$orderbys[$id]),'id='.$id);
		}
		return true;
	}

	static public function menuTypeName($typeid=1)
	{
		$name='';

		switch($typeid)
		{
			case 1:
				$name='关键词回复菜单';	
				break;
			case 2:
				$name='url链接菜单';	
				break;
			case 3:
				$name='微信扩展菜单';	
				break;
			case 4:
				$name='一键拨号菜单';	
				break;
			case 5:
				$name='位置地图';	
				break;
			case 6:
				$name='网页授权登录';	
				break;
			default:
				$name='未定义菜单类型';
				break;
		}

		return $name;
	}

	static public function menuTypeEvent($typeid=1)
	{
		$event='';

		switch($typeid)
		{
			case 1:
				$event='keyword';	
				break;
			case 2:
				$event='url';	
				break;
			case 3:
				$event='event';	
				break;
			case 4:
				$event='telephone';	
				break;
			case 5:
				$event='location';	
				break;
			case 6:
				$event='redirect_url';	
				break;
			default:
				$event='-';
				break;
		}

		return $event;
	}

	static public function createMenu()
	{
		$menu=array();
		$r=self::menuList(0);
		
		foreach($r as $key => $_r)
		{
			$children=self::menuList($_r['id']);
			if(!$children)
			{
				if($_r['typeid']==1)
				{
					$menu['button'][$key]=array('name'=>urlencode($_r['name']),'type'=>'click','key'=>urlencode($_r['keyword']));
				}

				if($_r['typeid']==2 || $_r['typeid']==6)
				{
					$menu['button'][$key]=array('name'=>urlencode($_r['name']),'type'=>'view','url'=>$_r['url']);
				}

				if($_r['typeid']==3)
				{
					$menu['button'][$key]=array('name'=>urlencode($_r['name']),'type'=>urlencode($_r['event']),'key'=>'rselfmenu_'.$key.'_0');
				}

				if($_r['typeid']==4)
				{
					$menu['button'][$key]=array('name'=>urlencode($_r['name']),'type'=>'view','url'=>format_url(U(MOD,'tel',array('tel'=>$_c['telephone']))));
				}

				if($_r['typeid']==5)
				{
					$menu['button'][$key]=array('name'=>urlencode($_r['name']),'type'=>'view','url'=>format_url(U(MOD,'map',array('coord'=>$_r['location']))));
				}
			}
			else
			{
				$c=array();
				foreach($children as $k => $_c)
				{
					if($_c['typeid']==1)
					{
						$c[$k]=array('name'=>urlencode($_c['name']),'type'=>'click','key'=>urlencode($_c['keyword']));
					}

					if($_c['typeid']==2 || $_c['typeid']==6)
					{
						$c[$k]=array('name'=>urlencode($_c['name']),'type'=>'view','url'=>$_c['url']);
					}

					if($_c['typeid']==3)
					{
						$c[$k]=array('name'=>urlencode($_c['name']),'type'=>urlencode($_c['event']),'key'=>'rselfmenu_'.$key.'_'.$k);
					}

					if($_c['typeid']==4)
					{
						$c[$k]=array('name'=>urlencode($_c['name']),'type'=>'view','url'=>format_url(U(MOD,'tel',array('tel'=>$_c['telephone']))));
					}

					if($_c['typeid']==5)
					{
						$c[$k]=array('name'=>urlencode($_c['name']),'type'=>'view','url'=>format_url(U(MOD,'map',array('coord'=>$_r['location']))));
					}
				}
				$menu['button'][$key]=array('name'=>urlencode($_r['name']),'sub_button'=>$c);
			}
			
		}

		$url='https://api.weixin.qq.com/cgi-bin/menu/create?access_token='.WECHAT_ACCESS_TOKEN;
		$output=(array)json_decode(http_request($url,urldecode(stripslashes(json_encode($menu)))));

		return $output['errcode'];
	}

	/*
		素材
	*/
	static public function postImageMaterial($info)
	{
		return MySql::insert(DB_PRE.self::$mImageMaterialTable,$info,true);
	}

	static public function postVoiceMaterial($info)
	{
		return MySql::insert(DB_PRE.self::$mVoiceMaterialTable,$info,true);
	}
	
	static public function postVideoMaterial($info)
	{
		return MySql::insert(DB_PRE.self::$mVideoMaterialTable,$info,true);
	}

	static public function imageMaterialJqueryList($page=1,$pagesize=16)
	{
		$page=max(1,intval($page));
		$pagesize=intval($pagesize);

		return MySql::fetchAll("SELECT * FROM `".DB_PRE.self::$mImageMaterialTable."` ORDER BY `created_at` DESC LIMIT ".($page-1)*$pagesize.",".$pagesize);
	}

	static public function voiceMaterialJqueryList($page=1,$pagesize=16)
	{
		$page=max(1,intval($page));
		$pagesize=intval($pagesize);

		return MySql::fetchAll("SELECT * FROM `".DB_PRE.self::$mVoiceMaterialTable."` ORDER BY `created_at` DESC LIMIT ".($page-1)*$pagesize.",".$pagesize);
	}

	static public function videoMaterialJqueryList($page=1,$pagesize=16)
	{
		$page=max(1,intval($page));
		$pagesize=intval($pagesize);

		return MySql::fetchAll("SELECT * FROM `".DB_PRE.self::$mVideoMaterialTable."` ORDER BY `created_at` DESC LIMIT ".($page-1)*$pagesize.",".$pagesize);
	}

	static public function materialJqueryList($group_id=0,$count=1)
	{
		$count=max(1,intval($count));
		$group_id=intval($group_id);

		if($count>1)
		{
			return MySql::fetchAll("SELECT * FROM `".DB_PRE.self::$mMaterialTable."` WHERE `group_id`=".$group_id." ORDER BY `orderby` ASC LIMIT 0,".$count);
		}
		else
		{
			return MySql::fetchOne("SELECT * FROM `".DB_PRE.self::$mMaterialTable."` WHERE `group_id`=".$group_id);
		}
	}

	static public function editMediaMaterial($Title,$Author,$Description,$PicUrl,$thumb_media_id,$content,$url,$up_group_id=0)
	{
		if(!$Title)
		{
			return -1;
		}

		$op=self::deleteMaterial($up_group_id);
		
		if($op<0)
		{
			return $op;
		}

		$orderby=1;
		$group_id=0;
		
		foreach($Title as $id => $_title)
		{
			$info['Title']=trim($_title);

			if(empty($thumb_media_id[$id]))
			{
				$_picurl=$PicUrl[$id];
				
				/*
					上传封面图片到素材库 1、获取图片media_id, 2、入图片素材库
				*/
				$url='https://api.weixin.qq.com/cgi-bin/material/add_material?access_token='.WECHAT_ACCESS_TOKEN.'&type=image';

				if (class_exists('CURLFile')) 
				{
					$field = array('media' => new \CURLFile(PW_ROOT.$_picurl));
				} 
				else 
				{
					$field = array('media' => '@' . PW_ROOT.$_picurl);
				}
				$output=(array)json_decode(http_media_request($url,$field));
				$output['local_url']=$_picurl;
				$output['created_at']=CLIENT_TIME;
				$output['remote_url']=$output['url'];

				if(!isset($output['media_id']))
				{
					return 0-$output['errcode'];
				}

				self::postImageMaterial($output);
				$info['thumb_media_id']=$output['media_id'];
			}
			else
			{
				$info['thumb_media_id']=$thumb_media_id[$id];
			}
			$info['Author']=$Author[$id];
			$info['Description']=$Description[$id];
			$info['PicUrl']=$PicUrl[$id];
			$info['content']=$content[$id];

			preg_match_all('/<img([ ]+)src="([^\"]+)"/i',stripslashes($info['content']),$matches);

			foreach($matches[2] as $key => $val)
			{
				$val=str_replace(SITE_URL,'',$val);

				$url='https://api.weixin.qq.com/cgi-bin/media/uploadimg?access_token='.WECHAT_ACCESS_TOKEN;
				if (class_exists('CURLFile')) 
				{
					$field = array('media' => new \CURLFile(PW_ROOT.$val));
				} 
				else 
				{
					$field = array('media' => '@' . PW_ROOT.$val);
				}

				if(is_file(PW_ROOT.$val) && in_array(strtolower(get_fileext($val)),array('jpg','png')))
				{
					$output=(array)json_decode(http_media_request($url,$field));

					if(isset($output['url']))
					{
						$info['content']=str_replace(SITE_URL.$val,$output['url'],$info['content']);
					}
				}
			}

			$info['Url']=$urls[$id];
			$info['orderby']=$orderby;
			$info['UpdateTime']=CLIENT_TIME;

			if($id==0)
			{
				$info['id']=$up_group_id;
				$info['group_id']=0;
				MySql::insert(DB_PRE.self::$mMaterialTable,$info,true);
			}
			else
			{
				unset($info['id']);
				$info['group_id']=$up_group_id;
				MySql::insert(DB_PRE.self::$mMaterialTable,$info,true);
			}

			$orderby++;
		}

		/*
			插入图文素材
		*/
		$data=array();
		
		$r=MySql::fetchAll("SELECT * FROM ".DB_PRE.self::$mMaterialTable." WHERE `id`=$up_group_id");
		$r=pw_addslashes($r);

		foreach($r as $k => $_r)
		{
			$content_source_url=($_r['Url'] && substr($_r['Url'],0,7)!='http://')?urlencode($_r['Url']):format_url(U('wechat','view',array('id'=>$_r['id'])));

			$data['articles'][]=array(
			'title'=>urlencode($_r['Title']),	
			'thumb_media_id'=>$_r['thumb_media_id'],	
			'author'=>urlencode($_r['Author']),	
			'digest'=>urlencode($_r['Description']),	
			'show_cover_pic'=>1,	
			'content'=>urlencode($_r['content']),	
			'content_source_url'=>$content_source_url,
			);

			MySql::update(DB_PRE.self::$mMaterialTable,array('content_source_url'=>$content_source_url),'id='.$_r['id']);
		}
		
		$r=MySql::fetchAll("SELECT * FROM ".DB_PRE.self::$mMaterialTable." WHERE `group_id`=$up_group_id");
		$r=pw_addslashes($r);

		foreach($r as $k => $_r)
		{
			$content_source_url=($_r['Url'] && substr($_r['Url'],0,7)!='http://')?urlencode($_r['Url']):format_url(U('wechat','view',array('id'=>$_r['id'])));

			$data['articles'][]=array(
			'title'=>urlencode($_r['Title']),	
			'thumb_media_id'=>$_r['thumb_media_id'],	
			'author'=>urlencode($_r['Author']),	
			'digest'=>urlencode($_r['Description']),	
			'show_cover_pic'=>1,	
			'content'=>urlencode($_r['content']),	
			'content_source_url'=>$content_source_url,
			);

			MySql::update(DB_PRE.self::$mMaterialTable,array('content_source_url'=>$content_source_url),'id='.$_r['id']);
		}

		$url='https://api.weixin.qq.com/cgi-bin/material/add_news?access_token='.WECHAT_ACCESS_TOKEN.'&type=news';
		$output=(array)json_decode(http_request($url,urldecode(json_encode($data))));

		if(isset($output['errcode']))
		{
			return 0-$output['errcode'];
		}
			
		MySql::update(DB_PRE.self::$mMaterialTable,array('media_id'=>$output['media_id']),'id='.$up_group_id);
		

		return $orderby;  // 正确时返回图文条数
	}

	static public function postMediaMaterial($Title,$Author,$Description,$PicUrl,$thumb_media_id,$content,$urls)
	{
		if(!$Title)
		{
			return -1;
		}

		$orderby=1;
		$group_id=0;
		
		foreach($Title as $id => $_title)
		{
			$info['Title']=trim($_title);
			
			if(empty($thumb_media_id[$id]))
			{
				$_picurl=$PicUrl[$id];
				
				/*
					上传封面图片到素材库 1、获取图片media_id, 2、入图片素材库
				*/
				$url='https://api.weixin.qq.com/cgi-bin/material/add_material?access_token='.WECHAT_ACCESS_TOKEN.'&type=image';

				if (class_exists('CURLFile')) 
				{
					$field = array('media' => new \CURLFile(PW_ROOT.$_picurl));
				} 
				else 
				{
					$field = array('media' => '@' . PW_ROOT.$_picurl);
				}
				$output=(array)json_decode(http_media_request($url,$field));
				$output['local_url']=$_picurl;
				$output['created_at']=CLIENT_TIME;
				$output['remote_url']=$output['url'];

				if(!isset($output['media_id']))
				{
					return 0-$output['errcode'];
				}

				self::postImageMaterial($output);
				$info['thumb_media_id']=$output['media_id'];
			}
			else
			{
				$info['thumb_media_id']=$thumb_media_id[$id];
			}
			$info['Author']=$Author[$id];
			$info['Description']=$Description[$id];
			$info['PicUrl']=$PicUrl[$id];
			$info['content']=$content[$id];

			preg_match_all('/<img([ ]+)src="([^\"]+)"/i',stripslashes($info['content']),$matches);

			foreach($matches[2] as $key => $val)
			{
				$val=str_replace(SITE_URL,'',$val);

				$url='https://api.weixin.qq.com/cgi-bin/media/uploadimg?access_token='.WECHAT_ACCESS_TOKEN;
				if (class_exists('CURLFile')) 
				{
					$field = array('media' => new \CURLFile(PW_ROOT.$val));
				} 
				else 
				{
					$field = array('media' => '@' . PW_ROOT.$val);
				}

				if(is_file(PW_ROOT.$val) && in_array(strtolower(get_fileext($val)),array('jpg','png')))
				{
					$output=(array)json_decode(http_media_request($url,$field));

					if(isset($output['url']))
					{
						$info['content']=str_replace(SITE_URL.$val,$output['url'],$info['content']);
					}
				}
			}

			$info['Url']=$urls[$id];
			$info['orderby']=$orderby;
			$info['UpdateTime']=CLIENT_TIME;

			if($id==0)
			{
				$group_id=MySql::insert(DB_PRE.self::$mMaterialTable,$info,true);
			}
			else
			{
				$info['group_id']=$group_id;
				MySql::insert(DB_PRE.self::$mMaterialTable,$info,true);
			}

			$orderby++;
		}

		/*
			插入图文素材
		*/
		$data=array();
		
		$r=MySql::fetchAll("SELECT * FROM ".DB_PRE.self::$mMaterialTable." WHERE `id`=$group_id");
		$r=pw_addslashes($r);

		foreach($r as $k => $_r)
		{
			$content_source_url=($_r['Url'] && substr($_r['Url'],0,7)!='http://')?urlencode($_r['Url']):format_url(U('wechat','view',array('id'=>$_r['id'])));

			$data['articles'][]=array(
			'title'=>urlencode($_r['Title']),	
			'thumb_media_id'=>$_r['thumb_media_id'],	
			'author'=>urlencode($_r['Author']),	
			'digest'=>urlencode($_r['Description']),	
			'show_cover_pic'=>1,	
			'content'=>urlencode($_r['content']),	
			'content_source_url'=>$content_source_url,
			);

			MySql::update(DB_PRE.self::$mMaterialTable,array('content_source_url'=>$content_source_url),'id='.$_r['id']);
		}
		
		$r=MySql::fetchAll("SELECT * FROM ".DB_PRE.self::$mMaterialTable." WHERE `group_id`=$group_id");
		$r=pw_addslashes($r);

		foreach($r as $k => $_r)
		{
			$content_source_url=($_r['Url'] && substr($_r['Url'],0,7)!='http://')?urlencode($_r['Url']):format_url(U('wechat','view',array('id'=>$_r['id'])));

			$data['articles'][]=array(
			'title'=>urlencode($_r['Title']),	
			'thumb_media_id'=>$_r['thumb_media_id'],	
			'author'=>urlencode($_r['Author']),	
			'digest'=>urlencode($_r['Description']),	
			'show_cover_pic'=>1,	
			'content'=>urlencode($_r['content']),	
			'content_source_url'=>$content_source_url,
			);

			MySql::update(DB_PRE.self::$mMaterialTable,array('content_source_url'=>$content_source_url),'id='.$_r['id']);
		}

		$url='https://api.weixin.qq.com/cgi-bin/material/add_news?access_token='.WECHAT_ACCESS_TOKEN.'&type=news';
		$output=(array)json_decode(http_request($url,urldecode(json_encode($data))));

		if(isset($output['errcode']))
		{
			return 0-$output['errcode'];
		}
			
		MySql::update(DB_PRE.self::$mMaterialTable,array('media_id'=>$output['media_id']),'id='.$group_id);
		

		return $orderby;  // 正确时返回图文条数
	}
	
	
	static public function materialList($pagesize=12)
	{
		$where='`group_id`=0';
		$orderby='id DESC';

		$result=DataList::getList(DB_PRE.self::$mMaterialTable,$where,$orderby,max(isset($_GET['page'])?intval($_GET['page']):1,1),intval($pagesize),0,'right');

		self::$mPageString=DataList::$mPageString;

		return $result;
	}

	static public function videoMaterialList($pagesize=12)
	{
		$where='1';
		$orderby='created_at DESC';

		$result=DataList::getList(DB_PRE.self::$mVideoMaterialTable,$where,$orderby,max(isset($_GET['page'])?intval($_GET['page']):1,1),intval($pagesize),0,'right');

		self::$mPageString=DataList::$mPageString;

		return $result;
	}

	static public function voiceMaterialList($pagesize=12)
	{
		$where='1';
		$orderby='created_at DESC';

		$result=DataList::getList(DB_PRE.self::$mVoiceMaterialTable,$where,$orderby,max(isset($_GET['page'])?intval($_GET['page']):1,1),intval($pagesize),0,'right');

		self::$mPageString=DataList::$mPageString;

		return $result;
	}

	static public function imagesMaterialList($pagesize=12)
	{
		$where='1';
		$orderby='created_at DESC';

		$result=DataList::getList(DB_PRE.self::$mImageMaterialTable,$where,$orderby,max(isset($_GET['page'])?intval($_GET['page']):1,1),intval($pagesize),0,'right');

		self::$mPageString=DataList::$mPageString;

		return $result;
	}

	static public function getMutiMaterial($group_id)
	{
		return MySql::fetchAll("SELECT * FROM `".DB_PRE.self::$mMaterialTable."` WHERE `group_id`=".intval($group_id));
	}

	static public function getMaterial($id,$f='*')
	{
		$r=MySql::fetchOne("SELECT * FROM `".DB_PRE.self::$mMaterialTable."` WHERE `id`=".intval($id));
		return $f=='*'?$r:$r[$f];
	}

	static public function getMaterialByMediaId($media_id,$f='*')
	{
		$r=MySql::fetchOne("SELECT * FROM `".DB_PRE.self::$mMaterialTable."` WHERE `media_id`='".trim($media_id)."'");
		return $f=='*'?$r:$r[$f];
	}

	static public function getImageMaterial($id,$f='*')
	{
		$r=MySql::fetchOne("SELECT * FROM `".DB_PRE.self::$mImageMaterialTable."` WHERE `id`=".intval($id));
		return $f=='*'?$r:$r[$f];
	}

	static public function getImageMaterialByMediaId($media_id,$f='*')
	{
		$r=MySql::fetchOne("SELECT * FROM `".DB_PRE.self::$mImageMaterialTable."` WHERE `media_id`='".trim($media_id)."'");
		return $f=='*'?$r:$r[$f];
	}

	static public function getVoiceMaterial($id,$f='*')
	{
		$r=MySql::fetchOne("SELECT * FROM `".DB_PRE.self::$mVoiceMaterialTable."` WHERE `id`=".intval($id));
		return $f=='*'?$r:$r[$f];
	}

	static public function getVoiceMaterialByMediaId($media_id,$f='*')
	{
		$r=MySql::fetchOne("SELECT * FROM `".DB_PRE.self::$mVoiceMaterialTable."` WHERE `media_id`='".trim($media_id)."'");
		return $f=='*'?$r:$r[$f];
	}

	static public function getVideoMaterial($id,$f='*')
	{
		$r=MySql::fetchOne("SELECT * FROM `".DB_PRE.self::$mVideoMaterialTable."` WHERE `id`=".intval($id));
		return $f=='*'?$r:$r[$f];
	}

	static public function getVideoMaterialByMediaId($media_id,$f='*')
	{
		$r=MySql::fetchOne("SELECT * FROM `".DB_PRE.self::$mVideoMaterialTable."` WHERE `media_id`='".trim($media_id)."'");
		return $f=='*'?$r:$r[$f];
	}
	
	static public function deleteMaterial($id,$type='Media')
	{
		if($type=='Media')
		{
			$data=self::getMaterial($id);
		}
		else
		{
			$func='get'.$type.'Material';
			$data=self::$func($id);
		}

		$url='https://api.weixin.qq.com/cgi-bin/material/del_material?access_token='.WECHAT_ACCESS_TOKEN;
		$output=(array)json_decode(http_request($url,'{"media_id":"'.$data['media_id'].'"}'));
		
		if($output['errcode'])
		{
			return 0-$output['errcode'];
		}
		else
		{
			if($type=='Media')
			{
				MySql::mysqlDelete(DB_PRE.self::$mMaterialTable,intval($id));
				MySql::mysqlDelete(DB_PRE.self::$mMaterialTable,intval($id),'group_id');
			}
			else
			{
				MySql::mysqlDelete(DB_PRE.'wechat_'.strtolower($type).'_material',intval($id));
			}
			return true;
		}
	}

	static public function imageMaterialUpload()
	{
		$imgfile=Upload::imageUpload('images_material');

		if(!is_file(PW_ROOT.$imgfile)) 
		{	
			return -1;
		}

		if(!is_image(PW_ROOT.$imgfile))
		{	
			return -2;
		}

		$url='https://api.weixin.qq.com/cgi-bin/material/add_material?access_token='.WECHAT_ACCESS_TOKEN.'&type=image';

		if (class_exists('CURLFile')) 
		{
			$field = array('media' => new \CURLFile(PW_ROOT.$imgfile));
		} 
		else 
		{
			$field = array('media' => '@' . PW_ROOT.$imgfile);
		}
		
		$output=(array)json_decode(http_media_request($url,$field));

		if(isset($output['errcode']))
		{
			return 0-$output['errcode'];
		}
		else
		{
			$info=$output;
			$info['local_url']=$imgfile;
			$info['remote_url']=$output['url'];
			return self::postImageMaterial($info);
		}

	}

	static public function voiceMaterialUpload()
	{
		$voicefile=Upload::voiceUpload('voice_material');

		if(!is_file(PW_ROOT.$voicefile))
		{	
			return -1;
		}

		if(!is_voice(PW_ROOT.$voicefile))
		{	
			return -2;
		}

		$url='https://api.weixin.qq.com/cgi-bin/material/add_material?access_token='.WECHAT_ACCESS_TOKEN.'&type=voice';

		if (class_exists('CURLFile')) 
		{
			$field = array('media' => new \CURLFile(PW_ROOT.$voicefile));
		} 
		else 
		{
			$field = array('media' => '@' . PW_ROOT.$voicefile);
		}
		
		$output=(array)json_decode(http_media_request($url,$field));

		if(isset($output['errcode']))
		{
			return 0-$output['errcode'];
		}
		else
		{
			$info=$output;
			$info['local_url']=$voicefile;
			$info['remote_url']=$output['url'];
			return self::postVoiceMaterial($info);
		}

	}

	static public function mediaMaterialSynchronism($offset=0,$count=20)
	{
		$url='https://api.weixin.qq.com/cgi-bin/material/batchget_material?access_token='.WECHAT_ACCESS_TOKEN;
		$output=(array)json_decode(http_request($url,'{"type":"news","offset":"'.$offset.'","count":"'.$count.'"}'));
		
		if($output['errcode'])
		{
			return 0-$output['errcode'];
		}
		else
		{
			foreach($output['item'] as $data)
			{
				$data=(array)$data;

				$content=(array)$data['content'];
				
				$group_id=0;

				foreach($content['news_item'] as $key => $r)
				{
					$info=array();
					$r=(array)$r;
					
					$info['Title']=$r['title'];
					$info['Author']=$r['author'];
					$info['Description']=$r['digest'];
					$info['Url']=$r['url']; //微信内部图文链接
					$info['content_source_url']=$r['content_source_url']; //原文链接
					$info['show_cover_pic']=$r['show_cover_pic'];
					$info['thumb_media_id']=$r['thumb_media_id'];
					$info['content']=$r['content'];

					/*
						根据thumb_media_id下载素材到本地
					*/
					$url='https://api.weixin.qq.com/cgi-bin/material/get_material?access_token='.WECHAT_ACCESS_TOKEN;
					$imageoutput=http_request($url,'{"media_id":"'.$info['thumb_media_id'].'"}');
					
					$err=(array)json_decode($imageoutput);

					if(!isset($err['errcode']))
					{
						$save_path='upload/images/'.date('Y-m-d').'/';
						$save_file=time().mt_rand().'.jpg';
						@mkdir(PW_ROOT.$save_path,0777);

						file_put_contents(PW_ROOT.$save_path.$save_file,$imageoutput);

						$info['PicUrl']=$save_path.$save_file;
						$info['UpdateTime']=$data['update_time'];
						$info['orderby']=$key;
						$info['group_id']=$group_id;

						if($key==0)
						{
							$info['media_id']=$data['media_id'];
							$group_id=self::addMaterial($info);
						}
						else
						{
							$info['media_id']='';
							$info['group_id']=$group_id;
							self::addMaterial($info);
						}
					}
				}
			}
		}

		return $output;
	}

	static public function materialSynchronism($offset=0,$count=20,$type='image')
	{
		$url='https://api.weixin.qq.com/cgi-bin/material/batchget_material?access_token='.WECHAT_ACCESS_TOKEN;
		$output=(array)json_decode(http_request($url,'{"type":"'.$type.'","offset":"'.$offset.'","count":"'.$count.'"}'));

		if($output['errcode'])
		{
			return 0-$output['errcode'];
		}
		else
		{
			foreach($output['item'] as $data)
			{
				$data=(array)$data;
				
				$info=array();
				$info['name']=$data['name'];
				$info['media_id']=$data['media_id'];
				$info['created_at']=$data['update_time'];
				$info['remote_url']=$data['url'];
				
				/*
					media_id
				*/
				$url='https://api.weixin.qq.com/cgi-bin/material/get_material?access_token='.WECHAT_ACCESS_TOKEN;
				$mediaoutput=http_request($url,'{"media_id":"'.$info['media_id'].'"}');

				$err=(array)json_decode($mediaoutput);

				if(!isset($err['errcode']))
				{
					if($type!='video')
					{
						$save_path='upload/'.($type=='image'?'images':$type).'/'.date('Y-m-d').'/';
						$save_file=time().mt_rand().'.'.get_fileext($data['name']);
						@mkdir(PW_ROOT.$save_path,0777);

						file_put_contents(PW_ROOT.$save_path.$save_file,$mediaoutput);

						$info['local_url']=$save_path.$save_file;
					}
					$func='post'.ucfirst($type).'Material';
					$info['description']=$err['description'];
					$info['remote_url']=$err['down_url']?$err['down_url']:$info['remote_url'];
					self::$func($info);
				}
				else
				{
					return 0-$mediaoutput['errcode'];
				}
			}
		}

		return $output;
	}

	
	static public function addMaterial($info)
	{
		return MySql::insert(DB_PRE.self::$mMaterialTable,$info,true);
	}

	static public function materialKeywordList($keyword='')
	{
		$keyword=htmlspecialchars($keyword);
		
		$data=array();

		switch(WECHAT_HELP_MSG_TYPE)
		{
			case 'text':
				$r=MySql::fetchOne("SELECT * FROM `".DB_PRE.self::$mAutoReplyTable."` WHERE `material_type`='text' AND `keyword_type`=1 AND `keyword`='".$keyword."'");
				
				if(!$r)
				{
					$r=MySql::fetchOne("SELECT * FROM `".DB_PRE.self::$mAutoReplyTable."` WHERE `material_type`='text' AND `keyword_type`=0 AND `keyword` LIKE '%".$keyword."%'");
				}
				$data=strip_tags($r['content'],'</a>');
				break;
			case 'image':
				$r=MySql::fetchOne("SELECT * FROM `".DB_PRE.self::$mAutoReplyTable."` WHERE `material_type`='image' AND `keyword_type`=1 AND `keyword`='".$keyword."'");
				if(!$r)
				{
					$r=MySql::fetchOne("SELECT * FROM `".DB_PRE.self::$mAutoReplyTable."` WHERE `material_type`='image' AND `keyword_type`=0 AND `keyword` LIKE '%".$keyword."%'");
				}
				
				if($r)
				{
					$data=self::getImageMaterialByMediaId($r['media_id']);
				}
				break;
			case 'voice':
				$r=MySql::fetchOne("SELECT * FROM `".DB_PRE.self::$mAutoReplyTable."` WHERE `material_type`='voice' AND `keyword_type`=1 AND `keyword`='".$keyword."'");
				if(!$r)
				{
					$r=MySql::fetchOne("SELECT * FROM `".DB_PRE.self::$mAutoReplyTable."` WHERE `material_type`='voice' AND `keyword_type`=0 AND `keyword` LIKE '%".$keyword."%'");
				}
				
				if($r)
				{
					$data=self::getVoiceMaterialByMediaId($r['media_id']);
				}
				break;
			case 'video':
				$r=MySql::fetchOne("SELECT * FROM `".DB_PRE.self::$mAutoReplyTable."` WHERE `material_type`='video' AND `keyword_type`=1 AND `keyword`='".$keyword."'");
				if(!$r)
				{
					$r=MySql::fetchOne("SELECT * FROM `".DB_PRE.self::$mAutoReplyTable."` WHERE `material_type`='video' AND `keyword_type`=0 AND `keyword` LIKE '%".$keyword."%'");
				}
				
				if($r)
				{
					$data=self::getVideoMaterialByMediaId($r['media_id']);
				}
				break;
			default:
				$r=MySql::fetchOne("SELECT * FROM `".DB_PRE.self::$mAutoReplyTable."` WHERE `material_type`='media' AND `keyword_type`=1 AND `keyword`='".$keyword."'");
				if(!$r)
				{
					$r=MySql::fetchOne("SELECT * FROM `".DB_PRE.self::$mAutoReplyTable."` WHERE `material_type`='media' AND `keyword_type`=0 AND `keyword` LIKE '%".$keyword."%'");
				}
				
				if($r)
				{
					$data=self::getMaterialByMediaId($r['media_id']);
				}
				break;
		}

		return $data;
	}

	/*
		表情
	*/
	static public function getEmojiList()
	{
		return MySql::fetchAll("SELECT * FROM `".DB_PRE.self::$mEmojitTable."`");
	}

	static public function getEmoji($emoji='')
	{
		return MySql::fetchOne("SELECT * FROM `".DB_PRE.self::$mEmojitTable."` WHERE `emoji`='".str_callback_w($emoji)."'");
	}

	/*
		粉丝
	*/
	
	static public function groupLimitList()
	{
		return MySql::fetchAll("SELECT * FROM `".DB_PRE.self::$mGroupsTable."` ORDER BY `id` ASC");
	}

	static public function groupList($pagesize=20)
	{
		$where='1';
		$orderby='id ASC';

		$result=DataList::getList(DB_PRE.self::$mGroupsTable,$where,$orderby,max(isset($_GET['page'])?intval($_GET['page']):1,1),intval($pagesize),0,'right');

		self::$mPageString=DataList::$mPageString;

		return $result;
	}

	static public function groupsAdd($info)
	{
		$url='https://api.weixin.qq.com/cgi-bin/groups/create?access_token='.WECHAT_ACCESS_TOKEN;
		$output=(array)json_decode(http_request($url,'{"group":{"name":"'.$info['name'].'"}}'));

		if(isset($output['errcode']))
		{
			return false;
		}

		MySql::insert(DB_PRE.self::$mGroupsTable,(array)$output['group'],true);

		return true;
	}

	static public function groupsDelete($ids)
	{
		$ids=is_array($ids)?array_map('intval',$ids):array(intval($ids));
		if(!$ids)
		{
			return -1;
		}
		
		foreach($ids as $id)
		{
			if($id>=100)
			{
				$url='https://api.weixin.qq.com/cgi-bin/groups/delete?access_token='.WECHAT_ACCESS_TOKEN;
				$output=(array)json_decode(http_request($url,'{"group":{"id":'.$id.'}}'));
				MySql::mysqlDelete(DB_PRE.self::$mGroupsTable,$id);
			}
		}
		return true;
	}

	static public function groupsEdit($ids,$names)
	{
		$ids=is_array($ids)?array_map('intval',$ids):array(intval($ids));
		if(!$ids)
		{
			return -1;
		}
		
		foreach($ids as $id)
		{
			if($id>=100)
			{
				$url='https://api.weixin.qq.com/cgi-bin/groups/update?access_token='.WECHAT_ACCESS_TOKEN;
				$output=(array)json_decode(http_request($url,'{"group":{"id":'.$id.',"name":"'.$names[$id].'"}}'));
				MySql::update(DB_PRE.self::$mGroupsTable,array('name'=>$names[$id]),'id='.$id);
			}
		}
		return true;
	}

	static public function groupsSynchronism()
	{
		$url='https://api.weixin.qq.com/cgi-bin/groups/get?access_token='.WECHAT_ACCESS_TOKEN;
		$output=(array)json_decode(http_request($url));
		
		if(isset($output['errcode']))
		{
			return false;
		}
		
		MySql::query("TRUNCATE ".DB_PRE.self::$mGroupsTable."");

		foreach($output['groups'] as $group)
		{
			MySql::insert(DB_PRE.self::$mGroupsTable,(array)$group,true);
		}

		return true;
	}

	static public function fansSynchronism($next_openid)
	{
		$url='https://api.weixin.qq.com/cgi-bin/user/get?access_token='.WECHAT_ACCESS_TOKEN.'&next_openid='.$next_openid;
		$output=(array)json_decode(http_request($url));
		$data=(array)$output['data'];

		if($data['openid'])
		{
			foreach($data['openid'] as $openid)
			{
				$url='https://api.weixin.qq.com/cgi-bin/user/info?access_token='.WECHAT_ACCESS_TOKEN.'&openid='.$openid.'&lang=zh_CN ';
				$useroutput=(array)json_decode(http_request($url));
				$useroutput['issubscribe']=$useroutput['subscribe'];
				$useroutput['subscribetime']=$useroutput['subscribe_time'];
				
				$r=self::getUserByOpenid($openid);
				if($r)
				{
					MySql::update(DB_PRE.self::$mFansTable,$useroutput,'id='.$r['id']);
				}
				else
				{
					MySql::insert(DB_PRE.self::$mFansTable,$useroutput,true);
				}
			}
		}
		return $output;
	}

	static public function refreshFans($ids=array())
	{
		$ids=is_array($ids)?array_map('intval',$ids):array(intval($ids));
		if(!$ids)
		{
			return -1;
		}
	
		foreach($ids as $id)
		{
			$openid=self::getUser($id,'openid');

			$url='https://api.weixin.qq.com/cgi-bin/user/info?access_token='.WECHAT_ACCESS_TOKEN.'&openid='.$openid.'&lang=zh_CN ';
			$useroutput=(array)json_decode(http_request($url));

			$useroutput['issubscribe']=$useroutput['subscribe'];
			$useroutput['subscribetime']=$useroutput['subscribe_time'];

			MySql::update(DB_PRE.self::$mFansTable,$useroutput,'id='.$id);
		}
		return true;
	}

	static public function getGroup($id=0,$f='*')
	{
		$r=MySql::fetchOne("SELECT * FROM `".DB_PRE.self::$mGroupsTable."` WHERE `id`=".intval($id));

		return $f=='*'?$r:$r[$f];
	}

	static public function userList($groupid=0,$pagesize=10)
	{
		$groupid=intval($groupid);

		$where=$groupid?'groupid='.$groupid:'1';
		$orderby='id DESC';

		$result=DataList::getList(DB_PRE.self::$mFansTable,$where,$orderby,max(isset($_GET['page'])?intval($_GET['page']):1,1),intval($pagesize),0,'right');

		self::$mPageString=DataList::$mPageString;

		return $result;
	}

	static public function getUser($id=0,$f='*')
	{
		$r=MySql::fetchOne("SELECT * FROM `".DB_PRE.self::$mFansTable."` WHERE `id`=".intval($id));

		return $f=='*'?$r:$r[$f];
	}

	static public function getUserByOpenid($openid='',$f='*')
	{
		$r=MySql::fetchOne("SELECT * FROM `".DB_PRE.self::$mFansTable."` WHERE `openid`='".$openid."'");

		return $f=='*'?$r:$r[$f];
	}

	static public function setUserGroup($ids=array(),$gotogroupid=0)
	{
		$gotogroupid=intval($gotogroupid);

		$ids=is_array($ids)?array_map('intval',$ids):array(intval($ids));
		if(!$ids)
		{
			return -1;
		}
		
		foreach($ids as $id)
		{
			$openid=self::getUser($id,'openid');
			
			if($openid)
			{
				$url='https://api.weixin.qq.com/cgi-bin/groups/members/update?access_token='.WECHAT_ACCESS_TOKEN;
				$output=(array)json_decode(http_request($url,'{"openid":"'.$openid.'","to_groupid":'.$gotogroupid.'}'));

				MySql::update(DB_PRE.self::$mFansTable,array('groupid'=>$gotogroupid),'id='.$id);
			}
		}
		return true;
	}

	static public function fansActionList($openid='')
	{
		$where='`FromUserName`=\''.$openid.'\'';
		$orderby='id DESC';

		$result=DataList::getList(DB_PRE.self::$mFansActionTable,$where,$orderby,max(isset($_GET['page'])?intval($_GET['page']):1,1),20,0,'right');

		self::$mPageString=DataList::$mPageString;

		return $result;
	}

	static public function fansActionDelete($ids)
	{
		$ids=is_array($ids)?array_map('intval',$ids):array(intval($ids));
		if(!$ids)
		{
			return -1;
		}
		
		foreach($ids as $id)
		{
			MySql::mysqlDelete(DB_PRE.self::$mFansActionTable,$id);
		}
		return true;
	}

	static public function getUserInput($MsgType='text',$keyword='')
	{
		$data='';

		switch($MsgType)
		{
			case 'text':
				$data=getXmlVal('<Content>','<\/Content>',$keyword);
				break;
			case 'image'://
				$data='<a href="https://api.weixin.qq.com/cgi-bin/media/get?access_token='.WECHAT_ACCESS_TOKEN.'&media_id='.getXmlVal('<MediaId>','<\/MediaId>',$keyword).'" target="_blank">查看图片</a>';
				break;
			case 'location':
				$data='<a href="http://apis.map.qq.com/uri/v1/geocoder?coord='.getXmlVal('<Location_X>','<\/Location_X>',$keyword).','.getXmlVal('<Location_Y>','<\/Location_Y>',$keyword).'&referer=WeChat" target="_blank">查看地图</a>';
				break;
			case 'shortvideo':
				$data='<a href="https://api.weixin.qq.com/cgi-bin/media/get?access_token='.WECHAT_ACCESS_TOKEN.'&media_id='.getXmlVal('<MediaId>','<\/MediaId>',$keyword).'" target="_blank">查看视频</a>';
				break;
			default:
				$data='未知数据';
				break;
		}

		return strip_tags($data,'<a>');
	}

	/*
		二维码
	*/
	static public function sceneqrcodeList($pagesize=20)
	{
		$where='1';
		$orderby='scene_id DESC';

		$result=DataList::getList(DB_PRE.self::$mQrcodeTable,$where,$orderby,max(isset($_GET['page'])?intval($_GET['page']):1,1),intval($pagesize),0,'right');

		self::$mPageString=DataList::$mPageString;

		return $result;
	}

	static public function sceneqrcodeAdd($info)
	{
		$info['createtime']=CLIENT_TIME;
		$info['typeid']=1;

		return MySql::insert(DB_PRE.self::$mQrcodeTable,$info,true);
	}

	static public function sceneqrcodeDelete($ids)
	{
		$ids=is_array($ids)?array_map('intval',$ids):array(intval($ids));
		if(!$ids)
		{
			return -1;
		}
		
		MySql::mysqlDelete(DB_PRE.self::$mQrcodeTable,$ids,'scene_id');

		return true;
	}

	static public function sceneqrcodeEdit($ids,$scene_names,$keywords)
	{
		$ids=is_array($ids)?array_map('intval',$ids):array(intval($ids));
		if(!$ids)
		{
			return -1;
		}
		
		foreach($ids as $id)
		{
			MySql::update(DB_PRE.self::$mQrcodeTable,array('scene_name'=>$scene_names[$id],'keyword'=>$keywords[$id]),'scene_id='.$id);
		}
		return true;
	}

	static public function getSceneqrcode($id,$f='*')
	{
		$r=MySql::fetchOne("SELECT * FROM `".DB_PRE.self::$mQrcodeTable."` WHERE `scene_id`=".intval($id));
		return $f=='*'?$r:$r[$f];
	}

	static public function sceneqrcodeCreate($id)
	{
		$id=intval($id);

		$url='https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token='.WECHAT_ACCESS_TOKEN;
		$output=(array)json_decode(http_request($url,'{"action_name": "QR_LIMIT_SCENE", "action_info": {"scene": {"scene_id": '.$id.'}}}'));
		
		$output['expiretime']=$output['expire_seconds']?(CLIENT_TIME+$output['expire_seconds']):0;
		MySql::update(DB_PRE.self::$mQrcodeTable,$output,'scene_id='.$id);
	}

	static public function sceneqrcodeView($id)
	{
		$id=intval($id);

		return 'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket='.self::getSceneqrcode($id,'ticket');
	}

	static public function sceneqrcodeStatistics($scene_id=0)
	{
		$scene_id=intval($scene_id);

		MySql::query("UPDATE `".DB_PRE.self::$mQrcodeTable."` SET `scan_times`=`scan_times`+1 WHERE `scene_id`=$scene_id");

		$r=MySql::fetchAll("SELECT distinct `FromUserName` FROM `".DB_PRE.self::$mFansActionTable."` WHERE `event`='SCAN' AND `EventKey`=\"'".$scene_id."'\"");
	
		MySql::update(DB_PRE.self::$mQrcodeTable,array('scan_fans'=>sizeof($r)),'scene_id='.$scene_id);

		return true;
	}
}
?>