<?php use phpWeChat\MySql;?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="<?php echo isset($PW['wechat_weshop_site_keywords'])?$PW['wechat_weshop_site_keywords']:'';?>">
<meta name="description" content="<?php echo isset($PW['wechat_weshop_site_description'])?$PW['wechat_weshop_site_description']:'';?>">
<link href='http://fonts.useso.com/css?family=Raleway' rel='stylesheet' type='text/css'>
<title><?php echo isset($PW['wechat_weshop_site_title'])?$PW['wechat_weshop_site_title']:'';?></title>
<style type="text/css">
	body{font-size:12px; padding:0px; margin:0px; font-family:"Raleway",'Microsoft YaHei'; background:#FFFFFF}
	a{color:#44b549; text-decoration:none}
	a:hover{text-decoration:underline}
	.header{width:100%;  -moz-box-shadow:0 0 2px  2px #ccc;-webkit-box-shadow:0 0 2px 2px #ccc;box-shadow:0 0 2px 2px #ccc; position:fixed; top:0px; height:80px; background:#F9F9F9}
	.header .logo{width:1032px; margin:0px auto; clear:both; line-height:80px; font-size:30px; font-family:"Raleway",'Microsoft YaHei'; position:relative}
	.header .logo font{float:right; font-weight:normal; font-size:26px}
	.header .logo span{position:absolute; font-size:12px; color:#FFFFFF; height:22px; padding:0px 4px; margin:0px; line-height:22px; top:20px; left:180px; background:darkseagreen;border-radius:2px; font-weight:normal}
	.main{background:url(<?php echo defined('TLP')?TLP:'{__TLP__}';?>images/bg.png) center top no-repeat; width:1032px; height:600px; margin:8px auto; clear:both; margin-top:158px; padding:36px 0px}
	.main .erweima{float:right; padding-top:168px; width:260px;}
	.main .erweima img{width:250px; height:250px;}
	.main .erweima p{text-align:center; padding:20px 0px; margin:0px; color:indianred}
	.footer{text-align:center; color:#666; width:1032px; margin:28px auto; padding-top:16px; line-height:2em}
	.footer strong{font-weight:normal}
</style>
</head>
<body>
<div class="header"><h3 class="logo"><font>☏ <?php echo isset($PW['contact_telephone'])?$PW['contact_telephone']:'';?></font>phpWeChat<span>演示</span></h3></div>
<div class="main">
	<div class="erweima">
        <div class="img"><?php if($PW['wechat_qr']) { ?><img src="<?php echo isset($PW['wechat_qr'])?$PW['wechat_qr']:'';?>" alt="<?php echo isset($PW['wechat_weshop_site_name'])?$PW['wechat_weshop_site_name']:'';?>" /><?php }?></div>
        <p>手机扫一扫，关注有好礼</p>
    </div>
</div>

<div class="footer">
Powered by <strong><a href="http://www.phpwechat.com/" target="_blank">phpWeChat微商城（三级分销）系统</a></strong> V<?php echo defined('PHPWECHAT_VERSION')?PHPWECHAT_VERSION:'{__PHPWECHAT_VERSION__}';?><?php echo defined('PHPWECHAT_RELEASE')?PHPWECHAT_RELEASE:'{__PHPWECHAT_RELEASE__}';?> © , Processed in <?php echo microtime()-$PW['time_start'];?> second(s) , <?php echo MySql::$mQuery;?> queries 
<br />
<a href="http://www.miitbeian.gov.cn/" target="_blank" rel="nofollow"><?php echo isset($PW['site_icpno'])?$PW['site_icpno']:'';?></a>
</div>
</body>
</html>
