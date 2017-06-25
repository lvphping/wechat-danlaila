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
            <div class="crumb-list"><i class="icon-font"></i><a href="{__PW_PATH__}{__ADMIN_FILE__}?file=index&action=main">管理首页</a><span class="crumb-step">&gt;</span><span class="crumb-name">公共号配置</span></div>
        </div>
        <div class="result-wrap">
            <form action="" method="post" name="mysubmitform" id="mysubmitform" enctype="multipart/form-data">
            	<input type="hidden" value="1" name="dosubmit">
				<input type="hidden" value="{if $PW['wechat_token']}{$PW['wechat_token']}{else}{create_rand(32)}{/if}" name="info[wechat_token]">
				<input type="hidden" value="{if $PW['wechat_encodingaeskey']}{$PW['wechat_encodingaeskey']}{else}{create_rand(43)}{/if}" name="info[wechat_encodingaeskey]">
                <div class="config-items">
                    <div class="admin-nav">
                        <h2>公共号配置</h2>
                        <div class="nav">
                            <a href="{__PW_PATH__}{__ADMIN_FILE__}?mod=wechat&file=config&action=config" class="hover">公共号配置</a>
                            <a href="{__PW_PATH__}{__ADMIN_FILE__}?mod=wechat&file=config&action=interface">公共号接入</a>
                        </div>
                    </div>
                    <div class="result-content">
                        <table width="100%" class="insert-tab">
                            <tbody>
                                <tr class="formtr">
                                    <th class="formth" width="20%"><i class="require-red">*</i>公共号名称：</th>
                                    <td class="formtd"><input type="text" value="{$PW['wechat_name']}" size="32" name="info[wechat_name]" class="common-text"></td>
                                </tr>
                                <tr class="formtr">
                                    <th class="formth"><i class="require-red">*</i>公共号原始id：</th>
                                    <td class="formtd"><input type="text" value="{$PW['wechat_id']}" size="65" name="info[wechat_id]" class="common-text"></td>
                                </tr>
                                <tr class="formtr">
                                    <th class="formth"><i class="require-red">*</i>微信号：</th>
                                    <td class="formtd"><input type="text" value="{$PW['wechat_no']}" size="32" name="info[wechat_no]" class="common-text"></td>
                                </tr>
                                <tr class="formtr">
                                    <th class="formth"><i class="require-red">*</i>AppID（公共号）：</th>
                                    <td class="formtd"><input type="text" value="{$PW['wechat_appid']}" size="65" name="info[wechat_appid]" class="common-text"></td>
                                </tr>
								<tr class="formtr">
                                    <th class="formth"><i class="require-red">*</i>AppSecret：</th>
                                    <td class="formtd"><input type="text" value="{$PW['wechat_appsecret']}" size="65" name="info[wechat_appsecret]" class="common-text"></td>
                                </tr>
                                <tr class="formtr">
                                    <th class="formth"><i class="require-red">*</i>二维码：</th>
                                    <td class="formtd">
                                    {php echo Form::image('二维码','wechat_qr',$PW['wechat_qr']);}
                                    
                                    </td>
                                </tr>
								<tr class="formtr">
                                    <th class="formth"><i class="require-red">*</i>微信号类型：</th>
                                    <td class="formtd">
									<select name="info[wechat_type]">
										<option value="1" {if $PW['wechat_type']==1} selected="selected"{/if}>订阅号</option>
										<option value="2" {if $PW['wechat_type']==2} selected="selected"{/if}>服务号</option>
										<option value="3" {if $PW['wechat_type']==3} selected="selected"{/if}>认证订阅号</option>
										<option value="4" {if $PW['wechat_type']==4} selected="selected"{/if}>认证服务号</option>
									</select>
									</td>
                                </tr>
								<tr class="formtr">
                                    <th class="formth"><i class="require-red">*</i>是否启用小逗比智能回复：</th>
                                    <td class="formtd">
                                    <label><input type="radio" class="common-radio" name="info[wechat_isxiaodoubi_on]" {if $PW['wechat_isxiaodoubi_on']=='1'}checked="checked"{/if} value="1">启用</label>
									&nbsp;&nbsp;
                                    <label><input type="radio" class="common-radio" name="info[wechat_isxiaodoubi_on]" {if $PW['wechat_isxiaodoubi_on']=='0'}checked="checked"{/if} value="0">不启用</label>
	
									<font style="color:#CCCCCC; font-size:12px">启用后，用户回复时，将以智能回复模式代替自定义模式。</font>
                                    </td>
                                </tr>
								<tr class="formtr">
                                    <th class="formth"><i class="require-red">*</i>小逗比智能回复授权码：</th>
                                    <td class="formtd">
                                    <input type="text" value="{$PW['wechat_xiaodoubi_key']}" size="65" name="info[wechat_xiaodoubi_key]" class="common-text">
									<br>
                                    <font style="color:#CCCCCC; font-size:12px">小逗比智能回复授权码获取地址：<a href="http://xiao.douqq.com/" target="_blank">http://xiao.douqq.com/</a></font>
                                    </td>
                                </tr>
                                <tr class="formtr">
                                    <th class="formth" width="20%"><i class="require-red">*</i>转在线客服：</th>
                                    <td class="formtd"><input type="text" value="{$PW['to_kf_keyword']}" size="32" name="info[to_kf_keyword]" class="common-text"></td>
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