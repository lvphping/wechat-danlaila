<?php
/*将文字写进图片*/
function textinimg($srcimg,$color,$top,$left,$text){
	$color = explode(',',$color);
	header("content-type:text/html;charset=utf-8");
	header("Content-type: image/jpeg");    
	$im = @imagecreatefromjpeg($srcimg);    
	$white = imagecolorallocate($im,$color[0],$color[1],$color[2]);
	$font = 'c:/windows/fonts/simsun.ttc';  
	$srcw=imagesx($im);
	imagettftext($im, 40, 0, $left ,$top,  $white, $font, $text);
	imagegif($im);
	imagedestroy($im);
}

echo textinimg('temp-2016091311135704.jpg', '0, 0, 0', '550', '100', '何丽是猪');

?>