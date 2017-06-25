<?php !defined('IN_MANAGE') && exit('Access Denied!');?>
<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>phpWeChat后台管理</title>
    <link href="<?php echo defined('PW_PATH')?PW_PATH:'{__PW_PATH__}';?>admin/template/css/login.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="<?php echo defined('PW_PATH')?PW_PATH:'{__PW_PATH__}';?>statics/core.css"/>
    <link rel="stylesheet" type="text/css" href="<?php echo defined('PW_PATH')?PW_PATH:'{__PW_PATH__}';?>statics/reveal/reveal.css"/>
	<script src="<?php echo defined('PW_PATH')?PW_PATH:'{__PW_PATH__}';?>statics/jquery/jquery-1.12.2.min.js" language="javascript"></script>
	<script src="<?php echo defined('PW_PATH')?PW_PATH:'{__PW_PATH__}';?>statics/core.js" language="javascript"></script>
    <script src="<?php echo defined('PW_PATH')?PW_PATH:'{__PW_PATH__}';?>statics/reveal/jquery.reveal.js" language="javascript"></script>
    <script language="javascript" type="text/javascript">
	var PW_PATH='<?php echo defined('PW_PATH')?PW_PATH:'{__PW_PATH__}';?>';
	$(document).ready(function(){
		$('#adminlogin').submit(function(){
			if($.trim($('#username').val())==''){
				$('#username').addClass('err_style');
				$('#username').focus();
				return false;	
			}
			else{	
				$('#username').addClass('success_style');
			}
			
			if($.trim($('#password').val())==''){
				$('#password').addClass('err_style');
				$('#password').focus();
				return false;	
			}
			else{	
				$('#password').addClass('success_style');
			}
			
			if($.trim($('#captchacode').val()).length!=<?php echo defined('CAPTCHA_LEN')?CAPTCHA_LEN:'{__CAPTCHA_LEN__}';?>){
				$('#captchacode').addClass('err_style');
				$('#captchacode').focus();
				return false;	
			}
			else{	
				$('#captchacode').addClass('success_style');
			}
			});
		});
	</script>
</head>
<body>
<div class="admin_login_wrap">
    <h1>后台管理</h1>
    <div class="adming_login_border">
        <div class="admin_input">
            <form action="" method="post" name="adminlogin" id="adminlogin">
            	<input type="hidden" name="dosubmit" value="1" />
                <ul class="admin_items">
                    <li>
                        <label for="user">用户名：</label>
                        <input type="text" name="username" autocomplete="off" id="username" size="35" class="admin_input_style" />
                    </li>
                    <li>
                        <label for="pwd">密码：</label>
                        <input type="password" name="password" autocomplete="off" id="password" size="35" class="admin_input_style" />
                    </li>
					<li>
                        <label for="pwd">验证码：</label>
						<table width="100%"><tr>
						<td width="35%">
                        <input type="text" name="captchacode" autocomplete="off" id="captchacode" size="8" class="admin_input_style" />
						</td><td width="35%" style="position:relative">
						<img class="captchaimg" id="captchaimg"  onClick="getCaptcha('captchaimg')" src="<?php echo defined('PW_PATH')?PW_PATH:'{__PW_PATH__}';?>api/captcha/index.php?t=<?php echo mt_rand();?>" /> 
						</td><td width="30%"><a href="javascript:void(0);" onClick="getCaptcha('captchaimg')">换一张</a></td></tr></table>
                    </li>
                    <li>
                        <input type="submit" tabindex="3" value="提交" class="btn btn-primary" />
                    </li>
                </ul>
            </form>
        </div>
    </div>
    <p class="admin_copyright"><a tabindex="5" href="<?php echo defined('PW_PATH')?PW_PATH:'{__PW_PATH__}';?>" target="_blank">返回首页</a> &copy; 2009 - <?php echo date('Y');?> Powered by <a href="http://www.phpwechat.com/" target="_blank">phpWeChat</a></p>
</div>
</body>
</html>