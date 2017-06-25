<?php
namespace WeChat;

class ReceiveLocation extends WeChat
{
	static public function receiveLocationFunc($object)
    {
		$timestamp  = $_GET['timestamp'];
		$nonce = $_GET["nonce"];
		$msg_signature  = $_GET['msg_signature'];
		$encrypt_type = (isset($_GET['encrypt_type']) && ($_GET['encrypt_type'] == 'aes')) ? "aes" : "raw";

        $content = "你发送的是位置，经度为：".$object->Location_Y."；纬度为：".$object->Location_X."；缩放级别为：".$object->Scale."；位置为：".$object->Label;
        $result = self::transmitText($object, $content);

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