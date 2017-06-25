<?php
namespace WeChat;

class ReceiveText extends WeChat
{
	static public function receiveTextFunc($object)
    {
		$timestamp  = $_GET['timestamp'];
		$nonce = $_GET["nonce"];
		$msg_signature  = $_GET['msg_signature'];
		$encrypt_type = (isset($_GET['encrypt_type']) && ($_GET['encrypt_type'] == 'aes')) ? "aes" : "raw";

		$content = "";
        $keyword = trim($object->Content);

        if (strstr($keyword, TO_KF_KEYWORD)){
            $result = self::transmitService($object);

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
		
		$replydata=WeChatManage::materialKeywordList($keyword);

		if($replydata)
		{
			switch(WECHAT_HELP_MSG_TYPE)
            {
                case 'image':
                    $content = array();
                    $content = array("MsgType"=>"image","MediaId"=>$replydata['media_id']);
                    break;
                case 'voice':
                    $content = array();
                    $content = array("MsgType"=>"voice","Title"=>$replydata['name'], "Description"=>"来自：".WECHAT_NAME, "MusicUrl"=>format_url($replydata['local_url']), "HQMusicUrl"=>format_url($replydata['local_url'])); 
                    break;
                case 'video':
                    $content = array();
                    $content = array("MsgType"=>"video","MediaId"=>$replydata['media_id'], "ThumbMediaId"=>"", "Title"=>$replydata['name'], "Description"=>$replydata['description']);
                    break;
                case 'media':
                    $r=WeChatManage::getMaterialByMediaId($replydata['media_id']);

                    $content = array();
                    $content[] = array("MsgType"=>"news","Title"=>$r['Title'],  "Description"=>$r['Description'], "PicUrl"=>format_url($r['PicUrl']), "Url" =>$r['Url']?$r['Url']:format_url(U(MOD,'view',array('id'=>$r['id']))));

                    $c=WeChatManage::getMutiMaterial($r['id']);
                    foreach($c as $_c)
                    {
                        $content[] = array("MsgType"=>"news","Title"=>$_c['Title'],  "Description"=>$_c['Description'], "PicUrl"=>format_url($_c['PicUrl']), "Url" =>$_c['Url']?$_c['Url']:format_url(U(MOD,'view',array('id'=>$_c['id']))));
                    }
                    break;
                case 'text':
                    $content = '';
                    $content=strip_tags($replydata,'<a>');

                    preg_match_all('/\[([0-9a-z_]+)\]/i',$content,$matches);
                    foreach($matches[1] as $emoji)
                    {
                        $r=WeChatManage::getEmoji($emoji);
                        $content=str_replace('['.$emoji.']',self::bytesToEmoji($r['utf16']),$content);
                    }
                
                    break;
            }
		}
		else
		{
			//自动回复模式  // $content = array("Title"=>"111", "Description"=>"222", "MusicUrl"=>"http://121.199.4.61/music/zxmzf.mp3", "HQMusicUrl"=>"http://121.199.4.61/music/zxmzf.mp3"); 
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
						break;
					case 'voice':
						$r=WeChatManage::getVoiceMaterialByMediaId(WECHAT_AUTO_MSG_VALUE);

						$content = array();
						$content = array("MsgType"=>"voice","Title"=>$r['name'], "Description"=>"来自：".WECHAT_NAME, "MusicUrl"=>format_url($r['local_url']), "HQMusicUrl"=>format_url($r['local_url'])); 
						break;
					case 'video':
						$r=WeChatManage::getVideoMaterialByMediaId(WECHAT_AUTO_MSG_VALUE);

						$content = array();
						$content = array("MsgType"=>"video","MediaId"=>WECHAT_AUTO_MSG_VALUE, "ThumbMediaId"=>"", "Title"=>$r['name'], "Description"=>$r['description']);
						break;
					case 'media':
						$r=WeChatManage::getMaterialByMediaId(WECHAT_AUTO_MSG_VALUE);

						$content = array();
						$content[] = array("MsgType"=>"news","Title"=>$r['Title'],  "Description"=>$r['Description'], "PicUrl"=>format_url($r['PicUrl']), "Url" =>$r['Url']?$r['Url']:format_url(U(MOD,'view',array('id'=>$r['id']))));

						$c=WeChatManage::getMutiMaterial($r['id']);
						foreach($c as $_c)
						{
							$content[] = array("MsgType"=>"news","Title"=>$_c['Title'],  "Description"=>$_c['Description'], "PicUrl"=>format_url($_c['PicUrl']), "Url" =>$_c['Url']?$_c['Url']:format_url(U(MOD,'view',array('id'=>$_c['id']))));
						}
						break;
					case 'text':
						$content = '';
						$content=strip_tags(WECHAT_AUTO_MSG_TEXT,'<a>');

						preg_match_all('/\[([0-9a-z_]+)\]/i',$content,$matches);
						foreach($matches[1] as $emoji)
						{
							$r=WeChatManage::getEmoji($emoji);
							$content=str_replace('['.$emoji.']',self::bytesToEmoji($r['utf16']),$content);
						}
						break;
                }
			}
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