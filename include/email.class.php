<?php
// +----------------------------------------------------------------------
// | phpWeChat 邮件操作类 Last modified 2016-04-05 14:13
// +----------------------------------------------------------------------
// | Copyright (c) 2009-2016 phpWeChat http://www.phpwechat.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 骑马的少年 <phpwechat@126.com> <http://www.phpwechat.com>

// | 使用格式：Email::send('收件人账号','邮件主题','邮件内容','邮件来源');
// +----------------------------------------------------------------------
namespace phpWeChat;

class Email
{
	static private $type='';
	static private $server='';
	static private $port=25;
	static private $user='';
	static private $password='';
	static private $delimiter=1;
	static private $mailusername='';
	static private $mCharset="UTF-8";
	static public  $mError=array();

	static public function set($server, $port, $user, $password, $type = 1, $delimiter = 1, $mailusername = 0)
	{
		self::$type = $type;
		self::$server = $server;
		self::$port = $port;
		self::$user = $user;
		self::$password = $password;
        self::$delimiter = $delimiter == 1 ? "\r\n" : ($delimiter == 2 ? "\r" : "\n");
		self::$mailusername = $mailusername;
	}
	
	static public function send($email_to, $email_subject, $email_message, $email_from = '')
	{
		global $PW;
		$email_subject = '=?'.self::$mCharset.'?B?'.base64_encode(str_replace("\r", '', $email_subject)).'?=';
		$email_message = str_replace("\r\n.", " \r\n..", str_replace("\n", "\r\n", str_replace("\r", "\n", str_replace("\r\n", "\n", str_replace("\n\r", "\r", $email_message)))));
		$adminemail = self::$type == 1 ? $PW['mail_user'] : $PW['mail_user'];
		$email_from = $email_from == '' ? '=?'.self::$mCharset.'?B?'.base64_encode($PW['sitename'])."?= <$adminemail>" : (preg_match('/^(.+?) \<(.+?)\>$/',$email_from, $from) ? '=?'.self::$mCharset.'?B?'.base64_encode($from[1])."?= <$from[2]>" : $email_from);
		$emails = explode(',', $email_to);
		foreach($emails as $touser)
		{
			$tousers[] = preg_match('/^(.+?) \<(.+?)\>$/',$touser, $to) ? (self::$mailusername ? '=?'.self::$mCharset.'?B?'.base64_encode($to[1])."?= <$to[2]>" : $to[2]) : $touser;
		}
		$email_to = implode(',', $tousers);
		$headers = "From: $email_from".self::$delimiter."X-Priority: 3".self::$delimiter."X-Mailer: DeDeChat ".self::$delimiter."MIME-Version: 1.0".self::$delimiter."Content-type: text/html; charset=".self::$mCharset.self::$delimiter;

		if(self::$type == 1)
		{
			return self::smtp($email_to, $email_subject, $email_message, $email_from, $headers);
		}
		elseif(self::$type == 2)
		{
			return @mail($email_to, $email_subject, $email_message, $headers);
		}
		else
		{
			ini_set('SMTP', self::$server);
			ini_set('smtp_port', self::$port);
			ini_set('sendmail_from', $email_from);
			return @mail($email_to, $email_subject, $email_message, $headers);
		}
	}

	static public function smtp($email_to, $email_subject, $email_message, $email_from = '', $headers = '')
	{
		if(!$fp = fsockopen(self::$server, self::$port, $errno, $errstr, 10))
		{
			self::errorlog('SMTP', "(self::$server:self::$port) CONNECT - Unable to connect to the SMTP server", 0);
			return false;
		}
		stream_set_blocking($fp, true);
		$lastmessage = fgets($fp, 512);
		if(substr($lastmessage, 0, 3) != '220')
		{
			self::errorlog('SMTP', "self::$server:self::$port CONNECT - $lastmessage", 0);
			return false;
		}
		fputs($fp, "EHLO DIRCMS\r\n");
		$lastmessage = fgets($fp, 512);
		if(substr($lastmessage, 0, 3) != 220 && substr($lastmessage, 0, 3) != 250)
		{
			self::errorlog('SMTP', "(self::$server:self::$port) HELO/EHLO - $lastmessage", 0);
			return false;
		}
		while(1)
		{
			if(substr($lastmessage, 3, 1) != '-' || empty($lastmessage))
			{
				break;
			}
			$lastmessage = fgets($fp, 512);
		}
		fputs($fp, "AUTH LOGIN\r\n");
		$lastmessage = fgets($fp, 512);
		if(substr($lastmessage, 0, 3) != 334)
		{
			self::errorlog('SMTP', "(self::$server:self::$port) AUTH LOGIN - $lastmessage", 0);
			return false;
		}
		fputs($fp, base64_encode(self::$user)."\r\n");
		$lastmessage = fgets($fp, 512);
		if(substr($lastmessage, 0, 3) != 334)
		{
			self::errorlog('SMTP', "(self::$server:self::$port) USERNAME - $lastmessage", 0);
			return false;
		}

		fputs($fp, base64_encode(self::$password)."\r\n");
		$lastmessage = fgets($fp, 512);

		if(substr($lastmessage, 0, 3) != 235)
		{
			self::errorlog('SMTP', "(self::$server:self::$port) PASSWORD - $lastmessage", 0);
			return false;
		}
		fputs($fp, "MAIL FROM: <".preg_replace("/.*\<(.+?)\>.*/", "\\1", $email_from).">\r\n");
		$lastmessage = fgets($fp, 512);
		if(substr($lastmessage, 0, 3) != 250)
		{
			fputs($fp, "MAIL FROM: <".preg_replace("/.*\<(.+?)\>.*/", "\\1", $email_from).">\r\n");
			$lastmessage = fgets($fp, 512);
			if(substr($lastmessage, 0, 3) != 250)
			{
				self::errorlog('SMTP', "(self::$server:self::$port) MAIL FROM - $lastmessage", 0);
				return false;
			}
		}
		$email_tos = array();
		$emails = explode(',', $email_to);
		foreach($emails as $touser)
		{
			$touser = trim($touser);
			if($touser) 
			{
				fputs($fp, "RCPT TO: <".preg_replace("/.*\<(.+?)\>.*/", "\\1", $touser).">\r\n");
				$lastmessage = fgets($fp, 512);
				if(substr($lastmessage, 0, 3) != 250)
				{
					fputs($fp, "RCPT TO: <".preg_replace("/.*\<(.+?)\>.*/", "\\1", $touser).">\r\n");
					$lastmessage = fgets($fp, 512);
					self::errorlog('SMTP', "(self::$server:self::$port) RCPT TO - $lastmessage", 0);
					return false;
				}
			}
		}
		fputs($fp, "DATA\r\n");
		$lastmessage = fgets($fp, 512);
		if(substr($lastmessage, 0, 3) != 354)
		{
			self::errorlog('SMTP', "(self::$server:self::$port) DATA - $lastmessage", 0);
		}
		$headers .= 'Message-ID: <'.gmdate('YmdHs').'.'.substr(md5($email_message.microtime()), 0, 6).rand(100000, 999999).'@'.$_SERVER['HTTP_HOST'].">{self::$delimiter}";
		fputs($fp, "Date: ".gmdate('r')."\r\n");
		fputs($fp, "To: ".$email_to."\r\n");
		fputs($fp, "Subject: ".$email_subject."\r\n");
		fputs($fp, $headers."\r\n");
		fputs($fp, "\r\n\r\n");
		fputs($fp, "$email_message\r\n.\r\n");
		fputs($fp, "QUIT\r\n");
		return true;
	}

	static public function errorlog($type, $message, $is)
	{
		self::$mError= array($type, $message, $is);
	}
}
?>