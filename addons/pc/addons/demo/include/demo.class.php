<?php
// +----------------------------------------------------------------------
// | phpWeChat demo 操作类 Last modified 2016-08-12 17:20:31
// +----------------------------------------------------------------------
// | Copyright (c) 2009-2016 phpWeChat http://www.phpwechat.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 骑马的少年 <phpwechat@126.com> <http://www.phpwechat.com>
// +----------------------------------------------------------------------
namespace Pc\Demo;
use phpWeChat\DataInput;
use phpWeChat\DataList;
use phpWeChat\MySql;

class Demo
{
	private static $mDemoTable = 'pc_demo';

	public static $mPageString=''; // 这个静态成员是系统自带，请勿删除

    //获取列表不分页
    public static function demoList()
    {
        return MySql::fetchAll("SELECT * FROM `".DB_PRE.self::$mDemoTable."`");
    }

    //获取列表分页
    public static function demoListPage($title,$pagesize=20)
    {
        $where='1';
        $where.=$title?' AND title = \''.$title.'\'':'';

        $orderby='`id` DESC';

        $result=DataList::getList(DB_PRE.self::$mDemoTable,$where,$orderby,max(isset($_GET['page'])?intval($_GET['page']):1,1),intval($pagesize),0,'right');

        self::$mPageString=DataList::$mPageString;

        return $result;
    }

    //删除
    public  static function demoDelete($id)
    {
        return MySql::mysqlDelete(DB_PRE.self::$mDemoTable,$id,'id');
    }

    //添加
    public  static function demoAdd($info)
    {
        //$info['title'] = $info['title'];
        //$info['content'] = $info['content'];
        $info['posttime'] = CLIENT_TIME;
        return MySql::insert(DB_PRE.self::$mDemoTable,$info,true);
    }

    //修改
    public  static function demoEdit($info,$id)
    {

        //$info['title'] = $info['title'];
        //$info['content'] = $info['content'];

        //$id = intval($id);
        //echo DB_PRE.self::$mDemoTable,$info,'id=2';
        return MySql::update(DB_PRE.self::$mDemoTable,$info,'`id`='.intval($id));
        //return MySql::update(DB_PRE.self::$mDemoTable,$info,'id=2');
    }

    //前台页面使用和后台修改获取使用
    public  static function demoGet($id)
    {
        return MySql::fetchOne("SELECT * FROM `".DB_PRE.self::$mDemoTable."` WHERE `id`=".intval($id));
    }
}
?>