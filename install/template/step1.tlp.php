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
</head>
<body>
<div class="header"></div>
<div class="main">
	<div class="sidebar">
		<div class="logo" title="phpWeChat <?php echo PHPWECHAT_VERSION.PHPWECHAT_RELEASE;?> 安装向导"><a href="http://www.phpwechat.com/" target="_blank"></a></div>
		<div class="step">
			<ul class="current">
				<li class="statusdot"></li>
				<li class="name">1、软件使用授权许可协议</li>
			</ul>
			<ul>
				<li class="statusdot"></li>
				<li class="name">2、环境以及文件目录权限检查</li>
			</ul>
			<ul>
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
					<div class="agre_title">phpWeChat 软件使用协议</div>
					<div class="agre_content">
					　　感谢您选择phpWeChat产品。希望我们的努力能为您提供一个高效、快速和强大的网站+微信公共号解决方案。 <br />
					<br />
					　　本《phpWeChat用户使用授权许可协议》(以下简称"phpWeChat")是您(自然人、法人或其他组织)与phpWeChat之间有关复制、下载、安装、使用phpWeChat相关产品以及任何相关材料、补丁更新的法律协议，同时本协议亦适用于任何有关本软件的后期更新和升级。一旦复制、下载、安装或以其他方式使用本软件，即表明您同意接受本协议各项条款的约束。如果您不同意本协议中的条款，请勿复制、下载、安装或以其他方式使用本软件。<br />
					<br /> 
					<strong>phpWeChat 最终用户授权许可协议：</strong><br />
					　　本《phpWeChat用户使用授权许可协议》(以下简称"phpWeChat")是您(自然人、法人或其他组织)与phpWeChat之间有关复制、下载、安装、使用phpWeChat相关产品以及任何相关材料、补丁更新的法律协议，同时本协议亦适用于任何有关本软件的后期更新和升级。一旦复制、下载、安装或以其他方式使用本软件，即表明您同意接受本协议各项条款的约束。如果您不同意本协议中的条款，请勿复制、下载、安装或以其他方式使用本软件。<br />
					<br />
					<strong>约束和限制：</strong><br />
					1、 未获得商业授权之前，不得将本软件用于(包括但不限于)以下用途：<br />
					　　A、 不得将该软件用于企业或公司网站。<br />
					　　B、 不得将该软件用于政府、金融、教育机构、学校、社会团体及其他经营性网站、以营利为目的或实现盈利的网站。<br />
					　　C、 不是将该软件用于带有盈利性质的个人网站。<br />
					　　D、 不得将该软件用于免费性个人网站以外的其它任何网站。<br />
					2、 不得对本软件或与之关联的商业授权进行出租、出售、抵押或发放子许可证。<br />
					3、 不管您的网站是否整体使用phpWeChat，还是部份栏目使用phpWeChat，在您使用了phpWeChat的网站主页上必须保留phpWeChat版权信息及相关链接。<br />
					4、禁止在本软件的整体或任何部分基础上以发展任何派生版本、修改版本或第三方版本用于重新分发。<br />
					5、如果您未能遵守本协议的条款，您的授权将被终止，所被许可的权利将被收回，并承担相应法律责任。<br />
					<br />
					<strong>许可您的权利：</strong><br />
					1、您可以在完全遵守本最终用户授权协议的基础上，将本软件免费应用于个人非商业用途网站，而不必支付软件版权授权费用。<br />
					2、您可以在协议规定的约束和限制范围内根据需要对本软件进行必要的修改和美化，以适应您的网站要求。<br />
					3、您拥有使用本软件构建的网站中收集到的全部会员资料、文章、下载、图片、留言及相关信息的所有权，并独立承担与内容相关的法律义务。<br />
					4、获得商业授权之后，您可以将本软件应用于商业用途，同时依据所购买的授权类型中确定的技术支持服务期　限、服务方式和服务内容，自购买时刻起，在技术支持服务期限内拥有通过指定的方式获得指定范围内的技术支持服务。<br />
					5、服务网站数量：一份phpWeChat使用授权安装为一个服务网站，同一域名下安装多套phpWeChat 则视为多个服务网站。<br />
					<br />
					<strong>有限担保和免责声明：</strong><br />
					　　本软件及所附带的文件是作为不提供任何明确的或隐含的赔偿或担保的形式提供的。<br />
					　　用户出于自愿而使用本软件，您必须了解使用本软件的风险，在尚未购买产品技术服务之前，我们不承诺提供任何形式的技术支持、使用担保，也不承担任何因使用本软件而产生问题的相关责任。<br />
					　　phpWeChat不对使用本软件构建的网站的任何信息内容以及导致的任何版权纠纷和法律争议及后果承担责任。<br />
					　　有关本软件最终用户授权协议、商业授权与技术服务的详细内容，均由phpWeChat( <a href="http://www.phpwechat.com/" target="_blank">http://www.phpwechat.com/</a> )和官方论坛( <a href="http://bbs.phpwechat.com/" target="_blank">http://bbs.phpwechat.com/</a> )提供唯一的解释和官方价目表。phpWeChat拥有在不提前通知的情况下，修改授权协议和价目表的权力，修改后的协议或价目表对自改变之日起的新授权用户生效。<br />
					<br /> 
					<strong>权利和所有权的保留：</strong><br />
					　　phpWeChat保留所有未在本协议中明确授予您的权利。<br />
					　　您一旦开始安装本软件，即被视为完全理解并接受本协议的各项条款，在享有上述条款授予的权力的同时，受到相关的约束和限制。协议许可范围以外的行为，将直接违反本授权协议并构成侵权，我们有权随时终止授权，责令停止损害，并保留追究相关责任的权力。<br />
					　　请购买前务必认清并理解不同技术服务方式及服务范围。技术支持只面向官方标准安装版本，自行修改或使用非原始phpWeChat程序代码后可能产生的问题均不在服务范围之内。
					</div>
					<form id="install" action="index.php?" method="post">
					<input type="hidden" name="step" value="2">
					<table width="100%"><tr>
					<td width="80" height="80">&nbsp;</td>
					<td  align="center"><input type="submit" value="接 受" class="btn" /></td>
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