<?php
// +----------------------------------------------------------------------
// | phpWeChat 文件上传操作类 Last modified 2016-03-30 11:18
// +----------------------------------------------------------------------
// | Copyright (c) 2009-2016 phpWeChat http://www.phpwechat.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 骑马的少年 <phpwechat@126.com> <http://www.phpwechat.com>
// +----------------------------------------------------------------------
namespace phpWeChat;

class Upload
{
	static public function pemUpload($key,$path='')
	{
		global $PW;

		if(isset($_FILES[$key]))
		{
			$_file_name=$_FILES[$key]['name'];
			$_file_tmp_name=str_replace("\\\\", "\\", $_FILES[$key]['tmp_name']);
			$_file_ext=strtolower(get_fileext($_file_name));
			$_file_size=preg_replace('#[^0-9]#','',$_FILES[$key]['size']);

			if(!self::isUploadedFile($_file_tmp_name))
			{
				return '';
			}

			if($_file_size > ($PW['upload_size_limit']*1024*1024))                           
			{
				exit('<script language="javascript" type="text/javascript">alert("上传文件大小超过设定值");</script>');
			}

			if (!is_pem($_file_name))
			{
				exit('<script language="javascript" type="text/javascript">alert("不是有效的安全证书文件!");</script>');
			}

			$_file_savename=$_FILES[$key]['name'];

			$_file_savepath=$path;

			make_dir($_file_savepath);

			if(@move_uploaded_file($_file_tmp_name, PW_ROOT.$_file_savepath.$_file_savename))
			{
				return $_file_savepath.$_file_savename;
			}
			else
			{
				return '';
			}
		}
		return '';
	}

	static public function attachmentUpload($key,$originalname='')
	{
		global $PW;

		if(isset($_FILES[$key]))
		{
			$_file_name=$_FILES[$key]['name'];
			$_file_tmp_name=str_replace("\\\\", "\\", $_FILES[$key]['tmp_name']);
			$_file_ext=strtolower(get_fileext($_file_name));
			$_file_size=preg_replace('#[^0-9]#','',$_FILES[$key]['size']);

			if(!self::isUploadedFile($_file_tmp_name))
			{
				return '';
			}

			if($_file_size > ($PW['upload_size_limit']*1024*1024))                           
			{
				exit('<script language="javascript" type="text/javascript">alert("上传文件大小超过设定值");</script>');
			}

			if (!is_attachment($_file_name))
			{
				exit('<script language="javascript" type="text/javascript">alert("抱歉，目前只支持Rar、Zip、Tar.gz格式的附件文件!");</script>');
			}

			$_file_savename=($originalname?$originalname:CLIENT_TIME.mt_rand(1000,9999));

			$_file_savepath=$originalname?'':date('Y-m-d').'/';

			make_dir('upload/attachment/'.$_file_savepath);

			if(@move_uploaded_file($_file_tmp_name, PW_ROOT.'upload/attachment/'.$_file_savepath.$_file_savename.'.'.$_file_ext))
			{
				return 'upload/attachment/'.$_file_savepath.$_file_savename.'.'.$_file_ext;
			}
			else
			{
				return '';
			}
		}
		return '';
	}

	static public function videoUpload($key,$originalname='')
	{
		global $PW;

		if(isset($_FILES[$key]))
		{
			$_file_name=$_FILES[$key]['name'];
			$_file_tmp_name=str_replace("\\\\", "\\", $_FILES[$key]['tmp_name']);
			$_file_ext=strtolower(get_fileext($_file_name));
			$_file_size=preg_replace('#[^0-9]#','',$_FILES[$key]['size']);

			if(!self::isUploadedFile($_file_tmp_name))
			{
				return '';
			}

			if($_file_size > ($PW['upload_size_limit']*1024*1024))                           
			{
				exit('<script language="javascript" type="text/javascript">alert("上传文件大小超过设定值");</script>');
			}

			if (!is_video($_file_name))
			{
				exit('<script language="javascript" type="text/javascript">alert("抱歉，目前只支持Flv、Swf、Mp4格式的视频文件!");</script>');
			}

			$_file_savename=($originalname?$originalname:CLIENT_TIME.mt_rand(1000,9999));

			$_file_savepath=$originalname?'':date('Y-m-d').'/';

			make_dir('upload/video/'.$_file_savepath);

			if(@move_uploaded_file($_file_tmp_name, PW_ROOT.'upload/video/'.$_file_savepath.$_file_savename.'.'.$_file_ext))
			{
				return 'upload/video/'.$_file_savepath.$_file_savename.'.'.$_file_ext;
			}
			else
			{
				return '';
			}
		}
		return '';
	}

	static public function voiceUpload($key,$originalname='')
	{
		global $PW;

		if(isset($_FILES[$key]))
		{
			$_file_name=$_FILES[$key]['name'];
			$_file_tmp_name=str_replace("\\\\", "\\", $_FILES[$key]['tmp_name']);
			$_file_ext=strtolower(get_fileext($_file_name));
			$_file_size=preg_replace('#[^0-9]#','',$_FILES[$key]['size']);

			if(!self::isUploadedFile($_file_tmp_name))
			{
				return '';
			}

			if($_file_size > ($PW['upload_size_limit']*1024*1024))                           
			{
				exit('<script language="javascript" type="text/javascript">alert("上传文件大小超过设定值");</script>');
			}

			if (!is_voice($_file_name))
			{
				exit('<script language="javascript" type="text/javascript">alert("抱歉，目前只支持mp3、wma、wav、amr格式的视频文件!");</script>');
			}

			$_file_savename=($originalname?$originalname:CLIENT_TIME.mt_rand(1000,9999));

			$_file_savepath=$originalname?'':date('Y-m-d').'/';

			make_dir('upload/voice/'.$_file_savepath);

			if(@move_uploaded_file($_file_tmp_name, PW_ROOT.'upload/voice/'.$_file_savepath.$_file_savename.'.'.$_file_ext))
			{
				return 'upload/voice/'.$_file_savepath.$_file_savename.'.'.$_file_ext;
			}
			else
			{
				return '';
			}
		}
		return '';
	}

	static public function imageUpload($key,$originalname='',$isthumb=1)
	{
		global $PW;

		if(isset($_FILES[$key]))
		{
			$_file_name=$_FILES[$key]['name'];
			$_file_tmp_name=str_replace("\\\\", "\\", $_FILES[$key]['tmp_name']);
			$_file_ext=strtolower(get_fileext($_file_name));
			$_file_size=preg_replace('#[^0-9]#','',$_FILES[$key]['size']);

			if(!self::isUploadedFile($_file_tmp_name))
			{
				return '';
			}

			if($_file_size > ($PW['upload_size_limit']*1024*1024))                           
			{
				exit('<script language="javascript" type="text/javascript">alert("上传文件大小超过设定值");</script>');
			}

			if (!is_array(@getimagesize($_file_tmp_name)))
			{
				exit('<script language="javascript" type="text/javascript">alert("上传的不是一张图片");</script>');
			}

			$_file_savename=($originalname?$originalname:CLIENT_TIME.mt_rand(1000,9999));

			$_file_savepath=$originalname?'':date('Y-m-d').'/';

			make_dir('upload/images/'.$_file_savepath);

			if(@move_uploaded_file($_file_tmp_name, PW_ROOT.'upload/images/'.$_file_savepath.$_file_savename.'.'.$_file_ext))
			{
				$_image_info=imageinfo(PW_ROOT.'upload/images/'.$_file_savepath.$_file_savename.'.'.$_file_ext);
				
				if($isthumb)
				{
					if($_image_info['width']>$PW['upload_thumb1_width'])
					{
						self::createImageThumb(PW_ROOT.'upload/images/'.$_file_savepath.$_file_savename.'.'.$_file_ext,$PW['upload_thumb1_width']);
						self::createImageThumb(PW_ROOT.'upload/images/'.$_file_savepath.$_file_savename.'.'.$_file_ext,$PW['upload_thumb2_width']);
					}

					if($_image_info['width']>$PW['upload_thumb2_width'])
					{
						self::createImageThumb(PW_ROOT.'upload/images/'.$_file_savepath.$_file_savename.'.'.$_file_ext,$PW['upload_thumb2_width']);
					}
				}
				setwatermark(PW_ROOT.'upload/images/'.$_file_savepath.$_file_savename.'.'.$_file_ext);
				return 'upload/images/'.$_file_savepath.$_file_savename.'.'.$_file_ext;
			}
			else
			{
				return '';
			}
		}

		return '';
	}

	static public function createImageThumb($img='',$width='600')
	{
		$_image_info=imageinfo($img);
		$_image_ext=explode("/",$_image_info['mime']);
		$_image_ext=$_image_ext[1]=='jpeg'?'jpg':$_image_ext[1];

		$im=imagecreatetruecolor($width,$_image_info['height']*($width/$_image_info['width']));
		$_createfun='imagecreatefrom'.($_image_ext=='jpg'?'jpeg':$_image_ext);
		$_imagefun='image'.($_image_ext=='jpg'?'jpeg':$_image_ext);

		$thumbim = $_createfun($img);

		if(function_exists('imagecopyresampled'))
		{
			imagecopyresampled($im, $thumbim, 0, 0, 0, 0, $width, $_image_info['height']*($width/$_image_info['width']), $_image_info['width'], $_image_info['height']);
		}
		else
		{
			imagecopyresized($im, $thumbim, 0, 0, 0, 0, $width, $_image_info['height']*($width/$_image_info['width']), $_image_info['width'], $_image_info['height']);
		}
			
		if($_image_ext=='jpg')
		{
			$_imagefun($im,substr($img,0,'-'.(strlen($_image_ext)+1)).'x'.$width.'.'.get_fileext($img),100);
		}
		else
		{
			$_imagefun($im,substr($img,0,'-'.(strlen($_image_ext)+1)).'x'.$width.'.'.get_fileext($img));
		}

		setwatermark(substr($img,0,'-'.(strlen($_image_ext)+1)).'x'.$width.'.'.get_fileext($img));
	}

	static public function isUploadedFile($file)
	{
		return (is_uploaded_file($file) || is_uploaded_file(str_replace('\\\\','\\',$file))); 
	}
	
}

?>