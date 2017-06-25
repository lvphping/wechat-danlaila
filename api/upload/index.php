<?php
// +----------------------------------------------------------------------
// | phpWeChat 文件上传文件 Last modified 2016-03-31 13:49
// +----------------------------------------------------------------------
// | Copyright (c) 2009-2016 phpWeChat http://www.phpwechat.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 骑马的少年 <phpwechat@126.com> <http://www.phpwechat.com>
// +----------------------------------------------------------------------
use phpWeChat\Upload;

require substr(dirname(__FILE__),0,-11).'/include/common.inc.php';

$action=@return_edefualt(str_callback_w($_GET['action']),'imageupload');

switch($action)
{
	case 'imageupload':
		$originalname=preg_replace('/[^a-z0-9_]/i','',$originalname);

		$image=Upload::imageUpload($upload_file,$originalname);

		if(is_image($image))
		{
			echo '<script src="'.PW_PATH.'statics/jquery/jquery-1.12.2.min.js" language="javascript"></script>';
			exit('<script language="javascript">$("#'.$upload_hidden_val.'",window.parent.document).val("'.$image.'");$("#'.$img_demo.'",window.parent.document).attr("src","'.$image.'?t='.mt_Rand().'");$("#'.$upload_loading.'",window.parent.document).html("<font style=\"color:forestgreen\">上传成功</font>");</script>'); 
		}
		else
		{
			exit('<script language="javascript">$("#'.$upload_loading.'",window.parent.document).html("<font style=\"color:#ff3300\">上传失败</font>");</script>');
		}
		break;
	case 'videoupload':
		$video=Upload::videoUpload($video_file);

		echo '<script src="'.PW_PATH.'statics/jquery/jquery-1.12.2.min.js" language="javascript"></script>';
		if(is_video($video))
		{
			exit('<script language="javascript">alert("上传成功!");$("#'.$video_value.'",window.parent.document).val("'.$video.'");$("#'.$video_upload_btn.'",window.parent.document).removeClass("video_upload_btn_loading");$("#'.$video_upload_btn.'",window.parent.document).addClass("video_upload_btn");$("#'.$video_upload_btn.'",window.parent.document).text("上传一个视频");</script>');
		}
		else
		{
			exit('<script language="javascript">alert("'.$video.'上传失败!");$("#'.$video_upload_btn.'",window.parent.document).removeClass("video_upload_btn_loading");$("#'.$video_upload_btn.'",window.parent.document).addClass("video_upload_btn");$("#'.$video_upload_btn.'",window.parent.document).text("上传一个视频");</script>');
		}
		break;
	case 'attachmentupload':
		$attachment=Upload::attachmentUpload($attachment_file);

		echo '<script src="'.PW_PATH.'statics/jquery/jquery-1.12.2.min.js" language="javascript"></script>';
		if(is_attachment($attachment))
		{
			exit('<script language="javascript">alert("上传成功!");$("#'.$attachment_value.'",window.parent.document).val("'.$attachment.'");$("#'.$attachment_upload_btn.'",window.parent.document).removeClass("attachment_upload_btn_loading");$("#'.$attachment_upload_btn.'",window.parent.document).addClass("attachment_upload_btn");$("#'.$attachment_upload_btn.'",window.parent.document).text("上传一个附件");</script>');
		}
		else
		{
			exit('<script language="javascript">alert("'.$attachment.'上传失败!");$("#'.$attachment_upload_btn.'",window.parent.document).removeClass("attachment_upload_btn_loading");$("#'.$attachment_upload_btn.'",window.parent.document).addClass("attachment_upload_btn");$("#'.$attachment_upload_btn.'",window.parent.document).text("上传一个附件");</script>');
		}
		break;
}
?>