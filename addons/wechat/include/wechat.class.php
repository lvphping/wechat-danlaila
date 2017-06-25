<?php
// +----------------------------------------------------------------------
// | phpWeChat 微信操作类 Last modified 2016/7/5
// +----------------------------------------------------------------------
// | Copyright (c) 2009-2016 phpWeChat http://www.phpwechat.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 骑马的少年 <phpwechat@126.com> <http://www.phpwechat.com>
// +----------------------------------------------------------------------
namespace WeChat;
use phpWeChat\MemoryCache;
use phpWeChat\MySql;

class WeChat
{
	static private $mFansTable='wechat_fans';
	static private $mScanLogTable='wechat_scan_log';
	static private $mWeChatRequestTable='wechat_request';
	
	/**
		获取扫码信息
	*/
	static public function getScanLog($userid=0,$barcode='')
	{
		return MySql::fetchOne("SELECT * FROM `".DB_PRE.self::$mScanLogTable."` WHERE `userid`=".intval($userid)." AND `barcode`='".str_callback_w($barcode)."'");
	}

	public static function setScanLog($log)
	{
		$log=pw_htmlspecialchars($log);

		return MySql::insert(DB_PRE.self::$mScanLogTable,$log);
	}

	/**
		获取Access_token 2016/4/26 17:58
	*/

	static public function getAccessToken()
	{
		$access_token=MemoryCache::get('wechat_access_token');

		if(!$access_token){
			$url='https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.WECHAT_APPID.'&secret='.WECHAT_APPSECRET;
			$output=(array)json_decode(http_request($url));

			$access_token=$output['access_token'];
			MemoryCache::set('wechat_access_token',$access_token,$output['expires_in']);
		}
		return $access_token;
	}

	/**
		获取jsapi_ticket 2016/4/26 17:58
	*/

	static public function getJsapiTicket()
	{
		$jsapi_ticket=MemoryCache::get('wechat_jsapi_ticket');

		if(!$jsapi_ticket)
		{
			$url='https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token='.WECHAT_ACCESS_TOKEN.'&type=jsapi';
			$output=(array)json_decode(http_request($url));
			
			$jsapi_ticket=$output['ticket'];
			MemoryCache::set('wechat_jsapi_ticket',$jsapi_ticket,$output['expires_in']);
		}
		
		return $jsapi_ticket;
	}
    
	/*JDK 用于微信分享*/
	static public function getSignPackage() {
	    $jsapiTicket = $this->getJsapiTicket();
	
	    // 注意 URL 一定要动态获取，不能 hardcode.
	    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
	    $url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	
	    $timestamp = time();
	    $nonceStr = $this->createNonceStr();
	
	    // 这里参数的顺序要按照 key 值 ASCII 码升序排序
	    $string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";
	
	    $signature = sha1($string);
	
	    $signPackage = array(
	      "appId"     => WECHAT_APPID,
	      "nonceStr"  => $nonceStr,
	      "timestamp" => $timestamp,
	      "url"       => $url,
	      "signature" => $signature,
	      "rawString" => $string
	    );
	    return $signPackage; 
	  }
	  
	private function createNonceStr($length = 16) {
	    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
	    $str = "";
	    for ($i = 0; $i < $length; $i++) {
	      $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
	    }
	    return $str;
	  }
	/**
		验证和Listen 2016/4/22 18:43
	*/
	static public function run($token)
	{
		define('TOKEN',$token);

		if (! empty ( $_GET ['echostr'] ) && ! empty ( $_GET ["signature"] ) && ! empty ( $_GET ["nonce"] )) 
		{
			self::valid();
		}
		else
		{
			self::weListen();
		}
	}

	static private function valid()
	{
		$echoStr = $_GET["echostr"];

        if(self::checkSignature())
        {
        	echo $echoStr;
        	exit;
        }
	}

	static private function checkSignature()
	{        
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];
        		
		$token = TOKEN;
		$tmpArr = array($token, $timestamp, $nonce);

		sort($tmpArr, SORT_STRING);
		$tmpStr = implode( $tmpArr );
		$tmpStr = sha1( $tmpStr );

		if( $tmpStr == $signature )
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	static private function weListen()
    {
		$timestamp  = $_GET['timestamp'];
		$nonce = $_GET["nonce"];
		$msg_signature  = $_GET['msg_signature'];
		$encrypt_type = (isset($_GET['encrypt_type']) && ($_GET['encrypt_type'] == 'aes')) ? "aes" : "raw";

        $postStr = file_get_contents("php://input");

        if (!empty($postStr))
        {
			
			if($encrypt_type == 'aes')
			{
				include_once dirname(__FILE__).'/wxBizMsgCrypt/wxBizMsgCrypt.php';
				$wechatEncode=new WXBizMsgCrypt();
				$wechatEncode->set(WECHAT_TOKEN,WECHAT_ENCODINGAESKEY,WECHAT_APPID);

				$decryptMsg = "";
				$errCode = $wechatEncode->DecryptMsg($msg_signature, $timestamp, $nonce, $postStr, $decryptMsg);
				$postStr = $decryptMsg;
			}

            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            self::weChatRequestLog($postObj,$postStr);

            $callClass = 'receive'.ucfirst(trim($postObj->MsgType));
            $callFunc=$callClass.'Func';

            $classFile=str_replace('\\','/',dirname(__FILE__).'/'.$callClass.'.class.php'); 

            if(!is_file($classFile))
            {
            	exit("unknown msg type: ".trim($postObj->MsgType));
            }

            include_once $classFile;

            $callClass="WeChat\\".ucfirst($callClass);
            echo $callClass::$callFunc($postObj);
            exit("");
        }
        else
        {
            exit("");
        }
    }
	
	/**
		信息转换 2016/4/22 18:42
	*/

    static protected function transmitText($object, $content)
    {
        if (!isset($content) || empty($content))
        {
            return "";
        }

        $xmlTpl = "<xml>
    <ToUserName><![CDATA[%s]]></ToUserName>
    <FromUserName><![CDATA[%s]]></FromUserName>
    <CreateTime>%s</CreateTime>
    <MsgType><![CDATA[text]]></MsgType>
    <Content><![CDATA[%s]]></Content>
</xml>";
        $result = sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time(), $content);

        return $result;
    }

    static protected function transmitNews($object, $newsArray)
    {
        if(!is_array($newsArray))
        {
            return "";
        }
        $itemTpl = "<item>
            <Title><![CDATA[%s]]></Title>
            <Description><![CDATA[%s]]></Description>
            <PicUrl><![CDATA[%s]]></PicUrl>
            <Url><![CDATA[%s]]></Url>
        </item>
";
        $item_str = "";
        foreach ($newsArray as $item)
        {
            $item_str .= sprintf($itemTpl, $item['Title'], $item['Description'], $item['PicUrl'], $item['Url']);
        }
        $xmlTpl = "<xml>
    <ToUserName><![CDATA[%s]]></ToUserName>
    <FromUserName><![CDATA[%s]]></FromUserName>
    <CreateTime>%s</CreateTime>
    <MsgType><![CDATA[news]]></MsgType>
    <ArticleCount>%s</ArticleCount>
    <Articles>
$item_str    </Articles>
</xml>";

        $result = sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time(), count($newsArray));
        return $result;
    }

    static protected function transmitMusic($object, $musicArray)
    {
        if(!is_array($musicArray))
        {
            return "";
        }
        $itemTpl = "<Music>
        <Title><![CDATA[%s]]></Title>
        <Description><![CDATA[%s]]></Description>
        <MusicUrl><![CDATA[%s]]></MusicUrl>
        <HQMusicUrl><![CDATA[%s]]></HQMusicUrl>
    </Music>";

        $item_str = sprintf($itemTpl, $musicArray['Title'], $musicArray['Description'], $musicArray['MusicUrl'], $musicArray['HQMusicUrl']);

        $xmlTpl = "<xml>
    <ToUserName><![CDATA[%s]]></ToUserName>
    <FromUserName><![CDATA[%s]]></FromUserName>
    <CreateTime>%s</CreateTime>
    <MsgType><![CDATA[music]]></MsgType>
    $item_str
</xml>";

        $result = sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time());
        return $result;
    }

    static protected function transmitImage($object, $imageArray)
    {
        $itemTpl = "<Image>
        <MediaId><![CDATA[%s]]></MediaId>
    </Image>";

        $item_str = sprintf($itemTpl, $imageArray['MediaId']);

        $xmlTpl = "<xml>
    <ToUserName><![CDATA[%s]]></ToUserName>
    <FromUserName><![CDATA[%s]]></FromUserName>
    <CreateTime>%s</CreateTime>
    <MsgType><![CDATA[image]]></MsgType>
    $item_str
</xml>";

        $result = sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time());
        return $result;
    }

    static protected function transmitVoice($object, $voiceArray)
    {
        $itemTpl = "<Voice>
        <MediaId><![CDATA[%s]]></MediaId>
    </Voice>";

        $item_str = sprintf($itemTpl, $voiceArray['MediaId']);
        $xmlTpl = "<xml>
    <ToUserName><![CDATA[%s]]></ToUserName>
    <FromUserName><![CDATA[%s]]></FromUserName>
    <CreateTime>%s</CreateTime>
    <MsgType><![CDATA[voice]]></MsgType>
    $item_str
</xml>";

        $result = sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time());
        return $result;
    }

    static protected function transmitVideo($object, $videoArray)
    {
        $itemTpl = "<Video>
        <MediaId><![CDATA[%s]]></MediaId>
        <ThumbMediaId><![CDATA[%s]]></ThumbMediaId>
        <Title><![CDATA[%s]]></Title>
        <Description><![CDATA[%s]]></Description>
    </Video>";

        $item_str = sprintf($itemTpl, $videoArray['MediaId'], $videoArray['ThumbMediaId'], $videoArray['Title'], $videoArray['Description']);

        $xmlTpl = "<xml>
    <ToUserName><![CDATA[%s]]></ToUserName>
    <FromUserName><![CDATA[%s]]></FromUserName>
    <CreateTime>%s</CreateTime>
    <MsgType><![CDATA[video]]></MsgType>
    $item_str
</xml>";

        $result = sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time());
        return $result;
    }

    static protected function transmitService($object)
    {
        $xmlTpl = "<xml>
    <ToUserName><![CDATA[%s]]></ToUserName>
    <FromUserName><![CDATA[%s]]></FromUserName>
    <CreateTime>%s</CreateTime>
    <MsgType><![CDATA[transfer_customer_service]]></MsgType>
</xml>";
        $result = sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time());
        return $result;
    }

    static protected function bytesToEmoji($cp)
    {
		$cp=$cp+0;
        if ($cp > 0x10000){       # 4 bytes
            return chr(0xF0 | (($cp & 0x1C0000) >> 18)).chr(0x80 | (($cp & 0x3F000) >> 12)).chr(0x80 | (($cp & 0xFC0) >> 6)).chr(0x80 | ($cp & 0x3F));
        }else if ($cp > 0x800){   # 3 bytes
            return chr(0xE0 | (($cp & 0xF000) >> 12)).chr(0x80 | (($cp & 0xFC0) >> 6)).chr(0x80 | ($cp & 0x3F));
        }else if ($cp > 0x80){    # 2 bytes
            return chr(0xC0 | (($cp & 0x7C0) >> 6)).chr(0x80 | ($cp & 0x3F));
        }else{                    # 1 byte
            return chr($cp);
        }
    }

	/**
		行为日志 2016/4/22 18:41
	*/

    static protected function weChatRequestLog($log,$postStr='')
    {
        $log=(array)$log;
        $log['ip']=CLIENT_IP;
		$log['keyword']=$postStr;
        $log['EventKey']=var_export($log['EventKey'],true);

       MySql::insert(DB_PRE.self::$mWeChatRequestTable,$log);
    }

	/**
		粉丝行为 2016/4/22 18:41
	*/
	static public function subscribeFans($object)
	{
		$r=self::getFans($object);
		
		$url='https://api.weixin.qq.com/cgi-bin/user/info?access_token='.WECHAT_ACCESS_TOKEN.'&openid='.$object->FromUserName.'&lang=zh_CN ';
		$output=(array)json_decode(http_request($url));
		$output['subscribetime']=CLIENT_TIME;
		$output['issubscribe']=$output['subscribe'];

		$_SESSION['openid']=$object->FromUserName;
		if(!$r)
		{
			return MySql::insert(DB_PRE.self::$mFansTable,$output,true);
		}
		else
		{
			return MySql::update(DB_PRE.self::$mFansTable,$output,'openid=\''.$object->FromUserName.'\'');
		}
	}

	static public function unsubscribeFans($object)
	{
		return MySql::update(DB_PRE.self::$mFansTable,array('issubscribe'=>0),'openid=\''.$object->FromUserName.'\'');
	}

	static public function getFans($object)
	{
		return MySql::fetchOne("SELECT * FROM ".DB_PRE.self::$mFansTable." WHERE `openid`='".$object->FromUserName."'");
	}

	static public function getFansByOpenid($openid,$f='*')
	{
		$r=MySql::fetchOne("SELECT * FROM ".DB_PRE.self::$mFansTable." WHERE `openid`='".$openid."'");
		return $f=='*'?$r:$r[$f];
	}

	/**
		网页授权 2016/4/25
	*/
	
	static public function getOpenID()
	{
		if($_SESSION['openid'])
		{
			return $_SESSION['openid'];
		}
		else
		{
			$baseUrl = urlencode('http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING']);

			if(!isset($_GET['code']))
			{
				$url='https://open.weixin.qq.com/connect/oauth2/authorize?appid='.WECHAT_APPID.'&redirect_uri='.$baseUrl.'&response_type=code&scope=snsapi_base#wechat_redirect';
				
				header("location:$url");
				exit();
			}

			if(isset($_GET['code']))
			{
				$url='https://api.weixin.qq.com/sns/oauth2/access_token?appid='.WECHAT_APPID.'&secret='.WECHAT_APPSECRET.'&code='.$_GET['code'].'&grant_type=authorization_code';
				
				$output=(array)json_decode(http_request($url));
				$_SESSION['openid']=$output['openid'];
				
				return $output['openid'];
			}
		}
	}

	static public function wechatAuth($redirect_uri='',$go_uri='')
	{
		if(!$go_uri)
		{
			$go_uri=get_cookie('redirect_url');
		}
/*
		if($_SESSION['openid'])
		{
			header("location:".format_url(urlsafe_b64decode($go_uri)));
			exit();
		}
*/
		if(!isset($_GET['code']) && !isset($_GET['access_token']))
		{
			$url='https://open.weixin.qq.com/connect/oauth2/authorize?appid='.WECHAT_APPID.'&redirect_uri='.urlencode(format_url($redirect_uri)).'&response_type=code&scope=snsapi_userinfo#wechat_redirect';
			header("location:$url");
			exit();
		}
			
		if(isset($_GET['code']))
		{
			$url='https://api.weixin.qq.com/sns/oauth2/access_token?appid='.WECHAT_APPID.'&secret='.WECHAT_APPSECRET.'&code='.$_GET['code'].'&grant_type=authorization_code';
			$output=(array)json_decode(http_request($url));
			$_SESSION['openid']=$output['openid'];

			$url='https://api.weixin.qq.com/sns/userinfo?access_token='.$output['access_token'].'&openid='.$output['openid'].'&lang=zh_CN';
			$output=(array)json_decode(http_request($url));

			if(!isset($output['errcode']))
			{
				$r=self::getFansByOpenid($output['openid']);
				if($r)
				{
					MySql::update(DB_PRE.self::$mFansTable,$output,'id='.$r['id']);
				}
				else
				{
					$output['issubscribe']=0;
					MySql::insert(DB_PRE.self::$mFansTable,$output);
				}
			}

			header("location:".format_url(urlsafe_b64decode($go_uri)));
			exit();
		}
	}
	
	/*
		发送模板消息
	*/
	static public function sendTmplMsg($template_id='',$data=array())
	{
		$tlp=WeChatManage::getTmplMsg($template_id);
		
		$xml=array('touser'=>$data['openid'],'template_id'=>$template_id,'url'=>urlencode($data['url']));
		foreach($data as $key =>$val)
		{
			$xml['data'][$key]=array('value'=>urlencode($val),'color'=>'#173177');
		}
		$xml=urldecode(stripslashes(json_encode($xml)));
		
		$url='https://api.weixin.qq.com/cgi-bin/message/template/send?access_token='.WECHAT_ACCESS_TOKEN;
		return (array)json_decode(http_request($url,$xml));	
	}
}
?>