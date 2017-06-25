{php !defined('IN_MANAGE') && exit('Access Denied!');use phpWeChat\Config;use phpWeChat\Form;use phpWeChat\Module;use phpWeChat\MySql;}
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
            <div class="crumb-list"><i class="icon-font"></i><a href="{__PW_PATH__}{__ADMIN_FILE__}?file=index&action=main">管理首页</a><span class="crumb-step">&gt;</span><span class="crumb-name">配置选项</span></div>
        </div>
        <div class="result-wrap">
            <form action="" method="post" name="mysubmitform" id="mysubmitform" enctype="multipart/form-data">
            	<input type="hidden" value="1" name="dosubmit">
                <div class="config-items">
                    <div class="config-title">
                        <h1><i class="icon-font">&#xe018;</i>站点信息</h1>
                    </div>
                    <div class="result-content">
                        <table width="100%" class="insert-tab">
                            <tbody>
                                <tr class="formtr" width="15%">
                                    <th class="formth" width="20%"><i class="require-red">*</i>站点域名：</th>
                                    <td class="formtd"><input type="text" value="{$PW['site_url']}" size="24" name="info[site_url]" class="common-text"></td>
                                </tr>
                                <tr class="formtr">
                                    <th class="formth"><i class="require-red">*</i>站点根目录：</th>
                                    <td class="formtd"><input type="text" value="{$PW['pw_path']}" size="24" name="info[pw_path]" class="common-text"></td>
                                </tr>
                                <tr class="formtr">
                                    <th class="formth"><i class="require-red">*</i>ICP备案号码：</th>
                                    <td class="formtd"><input type="text" value="{$PW['site_icpno']}" size="24" name="info[site_icpno]" class="common-text"></td>
                                </tr>
                            </tbody></table>
                    </div>
                </div>
                <div class="config-items">
                    <div class="config-title">
                        <h1><i class="icon-font">&#xe018;</i>联系方式</h1>
                    </div>
                    <div class="result-content">
                        <table width="100%" class="insert-tab">
                            <tr class="formtr">
                                <th class="formth" width="20%"><i class="require-red">*</i>网站联系邮箱：</th>
                                <td class="formtd"><input type="text" value="{$PW['contact_email']}" size="24" name="info[contact_email]" class="common-text"></td>
                            </tr>
                                <tr class="formtr">
                                    <th class="formth"><i class="require-red">*</i>联系人：</th>
                                    <td class="formtd"><input type="text" value="{$PW['contact_user']}" size="24" name="info[contact_user]" class="common-text"></td>
                                </tr>
                                <tr class="formtr">
                                    <th class="formth"><i class="require-red">*</i>联系电话：</th>
                                    <td class="formtd"><input type="text" value="{$PW['contact_telephone']}" size="65" name="info[contact_telephone]" class="common-text"></td>
                                </tr>
                                 <tr class="formtr">
                                    <th class="formth"><i class="require-red">*</i>传真号码：</th>
                                    <td class="formtd"><input type="text" value="{$PW['contact_fax']}" size="65" name="info[contact_fax]" class="common-text"></td>
                                </tr>
                                <tr class="formtr">
                                    <th class="formth"><i class="require-red">*</i>手机号码：</th>
                                    <td class="formtd"><input type="text" value="{$PW['contact_mobile']}" size="65" name="info[contact_mobile]" class="common-text"></td>
                                </tr>
                                <tr class="formtr">
                                    <th class="formth"><i class="require-red">*</i>详细地址：</th>
                                    <td class="formtd"><textarea class="common-textarea" cols="75" rows="5" name="info[contact_address]">{$PW['contact_address']}</textarea></td>
                                </tr>
								
                                <tr class="formtr">
                                    <th class="formth"><i class="require-red">*</i>地图标注：</th>
                                    <td class="formtd">{php echo Form::map('地图标注','contact_map',$PW['contact_map'])}</td>
                                </tr>
								<tr class="formtr">
                                    <th class="formth"><i class="require-red">*</i>工作时间：</th>
                                    <td class="formtd"><textarea class="common-textarea" cols="75" rows="5" name="info[work_time]">{$PW['work_time']}</textarea></td>
                                </tr>
                                <tr class="formtr">
                                    <th class="formth"></th>
                                    <td class="formtd">
                                        <input type="button" onClick="doSubmit('mysubmitform','')" value="提 交" class="submit-button">
                                        <input type="button" value="返 回" onClick="history.go(-1)" class="reset-button">
                                    </td>
                                </tr>
                        </table>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="statics-footer"> Powered by phpWeChat V{__PHPWECHAT_VERSION__}{__PHPWECHAT_RELEASE__} © , Processed in {php echo microtime()-$PW['time_start'];} second(s) , {MySql::$mQuery} queries <a href="#">至顶端↑</a></div>
</body>
</html>