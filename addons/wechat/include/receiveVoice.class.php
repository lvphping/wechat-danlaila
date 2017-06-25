<?php
namespace WeChat;

class ReceiveVoice extends WeChat
{
	static public function receiveVoiceFunc($object)
    {
		$timestamp  = $_GET['timestamp'];
		$nonce = $_GET["nonce"];
		$msg_signature  = $_GET['msg_signature'];
		$encrypt_type = (isset($_GET['encrypt_type']) && ($_GET['encrypt_type'] == 'aes')) ? "aes" : "raw";

        if (isset($object->Recognition) && !empty($object->Recognition))
		{
            $content = "你刚才说的是：".$object->Recognition;
            $result = self::transmitText($object, $content);
        }
		else
		{
            $content = array("MediaId"=>$object->MediaId);
            $result = self::transmitVoice($object, $content);
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