<?php
// +----------------------------------------------------------------------
// | phpWeChat 微信支付操作类 Last modified 2016/5/6
// +----------------------------------------------------------------------
// | Copyright (c) 2009-2016 phpWeChat http://www.phpwechat.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 骑马的少年 <phpwechat@126.com> <http://www.phpwechat.com>
// +----------------------------------------------------------------------

require_once dirname(__FILE__)."/lib/WxPay.Api.php";
require_once dirname(__FILE__)."/WxPay.JsApiPay.php";
require_once dirname(__FILE__).'/log.php';

class Wepay
{
	static public function getWePayParameters($order=array())
	{
		$data=array();
		
		$tools = new JsApiPay();

		$openId = $_SESSION['openid'];
		
		$order['attach']=$order['attach']?$order['attach']:$order['body'];
		$order['detail']=$order['detail']?$order['detail']:$order['body'];
		$order['time_start']=$order['time_start']?$order['time_start']:date("YmdHis");
		$order['time_expire']=$order['time_expire']?$order['time_expire']:date("YmdHis", time() + 600);
		$order['goods_tag']=$order['goods_tag']?$order['goods_tag']:'No Tag';

		$order['body']=sub_string($order['body'],128,'');
		$order['detail']=sub_string($order['detail'],8192,'');
		$order['attach']=sub_string($order['attach'],127,'');
		$order['product_id']=intval($order['product_id']);
		$order['total_fee']=floatval($order['total_fee']);
		
		$order=array_map('strip_tags',$order);

		$input = new WxPayUnifiedOrder();
		$input->SetBody($order['body']);
		$input->SetAttach($order['attach']);
		$input->SetDetail($order['detail']);
		$input->SetOut_trade_no($order['out_trade_no']);
		$input->SetTotal_fee($order['total_fee']*100);
		$input->SetTime_start($order['time_start']);
		$input->SetTime_expire($order['time_expire']);
		$input->SetGoods_tag($order['goods_tag']);
		$input->SetProduct_id($order['product_id']);
		$input->SetNotify_url(SITE_URL."api/payment/wepay/notify.php");
		$input->SetTrade_type("JSAPI");
		$input->SetOpenid($openId);
		
		$orderdata = WxPayApi::unifiedOrder($input);

		if($orderdata['return_code']=='FAIL')
		{
			wealert($orderdata['return_msg'],'error');
		}
		else
		{
			$data['jsApiParameters'] = $tools->GetJsApiParameters($orderdata);
			$data['editAddress'] = $tools->GetEditAddressParameters();
		}
		
		return $data;
	}
}
?>