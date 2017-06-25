<?php

use phpWeChat\Config;
use phpWeChat\MySql;

/**
	* 安装程序 
	* @version		v1.0
	* @copyright	(C) 2009-2016 www.phpwechat.com
	* @lastmodify	2016/5/16 16:22
*/
define('PW_INSTALL',true);

/*
	屏蔽脚本运行错误信息
*/
error_reporting (E_ALL & ~E_NOTICE & ~E_WARNING);

/*
	设置运行环境
*/
set_time_limit(0);

ini_set('magic_quotes_runtime',0);

header("Content-type:text/html;charset=utf-8");

/*
	设置根目录并检查是否重复安装
*/
define('PW_ROOT',substr(dirname(__FILE__),0,-7));

if(!function_exists('mysqli_connect'))
{
	exit('MySqli is required!');
}

if(is_file(PW_ROOT.'data/phpwechat.lock'))
{
	exit('您已经安装过phpWeChat,如果需要重新安装, 请删除 ./data/phpwechat.lock 文件!');
}

/*
	引入安装程序运行所必需的文件
*/
include(PW_ROOT.'data/config.inc.php');
include(PW_ROOT.'data/version.php');
include(PW_ROOT.'include/global.func.php');
include(PW_ROOT.'include/mysql.class.php');

$_REQUEST=pw_trim($_REQUEST);

extract($_REQUEST);
$step=isset($step)?$step:1;

switch($step)
{
	case '2':
		/*
			GD库检测
		*/
		if(function_exists('gd_info') && extension_loaded('gd'))
		{
			$gd_version = gd_info();
			$gd_version = preg_replace('/[^0-9.]/','',$gd_version['GD Version']);
		} 
		else 
		{
			$gd_version = 0; 
		}

		/*
			所需函数检测
		*/
		$func_items = array('mysqli_connect','curl_init','file_get_contents','file_put_contents','json_encode','json_decode');

		/*
				文件夹检测
		*/
		$folder_items = array(
				'addons/',
				'addons/member/addons/',
				'addons/pc/addons/',
				'addons/wechat/addons/',
				'data/',
				'data/config.inc.php',
				'data/version.php',
				'data/cache/',
				'data/bakup/',
				'data/cache_template/',
				'data/tmp/',
				'data/log/',
				'data/_EXPORT/',
				'data/_IMPORT/',
				'upload/',
				'upload/attachment/',
				'upload/pem/',
				'upload/voice/',
				'upload/images/',
				'upload/video/'
		);

		$cantsubmit=0;

		if(PHP_VERSION < '5.3' || round($gd_version)< 2)
		{
			$cantsubmit=1;
		}
		break;
	case '4':
		$PHP_SELF=isset($_SERVER['PHP_SELF'])?$_SERVER['PHP_SELF']:(isset($_SERVER['SCRIPT_NAME'])?$_SERVER['SCRIPT_NAME']:$_SERVER['ORIG_PATH_INFO']);
		$file_path=str_replace("\\","/",dirname($PHP_SELF));
		$file_path=substr($file_path,0,-8);
		$file_path=strlen($file_path)>1?$file_path."/" : "/";

		/*
			定义数据库操作对象
		*/
		Mysql::connect($config['db_host'],$config['db_user'],$config['db_pwd'],$config['db_name'],'utf8');
		
		$sqlfile=PW_ROOT.'install/data/install.sql';
		
		if(!is_file($sqlfile))
		{
			exit('数据库安装文件丢失, 请重新下载 phpWeChat 安装包! <a href="http://www.phpwechat.com/">http://www.phpwechat.com/</a>');
		}
		
		/*
			获取当前域名(页面访问)地址
		*/
	
		$domain=getInstallServerName().$file_path;

		/*
			获取SQL内容
		*/
		$sqls=str_replace('#website_url#',$domain,file_get_contents($sqlfile));
		$sqls=explode(";\n",trim($sqls));

		$timenow=time();

		/*
			添加创始人账号
		*/
		$admin=array();
		$admin['username']=$username;
		$admin['password']= md5(trim($password));
		$admin['lastlogintime']=$timenow;
		$admin['privileges']=-1;
		$admin['status']=1;

		/*
			配置文件
		*/
		$config['db_host']=$config['db_host'];
		$config['db_user']=$config['db_user'];
		$config['db_pwd']=$config['db_pwd'];
		$config['db_name']=$config['db_name'];
		$config['db_pre']=$config['db_pre'];

		$configfile = PW_ROOT.'data/config.inc.php';
		$pattern = $replacement = array();

		foreach($config as $k=>$v)
		{
			$pattern[$k] = "/define\(\s*['\"]".strtoupper($k)."['\"]\s*,\s*([']?)[^']*([']?)\s*\)/is";
			$replacement[$k] = "define('".strtoupper($k)."', \${1}".$v."\${2})";
		}
		$str = file_get_contents($configfile);
		$str = preg_replace($pattern, $replacement, $str);
		file_put_contents($configfile, $str);
		break;
	case '5':
		$PHP_SELF=isset($_SERVER['PHP_SELF'])?$_SERVER['PHP_SELF']:(isset($_SERVER['SCRIPT_NAME'])?$_SERVER['SCRIPT_NAME']:$_SERVER['ORIG_PATH_INFO']);
		$file_path=str_replace("\\","/",dirname($PHP_SELF));
		$file_path=substr($file_path,0,-8);
		$file_path=strlen($file_path)>1?$file_path."/" : "/";

		$domain=getInstallServerName().$file_path;

		include(PW_ROOT.'include/common.inc.php');
		Config::setConfig('system',array('pw_path'=>$file_path,'site_url'=>$domain));
		Config::setConfig('pc',array('pc_site_url'=>$domain));

		file_put_contents(PW_ROOT.'data/phpwechat.lock',md5(mt_rand()));
		break;
	case 'checkdb':
			if(!$dblink=@mysqli_connect($dbhost, $dbuser, $dbpwd,$dbname))
			{
				if(mysqli_connect_errno()==1049)
				{
					exit('数据库 '.$dbname.' 不存在，请创建数据库!');
				}
				elseif(mysqli_connect_errno()==1045)
				{
					exit('不能连接数据库，用户名或密码错误!');
				}
				elseif(mysqli_connect_errno()==1044)
				{
					exit('当前用户没有访问数据库的权限!');
				}
				else
				{
					exit(mysqli_connect_error());
				}
				
			}

			$server_info = mysqli_get_server_info($dblink);

			if($server_info < '5.0') 
			{
				exit('MySqli版本过低['.$server_info.']，请升级至5.0+！');
			}
			
			Mysql::connect($dbhost, $dbuser, $dbpwd,$dbname,'utf8');
			$tables = MySql::getTables();

			if($tables && in_array($tablepre.'admin', $tables))
			{
				exit('您已安装过phpWeChat，重复安装将会删除旧数据！');
			}
			else
			{
				exit('1');
			}
			
			break;
}

include('template/step'.$step.'.tlp.php');

/*
	定义安装所需函数
*/
function check_iswriteable($path)
{
	if(!file_exists(PW_ROOT.$path))
	{
		return false;
	}

	if(is_dir(PW_ROOT.$path))
	{
		if(file_put_contents(PW_ROOT.$path.'install.test','ok'))
		{
			if(unlink(PW_ROOT.$path.'install.test'))
			{
				mkdir(PW_ROOT.$path.'_test/');
				return rmdir(PW_ROOT.$path.'_test/');
			}
			return false;
		}
	}
	else
	{
		$f = @fopen(PW_ROOT.$path,'a');
		if($f===false)
		{
			return false;
		}
		fclose($f);
		return true;
	}
	return false;
}

function jsmessage($msg) 
{
	echo '<script type="text/javascript">showmessage(\''.addslashes($msg).'\');</script>'."\r\n";
}

function getInstallServerName() 
{
	$port=$_SERVER["SERVER_PORT"]=='80'?'':':'.$_SERVER["SERVER_PORT"];
	$ServerName = strtolower($_SERVER['SERVER_NAME']?$_SERVER['SERVER_NAME']:$_SERVER['HTTP_HOST']);

	if(strpos($ServerName,'http://'))
	{   
		$ServerName=str_replace('http://','',$ServerName);
	}  
	return strpos($ServerName,':')?'http://'.$ServerName:'http://'.$ServerName.$port;
}

function getrandstr($strlen=10)
{
	$code='';
	$str='abcdefghkmnopqrstuvwyzABCDEFGHKLMNOPQRSTUVWYZ1234567890';
	$len=strlen($str)-1;

	for($i=0;$i<$strlen;$i++)
	{
		$code.=$str[mt_rand(0,$len)];
	}
	return $code;
}

function disablefunc($func)
{
	if(!function_exists($func))
	{
		return false;
	}
	return in_array($func,array_map('trim',explode(',',ini_get('disable_functions'))));
}
?>