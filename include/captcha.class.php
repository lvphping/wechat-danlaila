<?php
// +----------------------------------------------------------------------
// | phpWeChat 验证码类 Last modified 2016-03-25 16:27
// +----------------------------------------------------------------------
// | Copyright (c) 2009-2016 phpWeChat http://www.phpwechat.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 骑马的少年 <phpwechat@126.com> <http://www.phpwechat.com>
// +----------------------------------------------------------------------
namespace phpWeChat;

class Captcha
{
	private static $mCaptchaWidth=90;
	private static $mCaptchaHeight=35;
	private static $mCaptchaLen=4;
	private static $mCaptchaCode='abcdefghjklmnpqrstuvwxyABCDEFGHJKLMNPQRSTUVWXY23456789';

	/**
		函数：setCaptcha($mCaptchaWidth=90,$mCaptchaHeight=35,$mCaptchaLen=4) $mCaptchaWidth验证码宽度，默认90  $mCaptchaHeight验证码高度，默认35  $mCaptchaLen 验证码位数，默认4 
		功能：设置验证码参数 
	*/

	public static function setCaptcha($captchaWidth=90,$captchaHeight=35,$captchaLen=4)
	{
		self::$mCaptchaWidth=intval($captchaWidth);
		self::$mCaptchaHeight=intval($captchaHeight);
		self::$mCaptchaLen=intval($captchaLen);
	}

	/**
		函数：checkCaptcha($captcha='')
		功能：验证验证码
	*/

	public static function checkCaptcha($captcha='')
	{
		return strtolower($_SESSION['captcha_code'])==strtolower($captcha);
	}

	/**
		函数：drawCaptcha()
		功能：输出验证码
	*/

	public static function drawCaptcha()
	{
		$im=imagecreatetruecolor(self::$mCaptchaWidth,self::$mCaptchaHeight);
		$bgim = imagecreatefromjpeg(PW_ROOT.'statics/captcha/bgs/'.mt_rand(1,10).'.jpg');
		
		$bg_x=mt_rand(0,100);
		$bg_y=mt_rand(0,40);

		imagecopy($im,$bgim,0,0,$bg_x,$bg_y,$bg_x+self::$mCaptchaWidth,$bg_y+self::$mCaptchaHeight);

		$_SESSION['captcha_code']='';
		for($i=0;$i<self::$mCaptchaLen;$i++)
		{
			$_SESSION['captcha_code'].=self::$mCaptchaCode{mt_rand(0,strlen(self::$mCaptchaCode)-1)};
		}

		$font_ttf=PW_ROOT.'statics/captcha/ttfs/'.mt_rand(1,9).'.ttf';

		for($i=0,$j=5;$i<self::$mCaptchaLen;$i++)
		{
			$array = array(-1,1);
			$p = array_rand($array);
			$an = $array[$p]*mt_rand(1,10);
			$size = self::$mCaptchaHeight-10;
			imagettftext($im, $size, $an, $j,rand(self::$mCaptchaHeight/2+5,self::$mCaptchaHeight),imagecolorallocate($im,rand(0,100),rand(0,100),rand(0,100)), $font_ttf, $_SESSION['captcha_code'][$i]);
			$j+=15;
		}
		header('Content-type:image/png');
		imagepng($im);
		imagedestroy($im);
	}
}
?>