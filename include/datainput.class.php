<?php
// +----------------------------------------------------------------------
// | phpWeChat 数据过滤操作类 Last modified 2016/5/714:20
// +----------------------------------------------------------------------
// | Copyright (c) 2009-2016 phpWeChat http://www.phpwechat.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 骑马的少年 <phpwechat@126.com> <http://www.phpwechat.com>

// +----------------------------------------------------------------------

namespace phpWeChat;

class DataInput
{	
	static public function filterData(&$info)
	{
		foreach($info as $key => $val)
		{
			$info[$key]=$val;
		}
	}
}
?>