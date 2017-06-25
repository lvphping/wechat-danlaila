{php !defined('IN_MANAGE') && exit('Access Denied!');use phpWeChat\dbBak;use phpWeChat\MySql;}
<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>phpWeChat后台管理</title>
    <link rel="stylesheet" type="text/css" href="{__PW_PATH__}admin/template/css/common.css"/>
    <link rel="stylesheet" type="text/css" href="{__PW_PATH__}admin/template/css/main.css"/>
    <link rel="stylesheet" type="text/css" href="{__PW_PATH__}statics/core.css"/>
    <link rel="stylesheet" type="text/css" href="{__PW_PATH__}statics/reveal/reveal.css"/>
	<script src="{__PW_PATH__}statics/jquery/jquery-1.12.2.min.js" language="javascript"></script>
    <script src="{__PW_PATH__}statics/reveal/jquery.reveal.js" language="javascript"></script>
	<script src="{__PW_PATH__}statics/core.js" language="javascript"></script>
    <script type="text/javascript" src="{__PW_PATH__}admin/template/js/libs/modernizr.min.js"></script>
    <script language="javascript" type="text/javascript">
		var PW_PATH='{__PW_PATH__}';
	</script>
</head>
<body>
    <div class="ifame-main-wrap">
        <div class="crumb-wrap">
            <div class="crumb-list"><i class="icon-font"></i><a href="{__PW_PATH__}{__ADMIN_FILE__}?file=index&action=main">管理首页</a><span class="crumb-step">&gt;</span><span class="crumb-name">数据还原</span></div>
        </div>
        <div class="result-wrap">
                <div class="config-items">
                    <div class="result-content">
						<ul class="toggle-ul">
							<li style="border-left:#F3F3F3 1px solid"><a href="{__PW_PATH__}{__ADMIN_FILE__}?mod=&file=db&action=export">数据备份</a></li>
							<li class="hover"><a href="{__PW_PATH__}{__ADMIN_FILE__}?mod=&file=db&action=import">数据还原</a></li>
							<li><a href="{__PW_PATH__}{__ADMIN_FILE__}?mod=&file=db&action=repair">优化修复</a></li>
						</ul>
                        <table class="result-tab" width="100%">
						  <tr>
							<th>备份文件名</th>
							<th>文件大小</th>
							<th>备份时间</th>
							<th>备份卷号</th>
							<th>操作</th>
						  </tr>
						  {loop $result $r}
							<tr>
								<td><a title="下载" href="?mod=&file=db&action=bakdown&filename=<?php echo $r['filename'];?>"><?php echo $r['filename'];?></a></td>
								<td><?php echo $r['filesize'];?></td>
								<td><?php echo $r['mtime'];?></td>
								<td><?php echo $r['volume'];?></td>
								<td><a onclick="if(!confirm('确定要将数据还原到 <?php echo $r['mtime'];?> 吗？')){return false}" href="?mod=&file=db&action=import&dosubmit=1&filename=<?php echo $r['filename'];?>">导入</a> | <a href="?mod=&file=db&action=bakdown&filename=<?php echo $r['filename'];?>">下载</a> | <a href="?mod=&file=db&action=bakdelete&filename=<?php echo $r['filename'];?>">删除</a></td>
							</tr>
						 {/loop}
					  </table>
                    </div>
                </div>
        </div>
    </div>
    <div class="statics-footer"> Powered by phpWeChat V{__PHPWECHAT_VERSION__}{__PHPWECHAT_RELEASE__} © , Processed in {php echo microtime()-$PW['time_start'];} second(s) , {MySql::$mQuery} queries <a href="#">至顶端↑</a></div>
</body>
</html>