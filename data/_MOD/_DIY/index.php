<?php
	use _MOD_PARENT\_DIY_CLASS\_DIY_CLASS;
	use phpWeChat\Area;
	use phpWeChat\CaChe;
	use phpWeChat\Config;
	use phpWeChat\Member;
	use phpWeChat\Module;
	use phpWeChat\MySql;
	use phpWeChat\Order;
	use phpWeChat\Upload;

	/**
	 * 本文件是 #_DIY# 模块的前端控制器
	 *
	 * 您可以通过 switch 的 case 分支来实现不同的业务逻辑
	 */

	!defined('IN_APP') && exit('Access Denied!');

	switch($action)
	{
		 //以下 case 条件仅为 示例。您可以根据业务逻辑自由修改和拓展


		//case 'index':
			
			//在此写 index.php?m=_DIY&a=index 时的逻辑
			
			//break;

		//case 'list':
			
			//在此写 index.php?m=_DIY&a=list 时的逻辑
			
			//break;

		//以此类推...

		//case '...':
			
			//在此写 index.php?m=_DIY&a=... 时的逻辑
			
			//break;
		default:
			break;
	}
?>