<?php
// +----------------------------------------------------------------------
// | phpWeChat 微信接收事件消息操作类 Last modified 2016/4/21 21:39
// +----------------------------------------------------------------------
// | Copyright (c) 2009-2016 phpWeChat http://www.phpwechat.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 骑马的少年 <phpwechat@126.com> <http://www.phpwechat.com>
// +----------------------------------------------------------------------
namespace WeChat;

class ReceiveEvent extends WeChat
{
	static public function receiveEventFunc($object)
    {
		$timestamp  = $_GET['timestamp'];
		$nonce = $_GET["nonce"];
		$msg_signature  = $_GET['msg_signature'];
		$encrypt_type = (isset($_GET['encrypt_type']) && ($_GET['encrypt_type'] == 'aes')) ? "aes" : "raw";

        $content = "";
        switch ($object->Event)
        {
            case "subscribe":
				self::subscribeFans($object);

				switch(WECHAT_SUBSCRIBE_MSG_TYPE)
				{
					case 'image':
						$content = array();
						$content = array("MsgType"=>"image","MediaId"=>WECHAT_SUBSCRIBE_MSG_VALUE);
						break 2;
					case 'voice':
						$r=WeChatManage::getVoiceMaterialByMediaId(WECHAT_SUBSCRIBE_MSG_VALUE);

						$content = array();
						$content = array("MsgType"=>"voice","Title"=>$r['name'], "Description"=>"来自：".WECHAT_NAME, "MusicUrl"=>format_url($r['local_url']), "HQMusicUrl"=>format_url($r['local_url'])); 
						break 2;
					case 'video':
						$r=WeChatManage::getVideoMaterialByMediaId(WECHAT_SUBSCRIBE_MSG_VALUE);

						$content = array();
						$content = array("MsgType"=>"video","MediaId"=>WECHAT_SUBSCRIBE_MSG_VALUE, "ThumbMediaId"=>"", "Title"=>$r['name'], "Description"=>$r['description']);
						break 2;
					case 'media':
						$r=WeChatManage::getMaterialByMediaId(WECHAT_SUBSCRIBE_MSG_VALUE);

						$content = array();
						$content[] = array("MsgType"=>"news","Title"=>$r['Title'],  "Description"=>$r['Description'], "PicUrl"=>format_url($r['PicUrl']), "Url" =>$r['Url']?$r['Url']:format_url(U(MOD,'view',array('id'=>$r['id']))));

						$c=WeChatManage::getMutiMaterial($r['id']);
						foreach($c as $_c)
						{
							$content[] = array("MsgType"=>"news","Title"=>$_c['Title'],  "Description"=>$_c['Description'], "PicUrl"=>format_url($_c['PicUrl']), "Url" =>$_c['Url']?$_c['Url']:format_url(U(MOD,'view',array('id'=>$_c['id']))));
						}
						break 2;
					case 'text':
						$content = '';
						$content=strip_tags(WECHAT_SUBSCRIBE_MSG_TEXT,'<a>');

						preg_match_all('/\[([0-9a-z_]+)\]/i',$content,$matches);
						foreach($matches[1] as $emoji)
						{
							$r=WeChatManage::getEmoji($emoji);
							$content=str_replace('['.$emoji.']',self::bytesToEmoji($r['utf16']),$content);
						}
					
						break 2;
				}

                break;
            case "unsubscribe":
				self::unsubscribeFans($object);
                break;
            case "CLICK":
                $keyword = $object->EventKey;
				$replydata=WeChatManage::materialKeywordList($keyword);

				if($replydata)
				{
					switch(WECHAT_HELP_MSG_TYPE)
					{
						case 'image':
							$content = array();
							$content = array("MsgType"=>"image","MediaId"=>$replydata['media_id']);
							break 2;
						case 'voice':
							$content = array();
							$content = array("MsgType"=>"voice","Title"=>$replydata['name'], "Description"=>"来自：".WECHAT_NAME, "MusicUrl"=>format_url($replydata['local_url']), "HQMusicUrl"=>format_url($replydata['local_url'])); 
							break 2;
						case 'video':
							$content = array();
							$content = array("MsgType"=>"video","MediaId"=>$replydata['media_id'], "ThumbMediaId"=>"", "Title"=>$replydata['name'], "Description"=>$replydata['description']);
							break 2;
						case 'media':
							$r=WeChatManage::getMaterialByMediaId($replydata['media_id']);

							$content = array();
							$content[] = array("MsgType"=>"news","Title"=>$r['Title'],  "Description"=>$r['Description'], "PicUrl"=>format_url($r['PicUrl']), "Url" =>$r['Url']?$r['Url']:format_url(U(MOD,'view',array('id'=>$r['id']))));

							$c=WeChatManage::getMutiMaterial($r['id']);
							foreach($c as $_c)
							{
								$content[] = array("MsgType"=>"news","Title"=>$_c['Title'],  "Description"=>$_c['Description'], "PicUrl"=>format_url($_c['PicUrl']), "Url" =>$_c['Url']?$_c['Url']:format_url(U(MOD,'view',array('id'=>$_c['id']))));
							}
							break 2;
						case 'text':
							$content = '';
							$content=strip_tags($replydata,'<a>');

							preg_match_all('/\[([0-9a-z_]+)\]/i',$content,$matches);
							foreach($matches[1] as $emoji)
							{
								$r=WeChatManage::getEmoji($emoji);
								$content=str_replace('['.$emoji.']',self::bytesToEmoji($r['utf16']),$content);
							}
						
							break 2;
					}
				}
				else
				{
					//自动回复模式 
					if (WECHAT_ISXIAODOUBI_ON)
					{
						include_once dirname(__FILE__).'/robot.class.php';
						$content=Robot::talk($keyword);
					}
					else
					{
						switch(WECHAT_AUTO_MSG_TYPE)
						{
							case 'image':
								$content = array();
								$content = array("MsgType"=>"image","MediaId"=>WECHAT_AUTO_MSG_VALUE);
								break 2;
							case 'voice':
								$r=WeChatManage::getVoiceMaterialByMediaId(WECHAT_AUTO_MSG_VALUE);

								$content = array();
								$content = array("MsgType"=>"voice","Title"=>$r['name'], "Description"=>"来自：".WECHAT_NAME, "MusicUrl"=>format_url($r['local_url']), "HQMusicUrl"=>format_url($r['local_url'])); 
								break 2;
							case 'video':
								$r=WeChatManage::getVideoMaterialByMediaId(WECHAT_AUTO_MSG_VALUE);

								$content = array();
								$content = array("MsgType"=>"video","MediaId"=>WECHAT_AUTO_MSG_VALUE, "ThumbMediaId"=>"", "Title"=>$r['name'], "Description"=>$r['description']);
								break 2;
							case 'media':
								$r=WeChatManage::getMaterialByMediaId(WECHAT_AUTO_MSG_VALUE);

								$content = array();
								$content[] = array("MsgType"=>"news","Title"=>$r['Title'],  "Description"=>$r['Description'], "PicUrl"=>format_url($r['PicUrl']), "Url" =>$r['Url']?$r['Url']:format_url(U(MOD,'view',array('id'=>$r['id']))));

								$c=WeChatManage::getMutiMaterial($r['id']);
								foreach($c as $_c)
								{
									$content[] = array("MsgType"=>"news","Title"=>$_c['Title'],  "Description"=>$_c['Description'], "PicUrl"=>format_url($_c['PicUrl']), "Url" =>$_c['Url']?$_c['Url']:format_url(U(MOD,'view',array('id'=>$_c['id']))));
								}
								break 2;
							case 'text':
								$content = '';
								$content=strip_tags(WECHAT_AUTO_MSG_TEXT,'<a>');

								preg_match_all('/\[([0-9a-z_]+)\]/i',$content,$matches);
								foreach($matches[1] as $emoji)
								{
									$r=WeChatManage::getEmoji($emoji);
									$content=str_replace('['.$emoji.']',self::bytesToEmoji($r['utf16']),$content);
								}
							
								break 2;
						}
					}
				}
                break;
            case "VIEW":
                $content = "跳转链接 ".$object->EventKey;
                break;
            case "SCAN":
				WeChatManage::sceneqrcodeStatistics($object->EventKey);
                $content = "扫描场景 ".$object->EventKey;
                break;
            case "LOCATION":
                //$content = "上传位置：纬度 ".$object->Latitude.";经度 ".$object->Longitude;
                break;
            case "scancode_waitmsg":
                if ($object->ScanCodeInfo->ScanType == "qrcode"){
                    $content = "扫码带提示：类型 二维码 结果：".$object->ScanCodeInfo->ScanResult;
                }else if ($object->ScanCodeInfo->ScanType == "barcode"){
                    $codeinfo = explode(",",strval($object->ScanCodeInfo->ScanResult));
                    $codeValue = $codeinfo[1];
                    $content = "扫码带提示：类型 条形码 结果：".$codeValue;
                }else{
                    $content = "扫码带提示：类型 ".$object->ScanCodeInfo->ScanType." 结果：".$object->ScanCodeInfo->ScanResult;
                }
                break;
            case "scancode_push":
                $content = "扫码推事件";
                break;
            case "pic_sysphoto":
                $content = "系统拍照";
                break;
            case "pic_weixin":
                $content = "相册发图：数量 ".$object->SendPicsInfo->Count;
                break;
            case "pic_photo_or_album":
                $content = "拍照或者相册：数量 ".$object->SendPicsInfo->Count;
                break;
            case "location_select":
                $content = "发送位置：标签 ".$object->SendLocationInfo->Label;
                break;
            default:
                $content = "receive a new event: ".$object->Event;
                break;
        }

        if(is_array($content))
		{
			if(is_array($content[0]))
			{
				switch($content[0]['MsgType'])
				{
					case 'image':
						$result = self::transmitImage($object, $content);
						break;
					case 'voice':
						$result = self::transmitMusic($object, $content);
						break;
					case 'video':
						$result = self::transmitVideo($object, $content);
						break;
					case 'news':
						$result = self::transmitNews($object, $content);
						break;
				}
			}
			else
			{
				switch($content['MsgType'])
				{
					case 'image':
						$result = self::transmitImage($object, $content);
						break;
					case 'voice':
						$result = self::transmitMusic($object, $content);
						break;
					case 'video':
						$result = self::transmitVideo($object, $content);
						break;
					case 'news':
						$result = self::transmitNews($object, $content);
						break;
				}
			}
        }
		else
		{
            $result = self::transmitText($object, $content);
        }

		if ($encrypt_type == 'aes')
		{
			include_once dirname(__FILE__).'/wxBizMsgCrypt/wxBizMsgCrypt.php';
			$wechatEncode=new WXBizMsgCrypt();
			$wechatEncode->set(WECHAT_TOKEN,WECHAT_ENCODINGAESKEY,WECHAT_APPID);

			$encryptMsg = '';
			$errCode = $wechatEncode->encryptMsg($result, $timestamp, $nonce, $encryptMsg);
			$result = $encryptMsg;
		}
        return $result;
    }
}
?>