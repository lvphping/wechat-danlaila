<?php
// +----------------------------------------------------------------------
// | phpWeChat 公共文件 Last modified 2016-03-25 10:41
// +----------------------------------------------------------------------
// | Copyright (c) 2009-2016 phpWeChat http://www.phpwechat.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 骑马的少年 <phpwechat@126.com> <http://www.phpwechat.com>
// +----------------------------------------------------------------------
PHP_VERSION < '5.3' && exit("PHP 5.3+ is required!");

define('IN_SYS',true);

/**
 * 系统调试设置
 * 项目正式部署后请设置为false
 */
define('IN_DEBUG',false);
define('IN_LOG',true);

!IN_DEBUG?error_reporting (E_ALL & ~E_NOTICE & ~E_WARNING):error_reporting (E_ALL);

header("Content-type:text/html;charset=utf-8");

if(!defined('PW_ROOT'))
{
	define('PW_ROOT', str_replace("\\", '/', substr(dirname(__FILE__), 0, -7)));
}
define('MAGIC_QUOTES_GPC', get_magic_quotes_gpc());
define('CLIENT_TIME',time());

set_time_limit(0);

ini_set('magic_quotes_runtime',0);
unset($HTTP_RAW_POST_DATA, $HTTP_ENV_VARS, $HTTP_POST_VARS, $HTTP_GET_VARS, $HTTP_POST_FILES, $HTTP_COOKIE_VARS);

/**
 * 公共变量 $PW 包含大量信息
 */

$PW=array();
$PW['time_start']=microtime();

include_once PW_ROOT.'data/config.inc.php';

include_once PW_ROOT.'data/version.php';

include_once PW_ROOT.'include/global.func.php';

set_error_handler("myErrorHandler");

include_once PW_ROOT.'include/template.func.php';

spl_autoload_register('auto_load');

/**
 * 连接数据库
 */
phpWeChat\Mysql::connect(DB_HOST,DB_USER,DB_PWD,DB_NAME,DB_CHARSET);

/**
 * 当前用户IP
 */

define('CLIENT_IP',phpWeChat\Ip::getIp()); 

/**
 * 过滤$_GET $_POST $_COOKIE
 */
 $_GET = pw_htmlspecialchars($_GET);

if($_POST || $_GET || $_COOKIE)
{
	foreach($_REQUEST as $key => $value)
	{
		if(isset($$key))
		{
			unset($_REQUEST[$key]);
		}
	}
	
	if(MAGIC_QUOTES_GPC)
	{
		$_POST = pw_stripslashes($_POST);
		$_GET = pw_stripslashes($_GET);
		$_COOKIE =pw_stripslashes($_COOKIE);
	}

	$_POST = phpWeChat\MySql::escape($_POST);
	$_GET = phpWeChat\MySql::escape($_GET);
	$_COOKIE = phpWeChat\MySql::escape($_COOKIE);

	extract($_POST,EXTR_SKIP);
	extract($_GET,EXTR_SKIP);
	extract($_COOKIE,EXTR_SKIP);
}


/**
 * 配置文件
 */

$PW=phpWeChat\Config::loadConfig();

$PW['client_time']=CLIENT_TIME;
$PW['client_ip']=CLIENT_IP;

foreach($PW as $key => $val)
{
	if(!defined(strtoupper($key)))
	{
		define(strtoupper($key),$val);
	}
}

/**
 * 加载功能模块
 */
phpWeChat\Module::load();

/**
 * SESSION设置
 */
ini_set("session.cookie_domain", COOKIE_DOMAIN);
session_set_cookie_params(0, COOKIE_PATH, COOKIE_DOMAIN);
session_start();

/**
 * 验证码
 */
phpWeChat\Captcha::setCaptcha(CAPTCHA_WIDTH,CAPTCHA_HEIGHT,CAPTCHA_LEN);

/**
 *调用支付接口
 */

foreach(glob(PW_ROOT.'api/payment/*') as $dir)
{
	if(is_file($dir.'/'.basename($dir).'.class.php'))
	{
		include_once $dir.'/'.basename($dir).'.class.php';
	}
}

/**
 * 开启Gzip压缩传输
 */
if($PW['config_gzip'] && extension_loaded('zlib') && function_exists('ob_gzhandler') && function_exists('ob_start'))
{
	ob_start('ob_gzhandler');
}
else
{
	ob_start();
}

/**
 * 缓存类
 */
if(class_exists('Memcache'))
{
	include_once PW_ROOT.'include/cache_memcache.class.php';
}
else
{
	include_once PW_ROOT.'include/cache_mysql.class.php';
}

/**
 * 360安全防护
 */
if(is_file(PW_ROOT.'api/360safe/360webscan.php') && $PW['safety_360'] && !defined('IN_MANAGE'))
{
    require_once PW_ROOT.'api/360safe/360webscan.php';
}

if(phpWeChat\Module::isModuleInstalled('wechat'))
{
	include_once PW_ROOT.'api/wechat/errcode.php';
	$PW['wechat_access_token']=WeChat\Wechat::getAccessToken();
	define('WECHAT_ACCESS_TOKEN',$PW['wechat_access_token']);

	$PW['wechat_jsapi_ticket']=WeChat\Wechat::getJsapiTicket();
	define('WECHAT_JSAPI_TICKET',$PW['wechat_jsapi_ticket']);
}

/**
 * 当前用户信息
 */

phpWeChat\Member::load();

if(phpWeChat\Module::isModuleInstalled('member') && !defined('IN_MANAGE'))
{
	$_userid=0;
	$_levelid=-1;
	$_memberlogin=array();

	$_userid=intval(get_cookie('userid'));
	
	if($_userid)
	{
		$_memberlogin=phpWeChat\Member::getUserByUserId($_userid);
		if($_memberlogin['openid'])
		{
			$_SESSION['openid']=$_memberlogin['openid'];
		}
	}

	if(!$_memberlogin && $_SESSION['openid'])
	{
		$_memberlogin=phpWeChat\Member::getUserByOpenId($_SESSION['openid']);

		if($_memberlogin)
		{
			$_userid=$_memberlogin['userid'];
			set_cookie('userid',$_memberlogin['userid']);
		}
	}
	
	$PW['memberlogin']=$_memberlogin;

	if($_SESSION['openid'])
	{
		if($_memberlogin)
		{
			if(WeChat\WeChatManage::getUserByOpenid($_SESSION['openid']))
			{
				$PW['memberlogin']=array_merge($_memberlogin,WeChat\WeChatManage::getUserByOpenid($_SESSION['openid']));
			}
			else
			{
				$PW['memberlogin']=$_memberlogin;
			}
		}
		else
		{
			$PW['memberlogin']=WeChat\WeChatManage::getUserByOpenid($_SESSION['openid']);
		}

		$_userid=$PW['memberlogin']['userid'];
	}
}

$PW['i_count']=1;
?>