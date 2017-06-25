<?php
// +----------------------------------------------------------------------
// | phpWeChat 模板解析函数库 Last modified 2016-04-08 22:04
// +----------------------------------------------------------------------
// | Copyright (c) 2009-2016 phpWeChat http://www.phpwechat.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 骑马的少年 <phpwechat@126.com> <http://www.phpwechat.com>
// +----------------------------------------------------------------------
use phpWeChat\Cache;
use phpWeChat\Module;
use phpWeChat\MySql;

function template($tlp='',$module='')
{	
	if($module)
	{
		$module=substr($module,0,7)=='addons/'?$module:'addons/'.$module;

		$module=substr($module,-1,1)=='/'?$module:$module.'/';
	}
	else
	{
		$module=TLP;
	}

	$tlproute=$module.$tlp;

	if(!is_file(PW_ROOT.$tlproute.'.html'))
	{
		fatal_error('Failed to open stream: '.$tlproute.'.html. No such file or directory.',2);
	}

	make_dir(dirname('data/cache_template/'.$tlproute.'.html'));

	$compliedfile=PW_ROOT.'data/cache_template/'.$tlproute.'.tlp.php';

	if(!is_file($compliedfile) || filemtime(PW_ROOT.$tlproute.'.html')>filemtime($compliedfile))
	{
		template_complie($tlp,$module);
	}

	return $compliedfile;
}

function template_complie($tlp,$module='')
{
	$tlproute=$module.$tlp;

	$tplfile=file_get_contents(PW_ROOT.$tlproute.'.html');

	$compliedfile=PW_ROOT.'data/cache_template/'.$tlproute.'.tlp.php';
	
	$tplcon=template_parse($tplfile);

	$strlen=@file_put_contents($compliedfile,$tplcon);
	@chmod($compliedfile,0777);
	return $strlen;
}

function template_parse($tlp='')
{
	$tlp=preg_replace('/\{php\s+(.+?)\}/i','<?php \\1?>',$tlp);

	$tlp=preg_replace('/\{tlp\s+(.+)\}/i','<?php include template(\'\\1\');?>',$tlp); 
	$tlp=preg_replace('/\{include\s+(.+)\}/i','<?php include(\\1);?>',$tlp);

	$tlp=preg_replace('/\{(\$[a-z0-9_\+\'\"\[\]\x7f-\xff\$]+)\}/i','<?php echo isset(\\1)?\\1:\'\';?>',$tlp);
	$tlp=preg_replace('/\{__([a-z0-9_]+)__\}/i','<?php echo defined(\'\\1\')?\\1:\'{__\\1__}\';?>',$tlp);
	$tlp=preg_replace('/\{([a-z0-9_\-]+)::\$([a-z0-9_\-\'\"\[\]]+)\}/i','<?php echo \\1::$\\2;?>',$tlp);
	
	$tlp=preg_replace('/\[field:([a-z0-9_]+)\/\]/i','<?php echo $r[\'\\1\'];?>',$tlp);
	$tlp=preg_replace('/\{([a-z0-9_\-:]+)\(\)\}/i','<?php echo \\1();?>',$tlp);
	$tlp=preg_replace('/\{([a-z0-9_\-:]+)\(([^+]+?)\)\}/i','<?php echo \\1(\\2);?>',$tlp);
	
	$tlp=preg_replace('/\{if\s+([^\}]+)\}/i','<?php if(\\1) { ?>',$tlp);
	$tlp=preg_replace('/\{else\s*if\s+([^\}]+)\}/i','<?php }elseif(\\1){ ?>',$tlp);
	$tlp=preg_replace('/\{else\}/i','<?php }else{ ?>',$tlp);
	$tlp=preg_replace('/\{\/if\}/i','<?php }?>',$tlp);

	$tlp=preg_replace('/\{loop\s+(\S+)\s+(\S+)\}/i','<?php $no=1;if(is_array(\\1))foreach(\\1 as \\2){?>',$tlp);
	$tlp=preg_replace('/\{loop\s+(\S+)\s+(\S+)\s+(\S+)\}/i','<?php $no=1;if(is_array(\\1))foreach(\\1 as \\2=>\\3){?>',$tlp);
	$tlp=preg_replace('/\{\/loop\}/i','<?php $no++;}?>',$tlp);

	if(PHP_VERSION < '5.5')
	{
		$tlp=preg_replace('/\{pw:module\s+([^\}]+)\}/ie',"pw_call_user_func('get_module_tag','\\1')",$tlp);
	}
	else
	{
		$tlp=preg_replace_callback('/\{pw:module\s+([^\}]+)\}/i',function($r) { return pw_call_user_func('get_module_tag',$r[1]); },$tlp);
	}
	
	$tlp=preg_replace('/\{\/pw:module\}/i',"<?php unset(\$_DATA);?>",$tlp);


	foreach(Module::moduleList(pw_md5('pc'),0) as $mod)
	{
		if(function_exists('parse_'.$mod['folder'].'_template'))
		{
			$tlp=call_user_func('parse_'.$mod['folder'].'_template',$tlp);
		}
	}

	foreach(Module::moduleList(pw_md5('wechat'),0) as $mod)
	{
		if(function_exists('parse_'.$mod['folder'].'_template'))
		{
			$tlp=call_user_func('parse_'.$mod['folder'].'_template',$tlp);
		}
	}

	return $tlp;
}

function get_module_tag($para)
{
	/*
		设置区块默认参数
	*/

	$args=array(
				'parent'=>'0',
				'orderby'=>'orderby',
				'limit'=>'0',
				'row'=>'0');
	foreach($para as $key => $arg)
	{
		if(isset($args[$key]))
		{
			$args[$key]=$arg;
		}
	}
	
	extract($args);
	$row=intval($row)>0?intval($row):20;
	return '<?php $_DATA=get_module_data(\''.$parent.'\',\''.$orderby.'\','.$limit.','.$row.');?>';
}

function get_module_data($parent,$orderby,$limit,$row)
{
	global $PW;
	
	$parent=preg_replace('/[^a-z0-9_]/i','',$parent);
	$parentkey=$parent?pw_md5($parent):0;

	$orderby=preg_replace('/[^a-z0-9_]/i','',$orderby);
	$orderby=$orderby?$orderby:'orderby';

	$limit=intval($limit)?intval($limit):0;
	$row=intval($row)?intval($row):8;

	$where='`parentkey`=\''.$parentkey.'\' AND `disabled`=0';
	
	$sql="SELECT * FROM `".DB_PRE."module` WHERE $where ORDER BY `".DB_PRE."module`.`".$orderby."` ASC LIMIT $limit,$row";

	$data=Cache::get($sql);
	if(!$data)
	{
		$data=MySql::fetchAll($sql);
		if($data)
		{
			Cache::set($sql,$data);
		}
	}
	return $data;
}

function data_list_pages($number,$page,$pagesize,$align='right')
{
	$mtrand=mt_rand(100,999);
	$pagenum=max(1,ceil($number/$pagesize));
	$prepage=max(($page-1),1);
	$nextpage=max(1,min(($page+1),$pagenum));
		
	$cururl=preg_replace('/([&\?]?)(page=[0-9]+)([&\?]?)/i','\\1',get_current_url());
	$strlen=strlen($cururl)-1;
	$flag=(boolean)(substr(strrchr($cururl,'/'),1));

	if(substr($cururl,-1,1)=='&')
	{
		$cururl=$flag?substr($cururl,0,$strlen).'&page=':substr($cururl,0,$strlen).'?page=';
	}
	elseif(substr($cururl,-1,1)=='?')
	{
		$cururl=substr($cururl,0,$strlen).'?page=';
	}
	else
	{
		if(strpos($cururl,'?')===false && strpos($cururl,'&')===false)
		{
			$flag=false;
		}

		$cururl=$flag?$cururl.'&page=':$cururl.'?page=';
	}

	$pselect='<select name="pageto" id="pagetoselect'.$rand.'" onchange="window.location.href=\'http://'.get_server_name().$cururl.'\'+document.getElementById(\'pagetoselect'.$rand.'\').value">';
	for($i=1;$i<=intval($pagenum);$i++)
	{
		$pselect.='<option value="'.$i.'">第'.$i.'页</option>';
	}
	$pselect.='</select><script language="javascript">{document.getElementById(\'pagetoselect'.$rand.'\').value='.$page.'}</script>';

	if(defined('IN_MANAGE'))
	{
		return '<div align="'.$align.'">&nbsp;统计共 '.$number.' 条记录 &nbsp;
					<a href="http://'.get_server_name().$cururl.'1">第一页</a>&nbsp;<a href="http://'.get_server_name().$cururl.$prepage.'">上一页</a>&nbsp;
					<a href="http://'.get_server_name().$cururl.$nextpage.'">下一页</a>&nbsp;
					<a href="http://'.get_server_name().$cururl.$pagenum.'">尾页</a>&nbsp;总页数：<font color="#ff0000">'.$page.'</font>/'.$pagenum.' '.$pselect.'</div>';
	}
	else
	{
		return '<div align="'.$align.'">
					<table>
						<tr>
							<td>统计：'.$number.'</td>
							<td><a class="page_active" href="http://'.get_server_name().$cururl.'1">第一页</a></td>
							<td><a href="http://'.get_server_name().$cururl.$prepage.'">上一页</a></td>
							<td><a href="http://'.get_server_name().$cururl.$nextpage.'">下一页</a></td>
							<td><a href="http://'.get_server_name().$cururl.$pagenum.'">尾页</a></td><td>总页数：<b><font color="#ff0000">'.$page.'</font>/'.$pagenum.'</b> '.'</td>
						</tr>
					</table>
				</div>';
	}
}
?>