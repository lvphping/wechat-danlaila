<?php

// +----------------------------------------------------------------------

// | phpWeChat 功能模块操作类 Last modified 2016-03-29 17:06

// +----------------------------------------------------------------------

// | Copyright (c) 2009-2016 phpWeChat http://www.phpwechat.com All rights reserved.

// +----------------------------------------------------------------------

// | Author: 骑马的少年 <phpwechat@126.com> <http://www.phpwechat.com>

// +----------------------------------------------------------------------

namespace phpWeChat;



class Module

{

	private static $mInstalledMod=array();

	private static $mModTable='module';



	public static function load()

	{

		$r=MySql::fetchAll("SELECT * FROM `".DB_PRE.self::$mModTable."` WHERE `disabled`=0");

		

		$_installed=array();

		foreach($r as $_key => $_r)

		{

			$mod_info=self::getModule($_r['folder']);



			if($mod_info['parentkey'])

			{

				$mod_path=PW_ROOT.'addons/'.self::getModuleByKey($mod_info['parentkey'],'folder').'/addons/'.$_r['folder'].'/include/'.$_r['folder'].'.func.php';

			}

			else

			{

				$mod_path=PW_ROOT.$_r['folder'].'/include/'.$_r['folder'].'.func.php';

			}



			if(is_file($mod_path))

			{

				include_once $mod_path;

			}

			$_installed[$_key]=$_r['folder'];

		}



		self::$mInstalledMod=$_installed;

		return $_installed;

	}



	public static function diy($info)

	{

//		$info['configmenu']=stripslashes(trim($info['configmenu']));



//		$info['folder']=preg_replace('/[^a-z0-9_]/i', '', $info['folder']);

/*

		if(!$info['folder'])

		{

			return -1;

		}



		if(is_dir(PW_ROOT.self::getModuleByKey($info['parentkey'],'folder').'/'.$info['folder'].'/'))

		{

			return -2;

		}

		make_dir(self::getModuleByKey($info['parentkey'],'folder').'/'.$info['folder'].'/');

*/

		file_put_contents(PW_ROOT.self::getModuleByKey($info['parentkey'],'folder').'/'.$info['folder'].'/version.txt',date('Ymd'));



		$info['key']=pw_md5(self::getModuleByKey($info['parentkey'],'folder').'/'.$info['folder'].'/');



		MySql::insert(DB_PRE.self::$mModTable,$info);



		return 1;

	}



	public static function isModuleInstalled($folder='system')

	{

		return in_array(preg_replace('/[^a-z0-9_]/i','',$folder),self::$mInstalledMod);

	}



	public static function getModule($folder='system',$f='*')

	{

		$r=MySql::fetchOne("SELECT * FROM `".DB_PRE.self::$mModTable."` WHERE `folder`='".preg_replace('/[^a-z0-9_]/i','',$folder)."'");

		return ($f!='*' && isset($r[$f]))?$r[$f]:$r;

	}



	public static function version($modulekey='')

	{

		$_module=Module::getModuleByKey($modulekey);

		$_parentmodule=Module::getModuleByKey($_module['parentkey']);



		$version=PW_ROOT.'addons/'.$_parentmodule['folder'].'/addons/'.$_module['folder'].'/version.txt';

		return is_file($version)?'V'.file_get_contents($version):'未知版本';

	}



	public static function getModuleByKey($key='',$f='*')

	{

		$r=MySql::fetchOne("SELECT * FROM `".DB_PRE.self::$mModTable."` WHERE `key`='".preg_replace('/[^a-z0-9]/i','',$key)."'");

		return ($f!='*' && isset($r[$f]))?$r[$f]:$r;

	}



	public static function getChindrenCounts($parentkey=1)

	{

		return get_cache_counts("SELECT COUNT(*) AS count FROM `".DB_PRE.self::$mModTable."` WHERE `parentkey`='".str_callback_w($parentkey)."'");

	}



	public static function moduleSetOderby($keys,$orderbys=array())

	{

		$keys=is_array($keys)?array_map('str_callback_w',$keys):array(str_callback_w($keys));

		foreach($keys as $key)

		{

			MySql::update(DB_PRE.self::$mModTable,array('orderby'=>$orderbys[str_callback_w($key)]),'`key`=\''.str_callback_w($key).'\'');

		}



		return true;

	}



	public static function moduleSet($keys,$info=array())

	{

		$keys=is_array($keys)?array_map('str_callback_w',$keys):array(str_callback_w($keys));





		foreach($keys as $key)

		{

			MySql::update(DB_PRE.self::$mModTable,$info,'`key`=\''.str_callback_w($key).'\'');

		}



		return true;

	}



	public static function moduleUninstall($keys)

	{

		$keys=is_array($keys)?array_map('str_callback_w',$keys):array(str_callback_w($keys));



		foreach($keys as $key)

		{

			$selfinfo=self::getModuleByKey($key);

			if($selfinfo['parentkey'])

			{

				$uninstall_file=PW_ROOT.'addons/'.self::getModuleByKey($selfinfo['parentkey'],'folder').'/addons/'.$selfinfo['folder'].'/uninstall.sql';

				if(is_file($uninstall_file))

				{

					$uninstallsqls=explode(";",file_get_contents($uninstall_file));

					foreach($uninstallsqls as $uninstallsql)

					{

						$uninstallsql=trim($uninstallsql);

						if($uninstallsql)

						{

							MySql::query(str_replace('#DB_PRE#',DB_PRE,$uninstallsql),true);

						}

					}

				}

				rm_dirs(PW_ROOT.'addons/'.self::getModuleByKey($selfinfo['parentkey'],'folder').'/addons/'.$selfinfo['folder'].'/');

			}

			else

			{

				return -1; // 禁止卸载根模块

			}

			

			MySql::query("DELETE FROM `".DB_PRE.self::$mModTable."` WHERE `key`='".$key."'");

		}



		return true;

	}



	public static function moduleList($parentkey='0',$disabled=-1)

	{

		if($disabled==-1)

		{

			return MySql::fetchAll("SELECT * FROM `".DB_PRE.self::$mModTable."` WHERE `parentkey`='".str_callback_w($parentkey)."' AND `issystem`=0 ORDER BY `orderby` ASC");

		}

		else

		{

			return MySql::fetchAll("SELECT * FROM `".DB_PRE.self::$mModTable."` WHERE `parentkey`='".str_callback_w($parentkey)."' AND `issystem`=0 AND `disabled`=".intval($disabled)." ORDER BY `orderby` ASC");

		}

	}



	public static function import($modulezip)

	{

		include_once PW_ROOT.'include/pclzip.class.php';



		$zipObj = new PclZip(PW_ROOT.$modulezip);



		if (($list = $zipObj->listContent()) == 0) 

		{

        	return -1;

        }

		

		$modpath='';

        $filelist=array();

        foreach($list as $key => $r)

        {

        	$filelist[$key]=$r['filename'];

			if(substr($r['filename'],-12)=='/version.txt')

			{

				$modpath=strstr($r['filename'],'version.txt',true);

			}

        }



        if(!$filelist)

        {

        	return -2;

        }



		if(!$modpath)

        {

        	return -6;

        }



        if(is_file(PW_ROOT.$modpath.'version.txt'))

        {

        	return -3;

        }



        if(!in_array($modpath.'import.php',$filelist))

        {

        	return -4;

        }



        if($zipObj->extract(PCLZIP_OPT_PATH,PW_ROOT) == 0)

		{

			return -5;

        }

		else

		{

			@unlink(PW_ROOT.$modulezip);

		}



		if(is_file(PW_ROOT.$modpath.'install.sql'))

		{

			$installsqls=explode(";",file_get_contents(PW_ROOT.$modpath.'install.sql'));



			foreach($installsqls as $installsql)

			{

				if(trim($installsql))

				{

					MySql::query(str_replace('#DB_PRE#',DB_PRE,trim($installsql)),true);

				}

			}

		}



		$importsql=string2array(file_get_contents(PW_ROOT.$modpath.'import.php'));



		MySql::insert(DB_PRE.self::$mModTable,$importsql,true);



		return $importsql;

	}

	public static function export($modulekey='')
	{
		include_once PW_ROOT.'include/pclzip.class.php';

		$_module=Module::getModuleByKey($modulekey);
		$_parentmodule=Module::getModuleByKey($_module['parentkey']);

		@mkdir(PW_ROOT.'data/_EXPORT/',0777);
		$zipObj = new PclZip(PW_ROOT.'data/_EXPORT/'.$_parentmodule['folder'].'_'.$_module['folder'].'.zip'); 

		//获取模块关联数据表
		$tbs=array();
		foreach(MySql::getTables() as $key => $tb)
		{
			$_tb=explode("_",$tb);

			if($_tb[1]==$_parentmodule['folder'] && $_tb[2]==$_module['folder'])
			{
				$tbs[$key]=$tb;
			}
		}

		if($tbs)
		{
			//创建表创建SQL + 创建表卸载SQL
			$installsql=$uninstallsql="";
			foreach($tbs as $tb)
			{
				$installsql.=str_replace('`'.DB_PRE,'`#DB_PRE#',MySql::showCreateSql($tb)).";\r\n";
				$uninstallsql.=str_replace('`'.DB_PRE,'`#DB_PRE#',"DROP TABLE `".$tb."`").";\r\n";
			}
			
			// 获取配置信息
			$config_sql=MySql::fetchOne("SELECT * FROM `".DB_PRE."config` WHERE `mod`='".$_module['key']."'");
			foreach($config_sql as $t_k => $t_v)
			{
				$config_sql[$t_k]='\''.mysqli_real_escape_string(MySql::$mDbLink,$t_v).'\'';
			}
			$installsql.="REPLACE INTO `".DB_PRE."config` VALUES(".implode(',',str_replace(SITE_URL,'#website_url#',$config_sql)).");\n";

			file_put_contents(PW_ROOT.'addons/'.$_parentmodule['folder'].'/addons/'.$_module['folder'].'/install.sql',$installsql);
			file_put_contents(PW_ROOT.'addons/'.$_parentmodule['folder'].'/addons/'.$_module['folder'].'/uninstall.sql',$uninstallsql);
		}

		file_put_contents(PW_ROOT.'addons/'.$_parentmodule['folder'].'/addons/'.$_module['folder'].'/import.php',var_export(self::getModuleByKey($modulekey),true));
		file_put_contents(PW_ROOT.'addons/'.$_parentmodule['folder'].'/addons/'.$_module['folder'].'/version.txt',date('Ymd'));

		$zip_path=array();
		$zip_path[0]=PW_ROOT.'addons/'.$_parentmodule['folder'].'/addons/'.$_module['folder'];

		//打包程序 + 前端模板 并压缩
		$zipresult = $zipObj->create(implode(',',$zip_path),PCLZIP_OPT_REMOVE_PATH,PW_ROOT); 

		if($zipresult==0)
		{
			return -1;
		}

		if(!is_file(PW_ROOT.'data/_EXPORT/'.$_parentmodule['folder'].'_'.$_module['folder'].'.zip'))
		{
			return -2;
		}

		//完成导出
		if(ob_get_length() !== false) 
		{
			@ob_end_clean();
		}
		header('Pragma: public');
		header('Last-Modified: '.gmdate('D, d M Y H:i:s') . ' GMT');
		header('Cache-Control: no-store, no-cache, must-revalidate');
		header('Cache-Control: pre-check=0, post-check=0, max-age=0');
		header('Content-Transfer-Encoding: binary');
		header('Content-Encoding: none');
		header('Content-type: application/x-zip');
		header('Content-Disposition: attachment; filename="'.$_parentmodule['folder'].'_'.$_module['folder'].'.zip"');
		header('Content-length: '.filesize(PW_ROOT.'data/_EXPORT/'.$_parentmodule['folder'].'_'.$_module['folder'].'.zip'));
		readfile(PW_ROOT.'data/_EXPORT/'.$_parentmodule['folder'].'_'.$_module['folder'].'.zip');
		@unlink(PW_ROOT.'data/_EXPORT/'.$_parentmodule['folder'].'_'.$_module['folder'].'.zip');
		exit();
	}

}