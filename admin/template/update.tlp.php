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
	<style type="text/css">
	.updatebtn{background:#44b549 ; color:#FFFFFF; font-size: 14px;font-weight: 400; display:inline-block; padding:0px 10px;font-family:'Microsoft YaHei'; cursor:pointer; min-width:80px; border:0px; height:30px; line-height:30px; text-align:center;border-radius: 4px;}
	.updatebtn:hover{color:#FFFFFF;background:#339933}
	</style>
</head>
<body>
    <div class="ifame-main-wrap">
        <div class="crumb-wrap">
            <div class="crumb-list"><i class="icon-font"></i><a href="{__PW_PATH__}{__ADMIN_FILE__}?file=index&action=main">管理首页</a><span class="crumb-step">&gt;</span><span class="crumb-name">在线更新</span></div>
        </div>
        <div class="result-wrap">
                <div class="config-items">
					<form action="" method="post" name="myform" id="myform">
					<input type="hidden" name="dosubmit" value="1" />
                    <div class="result-content">
						<div style="margin:8px auto; clear:bothl; padding:8px; border:#F5F5F5 1px solid;">
						<a href="javascript:void(0);" onClick="$('#myform').submit();" class="updatebtn">开始更新以下文件</a>
						<br>
						<font style="color:#FF3300; font-size:12px">* 开始更新前请保证以下文件均为【可更新】状态。</font>
						<br>
						* 如需手动下载更新包或寻求帮助，请进入 <a href="http://bbs.phpwechat.com/forum.php?mod=viewthread&tid=12&extra=page%3D1" target="_blank">http://bbs.phpwechat.com/forum.php?mod=viewthread&tid=12&extra=page%3D1</a>
						</div>
                        <table class="result-tab" width="100%">
						  <tr>
							<th>更新文件/目录</th>
							<th>是否可写</th>
							<th>文件大小</th>
							<th>修改时间</th>
							<th>更新建议</th>
						  </tr>
						  {loop $list $r}
							<tr>
								<td style="text-align:left">{$r['stored_filename']}</td>
								<td>{php echo (is_writable(PW_ROOT.$r['stored_filename'])|| !file_exists(PW_ROOT.$r['stored_filename']))?'<font color="#009900">可写</font>':'<font color="#990000">不可写</font>';}</td>
								<td>{round($r['size']/1024,2)} K </td>
								<td>{date('Y-m-d H:i:s',$r['mtime'])} </td>
								<td>{php echo (is_writable(PW_ROOT.$r['stored_filename'])|| !file_exists(PW_ROOT.$r['stored_filename']))?'<font color="#009900">可更新</font>':'<font color="#990000">请设置为0777后更新</font>';} </td>
							</tr>
						 {/loop}
					  </table>
                    </div>
					</form>
                </div>
        </div>
    </div>
    <div class="statics-footer"> Powered by phpWeChat V{__PHPWECHAT_VERSION__}{__PHPWECHAT_RELEASE__} © , Processed in {php echo microtime()-$PW['time_start'];} second(s) , {MySql::$mQuery} queries <a href="#">至顶端↑</a></div>
</body>
</html>