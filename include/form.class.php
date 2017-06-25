<?php
// +----------------------------------------------------------------------
// | phpWeChat 表单操作类 Last modified 2016-04-03 13:33
// +----------------------------------------------------------------------
// | Copyright (c) 2009-2016 phpWeChat http://www.phpwechat.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 骑马的少年 <phpwechat@126.com> <http://www.phpwechat.com>
// +----------------------------------------------------------------------
namespace phpWeChat;

class Form
{
	static public $mFname='info';

	static public function loadForm($fname='info')
	{
		self::$mFname=$fname;
	}

	static public function image($cnname,$enname,$defaultvalue='',$options='',$regex='limit',$notnull=0,$length='255',$css='')
	{
		return '<div class="upload_div">
					<input type="hidden" name="'.self::$mFname.'['.$enname.']" value="'.$defaultvalue.'" id="upload_hidden_val'.$enname.'">
                    <iframe width="0" height="0" frameborder="0" src="" name="upload_iframe'.$enname.'" id="upload_iframe'.$enname.'"></iframe>
                    <span class="form_file">
                    <div class="file_path" id="upload_path'.$enname.'"></div>
                    <label class="file_btn">本地图片</label>
                    <input type="file" name="upload_file'.$enname.'" id="upload_file'.$enname.'" onChange="selectImageBtn(\''.$enname.'\',\'\');" accept="image/jpeg,image/x-png,image/gif" class="file">
                    &nbsp;
                    <span id="upload_loading'.$enname.'"></span>
                    </span>
                    <div style="clear:both">
                    <img src="'.(is_image($defaultvalue)?format_url($defaultvalue):format_url('statics/images/demo.png')).'?t='.mt_rand().'" id="img_demo'.$enname.'" class="form_file_img_demo">
					</div>
               </div>';
	}

	static public function attachment($cnname,$enname,$defaultvalue='',$options='',$regex='limit',$notnull=0,$length='255',$css='')
	{
		return '<div class="attachment-div">
				<iframe width="0" height="0" frameborder="0" src="" name="attachment_upload_iframe'.$enname.'" style="display:none"></iframe>
				<div>
				外部附件：
				</div>
				<div>
				<input type="text" class="attachment_value" name="'.self::$mFname.'['.$enname.']" value="'.(is_attachment($defaultvalue)?$defaultvalue:'http://').'" id="attachment_value'.$enname.'">
				</div>
				<div>
				您也可以：
				</div>
				<div>
				<input type="file" class="attachment_file" name="attachment_file'.$enname.'" id="attachment_file'.$enname.'" onchange="selectAttachmentBtn(\''.$enname.'\');">
				<span class="attachment_upload_btn" id="attachment_upload_btn'.$enname.'">上传一个'.$cnname.'</span>
				</div>
				<div><font style="color:#ff0000;font-size:12px">上传提示：只允许上传 ZIP\RAR\TAR.GZ 格式的附件</font></div>
		</div>';
	}

	static public function video($cnname,$enname,$defaultvalue='',$options='',$regex='limit',$notnull=0,$length='255',$css='')
	{
		return '<div class="video-div">
			<iframe width="0" height="0" frameborder="0" src="" name="video_upload_iframe'.$enname.'" style="display:none"></iframe>
				<div>
				外部视频：
				</div>
				<div>
				<input type="text" class="video_value" name="'.self::$mFname.'['.$enname.']" value="'.(is_video($defaultvalue)?$defaultvalue:'http://').'" id="video_value'.$enname.'">
				</div>
				<div>
				您也可以：
				</div>
				<div>
				<input type="file" class="video_file" name="video_file'.$enname.'" id="video_file'.$enname.'" onchange="selectVideoBtn(\''.$enname.'\');">
				<span class="video_upload_btn" id="video_upload_btn'.$enname.'">上传一个视频</span>
				</div>
				<div><font style="color:#ff0000;font-size:12px">上传提示：只允许上传 Flv\Swf\Mp4 格式的视频</font></div>
		</div>';
	}

	static public function formFile($cnname,$enname,$defaultvalue='',$options='',$regex='limit',$notnull=0,$length='255',$css='')
	{
		return '<div class="formfile-div">
				<div>
				<input type="hidden" name="'.self::$mFname.'['.$enname.']" value="'.(is_pem($defaultvalue)?$defaultvalue:'').'">
				<input type="file" class="formfile_file" name="'.$enname.'" id="formfile_file'.$enname.'" onchange="selectFormfileBtn(\''.$enname.'\');">
				<span class="formfile_upload_btn" id="formfile_upload_btn'.$enname.'">上传一个'.trim($cnname,'.'.get_fileext($cnname)).'</span> '.(is_pem($defaultvalue)?'<font style="font-size:12px;color:#339933">已上传</font>':'').'
				</div>
				<div><font style="color:#ff0000;font-size:12px">上传提示：'.trim($cnname,'.'.get_fileext($cnname)).'是'.get_fileext($cnname).'格式的文件</font></div>
		</div>';
	}

	static public function images($cnname,$enname,$defaultvalue='',$options='',$regex='limit',$notnull=0,$length='255',$css='')
	{
		global $PW;
		
		$list='';
		$val=array();
		if($defaultvalue)
		{
			$val=explode(',',$defaultvalue);
			if($val)foreach($val as $k => $v)
			{
				$v=explode('`',$v);
				$list.='<li class="albCt"><img style="border:#EBEBEB 1px solid;max-width:139px;height:100px;" src="'.$v[0].'" alt="'.$v[1].'"/> <input type="hidden" name="urlimgs_'.$enname.'['.$k.']" value="'.$v[0].'" /><span class="thumbpictip"><input type="checkbox" style="cursor:pointer;border:0px" class="common-checkbox" name=deleteimgs_'.$enname.'['.$k.'] value="0" id="checkbox_images_'.$k.'" checked="checked" /></span><div style="margin:0px; padding:0px;margin-top:5px; clear:both"><input type="text" name="nameimgs_'.$enname.'['.$k.']" value="'.$v[1].'" style="border:#EBEBEB 1px solid; font-size:12px;width:135px; line-height:24px;height:24px; padding:2px" placeholder="请输入图片名称" /></div></li>';
			}
		}

		$_SESSION["file_info"] = array();
		return '<input type="hidden" value="'.$value.'" name="'.self::$mFname.'['.$enname.']" />
<script type="text/javascript" src="'.$PW['site_url'].'statics/swfupload/swfupload.js"></script>
<script type="text/javascript" src="'.$PW['site_url'].'statics/swfupload/handlers.js"></script>
<script type="text/javascript">
		var swfu'.$enname.';
		var func_'.$enname.' = window.onload;
		
		window.onload = function () {
			func_'.$enname.' ? func_'.$enname.'() : 0;
			swfu'.$enname.' = new SWFUpload({
				// Backend Settings
				upload_url: "'.$PW['site_url'].'api/upload/swfupload/swfupload.php",
				post_params: {"PHPSESSID": "'.session_id().'"},

				file_size_limit : "20MB",	
				file_types : "*.jpg;*.gif;*.png;*.jpeg",
				file_types_description : "图片文件",
				file_upload_limit : '.(defined('IN_MANAGE')?127:5).',

				swfupload_preload_handler : preLoad,
				swfupload_load_failed_handler : loadFailed,
				file_queue_error_handler : fileQueueError,
				file_dialog_complete_handler : fileDialogComplete,
				upload_progress_handler : uploadProgress,
				upload_error_handler : uploadError,
				upload_success_handler : uploadSuccess,
				upload_complete_handler : uploadComplete,

				button_image_url : "'.$PW['site_url'].'statics/swfupload/images/SmallSpyGlassWithTransperancy_17x18.png",
				button_placeholder_id : "spanButtonPlaceholder'.$enname.'",
				button_width: 95,
				button_height: 18,
				button_text : \'上传图片\',
				button_text_style : ".button {    font: 14px/1.5 \'Microsoft YaHei\';}",
				button_text_top_padding: 2,
				button_text_left_padding: 18,
				button_window_mode: SWFUpload.WINDOW_MODE.TRANSPARENT,
				button_cursor: SWFUpload.CURSOR.HAND,
				
				flash_url : "'.$PW['site_url'].'statics/swfupload/swfupload.swf",
				flash9_url : "'.$PW['site_url'].'statics/swfupload/swfupload_FP9.swf",

				custom_settings : {
					upload_target : "divFileProgressContainer'.$enname.'",
					thumbnail_width : "800",
					thumbnail_height : "800",
					fieldid : "'.$enname.'",
					startid : "'.(count($val)+1).'"
				},
				
				// Debug Settings
				debug: false
			});
		};
	</script>
	<style type="text/css">
		.progressBarStatus{padding:0px;margin:0px; color:forestgreen;height: 18px;line-height: 18px;padding-top: 3px;padding-left:8px}
		.progressName{display:none}
		#thumbnails'.$enname.'{padding:0px; margin:0px; clear:both}
		#thumbnails'.$enname.' ul{width:800px; clear:both; margin:0px; list-style:none; padding:0px}
		#thumbnails'.$enname.' ul li{width:150px; float:left; margin:4px; padding:0px; overflow:hidden;position:relative}
		.thumbpictip{position:absolute;left:0px;top:0px;color:#ffffff;padding:0px;height: 20px;font-size:12px;width: 20px;line-height: 20px; text-align: center;}
	</style>
	<div id="content" style="margin-top: 8px;width:800px;overflow:hidden">
		<div style="display:inline-block;line-height:19px;height:19px;border: solid 1px #ccc; background-color: #f7f7f7; padding: 2px;width:80px;float:left ">
			<span id="spanButtonPlaceholder'.$enname.'"></span>
		</div>
		<div style="float:left;padding-left:8px;padding-top:2px;font-size:12px;line-height:20px;height:19px;color:#666">
		点击选择图片
		</div>
	<div id="divFileProgressContainer'.$enname.'"></div>
	<div id="thumbnails'.$enname.'"><ul id="thumbnailsul'.$enname.'">'.$list.'</ul></div></div>
	<div style="clear:both;margin:8px 0px;margin-top:4px;" id="adtips'.$enname.'"><img src="'.$PW['site_url'].'statics/swfupload/images/adshow.png"></div>
	';
	}

	static public function map($cnname,$enname,$defaultvalue='',$options='',$regex='limit',$notnull=0,$length='255',$css='')
	{
		$str=defined('form_map')?'':'<script charset="utf-8" src="http://map.qq.com/api/js?v=2.exp"></script>';
		$str.='<input name="'.self::$mFname.'['.$enname.']" readonly="1" class="map_value" value="'.($defaultvalue?$defaultvalue:'39.916527, 116.397128').'" id="longitude_latitude'.$enname.'"> <input type="button" data-reveal-id="myModal'.$enname.'" class="map_btn" value="标注地图" data-animation="fade">
		<div id="myModal'.$enname.'" class="reveal-modal">
			<h1>地图标注</h1>
			<p>
			<div id="container'.$enname.'" style="width:800px;height:550px"></div>
			<script type="text/javascript">
				function init'.$enname.'() {
					//div容器
					var container = document.getElementById("container'.$enname.'");
					//初始化地图
					var map = new qq.maps.Map(container, {
						// 地图的中心地理坐标
						center: new qq.maps.LatLng('.($defaultvalue?$defaultvalue:'39.916527, 116.397128').'),
						zoom: 12
					});
				  //创建自定义控件
				   var middleControl = document.createElement("div");
					middleControl.style.left="50%";
					middleControl.style.top="250px";
					middleControl.style.position="relative";
					middleControl.style.width="36px";
					middleControl.style.height="36px";
					middleControl.style.zIndex="100000";
				    middleControl.innerHTML =\'<img src="'.PW_PATH.'statics/images/icon-location.png" />\';
				    container.appendChild(middleControl);

					//当地图中心属性更改时触发事件
					qq.maps.event.addListener(map, "center_changed", function() {
						$("#longitude_latitude'.$enname.'").val(map.getCenter());
					});
				}
				init'.$enname.'();
			</script>
			</p>
			<a class="close-reveal-modal">&#215;</a>
		</div>';

		if(!defined('form_map'))
		{
			define('form_map',true);
		}

		return $str;
	}

	static public function dateNoTime($cnname,$enname,$defaultvalue='',$options='',$regex='limit',$notnull=0,$length='255',$css='')
	{
		global $PW;
		
		$str= defined('form_laydate')?'':'<script src="'.$PW['site_url'].'statics/laydate/laydate.js"></script>';
		$str.='<input name="'.self::$mFname.'['.$enname.']" value="'.date('Y-m-d',$defaultvalue).'" class="laydate-icon" style="padding:3px;" onclick="laydate({istime: false, format: \'YYYY-MM-DD\'})">';
		
		if(!defined('form_laydate'))
		{
			define('form_laydate',true);
		}

		return $str;
	}

	static public function dateNoTimeArray($cnname,$enname,$defaultvalue='',$options='',$regex='limit',$notnull=0,$length='255',$css='')
	{
		global $PW;
		
		$str= defined('form_laydate')?'':'<script src="'.$PW['site_url'].'statics/laydate/laydate.js"></script>';
		$str.='<input name="'.self::$mFname.'['.$enname.'][]" value="'.date('Y-m-d',$defaultvalue).'" class="laydate-icon" style="padding:3px;" onclick="laydate({istime: false, format: \'YYYY-MM-DD\'})">';
		
		if(!defined('form_laydate'))
		{
			define('form_laydate',true);
		}

		return $str;
	}

	static public function dateWithTime($cnname,$enname,$defaultvalue='',$options='',$regex='limit',$notnull=0,$length='255',$css='')
	{
		global $PW;
		
		$str= defined('form_laydate')?'':'<script src="'.$PW['site_url'].'statics/laydate/laydate.js"></script>';
		$str.='<input name="'.self::$mFname.'['.$enname.']" value="'.date('Y-m-d H:i:s',$defaultvalue).'" class="laydate-icon" style="padding:3px;" onclick="laydate({istime: true, format: \'YYYY-MM-DD hh:mm:ss\'})">';

		if(!defined('form_laydate'))
		{
			define('form_laydate',true);
		}

		return $str;
	}

	static public function dateWithTimeArray($cnname,$enname,$defaultvalue='',$options='',$regex='limit',$notnull=0,$length='255',$css='')
	{
		global $PW;
		
		$str= defined('form_laydate')?'':'<script src="'.$PW['site_url'].'statics/laydate/laydate.js"></script>';
		$str.='<input name="'.self::$mFname.'['.$enname.'][]" value="'.date('Y-m-d H:i:s',$defaultvalue).'" class="laydate-icon" style="padding:3px;" onclick="laydate({istime: true, format: \'YYYY-MM-DD hh:mm:ss\'})">';

		if(!defined('form_laydate'))
		{
			define('form_laydate',true);
		}

		return $str;
	}

	static public function dateTimeStartToEnd($cnname,$enname,$defaultvalue='',$options='',$regex='limit',$notnull=0,$length='255',$css='')
	{
		global $PW;
		
		$defaultvalue=explode(',',$defaultvalue);

		$str= defined('form_laydate')?'':'<script src="'.$PW['site_url'].'statics/laydate/laydate.js"></script>';

		$str.= '<input id="start'.$enname.'_'.$PW['i_count'].'" name="'.self::$mFname.'[start'.$enname.']" value="'.($defaultvalue[0]?date('Y-m-d H:i:s',$defaultvalue[0]):date('Y-m-d H:i:s')).'" class="laydate-icon" style="padding:3px;" > - 
				<input id="end'.$enname.'_'.$PW['i_count'].'" name="'.self::$mFname.'[end'.$enname.']" value="'.(isset($defaultvalue[1]) && $defaultvalue[1]?date('Y-m-d H:i:s',$defaultvalue[1]):date('Y-m-d H:i:s',CLIENT_TIME+24*3600)).'" class="laydate-icon" style="padding:3px;" >
				<script>
					var start'.$enname.'_'.$PW['i_count'].' = {
						elem: \'#start'.$enname.'_'.$PW['i_count'].'\',
						format: \'YYYY/MM/DD hh:mm:ss\',
						min: laydate.now(), //设定最小日期为当前日期
						max: \'2099-06-16 23:59:59\', //最大日期
						istime: true,
						istoday: false,
						choose: function(datas){
							 end.min = datas; //开始日选好后，重置结束日的最小日期
							 end.start = datas //将结束日的初始值设定为开始日
						}
					};
					var end'.$enname.'_'.$PW['i_count'].' = {
						elem: \'#end'.$enname.'_'.$PW['i_count'].'\',
						format: \'YYYY/MM/DD hh:mm:ss\',
						min: laydate.now(),
						max: \'2099-06-16 23:59:59\',
						istime: true,
						istoday: false,
						choose: function(datas){
							start.max = datas; //结束日选好后，重置开始日的最大日期
						}
					};
					laydate(start'.$enname.'_'.$PW['i_count'].');
					laydate(end'.$enname.'_'.$PW['i_count'].');
				</script>';
		if(!defined('form_laydate'))
		{
			define('form_laydate',true);
		}
		else
		{
			$PW['i_count']++;
		}
		return $str;
	}

	static public function dateTimeStartToEndArray($cnname,$enname,$defaultvalue='',$options='',$regex='limit',$notnull=0,$length='255',$css='')
	{
		global $PW;
		
		$defaultvalue=explode(',',$defaultvalue);

		$str= defined('form_laydate')?'':'<script src="'.$PW['site_url'].'statics/laydate/laydate.js"></script>';

		$str.= '<input id="start'.$enname.'_'.$PW['i_count'].'" name="'.self::$mFname.'[start'.$enname.'][]" value="'.($defaultvalue[0]?date('Y-m-d H:i:s',$defaultvalue[0]):date('Y-m-d H:i:s')).'" class="laydate-icon" style="padding:3px;" > - 
				<input id="end'.$enname.'_'.$PW['i_count'].'" name="'.self::$mFname.'[end'.$enname.'][]" value="'.(isset($defaultvalue[1]) && $defaultvalue[1]?date('Y-m-d H:i:s',$defaultvalue[1]):date('Y-m-d H:i:s',CLIENT_TIME+24*3600)).'" class="laydate-icon" style="padding:3px;" >
				<script>
					var start'.$enname.'_'.$PW['i_count'].' = {
						elem: \'#start'.$enname.'_'.$PW['i_count'].'\',
						format: \'YYYY/MM/DD hh:mm:ss\',
						min: laydate.now(), //设定最小日期为当前日期
						max: \'2099-06-16 23:59:59\', //最大日期
						istime: true,
						istoday: false,
						choose: function(datas){
							 end.min = datas; //开始日选好后，重置结束日的最小日期
							 end.start = datas //将结束日的初始值设定为开始日
						}
					};
					var end'.$enname.'_'.$PW['i_count'].' = {
						elem: \'#end'.$enname.'_'.$PW['i_count'].'\',
						format: \'YYYY/MM/DD hh:mm:ss\',
						min: laydate.now(),
						max: \'2099-06-16 23:59:59\',
						istime: true,
						istoday: false,
						choose: function(datas){
							start.max = datas; //结束日选好后，重置开始日的最大日期
						}
					};
					laydate(start'.$enname.'_'.$PW['i_count'].');
					laydate(end'.$enname.'_'.$PW['i_count'].');
				</script>';
		if(!defined('form_laydate'))
		{
			define('form_laydate',true);
		}
		else
		{
			$PW['i_count']++;
		}
		return $str;
	}

	static public function baiduEditor($cnname,$enname,$defaultvalue='',$options='',$regex='limit',$notnull=0,$length='255',$css='')
	{
		global $PW;

		return '<link href="'.$PW['site_url'].'statics/umeditor/themes/default/css/umeditor.css" type="text/css" rel="stylesheet">
		<textarea name="'.self::$mFname.'['.$enname.']" id="myEditor'.$enname.'">'.htmlspecialchars($defaultvalue).'</textarea>
		<script type="text/javascript">
			var UEDITOR_SITE_URL="'.$PW['site_url'].'";
		</script>
		<script type="text/javascript" charset="utf-8" src="'.$PW['site_url'].'statics/umeditor/umeditor.config.js"></script>
		<script type="text/javascript" charset="utf-8" src="'.$PW['site_url'].'statics/umeditor/umeditor.min.js"></script>
		<script type="text/javascript" src="'.$PW['site_url'].'statics/umeditor/lang/zh-cn/zh-cn.js"></script>
		<script type="text/javascript">
			var um = UM.getEditor("myEditor'.$enname.'");
		</script>';
	}
}

?>