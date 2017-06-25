<?php
// +----------------------------------------------------------------------
// | phpWeChat 地区读取select文件 Last modified 2016/5/5
// +----------------------------------------------------------------------
// | Copyright (c) 2009-2016 phpWeChat http://www.phpwechat.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 骑马的少年 <phpwechat@126.com> <http://www.phpwechat.com>
// +----------------------------------------------------------------------
use phpWeChat\Area;

require substr(dirname(__FILE__),0,-9).'/include/common.inc.php';
$action=@return_edefualt(str_callback_w($_GET['action']),'getcity');

switch ($action) 
{
	case 'getcity':
		$provinceid=intval($provinceid);

		$option='<option value="0">不限</option>';
		if($provinceid)
		{
			foreach(Area::cityList($provinceid) as $r)
			{
				$option.='<option value="'.$r['id'].'">'.$r['name'].'</option>';
			}
		}
		exit($option);
		break;
	case 'getarea':
		$cityid=intval($cityid);

		$option='<option value="0">不限</option>';
		if($cityid)
		{
			foreach(Area::areaList($cityid) as $r)
			{
				$option.='<option value="'.$r['id'].'">'.$r['name'].'</option>';
			}
		}
		exit($option);
		break;
	case 'getareacheckbox':
		$cityid=intval($cityid);
		$html='';
		if($cityid)
		{
			foreach(Area::areaList($cityid) as $r)
			{
				$html.='<label><input type="checkbox" class="common-checkbox" name="area[]" checked="checked" value="'.$r['id'].'">'.$r['name'].'</label>&nbsp;&nbsp;';
			}
		}
		exit($html);
		break;
	default:
		# code...
		break;
}
?>