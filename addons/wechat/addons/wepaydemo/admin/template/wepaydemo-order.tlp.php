{php !defined('IN_MANAGE') && exit('Access Denied!');}
<?php
/* 引入常见类的命名空间 */
use wechat\Wepaydemo\Wepaydemo;

use phpWeChat\Area;

use phpWeChat\CaChe;

use phpWeChat\Config;

use phpWeChat\Member;

use phpWeChat\Module;

use phpWeChat\MySql;

use phpWeChat\Order;

use phpWeChat\Upload;
?>
<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>phpWeChat后台管理</title>
    <link rel="stylesheet" type="text/css" href="{__PW_PATH__}admin/template/css/common.css"/>
    <link rel="stylesheet" type="text/css" href="{__PW_PATH__}admin/template/css/main.css"/>
    <link rel="stylesheet" type="text/css" href="{__PW_PATH__}statics/core.css"/>
    <link rel="stylesheet" type="text/css" href="{__PW_PATH__}statics/reveal/reveal.css"/>
    <script language="javascript" type="text/javascript">
		var PW_PATH='{__PW_PATH__}';
	</script>
	<script src="{__PW_PATH__}statics/jquery/jquery-1.12.2.min.js" language="javascript"></script>
    <script src="{__PW_PATH__}statics/reveal/jquery.reveal.js" language="javascript"></script>
	<script src="{__PW_PATH__}statics/core.js" language="javascript"></script>
    <script type="text/javascript" src="{__PW_PATH__}admin/template/js/libs/modernizr.min.js"></script>
    <style type="text/css">
	.a-btn-post {
	    background: #44b549;
	    color: #FFFFFF;
	    font-size: 14px;
	    font-weight: 400;
	    display: block;
	    width: 265px;
	    height: 30px;
	    line-height: 30px;
	    text-align: center;
	    border-radius: 4px;
	    float: left;
	    margin-right: 20px;
	}
	.a-btn-post:hover{color:#FFFFFF; background:#009900}
	</style>
</head>
<body>
    <div class="ifame-main-wrap">
        <div class="crumb-wrap">
            <div class="crumb-list"><i class="icon-font"></i><a href="{__PW_PATH__}{__ADMIN_FILE__}?file=index&action=main">管理首页</a><span class="crumb-step">&gt;</span><span class="crumb-name">订单管理</span></div>
        </div>
        <div class="result-wrap">
                <div class="config-items">
					<div style="border: 1px solid #F3F3F3; padding:8px; margin:8px 0px">
					  <form name="seatchform" method="post" action="{__PW_PATH__}{__ADMIN_FILE__}?mod={$mod}&file={$file}&action={$action}">
					    按订单状态检索：
						<select name="status">
							<option value="0">全部订单</option>
							<option value="-1">待支付</option>
							<option value="1">待发货</option>
                            <option value="3">已发货</option>
							<option value="2">订单完成</option>
							<option value="-2">已退货</option>
							<option value="-3">已取消</option>
							<option value="-4">退货申请中</option>
                            <option value="-5">部分商品待退货</option>
                            <option value="-6">部分商品已退货</option>
						</select>
						&nbsp;&nbsp;&nbsp;&nbsp;
						按订单号检索：
						<input type="text" class="common-text" name="out_trade_no" size="32" />
						<input type="submit" value="搜 索" class="common_btn">
					  </form>
					</div>
                    <form name="orderform" id="orderform" method="post" action="{__PW_PATH__}{__ADMIN_FILE__}?mod={$mod}&file={$file}&action=setorder">
                    <input type="hidden" name="status" value="3">
                    <input type="hidden" name="out_trade_no" id="out_trade_no" value="{$r['out_trade_no']}">
                    <div class="result-content">
                        <table class="result-tab" width="100%">
						  <tr>
							<th width="25%">订单号</th>
							<th width="25%">下单时间</th>
							<th width="10%">金额</th>
							<th width="15%">订单状态</th>
							<th width="25%">操作</th>
						  </tr>
						  {loop $data $r}
							<tr>
								<td>{$r['out_trade_no']}</td>
								<td>{date('Y-m-d',$r['createtime'])}<br>{date('H:i:s',$r['createtime'])}</td>
								<td>{$r['total_fee']} 元</td>
								<td>
								{if $r['status']==-1}
								<font style="color:#FF3300">待支付</font>
								{/if}
								{if $r['status']==-2}
								<font style="color:#666">已退货</font>
								{/if}
								{if $r['status']==-3}
								<font style="color:#666;font-style:italic">订单取消</font>
								{/if}
								{if $r['status']==-4}
								<font style="color:#666;font-style:italic">退货申请中</font>
								{/if}
                                {if $r['status']==-5}
								<font style="color:#666;font-style:italic">部分商品待退货</font>
								{/if}
                                {if $r['status']==-6}
								<font style="color:#666;font-style:italic">部分商品已退货</font>
								{/if}
								{if $r['status']==1}
								<font style="color:#336600;font-style:italic">待发货</font>
								{/if}
								{if $r['status']==2}
								<font style="color:#336600">订单完成</font>
								{/if}
                                {if $r['status']==3}
								<font style="color:#336600">已发货</font>
								{/if}
								</td>
								<td>
								{if $r['status']==-1}
								<a href="{__PW_PATH__}{__ADMIN_FILE__}?mod={$mod}&file={$file}&action=setorder&status=1&out_trade_no={$r['out_trade_no']}">设为已支付</a>
								<br>
								<a href="{__PW_PATH__}{__ADMIN_FILE__}?mod={$mod}&file={$file}&action=setorder&status=-3&out_trade_no={$r['out_trade_no']}">取消订单</a>
								{elseif $r['status']==1}
                                快递公司：<input type="text" class="common-text" name="kdcompany[{$r['out_trade_no']}]">
                                <br>
                                快递编号：<input type="text" class="common-text" name="kdno[{$r['out_trade_no']}]">
                                <br>
                                <a href="###" onClick="$('#out_trade_no').val('{$r['out_trade_no']}');$('#orderform').submit();">设为已发货</a>
								{elseif $r['status']==-4}
								<a href="{__PW_PATH__}{__ADMIN_FILE__}?mod={$mod}&file={$file}&action=setorder&status=-2&out_trade_no={$r['out_trade_no']}">同意退货</a>
								{else}
								-
								{/if}
								</td>
							</tr>
						 {/loop}
						 <tr>
							<td colspan="5">
								{Order::$mPageString} <!-- 分页 -->
							</td>
						 </tr>
					  </table>
                    </div>
                    </form>
                </div>
        </div>
    </div>
    <div class="statics-footer"> Powered by phpWeChat V{date('Y')} © , Processed in {php echo microtime()-$PW['time_start'];} second(s) , {MySql::$mQuery} queries <a href="#">至顶端↑</a></div>
</body>
</html>