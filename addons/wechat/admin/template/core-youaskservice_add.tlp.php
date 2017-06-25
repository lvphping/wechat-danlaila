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
    <style type="text/css">
		.btn-post{clear:both; margin:10px 10px; height:30px;}
		.a-btn-post{background:#44b549 ; color:#FFFFFF; font-size: 14px;font-weight: 400; display:block; width:125px; height:30px; line-height:30px; text-align:center;border-radius: 4px; float:left; margin-right:20px }
		.a-btn-post:hover{color:#FFFFFF; background:#339933}
	</style>
</head>
<body>
    <div class="ifame-main-wrap">
        <div class="crumb-wrap">
            <div class="crumb-list"><i class="icon-font"></i><a href="{__PW_PATH__}{__ADMIN_FILE__}?file=index&action=main">管理首页</a><span class="crumb-step">&gt;</span><span class="crumb-name">客服功能</span></div>
        </div>
        <div class="result-wrap">
            <form action="" method="post" name="mysubmitform" id="mysubmitform" enctype="multipart/form-data">
            	<input type="hidden" value="1" name="dosubmit">
				<input type="hidden" value="{if $PW['wechat_token']}{$PW['wechat_token']}{else}{create_rand(32)}{/if}" name="info[wechat_token]">
				<input type="hidden" value="{if $PW['wechat_encodingaeskey']}{$PW['wechat_encodingaeskey']}{else}{create_rand(43)}{/if}" name="info[wechat_encodingaeskey]">
                <div class="config-items">
                    <div class="admin-nav">
                        <h2>客服功能</h2>
                        <div class="nav">
                            <a href="{__PW_PATH__}{__ADMIN_FILE__}?mod=wechat&file=core&action=youaskservice">全部客服</a>
                            <a href="{__PW_PATH__}{__ADMIN_FILE__}?mod=wechat&file=core&action=youaskservice_onlie">在线客服</a>
                            <a href="{__PW_PATH__}{__ADMIN_FILE__}?mod=wechat&file=core&action=youaskservice_add" class="hover">添加客服</a>
                        </div>
                        <div class="admin-tips">
                           绑定后的客服帐号，可以<a href="https://mpkf.weixin.qq.com/" target="_blank">登录在线客服功能</a>，进行客服沟通。详情查看<a href="http://kf.qq.com/faq/120911VrYVrA160126Nvi6NN.html" target="_blank">使用说明</a>。
                        </div>
                    </div>
                    <div class="btn-post">
                        <a href="{__PW_PATH__}{__ADMIN_FILE__}?mod=wechat&file=core&action=youaskservice_synchronism" class="a-btn-post" ><i class="icon-font">&#xe025;</i> 同步微信客服</a>
                    </div>
                    <div class="result-content">
                        <table width="100%" class="insert-tab">
                            <tbody>
                                <tr class="formtr">
                                    <th class="formth" width="20%"><i class="require-red">*</i>客服账号：</th>
                                    <td class="formtd"><input type="text" size="20" name="info[kf_id]" class="common-text"> @{$PW['wechat_no']}</td>
                                </tr>
                                <tr class="formtr">
                                    <th class="formth"><i class="require-red">*</i>客服昵称：</th>
                                    <td class="formtd"><input type="text" size="32" name="info[kf_nick]" class="common-text"></td>
                                </tr>
                                <tr class="formtr">
                                    <th class="formth"><i class="require-red">*</i>客服密码：</th>
                                    <td class="formtd"><input type="password" size="32" name="info[password]" class="common-text"></td>
                                </tr>
                                <tr class="formtr">
                                    <th class="formth"><i class="require-red">*</i>客服头像：</th>
                                    <td class="formtd">
                                    {php echo Form::image('客服头像','kf_headimgurl');}
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
            </form>
        </div>
    </div>
</body>
</html>