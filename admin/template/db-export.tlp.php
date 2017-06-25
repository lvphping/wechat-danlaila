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
            <div class="crumb-list"><i class="icon-font"></i><a href="{__PW_PATH__}{__ADMIN_FILE__}?file=index&action=main">管理首页</a><span class="crumb-step">&gt;</span><span class="crumb-name">数据备份</span></div>
        </div>
        <div class="result-wrap">
                <div class="config-items">
					<form action="" method="post" name="myform" id="myform">
					<input type="hidden" name="dosubmit" value="1" />
                    <div class="result-content">
						<div style="margin:8px auto; clear:bothl; padding:8px; border:#F5F5F5 1px solid;">
						每个分卷文件大小：<input type="text" name="sizelimit" size="4" class="input-txt" value="2048" datatype="number" /> K &nbsp;&nbsp;<a href="javascript:void(0);" onClick="$('#myform').submit();" class="top">开始备份</a>
						</div>
						<ul class="toggle-ul">
							<li class="hover" style="border-left:#F3F3F3 1px solid"><a href="{__PW_PATH__}{__ADMIN_FILE__}?mod=&file=db&action=export">数据备份</a></li>
							<li><a href="{__PW_PATH__}{__ADMIN_FILE__}?mod=&file=db&action=import">数据还原</a></li>
							<li><a href="{__PW_PATH__}{__ADMIN_FILE__}?mod=&file=db&action=repair">优化修复</a></li>
						</ul>
                        <table class="result-tab" width="100%">
						  <tr>
							<th><input id="checkAll" class="common-checkbox" checked="checked" title="全选/反选" type="checkbox" /></th>
							<th>数据库表</th>
							<th>记录条数</th>
							<th>使用空间</th>
							<th>表类型</th>
							<th>记录更新时间</th>
						  </tr>
						  {loop dbBak::tablesList() $r}
							<tr>
								<td><input type="checkbox" name="name[]" class="common-checkbox" checked="checked" la="checkbox" value="{$r['Name']}" /></td>
								<td>{$r['Name']}</td>
								<td>{$r['Rows']} 条</td>
								<td>{round($r['Data_length']/1024,2)} K </td>
								<td>{$r['Engine']} </td>
								<td>{php echo $r['Update_time']?$r['Update_time']:'-';} </td>
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