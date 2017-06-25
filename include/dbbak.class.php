<?php
// +----------------------------------------------------------------------
// | phpWeChat 数据库备份类 Last modified 2016/7/4 17:05
// +----------------------------------------------------------------------
// | Copyright (c) 2009-2016 phpWeChat http://www.phpwechat.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 骑马的少年<phpwechat@126.com> <http://www.phpwechat.com>
// +----------------------------------------------------------------------
namespace phpWeChat;

class dbBak
{
	static public function tablesList()
	{
		$result=MySql::getTableStatus(DB_NAME);
		foreach($result as $key => $value)
		{
			if(substr($value['Name'],0,strlen(DB_PRE))==DB_PRE)
			{
				$result[$key]=$value;
			}
			else 
			{
				unset($result[$key]);
			}
		}
		return $result;
	}
}
?>