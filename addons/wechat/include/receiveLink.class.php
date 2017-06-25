<?php
namespace WeChat;

class ReceiveLink extends WeChat
{
	static public function receiveLinkFunc($object)
    {
		$timestamp  = $_GET['timestamp'];
		$nonce = $_GET["nonce"];
		$msg_signature  = $_GET['msg_signature'];
		$encrypt_type = (isset($_GET['encrypt_type']) && ($_GET['encrypt_type'] == 'aes')) ? "aes" : "raw";

        $content = "你发送的是链接，标题为：".$object->Title."；内容为：".$object->Description."；链接地址为：".$object->Url;
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