{php !defined('IN_MANAGE') && exit('Access Denied!');use Wechat\WeChatManage;use Wechat\Wechat;use phpWeChat\Form;use phpWeChat\MySql;}
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
            <div class="crumb-list"><i class="icon-font"></i><a href="{__PW_PATH__}{__ADMIN_FILE__}?file=index&action=main">管理首页</a><span class="crumb-step">&gt;</span><span class="crumb-name">微信支付配置</span></div>
        </div>
		<form action="" method="post" name="mysubmitform" id="mysubmitform" enctype="multipart/form-data">
		<input type="hidden" value="1" name="dosubmit">
        <div class="result-wrap">
			<div class="config-items">
				<div class="admin-nav">
                        <h2>微信支付配置</h2>
                        <div class="nav">
                            <a href="{__PW_PATH__}{__ADMIN_FILE__}?mod=wechat&file=config&action=wepay" class="hover">微信支付配置</a>
                        </div>
                    </div>
				<div class="result-content">
					<table width="100%" class="insert-tab">
						<tbody>
							<tr class="formtr">
								<th class="formth"><i class="require-red">*</i>AppID（公众号）：</th>
								<td class="formtd"><input type="text" value="{$PW['wechat_appid']}" size="65" name="info[wechat_appid]" class="common-text"></td>
							</tr>
							<tr class="formtr">
								<th class="formth"><i class="require-red">*</i>AppSecret：</th>
								<td class="formtd"><input type="text" value="{$PW['wechat_appsecret']}" size="65" name="info[wechat_appsecret]" class="common-text"></td>
							</tr>
							<tr class="formtr">
								<th class="formth"><i class="require-red">*</i>支付密钥（PartnerKey）：</th>
								<td class="formtd"><input type="text" value="{$PW['wechat_paysignkey']}" size="65" name="info[wechat_paysignkey]" class="common-text"></td>
							</tr>
							<tr class="formtr">
								<th class="formth"><i class="require-red">*</i>微信支付商户号：</th>
								<td class="formtd"><input type="text" value="{$PW['wechat_mchid']}" size="65" name="info[wechat_mchid]" class="common-text"></td>
							</tr>
							<tr class="formtr">
								<th class="formth">上传证书 （apiclient_cert.pem）：</th>
								<td class="formtd">
								{php echo Form::formFile('apiclient_cert.pem','wechat_apiclient_cert',$PW['wechat_apiclient_cert']);}
								
								</td>
							</tr>
							<tr class="formtr">
								<th class="formth">上传密匙 （apiclient_key.pem）：</th>
								<td class="formtd">
								{php echo Form::formFile('apiclient_key.pem','wechat_apiclient_key',$PW['wechat_apiclient_key']);}
								
								</td>
							</tr>
							<tr class="formtr">
								<th class="formth"></th>
								<td class="formtd">
									<input type="button" onClick="doSubmit('mysubmitform','')" value="提 交" class="submit-button">
									<input type="button" value="返 回" onClick="history.go(-1)" class="reset-button">
								</td>
							</tr>
						</tbody></table>
				</div>
			</div>
        </div>
		</form>
    </div>
</body>
</html>