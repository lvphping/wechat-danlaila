<?php
// +----------------------------------------------------------------------
// | phpWeChat 验证码文件 Last modified 2016-03-25 16:45
// +----------------------------------------------------------------------
// | Copyright (c) 2009-2016 phpWeChat http://www.phpwechat.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 骑马的少年 <phpwechat@126.com> <http://www.phpwechat.com>
// +----------------------------------------------------------------------
use phpWeChat\Captcha;

require substr(dirname(__FILE__),0,-12).'/include/common.inc.php';

Captcha::setCaptcha(CAPTCHA_WIDTH,CAPTCHA_HEIGHT,CAPTCHA_LEN);
Captcha::drawCaptcha();
?>