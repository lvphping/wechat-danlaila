<?php
// +----------------------------------------------------------------------
// | phpWeChat 后台管理函数库 Last modified 2016-04-08 17:13
// +----------------------------------------------------------------------
// | Copyright (c) 2009-2016 phpWeChat http://www.phpwechat.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 骑马的少年 <phpwechat@126.com> <http://www.phpwechat.com>
// +----------------------------------------------------------------------

/**
	parse_admin_tlp($tlp,$module='admin')  $tlp模板名称 $module所属功能模块
	功能：后台管理模块模板解析函数
*/
use phpWeChat\Module;
use phpWeChat\MySql;

function parse_admin_tlp($tlp,$module='admin')
{
	$mod_parent=Module::getModule($module);
	
	if($mod_parent['parentkey'])
	{
		$tlpfile=$module=='admin'?'admin/template/'.$tlp.'.tlp.php':'addons/'.Module::getModuleByKey($mod_parent['parentkey'],'folder').'/addons/'.$module.'/admin/template/'.$tlp.'.tlp.php';
	}
	else
	{
		$tlpfile=$module=='admin'?'admin/template/'.$tlp.'.tlp.php':'addons/'.$module.'/admin/template/'.$tlp.'.tlp.php';
	}

	!is_file(PW_ROOT.$tlpfile) && fatal_error('File '.PW_ROOT.$tlpfile.' not exists!',1001);
	
	$tlpcachefile=PW_ROOT.'data/cache_template/'.$tlpfile;

	make_dir(dirname('data/cache_template/'.$tlpfile));
	if(!is_file($tlpcachefile) || filemtime(PW_ROOT.$tlpfile) > filemtime($tlpcachefile))
	{
		@file_put_contents($tlpcachefile,template_parse(file_get_contents($tlpfile)));
	}

	return $tlpcachefile;
}

/**
	operation_tips($msg='',$url='',$result='success',$timeout=1000)  $msg错误内容 $url跳转链接 $result结果标示：success/error  $timeout提示延时时长 
	功能：后台操作提示函数
*/
function operation_tips($msg='',$url='',$result='success',$timeout=1000)
{
	global $PW;
	$flag=false;

	if(!$url)
	{
		$redirecturl='javascript:self.history.back();';
	}
	else
	{
		$redirecturl=$url;
	}

	include parse_admin_tlp('operation-tips-'.$result);
	exit('<script language="javascript" type="text/javascript">setTimeout("urlRedirect(\''.$url.'\');",'.$timeout.');</script>');
}

/**
	operation_tips_nourl($msg='',$result='success')
	功能：后台操作提示函数
*/
function operation_tips_nourl($msg='',$result='success')
{
	global $PW;
	$flag=true;
	include parse_admin_tlp('operation-tips-'.$result);
	exit();
}

/**
	system_software()
	功能：获取当前系统的软件环境
*/
function system_software()
{
	$r=explode(' ',$_SERVER['SERVER_SOFTWARE']);

	return preg_replace('/[^a-z0-9_\-]/i','',PHP_OS).' '.$r[0].' Php/'.PHP_VERSION.' MySql/'.MySql::mysqlVersion();
}
?>