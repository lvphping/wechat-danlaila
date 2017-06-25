<?php
	!defined('PW_INSTALL') && exit('Access Denied!');
	use phpWeChat\MySql;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<link rel="stylesheet" type="text/css" href="template/images/style.css" />
<title>phpWeChat <?php echo PHPWECHAT_VERSION.PHPWECHAT_RELEASE;?> 安装向导</title>
<script language="JavaScript" src="template/images/jquery.min.js"></script>
<script language="javascript" type="text/javascript">
var step = 0;
var interval = 200; // 间隔时间
function showmessage(message) 
{
	step++;
	setTimeout(function()
	{
		document.getElementById('notice').innerHTML += message;
		document.getElementById('notice').scrollTop = 100000000;
	},step * interval);	
}
</script>
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
			<ul class="done">
				<li class="statusdot"></li>
				<li class="name">3、数据库连接参数设置</li>
			</ul>
			<ul class="current">
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
					<form id="install" action="index.php?" method="post">
					<input type="hidden" name="step" value="5">
					<?php
					echo '<div id="notice"></div>';
					$result = true;
					foreach($sqls as $i=>$sql)
					{
						if($config['db_pre']!='pw_')
						{
							$sql=str_replace('pw_',$config['db_pre'],$sql);
						}
						
						if(substr($sql, 0, 12) == 'CREATE TABLE')
						{ 
							$tname = preg_replace("/CREATE TABLE `([a-z0-9_]+)` .*/is","\\1",$sql); 
							if(MySql::query($sql))
							{
								jsmessage('<ol>正在创建数据表：'.$tname.' &nbsp; … &nbsp;&nbsp;&nbsp; <img src="template/images/ok.png" /></ol>');
							} 
							else 
							{
								$result = false;
								jsmessage('<ol><font color="#FF0000">正在创建数据表：'.$tname.' &nbsp; … &nbsp;&nbsp;&nbsp; </font><img src="template/images/not.png" /></ol>');
							}
						}
						else 
						{ 
							MySql::query($sql); 
						}						
					}

					if($admin_founders=MySql::insert($config['db_pre'].'admin',$admin,true))
					{
						jsmessage('<ol>网站创始人信息创建成功… &nbsp;&nbsp;&nbsp; <img src="template/images/ok.png" /></ol>');
					}
					else
					{
						$result=false;
					}
					
					if($result)
					{
						jsmessage('<ol><font color="#000000">数据库安装成功，请继续下一步安装。</font></ol>');
					} 
					else 
					{
						jsmessage('<ol><font color="#FF0000">数据库没有正确安装或是安装过程中出现异常，请检查连接参数设置是否正确。</font></ol>');
					}
					?>
					<div id="status"><table width="100%"><tr><td height="80" align="center"><img src="template/images/loading.gif" align="absmiddle" /> 正在执行数据库安装...</td></tr></table></div>
					<script type="text/javascript">
					var table = '<table width="100%"><tr>';
					table+='<td width="80" height="80">&nbsp;</td>';
					table+='<td align="right"><input type="button" onClick="javascript:history.back(-1);" value="上一步" class="btn" /></td>';
					table+='<td align="left"><input type="submit" value="下一步" class="btn" /></td>';
					table+='<td width="80">&nbsp;</td>';
					table+='</tr></table>';
					setInterval(function()
					{
						document.getElementById('status').innerHTML = table;
					},step * interval);
					</script>
					</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="foot">&copy; 2009-<?php echo date('Y');?> phpWeChat <?php echo PHPWECHAT_VERSION.PHPWECHAT_RELEASE;?> 京ICP备16033921号-2</div>
</body>
</html>