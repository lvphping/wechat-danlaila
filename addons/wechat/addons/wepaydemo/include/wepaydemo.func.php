<?php
use wechat\Wepaydemo\Wepaydemo;
use phpWeChat\Area;
use phpWeChat\CaChe;
use phpWeChat\Config;
use phpWeChat\Member;
use phpWeChat\Module;
use phpWeChat\MySql;
use phpWeChat\Order;
use phpWeChat\Upload;

//支付成功 回调函数
//该函数由订单中 mod+action+'PaySuccess'组成，参数为订单号

function wepaydemoGoodsPaySuccess($out_trade_no='')
{
	return Wepaydemo::goodsPaySuccess($out_trade_no);
}
?>