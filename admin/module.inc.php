<?php

// +----------------------------------------------------------------------

// | phpWeChat 后台模块管理入口文件 Last modified 2016-04-06 18:04

// +----------------------------------------------------------------------

// | Copyright (c) 2009-2016 phpWeChat http://www.phpwechat.com All rights reserved.

// +----------------------------------------------------------------------

// | Author: 骑马的少年 <phpwechat@126.com> <http://www.phpwechat.com>

// +----------------------------------------------------------------------

use phpWeChat\Module;

use phpWeChat\MySql;

use phpWeChat\Upload;



!defined('IN_MANAGE') && exit('Access Denied!');



$mod='system';

$file=@return_edefualt(str_callback_w($_GET['file']),'module');

$action=@return_edefualt(str_callback_w($_GET['action']),'manage');



switch($action)

{

	case 'manage':

		if(isset($job))

		{

			switch($job)

			{

				case 'setoderby':

					Module::moduleSetOderby($keys,$orderbys);

					operation_tips('操作成功！');

					break 2;

				case 'setdisabled':

					Module::moduleSet($keys,array('disabled'=>1));

					operation_tips('操作成功！');

					break 2;

				case 'setundisabled':

					Module::moduleSet($keys,array('disabled'=>0));

					operation_tips('操作成功！');

					break 2;

				case 'uninstall':

					Module::moduleUninstall($keys);

					operation_tips('操作成功！');

					break 2;

			}

		}

		$parentkey=isset($parentkey)?str_callback_w($parentkey):'0';

		include_once parse_admin_tlp($file.'-'.$action);

		break;

	case 'diy':

		if(isset($dosubmit))

		{

			$configmenu=array();

			foreach($menu['name'] as $key => $name)

			{

				$name=trim($name);

				if($name)

				{

					$configmenu[trim($menu['action'][$key])]=urlencode($name).','.trim($menu['ico'][$key]);

				}

			}

			

			$info['configmenu']=urldecode(json_encode($configmenu));



			$src_folder=PW_ROOT.'data/_MOD/_DIY/';

			$par_folder=PW_ROOT.'addons/'.Module::getModuleByKey($info['parentkey'],'folder').'/';

			$dis_folder=PW_ROOT.'addons/'.Module::getModuleByKey($info['parentkey'],'folder').'/addons/'.$info['folder'].'/';



			if(!preg_match('/^[a-z0-9_]{1,32}$/i',$info['folder']) || is_dir($dis_folder))

			{

				operation_tips('该文件夹已经存在，请更换文件夹名称 [0]！','','error');

			}



			if(!is_writable($par_folder))

			{

				operation_tips('请将文件夹 '.$par_folder.' 设为 0777 [0]！','','error');

			}

			

			recurse_copy($src_folder,$dis_folder);



			rename($dis_folder.'admin/_DIY.inc.php',$dis_folder.'admin/'.$info['folder'].'.inc.php');

			rename($dis_folder.'admin/template/_DIY-config.tlp.php',$dis_folder.'admin/template/'.$info['folder'].'-config.tlp.php');

			rename($dis_folder.'include/_DIY.class.php',$dis_folder.'include/'.$info['folder'].'.class.php');

			rename($dis_folder.'include/_DIY.func.php',$dis_folder.'include/'.$info['folder'].'.func.php');

			

			file_put_contents($dis_folder.'admin/'.$info['folder'].'.inc.php',str_replace(array('#_DIY#','_DIY&',"'_DIY'",'_DIY_CLASS','_MOD_PARENT'),array($info['folder'],$info['folder'].'&',"'".$info['folder']."'",ucfirst($info['folder']),Module::getModuleByKey($info['parentkey'],'folder')),file_get_contents($dis_folder.'admin/'.$info['folder'].'.inc.php')));



			file_put_contents($dis_folder.'admin/template/'.$info['folder'].'-config.tlp.php',str_replace(array('_DIY_PARENT__DIY','_DIY_CLASS','_MOD_PARENT'),array(Module::getModuleByKey($info['parentkey'],'folder').'_'.$info['folder'],ucfirst($info['folder']),Module::getModuleByKey($info['parentkey'],'folder')),file_get_contents($dis_folder.'admin/template/'.$info['folder'].'-config.tlp.php')));



			file_put_contents($dis_folder.'include/'.$info['folder'].'.class.php',str_replace(array('#nowtime#','#_DIY#','_DIY_CLASS','_MOD_PARENT'),array(date('Y-m-d H:i:s'),$info['folder'],ucfirst($info['folder']),Module::getModuleByKey($info['parentkey'],'folder')),file_get_contents($dis_folder.'include/'.$info['folder'].'.class.php')));

			file_put_contents($dis_folder.'include/'.$info['folder'].'.func.php',str_replace(array('_DIY_CLASS'),array(ucfirst($info['folder'])),file_get_contents($dis_folder.'include/'.$info['folder'].'.func.php')));

			file_put_contents($dis_folder.'include/'.$info['folder'].'.func.php',str_replace(array('_DIY','_MOD_PARENT'),array($info['folder'],Module::getModuleByKey($info['parentkey'],'folder')),file_get_contents($dis_folder.'include/'.$info['folder'].'.func.php')));

			file_put_contents($dis_folder.'index.php',str_replace(array('#_DIY#','_DIY&','_DIY_CLASS','_MOD_PARENT'),array($info['folder'],$info['folder'].'&',ucfirst($info['folder']),Module::getModuleByKey($info['parentkey'],'folder')),file_get_contents($dis_folder.'index.php')));

	

			$op=Module::diy($info);



			if($op>0)

			{

				operation_tips('自定义模块导入成功！','?mod=&file=module&action=manage&parentkey='.$info['parentkey']);

			}

			else

			{	

				operation_tips('操作失败 ['.$op.']！','','error');

			}

		}

		include_once parse_admin_tlp($file.'-'.$action);

		break;

	case 'tables':

		$_module=Module::getModuleByKey($modulekey);

		$_parentmodule=Module::getModuleByKey($_module['parentkey']);



		$_modulemodel=array();



		foreach (MySql::getTables() as $key => $table) 

		{

			$_tb=explode("_",$table);



			if($_tb[1]==$_parentmodule['folder'] && $_tb[2]==$_module['folder'])

			{

				$_modulemodel[$key]=$table;

			}

		}



		include_once parse_admin_tlp($file.'-'.$action);

		break;

	case 'createsql':

		$create_sql=MySql::fetchOne("SHOW CREATE TABLE `".DB_PRE.$tbname."`");



		include_once parse_admin_tlp($file.'-'.$action);

		break;
	case 'export':
		$op=Module::export($modulekey);

		if($op<=0)
		{
			operation_tips('模块导出失败 ['.$op.']！','','error');
		}
		break;
		
	case 'import':

		if(isset($dosubmit))

		{

			$modulezip=Upload::attachmentUpload('modulezipfile','modulezipfile'.$_userid);



			$op=Module::import($modulezip);



			if(!is_array($op))

			{

				operation_tips('操作失败 ['.$op.']！','','error');

			}

			else

			{	

				operation_tips('模块导入成功！','?mod=&file=module&action=manage&parentkey='.$op['parentkey']);

			}

		}

		include_once parse_admin_tlp($file.'-'.$action);

		break;

	case 'download':

		$randn=preg_replace('/[^0-9a-z]/i','',$randn);

		if(!is_writable(PW_ROOT.'addons/'))

		{

			operation_tips('请将 '.PW_ROOT.'addons/ 及子目录设为0777后再继续！','','error');

		}

		$data=http_request('http://s.phpwechat.com/index.php?action=getzip&domain='.SITE_URL.'&randn='.$randn);

		switch($data)

		{

			case 'noregister':

				operation_tips('您尚未注册网站，请先注册！','http://s.phpwechat.com/mydomain.html','error');

				break 2;

			case 'noapp':

				operation_tips('该应用已下架！','','error');

				break 2;

			case 'nobuy':

				operation_tips('您尚未注册购买此应用！','http://s.phpwechat.com/app_'.$randn,'error');

				break 2;

			default:

				$modulezip='upload/attachment/modulezipfile'.$_userid.'.zip';

				file_put_contents(PW_ROOT.$modulezip,$data);



				$op=Module::import($modulezip);



				if(!is_array($op))

				{

					operation_tips('操作失败 ['.$op.']！','','error');

				}

				else

				{	

					operation_tips('模块导入成功！','?mod=&file=module&action=manage&parentkey='.$op['parentkey']);

				}

				break 2;

		}

		break;

}

?>