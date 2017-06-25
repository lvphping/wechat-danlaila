<?php
	!defined('PW_INSTALL') && exit('Access Denied!');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<link rel="stylesheet" type="text/css" href="template/images/style.css" />
<title>phpWeChat <?php echo PHPWECHAT_VERSION.PHPWECHAT_RELEASE;?> 安装向导</title>
<script language="JavaScript" src="template/images/jquery.min.js"></script>
</head>
<body>
<div class="header"></div>
<div class="main">
	<div class="sidebar">
		<div class="logo" title="phpWeChat <?php echo PHPWECHAT_VERSION.PHPWECHAT_RELEASE;?> 安装向导"><a href="http://www.phpwechat.com/" target="_blank"></a></div>
		<div class="step">
			<ul class="done">
				<li class="statusdot"></li>
				<li class="name">1、软件使用授权许可协议</li>
			</ul>
			<ul class="done">
				<li class="statusdot"></li>
				<li class="name">2、环境以及文件目录权限检查</li>
			</ul>
			<ul class="current">
				<li class="statusdot"></li>
				<li class="name">3、数据库连接参数设置</li>
			</ul>
			<ul>
				<li class="statusdot"></li>
				<li class="name">4、执行数据库安装</li>
			</ul>
			<ul>
				<li class="statusdot"></li>
				<li class="name">5、完成安装</li>
			</ul>
		</div>
	</div>
	<div class="main">
		<div class="version">程序版本：<?php echo PHPWECHAT_VERSION.PHPWECHAT_RELEASE;?></div>
		<div class="bg_center">
			<div class="bg_left">
				<div class="bg_right">
					<div class="content">
					<br />
					<form id="install" action="index.php?" method="post">
					<input type="hidden" name="step" value="4">
					<strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;数据库信息</strong>
					<table width="100%" cellpadding="0" cellspacing="0" border="0">
						<tr>
							<td width="150" align="right" height="35">数据库服务器：</td>
							<td><input type="text" name="config[db_host]" id="db_host" class="input" size="25" value="<?php echo DB_HOST;?>" /></td>
							<td>一般为127.0.0.1</td>
						</tr>
						<tr>
							<td align="right" height="35">数据库名称：</td>
							<td colspan="2"><input type="text" name="config[db_name]" id="db_name" class="input" maxlength="80" size="25" value="<?php echo DB_NAME;?>" /></td>
						</tr>
						<tr>
							<td align="right" height="35">数据库用户名：</td>
							<td colspan="2"><input type="text" name="config[db_user]" id="db_user" class="input" maxlength="80" size="25" value="<?php echo DB_USER;?>" /></td>
						</tr>
						<tr>
							<td align="right" height="35">数据库密码：</td>
							<td colspan="2"><input type="text" name="config[db_pwd]" id="db_pwd" class="input" maxlength="80" size="25" value="<?php echo DB_PWD;?>" /></td>
						</tr>
						<tr>
							<td align="right" height="35">数据表前缀：</td>
							<td><input type="text" name="config[db_pre]" id="db_pre" class="input" maxlength="20" size="25" value="<?php echo DB_PRE;?>" /></td>
							<td>建议使用默认，同一数据库安装多个phpWeChat时需修改</td>
						</tr>
					</table>
					<strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;创始人信息</strong>
					<table width="100%" cellpadding="0" cellspacing="0" border="0">
						<tr>
							<td width="150" align="right" height="35">管理员帐号：</td>
							<td align="left" colspan="2"><input type="text" name="username" id="username" class="input" maxlength="20" size="25" value="admin" /></td>
						</tr>
						<tr>
							<td align="right" height="35">密码：</td>
							<td colspan="2"><input type="text" name="password" id="password" class="input" maxlength="20" size="25" /></td>
						</tr>
						<tr>
							<td align="right" height="35">重复密码：</td>
							<td colspan="2"><input type="text" name="pwdconfirm" id="pwdconfirm" class="input" maxlength="20" size="25"/></td>
						</tr>
					</table>
					<table width="100%"><tr>
					<td width="80" height="80">&nbsp;</td>
					<td align="right"><input type="button" onClick="javascript:history.back(-1);" value="上一步" class="btn" /></td>
					<td align="left"><input type="button" onClick="return checkform();" value="下一步" class="btn" /></td>
					<td width="80">&nbsp;</td>
					</tr></table>
					</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="foot">&copy; 2009-<?php echo date('Y');?> phpWeChat <?php echo PHPWECHAT_VERSION.PHPWECHAT_RELEASE;?> 京ICP备16033921号-2</div>
</body>
</html>
<script language="JavaScript" type="text/javascript">
<!--
function checkform() 
{
	if($('#username').val().length<2 || $('#username').val().length>30)
	{
		alert('管理员帐号不能少于2个字符或者大于20个字符');
		$('#username').focus();
		return false;
	}
	if($('#password').val().length<6 || $('#username').val().length>30)
	{
		alert('管理员密码不能少于6个字符或者大于20个字符');
		$('#password').focus();
		return false;
	}
	if($('#password').val()!=$('#pwdconfirm').val())
	{
		alert('两次输入密码不一致！');
		$('#pwdconfirm').val()='';
		$('#pwdconfirm').focus();
		return false;
	}

	var url = '?step=checkdb&dbhost='+$('#db_host').val()+'&dbuser='+$('#db_user').val()+'&dbpwd='+$('#db_pwd').val()+'&dbname='+$('#db_name').val()+'&tablepre='+$('#db_pre').val()+'&sid='+Math.random()*5;
    $.get(url, function(data){		
		if(data != 1)
		{
			alert(data);
			return false;
		}
		else if(data == 1 || (data == 0 && confirm(data)))
		{
			$('#install').submit();
		}
	});
    return false;
}
//-->
</script>