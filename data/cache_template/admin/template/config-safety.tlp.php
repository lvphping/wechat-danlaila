<?php !defined('IN_MANAGE') && exit('Access Denied!');use phpWeChat\Cache;use phpWeChat\Form;use phpWeChat\Module;use phpWeChat\MySql;?>
<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>phpWeChat后台管理</title>
    <link rel="stylesheet" type="text/css" href="<?php echo defined('PW_PATH')?PW_PATH:'{__PW_PATH__}';?>admin/template/css/common.css"/>
    <link rel="stylesheet" type="text/css" href="<?php echo defined('PW_PATH')?PW_PATH:'{__PW_PATH__}';?>admin/template/css/main.css"/>
    <link rel="stylesheet" type="text/css" href="<?php echo defined('PW_PATH')?PW_PATH:'{__PW_PATH__}';?>statics/core.css"/>
    <link rel="stylesheet" type="text/css" href="<?php echo defined('PW_PATH')?PW_PATH:'{__PW_PATH__}';?>statics/reveal/reveal.css"/>
	<script src="<?php echo defined('PW_PATH')?PW_PATH:'{__PW_PATH__}';?>statics/jquery/jquery-1.12.2.min.js" language="javascript"></script>
    <script src="<?php echo defined('PW_PATH')?PW_PATH:'{__PW_PATH__}';?>statics/reveal/jquery.reveal.js" language="javascript"></script>
	<script src="<?php echo defined('PW_PATH')?PW_PATH:'{__PW_PATH__}';?>statics/core.js" language="javascript"></script>
    <script type="text/javascript" src="<?php echo defined('PW_PATH')?PW_PATH:'{__PW_PATH__}';?>admin/template/js/libs/modernizr.min.js"></script>
    <script language="javascript" type="text/javascript">
		var PW_PATH='<?php echo defined('PW_PATH')?PW_PATH:'{__PW_PATH__}';?>';
	</script>
</head>
<body>
    <div class="ifame-main-wrap">
        <div class="crumb-wrap">
            <div class="crumb-list"><i class="icon-font"></i><a href="<?php echo defined('PW_PATH')?PW_PATH:'{__PW_PATH__}';?><?php echo defined('ADMIN_FILE')?ADMIN_FILE:'{__ADMIN_FILE__}';?>?file=index&action=main">管理首页</a><span class="crumb-step">&gt;</span><span class="crumb-name">安全验证</span></div>
        </div>
        <form action="" method="post" name="mysubmitform" id="mysubmitform" enctype="multipart/form-data">
        <input type="hidden" value="1" name="dosubmit">
        <div class="result-wrap">
                <div class="config-items">
                    <div class="config-title">
                        <h1><i class="icon-font">&#xe018;</i>360安全防护</h1>
                    </div>
                    <div class="result-content">
                        <table width="100%" class="insert-tab">
                            <tbody>
                                <tr class="formtr">
                                    <th class="formth" width="20%"><img src="<?php echo defined('PW_PATH')?PW_PATH:'{__PW_PATH__}';?>statics/images/360.gif"></th>
                                    <td class="formtd">
                                    <label><input type="radio" class="common-radio" name="info[safety_360]" value="1"  <?php echo $PW['safety_360']?'checked="checked"':'';?>/>开启</label>
                                    &nbsp;&nbsp;
                                    <label><input type="radio" class="common-radio" name="info[safety_360]"  value="0"  <?php echo !$PW['safety_360']?'checked="checked"':'';?>/>关闭</label>
                                    <br><font style="color:#CCC; font-size:12px">开启360安全防护将最大程度保护您的网站安全</font>
                                    </td>
                                </tr>
                            </tbody></table>
                    </div>
                </div>
        </div>
        <div class="result-wrap">
                <div class="config-items">
                    <div class="config-title">
                        <h1><i class="icon-font">&#xe018;</i>后台登陆防护</h1>
                    </div>
                    <div class="result-content">
                        <table width="100%" class="insert-tab">
                            <tbody>
								<!--
                                <tr class="formtr" style="display:none">
                                    <th class="formth" width="20%">自定义后台文件名：</th>
                                    <td class="formtd"><input type="text" value="<?php echo isset($PW['admin_file'])?$PW['admin_file']:'';?>" size="24" name="info[admin_file]" class="common-text"></td>
                                </tr>
								-->
                                <tr class="formtr">
                                    <th class="formth" width="20%">开启后台登陆日志：</th>
                                    <td class="formtd">
                                    <label><input type="radio" class="common-radio" name="info[admin_log]" value="1"  <?php echo $PW['admin_log']?'checked="checked"':'';?>/>开启</label>
                                    &nbsp;&nbsp;
                                    <label><input type="radio" class="common-radio" name="info[admin_log]"  value="0"  <?php echo !$PW['admin_log']?'checked="checked"':'';?>/>关闭</label>
                                    </td>
                                </tr>
                            </tbody></table>
                    </div>
                </div>
        </div>
        <div class="result-wrap">
                <div class="config-items">
                    <div class="config-title">
                        <h1><i class="icon-font">&#xe018;</i>验证码配置选项</h1>
                    </div>
                    <div class="result-content">
                        <table width="100%" class="insert-tab">
                            <tbody>
                                <tr class="formtr">
                                    <th class="formth" width="20%">验证码宽高设置：</th>
                                    <td class="formtd">
                                    宽 <input type="text" value="<?php echo isset($PW['captcha_width'])?$PW['captcha_width']:'';?>" size="4" name="info[captcha_width]" class="common-text"> Px
                                    &nbsp;&nbsp;
                                    高 <input type="text" value="<?php echo isset($PW['captcha_height'])?$PW['captcha_height']:'';?>" size="4" name="info[captcha_height]" class="common-text"> Px
                                    </td>
                                </tr>
                                <tr class="formtr">
                                    <th class="formth">验证码长度设置：</th>
                                    <td class="formtd">
                                    <input type="text" value="<?php echo isset($PW['captcha_len'])?$PW['captcha_len']:'';?>" size="4" name="info[captcha_len]" class="common-text"> 个字符
                                    </td>
                                </tr>
                                <tr class="formtr">
                                    <th class="formth">验证码效果演示：</th>
                                    <td class="formtd">
                                    <img class="captchaimg" id="captchaimg"  onClick="getCaptcha('captchaimg')" src="<?php echo defined('PW_PATH')?PW_PATH:'{__PW_PATH__}';?>api/captcha/index.php?t=<?php echo mt_rand();?>" /> 
                                    </td>
                                </tr>
                            </tbody></table>
                    </div>
                </div>
        </div>
        <div class="result-wrap">
                <div class="config-items">
                    <div class="config-title">
                        <h1><i class="icon-font">&#xe018;</i>Cookie访问控制</h1>
                    </div>
                    <div class="result-content">
                        <table width="100%" class="insert-tab">
                            <tbody>
                                <tr class="formtr">
                                    <th class="formth" width="20%">Cookie作用域：</th>
                                    <td class="formtd">
                                    <input type="text" value="<?php echo isset($PW['cookie_domain'])?$PW['cookie_domain']:'';?>" size="24" name="info[cookie_domain]" class="common-text">
                                    <br>
                                    <font style="color:#CCC; font-size:12px">如果您的网站开启了二级域名，请将此项设置为 .您的域名.com 。否则请留空</font>
                                    </td>
                                </tr>
                                <tr class="formtr">
                                    <th class="formth">Cookie作用目录：</th>
                                    <td class="formtd">
                                    <input type="text" value="<?php echo isset($PW['cookie_path'])?$PW['cookie_path']:'';?>" size="4" name="info[cookie_path]" class="common-text">
                                    <font style="color:#CCC; font-size:12px">一般为 / 根目录，如无必要请勿更改</font>
                                    </td>
                                </tr>
                                <tr class="formtr">
                                    <th class="formth">Cookie前缀：</th>
                                    <td class="formtd">
                                    <input type="text" value="<?php echo isset($PW['cookie_pre'])?$PW['cookie_pre']:'';?>" size="24" name="info[cookie_pre]" class="common-text">
                                    </td>
                                </tr>
                                <tr class="formtr">
                                    <th class="formth">Cookie生命周期：</th>
                                    <td class="formtd">
                                    <input type="text" value="<?php echo isset($PW['cookie_ttl'])?$PW['cookie_ttl']:'';?>" size="4" name="info[cookie_ttl]" class="common-text"> 秒
                                    <font style="color:#CCC; font-size:12px">一般设置为 0 即可</font>
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
    <div class="statics-footer"> Powered by phpWeChat V<?php echo defined('PHPWECHAT_VERSION')?PHPWECHAT_VERSION:'{__PHPWECHAT_VERSION__}';?><?php echo defined('PHPWECHAT_RELEASE')?PHPWECHAT_RELEASE:'{__PHPWECHAT_RELEASE__}';?> © , Processed in <?php echo microtime()-$PW['time_start'];?> second(s) , <?php echo MySql::$mQuery;?> queries <a href="#">至顶端↑</a></div>
</body>
</html>