<?php
/**
	 * 本文件是 demo 模块的后端控制器
	 *
	 * 您可以通过 switch 的 case 分支来实现不同的业务逻辑
	 */

use phpWeChat\Config;
use phpWeChat\Module;
use Pc\Demo\Demo;

!defined('IN_MANAGE') && exit('Access Denied!'); 

$mod=@return_edefualt(str_callback_w($_GET['mod']),'demo');
$file=@return_edefualt(str_callback_w($_GET['file']),'demo');
$action=@return_edefualt(str_callback_w($_GET['action']),'config');

$_parent=Module::getModuleByKey(Module::getModule($mod,'parentkey'));
$_mod=$_parent['folder'].'/'.$mod.'/';

switch($action)
{
	// case 'config' 是系统默认自带操作，用于进行模块选项配置的操作
	case 'config':
		if(isset($dosubmit))
		{
			Config::setConfig($_mod,$config);
			operation_tips('操作成功！','?mod=demo&file=demo&action=config');
		}
		include_once parse_admin_tlp($file.'-'.$action,$mod);
		break;

    //管理
	case 'manage':
		// 调用demo.class.php（模型） 里定义的类和方法 此时 $data 是个二维数组并包含所有的留言信息
		// 不分页
        // $data = Demo::demoList();

        //分页
        $title = htmlspecialchars(trim($title));
        $data = Demo::demoListPage($title,20);

        //echo Demo::$mPageString;
		// 调用 demo-manage.tlp.php （视图）模板
		include_once parse_admin_tlp($file.'-'.$action,$mod);
		break;

    //删除
    case 'delete':
        Demo::demoDelete($id);
        operation_tips('操作成功！','?mod=demo&file=demo&action=manage');
        break;

    //添加
	case 'add':
        if(isset($dosubmit))
        {
            $op = Demo::demoAdd($info);
            if($op>0)
            {
                operation_tips('添加成功！','?mod=demo&file=demo&action=manage');
            }
            else
            {
                operation_tips('操作失败 ['.$op.']！','?mod=demo&file=demo&action=add','error');
            }
        }
        include_once parse_admin_tlp($file.'-'.$action,$mod);
		break;

    //修改
    case 'edit':
        if(isset($dosubmit))
        {
            $op = Demo::demoEdit($info,$id);
            if($op>0)
            {
                operation_tips('编辑成功！','?mod=demo&file=demo&action=manage');
            }
            else
            {
                operation_tips('操作失败 ['.$op.']！','','error');
            }
        }

        $data = array();

        if($id)
        {
            $data=Demo::demoGet($id);
        }

        include_once parse_admin_tlp($file.'-'.$action,$mod);
        break;

	//以此类推...

	//case '...':
			
		//在此写 phpwechat.php?mod=demo&file=demo&action=... 时的逻辑
			
		//break;
	default:
		break;
}
?>