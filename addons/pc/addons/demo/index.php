<?php
	/**
	 * 本文件是 demo 模块的前端控制器
	 *
	 * 您可以通过 switch 的 case 分支来实现不同的业务逻辑
	 */

use Pc\Demo\Demo;

	!defined('IN_APP') && exit('Access Denied!');

	switch($action)
	{
		 //以下 case 条件仅为 示例。您可以根据业务逻辑自由修改和拓展


		case 'index':
			
			//在此写 index.php?m=demo&a=index 时的逻辑
            //不分页
			//$data = Demo::demoList();

            //分页
            $title = htmlspecialchars(trim($title));
            $data = Demo::demoListPage($title,20);
			break;

		case 'detail':

			//在此写 index.php?m=demo&a=index 时的逻辑
			$data = Demo::demoGet($id);
			break;


		//case 'list':
			
			//在此写 index.php?m=demo&a=list 时的逻辑
			
			//break;

		//以此类推...

		//case '...':
			
			//在此写 index.php?m=demo&a=... 时的逻辑
			
			//break;
		default:
			break;
	}
?>