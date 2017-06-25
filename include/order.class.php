<?php
// +----------------------------------------------------------------------
// | phpWeChat 订单操作类 Last modified 2016/5/7 14:00
// +----------------------------------------------------------------------
// | Copyright (c) 2009-2016 phpWeChat http://www.phpwechat.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 骑马的少年 <phpwechat@126.com> <http://www.phpwechat.com>
// +----------------------------------------------------------------------
namespace phpWeChat;

class Order
{
	protected static $mOrderTable='order';
	protected static $mBlockedAccountTable='member_blocked_account';
	protected static $mCacheOrderTable='cache_order';
	public static $mPageString='';
	
	/*
		缓存订单操作
	*/

	public static function getCacheOrder($userid) 
	{
		return MySql::fetchOne("SELECT * FROM `".DB_PRE.self::$mCacheOrderTable."` WHERE `userid`='".intval($userid)."'");
	}

	public static function setCacheOrder($goodsid,$standard,$num,$userid) 
	{
		$order=array();
		$order['goodsid']=json_encode($goodsid);
		$order['standard']=json_encode($standard);
		$order['num']=json_encode($num);
		$order['userid']=intval($userid);
		return MySql::insert(DB_PRE.self::$mCacheOrderTable,$order,true);
	}

	/*
		订单操作
	*/
	public static function orderList($status,$out_trade_no,$pagesize=10,$mod='',$action='') 
	{
		$status=intval($status);
		$out_trade_no=preg_replace('/[^0-9a-z_\-]/i','',$out_trade_no);
		$mod=preg_replace('/[^0-9a-z_\-]/i','',$mod);
		$action=preg_replace('/[^0-9a-z_\-]/i','',$action);

		$where=$status?'`status`='.$status:'1';
		$where.=$out_trade_no?' AND `out_trade_no`=\''.$out_trade_no.'\'':'';
		$where.=$mod?' AND `mod`=\''.$mod.'\'':'';
		$where.=$action?' AND `action`=\''.$action.'\'':'';

		$orderby='`id` DESC';

		$result=DataList::getList(DB_PRE.self::$mOrderTable,$where,$orderby,max(isset($_GET['page'])?intval($_GET['page']):1,1),intval($pagesize),0,'right');

		self::$mPageString=DataList::$mPageString;

		return $result;
	}
	
	public static function orderNoLimitList($userid=0,$status=0) 
	{
		if($status)
		{
			return MySql::fetchAll("SELECT * FROM `".DB_PRE.self::$mOrderTable."` WHERE `userid`=".intval($userid)." AND `status`=".intval($status)." ORDER BY `id` DESC");
		}
		else
		{
			return MySql::fetchAll("SELECT * FROM `".DB_PRE.self::$mOrderTable."` WHERE `userid`=".intval($userid)." ORDER BY `id` DESC");
		}
	}

	public static function createOrder($order=array(),$extra=array())
	{
		global $_userid,$PW;

		$order['extra']=$extra?urldecode(json_encode(array_map('pw_urlencode',$extra))):'';
		return MySql::insert(DB_PRE.self::$mOrderTable,pw_strip_tags($order));
	}

	public static function getOrder($out_trade_no,$f='*')
	{
		$out_trade_no=str_callback_w($out_trade_no);

		if(!trim($out_trade_no))
		{
			return array();
		}

		$r=MySql::fetchOne("SELECT * FROM `".DB_PRE.self::$mOrderTable."` WHERE `out_trade_no`='".trim($out_trade_no)."'");

		if(!$r)
		{
			return array();
		}

		return $f=='*'?$r:$r[$f];
	}
	
	public static function getOrderById($id,$f='*')
	{
		$id=intval($id);

		if(!trim($id))
		{
			return array();
		}

		$r=MySql::fetchOne("SELECT * FROM `".DB_PRE.self::$mOrderTable."` WHERE `id`=".intval($id));

		if(!$r)
		{
			return array();
		}

		return $f=='*'?$r:$r[$f];
	}

	public static function setOrder($info=array(),$out_trade_no='',$goodsid=0,$standard=0)
	{
		global $PW;

		$orderdata=Order::getOrder($out_trade_no);
		$memdata=Member::getUserByUserId($orderdata['userid']);
		$goodsid=intval($goodsid);
		$standard=intval($standard);

		$orderdata['goodsid']=pw_urlencode(json_decode($orderdata['goodsid'],true));

		$moretime=pw_urlencode(json_decode($orderdata['moretime'],true));
		
		if($info['status']!=3)
		{
			unset($info['kdno'],$info['kdcompany']);
		}

		if($info['status']==1)
		{
			$moretime['paytime']=array(urlencode('订单支付成功，待发货'),time());
		}

		if($info['status']==2)
		{
			$moretime['receipttime']=array(urlencode('买家已收货，订单完成'),time());
		}

		if($info['status']==3)
		{
			$moretime['shipmentstime']=array(urlencode('卖家已发货（'.$info['kdcompany'].'：'.$info['kdno'].'）'),time());
		}

		if($info['status']==-1)
		{
			$moretime['paybacktime']=array(urlencode('订单已退款'),time());
		}

		if($info['status']==-2)
		{
			$moretime['returntime']=array(urlencode('退货申请通过，已退货'),time());
		}

		if($info['status']==-3)
		{
			$moretime['canceltime']=array(urlencode('订单被取消'),time());
		}

		if($info['status']==-4)
		{
			if($goodsid && array_key_exists($goodsid,$orderdata['goodsid']))
			{
				if(isset($orderdata['goodsid'][$goodsid][$standard]))
				{
					if($orderdata['status']==1)
					{
						$orderdata['goodsid'][$goodsid][$standard]['status']=-6;
						$info['status']=-6;
						$moretime['subreturntime']=array(urlencode('部分商品已退货'),time());
					}
					else
					{
						$orderdata['goodsid'][$goodsid][$standard]['status']=-5;
						$info['status']=-5;
						$moretime['subreturningtime']=array(urlencode('部分商品退货待通过'),time());
					}
				}
			}
			else
			{
				$moretime['returningtime']=array(urlencode('退货申请中'),time());
				if($orderdata['status']==1)
				{
					$info['status']=-2;
					$moretime['returntime']=array(urlencode('退货申请通过，已退货'),time());
				}
			}
		}

		if($info['status']==-6 && $goodsid && array_key_exists($goodsid,$orderdata['goodsid']) && isset($orderdata['goodsid'][$goodsid][$standard]))
		{
			$orderdata['goodsid'][$goodsid][$standard]['status']=-6;
			$moretime['subreturntime']=array(urlencode('部分商品已退货'),time());
		}

		$info['moretime']=urldecode(json_encode($moretime));
		$info['goodsid']=json_encode($orderdata['goodsid']);

		return MySql::update(DB_PRE.self::$mOrderTable,$info,'`out_trade_no`=\''.str_callback_w($out_trade_no).'\'');
	}

	public static function createOutTradeNo()
	{
		$yCode = range('A','Z');
		$orderSn = $yCode[intval(date('Y')) - 2016] . date('m') . date('d') . substr(time(), -5) . substr(microtime(), 2, 5) . sprintf('%02d', mt_rand(0, 99));
		return strtoupper($orderSn.substr(uniqid('',true),-8));
	}

	public static function userOrderCount($userid,$status=0)
	{
		if(!intval($status))
		{
			return get_cache_counts("SELECT COUNT(*) AS count FROM `".DB_PRE.self::$mOrderTable."` WHERE `userid`=".intval($userid));
		}

		return get_cache_counts("SELECT COUNT(*) AS count FROM `".DB_PRE.self::$mOrderTable."` WHERE `userid`=".intval($userid)." AND `status`=".intval($status)); 
	}

	public static function userOrderTotalFee($userid,$status=0)
	{
		if(!intval($status))
		{
			$r=MySql::fetchOne("SELECT SUM(`total_fee`) AS totalfee FROM `".DB_PRE.self::$mOrderTable."` WHERE `userid`=".intval($userid));
		}

		$r=MySql::fetchOne("SELECT SUM(`total_fee`) AS totalfee FROM `".DB_PRE.self::$mOrderTable."` WHERE `userid`=".intval($userid)." AND `status`=".intval($status)); 
		
		return round($r['totalfee'],2);
	}
}