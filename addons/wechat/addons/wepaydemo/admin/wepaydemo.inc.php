<?php

/**

	 * 本文件是 wepaydemo 模块的后端控制器

	 *

	 * 您可以通过 switch 的 case 分支来实现不同的业务逻辑

	 */



use wechat\Wepaydemo\Wepaydemo;

use phpWeChat\Area;

use phpWeChat\CaChe;

use phpWeChat\Config;

use phpWeChat\Member;

use phpWeChat\Module;

use phpWeChat\MySql;

use phpWeChat\Order;

use phpWeChat\Upload;



!defined('IN_MANAGE') && exit('Access Denied!'); 



$mod=@return_edefualt(str_callback_w($_GET['mod']),'wepaydemo');

$file=@return_edefualt(str_callback_w($_GET['file']),'wepaydemo');

$action=@return_edefualt(str_callback_w($_GET['action']),'config');



$_parent=Module::getModuleByKey(Module::getModule($mod,'parentkey'));

$_mod=$_parent['folder'].'/'.$mod.'/';



switch($action)

{

	// case 'order' 是管理订单记录的操作
	// Order 类是 phpWeChat 系统自带的一个类，用于订单相关的操作。

	case 'order':
		//根据订单状态筛选
		$status=isset($status)?intval($status):0; 

		//根据订单号筛选
		$out_trade_no=isset($out_trade_no)?preg_replace('/[^0-9a-z]/i','',$out_trade_no):''; 
		
		//调用 Order类的 orderList()方法来读取订单记录
		$data=Order::orderList($status,$out_trade_no,20); 
		
		include_once parse_admin_tlp($file.'-'.$action,$mod);

		break;
	case 'setorder':
		// 定义$change数组，包含要更改的字段
		$change=array();
		$change['status']=intval($status); //订单状态
		$change['kdcompany']=$kdcompany[$out_trade_no]; //快递公司
		$change['kdno']=$kdno[$out_trade_no]; //快递单号

		//调用Order 的serOrder()方法，更改订单状态和快递单号（状态为发货时）等
		Order::setOrder($change,$out_trade_no);
		
		operation_tips('操作成功！');
		break;
	//以下 case 条件仅为 示例。您可以根据业务逻辑自由修改和拓展





	//case 'manage':

			

		//在此写 phpwechat.php?mod=wepaydemo&file=wepaydemo&action=manage 时的逻辑

			

		//break;



	//case 'add':

			

		//在此写 phpwechat.php?mod=wepaydemo&file=wepaydemo&action=add 时的逻辑

		

		//break;



	//以此类推...



	//case '...':

			

		//在此写 phpwechat.php?mod=wepaydemo&file=wepaydemo&action=... 时的逻辑

			

		//break;

	default:

		break;

}

?>