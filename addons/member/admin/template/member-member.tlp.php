{php !defined('IN_MANAGE') && exit('Access Denied!');use phpWeChat\Member;use phpWeChat\MySql;use phpWeChat\Ip;}
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
	<script src="{__PW_PATH__}statics/jquery/jquery-1.12.2.min.js" language="javascript"></script>
    <script src="{__PW_PATH__}statics/reveal/jquery.reveal.js" language="javascript"></script>
	<script src="{__PW_PATH__}statics/core.js" language="javascript"></script>
    <script type="text/javascript" src="{__PW_PATH__}admin/template/js/libs/modernizr.min.js"></script>
    <script language="javascript" type="text/javascript">
    
		var PW_PATH='{__PW_PATH__}';
	</script>
</head>
<body>
    <div class="ifame-main-wrap">
        <div class="crumb-wrap">
            <div class="crumb-list"><i class="icon-font"></i><a href="{__PW_PATH__}{__ADMIN_FILE__}?file=index&action=main">管理首页</a><span class="crumb-step">&gt;</span><span class="crumb-name">会员管理</span></div>
        </div>
        <div class="result-wrap">
                <div class="config-items">
					<div style="border: 1px solid #F3F3F3; padding:8px; margin:8px 0px">
					  <form name="seatchform" method="post" action="{__PW_PATH__}{__ADMIN_FILE__}?mod={$mod}&file={$file}&action={$action}">
						按会员名检索：
						<input type="text" class="common-text" name="username" size="32" />
						<input type="submit" value="搜 索" class="common_btn">
					  </form>
					</div>
                    <div class="result-content">
                        <table class="result-tab" width="100%">
						  <tr>
							<th>UserID</th>
							<th>会员账号</th>
							<th>会员等级</th>
							<th>积分余额</th>
							<th>注册邮箱</th>
							<th>注册手机</th>
							<th>注册信息</th>
                            <th>登录信息</th>
						  </tr>
						  {loop $data $r}
							<tr>
								<td>{$r['userid']}</td>
								<td>{$r['username']}</td>
								<td>{Member::levelGet($r['levelid'],'name')}</td>
								<td>积分：{$r['credits']} 分<br>余额：{$r['amount']} 元</td>
								<td>{if $r['email']}{$r['email']}{else}-{/if}</td>
								<td>{$r['telephone']}</td>
								<td>{date('Y-m-d H:i:s',$r['regtime'])}<br>{IP::ip2area($r['regip'])}</td>
                                <td>{date('Y-m-d H:i:s',$r['logintime'])}<br>{IP::ip2area($r['loginip'])}</td>
							</tr>
						 {/loop}
						 <tr>
							<td colspan="8">
								{Member::$mPageString}
							</td>
						 </tr>
					  </table>
                    </div>
                </div>
        </div> 
    </div>
    <div class="statics-footer"> Powered by phpWeChat V{__PHPWECHAT_VERSION__}{__PHPWECHAT_RELEASE__} © , Processed in {php echo microtime()-$PW['time_start'];} second(s) , {MySql::$mQuery} queries <a href="#">至顶端↑</a></div>
</body>
</html>