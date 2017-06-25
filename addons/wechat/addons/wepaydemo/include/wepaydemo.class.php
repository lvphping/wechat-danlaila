<?php

// +----------------------------------------------------------------------

// | phpWeChat wepaydemo 操作类 Last modified 2016-08-23 00:20:44

// +----------------------------------------------------------------------

// | Copyright (c) 2009-2016 phpWeChat http://www.phpwechat.com All rights reserved.

// +----------------------------------------------------------------------

// | Author: 骑马的少年 <phpwechat@126.com> <http://www.phpwechat.com>

// +----------------------------------------------------------------------

namespace wechat\Wepaydemo;

use phpWeChat\Area;

use phpWeChat\CaChe;

use phpWeChat\Config;

use phpWeChat\DataInput;

use phpWeChat\DataList;

use phpWeChat\Member;

use phpWeChat\Module;

use phpWeChat\MySql;

use phpWeChat\Order;

use phpWeChat\Upload;



class Wepaydemo

{

	public static $mPageString=''; // 这个静态成员是系统自带，请勿删除

	//支付成功 回调函数
	public static function goodsPaySuccess($out_trade_no='')
	{
		//检查订单是否存在
		$orderinfo=Order::getOrder($out_trade_no);
		if(!$orderinfo)
		{
			return false;
		}

		//判断订单状态是否是待支付状态
		if($orderinfo['status']!=-1)
		{
			return false;
		}

		//将订单状态设为已支付
		return Order::setOrder(array('status'=>1),$out_trade_no); 
	}


}

?>