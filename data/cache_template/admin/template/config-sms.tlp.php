<?php !defined('IN_MANAGE') && exit('Access Denied!');use phpWeChat\Form;use phpWeChat\Module;use phpWeChat\MySql;?>
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
            <div class="crumb-list"><i class="icon-font"></i><a href="<?php echo defined('PW_PATH')?PW_PATH:'{__PW_PATH__}';?><?php echo defined('ADMIN_FILE')?ADMIN_FILE:'{__ADMIN_FILE__}';?>?file=index&action=main">管理首页</a><span class="crumb-step">&gt;</span><span class="crumb-name">短信配置</span></div>
        </div>
        <form action="" method="post" name="mysubmitform" id="mysubmitform" enctype="multipart/form-data">
        <input type="hidden" value="1" name="dosubmit">
        <div class="result-wrap">
                <div class="config-items">
                    <div class="config-title">
                        <h1><i class="icon-font">&#xe018;</i>短信配置</h1>
                    </div>
                    <div class="result-content">
                        <table width="100%" class="insert-tab">
                            <tbody>
                            	<tr class="formtr">
                                    <th class="formth" width="20%"><i class="require-red">*</i>短信账号注册地址：</th>
                                    <td class="formtd">
                                   <a href="http://www.ihuyi.com/product.php?f=DeDechat.com#zhuce" target="_blank">http://www.ihuyi.com/product.php#zhuce [互亿无线]</a>
                                    </td>
                                </tr>
                                <tr class="formtr">
                                    <th class="formth"><i class="require-red">*</i>短信发送接口：</th>
                                    <td class="formtd">
                                    <input type="text" class="common-text" name="info[sms_target]" value="<?php echo isset($PW['sms_target'])?$PW['sms_target']:'';?>" size="60" />
                                    <font style="color:#CCC; font-size:12px">一般无需更改</font>
                                    </td>
                                </tr>
                                <tr class="formtr">
                                    <th class="formth"><i class="require-red">*</i>短信发送账号：</th>
                                    <td class="formtd">
                                    <input type="text" class="common-text" name="info[sms_account]" value="<?php echo isset($PW['sms_account'])?$PW['sms_account']:'';?>" size="16" />
                                    
                                    </td>
                                </tr>
                                <tr class="formtr">
                                    <th class="formth"><i class="require-red">*</i>短信发送密码：</th>
                                    <td class="formtd">
                                    <input type="password" class="common-text" name="info[sms_pwd]" value="<?php echo isset($PW['sms_pwd'])?$PW['sms_pwd']:'';?>" size="16" />
                                    </td>
                                </tr>
                                <tr class="formtr">
                                    <th class="formth"><i class="require-red">*</i>短信发送模板：</th>
                                    <td class="formtd">
                                   <textarea class="common-textarea" cols="75" rows="5" name="info[sms_template]"><?php echo isset($PW['sms_template'])?$PW['sms_template']:'';?></textarea></td>
                                    </td>
                                </tr>
                            </tbody></table>
                    </div>
                </div>
        </div>
        <div class="result-wrap">
                <div class="config-items">
                    <div class="config-title">
                        <h1><i class="icon-font">&#xe018;</i>短信测试</h1>
                    </div>
                    <div class="result-content">
                        <table width="100%" class="insert-tab">
                            <tbody>
                             	 <tr class="formtr">
                                    <th class="formth" width="20%">测试短信手机：</th>
                                    <td class="formtd">
                                    <input type="text" class="common-text" name="test_mobile" size="16" />
                                     <br><font style="color:#CCC; font-size:12px">用于测试您的配置信息是否正确，请填写真实手机号</font>
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
    <div class="statics-footer"> Powered by phpWeChat V<?php echo defined('PHPWECHAT_VERSION')?PHPWECHAT_VERSION:'{__PHPWECHAT_VERSION__}';?><?php echo defined('PHPWECHAT_RELEASE')?PHPWECHAT_RELEASE:'{__PHPWECHAT_RELEASE__}';?> © , Processed in <?php echo microtime()-$PW['time_start'];?> second(s) , <?php echo MySql::$mQuery;?> queries <a href="#">至顶端↑</a></div>
</body>
</html>