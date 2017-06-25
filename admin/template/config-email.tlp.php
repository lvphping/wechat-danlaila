{php !defined('IN_MANAGE') && exit('Access Denied!');use phpWeChat\Email;use phpWeChat\Form;use phpWeChat\Module;use phpWeChat\MySql;}
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
            <div class="crumb-list"><i class="icon-font"></i><a href="{__PW_PATH__}{__ADMIN_FILE__}?file=index&action=main">管理首页</a><span class="crumb-step">&gt;</span><span class="crumb-name">邮件配置</span></div>
        </div>
        <form action="" method="post" name="mysubmitform" id="mysubmitform" enctype="multipart/form-data">
        <input type="hidden" value="1" name="dosubmit">
        <div class="result-wrap">
                <div class="config-items">
                    <div class="config-title">
                        <h1><i class="icon-font">&#xe018;</i>邮件配置</h1>
                    </div>
                    <div class="result-content">
                        <table width="100%" class="insert-tab">
                            <tbody>
                                <tr class="formtr">
                                    <th class="formth" width="20%"><i class="require-red">*</i>邮件发送方式：</th>
                                    <td class="formtd">
                                    <label><input type="radio" class="common-radio" name="info[mail_type]" value="1"  {php echo $PW['mail_type']==1?'checked="checked"':'';}/>通过SMTP协议发送（支持ESMTP验证，推荐）</label><br />
                                    <label><input type="radio" class="common-radio" name="info[mail_type]"  {if strtolower(substr(PHP_OS,0,3))=='win'} disabled="disabled"{/if} value="2"  {php echo $PW['mail_type']==2?'checked="checked"':'';}/>通过mail函数发送（仅*unix类主机支持）</label><br />
                                    <label><input type="radio" class="common-radio" name="info[mail_type]"  {if strtolower(substr(PHP_OS,0,3))!='win'} disabled="disabled"{/if} value="3"  {php echo $PW['mail_type']==3?'checked="checked"':'';}/>通过SOCKET连接SMTP服务器发送（仅Windows主机支持）</label>
                                    </td>
                                </tr>

                                <tr class="formtr">
                                    <th class="formth"><i class="require-red">*</i>邮件服务器：</th>
                                    <td class="formtd">
                                    <input type="text" class="common-text" name="info[mail_server]" value="{$PW['mail_server']}" size="45" />
                                    </td>
                                </tr>
                                <tr class="formtr">
                                    <th class="formth"><i class="require-red">*</i>邮件发送端口：</th>
                                    <td class="formtd">
                                    <input type="text" class="common-text" name="info[mail_port]" value="{$PW['mail_port']}" size="2" />
                                    <font style="color:#CCC; font-size:12px">默认为25，一般不需要改</font>
                                    </td>
                                </tr>
                                <tr class="formtr">
                                    <th class="formth"><i class="require-red">*</i>发送邮箱帐号：</th>
                                    <td class="formtd">
                                    <input type="text" class="common-text" name="info[mail_user]" value="{$PW['mail_user']}" size="45" />
                                    </td>
                                </tr>
                                <tr class="formtr">
                                    <th class="formth"><i class="require-red">*</i>发送邮箱密码：</th>
                                    <td class="formtd">
                                    <input type="password" class="common-text" name="info[mail_pwd]" value="{$PW['mail_pwd']}" size="45" />
                                    </td>
                                </tr>
                            </tbody></table>
                    </div>
                </div>
        </div>
        <div class="result-wrap">
                <div class="config-items">
                    <div class="config-title">
                        <h1><i class="icon-font">&#xe018;</i>邮件配置</h1>
                    </div>
                    <div class="result-content">
                        <table width="100%" class="insert-tab">
                            <tbody>
                                <tr class="formtr">
                                    <th class="formth" width="20%">测试邮箱帐号：</th>
                                    <td class="formtd">
                                    <input type="text" class="common-text" name="test_email" size="45" />
                                     <br><font style="color:#CCC; font-size:12px">用于测试您的配置信息是否正确，请填写真实邮箱</font>
                                    </td>
                                </tr>
                                <tr class="formtr">
                                    <th class="formth"></th>
                                    <td class="formtd">
                                        <input type="button" onClick="doSubmit('mysubmitform','')" value="提 交" class="submit-button">
                                        <input type="button" value="返 回" onclick="history.go(-1)" class="reset-button">
                                    </td>
                                </tr>
                            </tbody></table>
                    </div>
                </div>
        </div>
        </form>
    </div>
    <div class="statics-footer"> Powered by phpWeChat V{__PHPWECHAT_VERSION__}{__PHPWECHAT_RELEASE__} © , Processed in {php echo microtime()-$PW['time_start'];} second(s) , {MySql::$mQuery} queries <a href="#">至顶端↑</a></div>
</body>
</html>