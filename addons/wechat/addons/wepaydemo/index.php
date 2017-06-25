<?php

	use wechat\Wepaydemo\Wepaydemo;

	use WeChat\WeChat;

	use phpWeChat\Area;

	use phpWeChat\CaChe;

	use phpWeChat\Config;

	use phpWeChat\Member;

	use phpWeChat\Module;

	use phpWeChat\MySql;

	use phpWeChat\Order;

	use phpWeChat\Upload;


	if(floatval(PHPWECHAT_VERSION)<1.1 && floatval(PHPWECHAT_RELEASE)<0.4)
	{
		wealert('版本过低，请先升级phpWeChat核心框架版本！','error');
	}


	/**

	 * 本文件是 wepaydemo 模块的前端控制器

	 *

	 * 您可以通过 switch 的 case 分支来实现不同的业务逻辑

	 */



	!defined('IN_APP') && exit('Access Denied!');

	
	switch($action)

	{

		 //以下 case 条件仅为 示例。您可以根据业务逻辑自由修改和拓展

		case 'index':
			// 自动获取当前用户openid 并存储到 $_SESSION['openid']
			// 此步骤是必须的

			if(!$_SESSION['openid'])
			{
				$_SESSION['openid']=WeChat::getOpenID();
			}

			Member::createRandAccount();

			//商品页逻辑
			//实际运用中可以在这里写商品读取逻辑
			//比如从商品表读取商品然后赋给视图模板展示
			break;
			
		case 'ordercomplate':
			//接受商品页传过来的参数并生成微信支付必须的参数
			//定义一个数组 $order=array() 用于存放订单信息

			$order=array();

			//调用 Order类的createOutTradeNo()方法生成订单号
			$order['out_trade_no']=Order::createOutTradeNo();

			//订单创建时间 CLIENT_TIME是phpWeChat 自带的Linux时间戳常量 
			$order['createtime']=CLIENT_TIME;

			//下单人userid，$_userid 是phpWeChat 自带的系统变量，指当前用户的ID
			$order['userid']=$_userid;

			//订单描述
			$order['body']='Macbook Air 银色';

			//订单商品数量 
			$order['num']=intval($num);

			//订单总额
			$order['total_fee']=intval($num)*floatval($price);

			// $order['mod'] 是一个固定参数 ，值为模块文件夹名
			$order['mod']='wepaydemo';

			// $order['action'] 是一个必选参数 ，由a-z字母自定义组成
			// 此时只需在 wepaydemo.class.php 里定义个方法 PaySuccess($out_trade_no='') 即可完成支付成功后的回调操作
			$order['action']='goods';

			//设置订单状态：-1 待支付
			$order['status']=-1;

			//生成待支付订单并插入系统订单表pw_order
			Order::createOrder($order);
			
			//生成微信支付参数
			$paypara=Wepay::getWePayParameters($order);

			break;
		case 'ordershow':
			//订单支付成功结束页
			//根据传过来的参数out_trade_no（订单号）获取订单信息

			$data=Order::getOrder($out_trade_no);
			break;



		//case 'list':

			

			//在此写 index.php?m=wepaydemo&a=list 时的逻辑

			

			//break;



		//以此类推...



		//case '...':

			

			//在此写 index.php?m=wepaydemo&a=... 时的逻辑

			

			//break;

		default:

			break;

	}

?>