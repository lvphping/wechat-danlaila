<?php
// +----------------------------------------------------------------------
// | phpWeChat 分页操作类 Last modified 2016/5/7 14:20
// +----------------------------------------------------------------------
// | Copyright (c) 2009-2016 phpWeChat http://www.phpwechat.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 骑马的少年 <phpwechat@126.com> <http://www.phpwechat.com>

// +----------------------------------------------------------------------

namespace phpWeChat;

class DataList
{
	static public $mPageString;
	static public $mTotalCount;
	
	static public function getList($table,$where,$orderby,$page=1,$pagesize=15,$totalnumber=0,$align='right')
	{
		$array=array();
		$where=empty($where)?'':'WHERE '.$where;
		$orderby=empty($orderby)?'':'ORDER BY '.$orderby;

		if(!$totalnumber)
		{
			$number=get_cache_counts("SELECT COUNT(*) AS count FROM `$table` $where"); 
		}
		else
		{
			$number=intval($totalnumber);
		}
		self::$mTotalCount=$number;

		$pagenum=ceil($number/$pagesize);
		$page=max(1,min(max(intval($page),1),$pagenum));
		$start=($page-1)*$pagesize;

		$limit="LIMIT $start,$pagesize";
		self::$mPageString=data_list_pages($number,$page,$pagesize,$align);
       
		return MySql::fetchAll("SELECT * FROM `$table` $where $orderby $limit");
	}
}
?>