<?php
	use phpWeChat\Module;

	define('IN_APP',true);

	if(!file_exists(dirname(__FILE__).'/data/phpwechat.lock'))
	{
		header('location:install/index.php');
		exit();
	}
	
	require dirname(__FILE__).'/include/common.inc.php';

	$_GET=array_map('str_callback_w',$_GET);

	$mod=@return_edefualt($_GET['m'],'pc');
	$action=@return_edefualt($_GET['a'],'index');

	define('MOD',$mod);
	define('ACTION',$action);

	$modinfo=Module::getModule($mod);

	if(!$modinfo['parentkey'])
	{	
		if(isset($PW[$mod.'_template']) && $PW[$mod.'_template'])
		{
			define('TLP','addons/'.$modinfo['folder'].'/template/'.$PW[$mod.'_template'].'/');
		}
		else
		{
			define('TLP','addons/'.$modinfo['folder'].'/template/default/');
		}

		if(is_file(PW_ROOT.'addons/'.$modinfo['folder'].'/index.php'))
		{
			include_once PW_ROOT.'addons/'.$modinfo['folder'].'/index.php';
		}
		include template($action);
	}
	else
	{
		$mod_file_path='addons/'.Module::getModuleByKey($modinfo['parentkey'],'folder').'/addons/'.$mod.'/index.php';

		if(isset($PW[$mod.'_template']) && $PW[$mod.'_template'])
		{
			define('TLP','addons/'.Module::getModuleByKey($modinfo['parentkey'],'folder').'/addons/'.$mod.'/template/'.$PW[$mod.'_template'].'/');
		}
		else
		{
			define('TLP','addons/'.Module::getModuleByKey($modinfo['parentkey'],'folder').'/addons/'.$mod.'/template/default/');
		}

		if(is_file(PW_ROOT.$mod_file_path))
		{
			include_once PW_ROOT.$mod_file_path;
		}
		else
		{	
			fatal_error('File '.PW_ROOT.$mod_file_path.' not exists!',1001);
		}
		include template($action);
	}
?>