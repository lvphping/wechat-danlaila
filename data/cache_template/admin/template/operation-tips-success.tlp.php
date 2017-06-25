<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script src="<?php echo isset($PW['d_path'])?$PW['d_path']:'';?>statics/jquery/jquery-1.12.2.min.js" language="javascript"></script>
<script src="<?php echo isset($PW['d_path'])?$PW['d_path']:'';?>statics/core.js" language="javascript"></script>
<title>操作<?php echo $result=='success'?'成功':'失败';?>提示</title>
<style type="text/css">
	body{margin:0px; padding:0px}
	.operation_tips{width:99%; border:#deeffa 2px solid; background:#f2f9fd; border-left:0px; border-right:0px; text-align:center; margin:0px auto; clear:both; margin-top:15%}
	.operation_tips .tips{font-size:14px; font-weight:bold; color:#009900; padding:30px 0px; padding-bottom:0px;}
	.operation_tips .tips .error{color:#C00}
	.operation_tips .url{padding:30px 0px; padding-top:10px}
	.operation_tips .url a{font-size:12px; color:#999; text-decoration:underline;}
</style>
</head>

<body>
	<div class="operation_tips">
    	<div class="tips"><?php echo $result=='success'?'':'<font class="error">';?><?php echo isset($msg)?$msg:'';?><?php echo $result=='success'?'':'</font>';?></div>
        <?php if(!$flag) { ?><div class="url"><a href="<?php echo isset($redirecturl)?$redirecturl:'';?>">如果您的浏览器没有跳转，请点击这里</a></div><?php }else{ ?><div class="url"><a href="https://www.so.com/s?ie=utf-8&shb=1&src=home_so.com&q=<?php echo isset($msg)?$msg:'';?>" target="_blank">您可以通过该链接，查看相关信息</a></div><?php }?>
    </div>
</body>
</html>
