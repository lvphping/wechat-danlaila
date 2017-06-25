{php !defined('IN_MANAGE') && exit('Access Denied!');use phpWeChat\Module;use phpWeChat\MySql;}
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
	<script src="{__PW_PATH__}statics/core.js" language="javascript"></script>
    <script type="text/javascript" src="{__PW_PATH__}admin/template/js/libs/modernizr.min.js"></script>
    <script src="{__PW_PATH__}statics/reveal/jquery.reveal.js" language="javascript"></script>
    <script language="javascript" type="text/javascript">
		var PW_PATH='{__PW_PATH__}';
		$(document).ready(function() { 
			setInterval(function(){
				var now = (new Date()).toLocaleString();
				$('#beijing-time').text(now);
				},1000);
		}); 
	</script>
    <style type="text/css">
	.module{clear:both; margin:0px 80px;}
	.module ul{width:900px;}
	.module ul li{float:left;min-width: 380px; padding:5px 10px; list-style:disc}
	
	.updatebtn{background:#44b549 ; color:#FFFFFF; font-size: 14px;font-weight: 400; display:inline-block; padding:0px 10px;font-family:'Microsoft YaHei'; cursor:pointer; min-width:80px; border:0px; height:30px; line-height:30px; text-align:center;border-radius: 4px;}
	.updatebtn:hover{color:#FFFFFF;background:#339933}
	</style>
</head>
<body>
	<div class="ifame-main-wrap">
        <div class="crumb-wrap">
            <div class="crumb-list"><i class="icon-font">&#xe06b;</i><span>欢迎使用phpWeChat</span></div>
        </div>
        <div class="result-wrap">
            <div class="result-title">
                <h1>快捷操作</h1>
            </div>
            <div class="result-content">
                <div class="short-wrap">
					<a href="{__PW_PATH__}{__ADMIN_FILE__}?file=config&action=base"><i class="icon-font">&#xe017;</i>基本配置</a>
					<a href="{__PW_PATH__}{__ADMIN_FILE__}?file=config&action=safety"><i class="icon-font">&#xe057;</i>安全验证</a>
                	{if Module::isModuleInstalled('wechat')}
                    <a href="{__PW_PATH__}{__ADMIN_FILE__}?mod=wechat&file=config&action=config"><i class="icon-font">&#xe018;</i>微信接口配置</a>
                    {/if}
                    {if Module::isModuleInstalled('member')}
                    <a href="{__PW_PATH__}{__ADMIN_FILE__}?mod=member&file=member&action=member"><i class="icon-font">&#xe014;</i>会员管理</a>
                    <a href="{__PW_PATH__}{__ADMIN_FILE__}?mod=member&file=member&action=level"><i class="icon-font">&#xe031;</i>会员等级管理</a>
                    {/if}
                    {if Module::isModuleInstalled('pc')}
					<a href="{__PW_PATH__}{__ADMIN_FILE__}?mod=pc&file=config&action=base"><i class="icon-font">&#xe018;</i>网站配置</a>
                    <a href="{__PW_PATH__}{__ADMIN_FILE__}?mod=pc&file=config&action=template"><i class="icon-font">&#xe053;</i>模板风格</a>
                    {/if}
                </div>
            </div>
        </div>
        <div class="result-wrap">
            <div class="result-title">
                <h1>系统基本信息</h1>
            </div>
            <div class="result-content">
                <ul class="sys-info-list">

                    <li>
                        <label class="res-lab">运行环境：</label><span class="res-info">{system_software()}</span>
                    </li>
                    <li>
                        <label class="res-lab">网站根目录：</label><span class="res-info">{php echo str_replace('\\','/',$_SERVER['DOCUMENT_ROOT']);}</span>
                    </li>
                    <li>
                        <label class="res-lab">上传附件限制：</label><span class="res-info">{ini_get('upload_max_filesize')}</span>
                    </li>
                    <li>
                        <label class="res-lab">北京时间：</label><span class="res-info" id="beijing-time">loading...</span>
                    </li>
                    <li>
                        <label class="res-lab">服务器域名/IP：</label><span class="res-info">{$_SERVER["SERVER_NAME"]} [ {gethostbyname($_SERVER["SERVER_NAME"])} ]</span>
                    </li>
                    <li>
                        <label class="res-lab">您的当前IP：</label><span class="res-info">{__CLIENT_IP__}</span>
                    </li>
                </ul>
            </div>
        </div>
        <div class="result-wrap">
            <div class="result-title">
                <h1>社区新帖</h1>
            </div>
            <div class="result-content">
                <script type="text/javascript" src="http://bbs.phpwechat.com/api.php?mod=js&bid=5"></script>
            </div>
        </div>
        <div class="result-wrap" style="clear:both">
            <div class="result-title">
                <h1>使用帮助</h1>
            </div>
            <div class="result-content">
                <ul class="sys-info-list">
                    <li>
                        <label class="res-lab">官方网站：</label><span class="res-info"><a href="http://www.phpwechat.com/" target="_blank">phpWeChat官网</a>  &nbsp;  &nbsp; <a href="http://s.phpwechat.com/" target="_blank">应用商城</a> &nbsp; &nbsp; <a href="http://wiki.phpwechat.com/" target="_blank">开发文档</a>&nbsp; &nbsp; <a href="http://bbs.phpwechat.com/" target="_blank">交流社区</a></span>
                    </li>
                    <li>
                        <label class="res-lab">程序版本：</label><span class="res-info">Version {__PHPWECHAT_VERSION__}{__PHPWECHAT_RELEASE__} </span>
                    </li>
                    <li>
                        <label class="res-lab">在线更新：</label><span class="res-info"><a href="{__PW_PATH__}{__ADMIN_FILE__}?file=index&action=update" onClick="if(!confirm('建议您在在线更新前备份您的程序数据和数据库，确认继续？')){return false;}" class="updatebtn">在线更新</a></span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="statics-footer"> Powered by phpWeChat V{__PHPWECHAT_VERSION__}{__PHPWECHAT_RELEASE__} © , Processed in {php echo microtime()-$PW['time_start'];} second(s) , {MySql::$mQuery} queries <a href="#">至顶端↑</a></div>
</body>
</html>