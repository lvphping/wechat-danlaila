<?php
	include_once substr(dirname(__FILE__),0,-10).'include/common.inc.php';

	include_once PW_ROOT.'include/qrcode.php';
	QRcode::png(urldecode(htmlspecialchars_decode($_GET['data'])));
	exit();
?>