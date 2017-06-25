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
    <script type="text/javascript" src="{__PW_PATH__}admin/template/js/admin-core.js"></script>
    <script type="text/javascript" src="{__PW_PATH__}admin/template/js/libs/modernizr.min.js"></script>
    <script src="{__PW_PATH__}statics/reveal/jquery.reveal.js" language="javascript"></script>
    <script type="text/javascript">
	var PW_PATH='{__PW_PATH__}';
	$(document).ready(function(){
		autoHeight('sidebar-wrap',75);
		autoHeight('main-wrap',75);
		autoHeight('iframe-wrap',75);
	});
	
	function adminTopMenu(modulekey,modulename,id)
	{
		$('#admin_top_a'+id).text('加载中...');
		$('#iframe-wrap').hide();
		$('#loading-div').show();
		
		var postUrl='{__PW_PATH__}{__ADMIN_FILE__}?action=index&action=menu&modulekey='+modulekey;
		$.get(postUrl, function(data){
			$('#sidebar-list').html(data);
		});
		
		$('#iframe-wrap').load(function(){
			for(var i=1;i<=<?php echo 2+sizeof(Module::moduleList(0));?>;i++)
			{
				$('#admin_top_a'+i).removeClass('on');	
			}
			$('#admin_top_a'+id).text(modulename);
			$('#admin_top_a'+id).addClass('on');	
			$('#iframe-wrap').show();
			$('#loading-div').hide();
	
			if($('#sidebar-list').height()+60>$(window).height())
			{
				$("#sidebar-list ul").each(function(i){
					if(i>0)
					{
						$(this).hide();
					}
				});
			}
		});
	}
	</script>
</head>
<body>
<div class="topbar-wrap white">
    <div class="topbar-inner clearfix">
        <div class="topbar-logo-wrap clearfix">
            <h1 class="topbar-logo none"><a href="{__PW_PATH__}{__ADMIN_FILE__}" class="navbar-brand">后台管理</a></h1>
            <ul class="navbar-list clearfix">
				<li><a href="{__PW_PATH__}{__ADMIN_FILE__}" style=" padding:0px"><img src="{__PW_PATH__}admin/template/images/logo.png"></a></li>
                <li {if !Module::isModuleInstalled('pc')}style="display:none"{/if}><a id="admin_top_a1" href="{__PW_PATH__}" target="_blank">前台首页</a></li>
                <li><a id="admin_top_a2" class="on" href="{__PW_PATH__}{__ADMIN_FILE__}">后台首页</a></li>
                {loop Module::moduleList(0,0)   $i   $r}
				<li><a id="admin_top_a{php echo $i+3;}" href="{__PW_PATH__}{__ADMIN_FILE__}?mod={$r['folder']}&file=config" target="iframe-wrap" onClick="adminTopMenu('{$r['key']}','{$r['name']}',{php echo $i+3;})">{$r['name']}</a></li>
				{/loop}
            </ul>
        </div>
        <div class="top-info-wrap">
            <ul class="top-info-list clearfix">
                <li><a href="{__PW_PATH__}{__ADMIN_FILE__}?mod=&file=admin&action=manage" target="iframe-wrap">{$_username}</a></li>
                <li><a href="{__PW_PATH__}{__ADMIN_FILE__}?mod=&file=admin&action=edit" target="iframe-wrap">修改密码</a></li>
                <li><a href="{__PW_PATH__}{__ADMIN_FILE__}?file=login&action=logout">退出</a></li>
            </ul>
        </div>
    </div>
</div>
<div class="container clearfix">
    <div class="sidebar-wrap" id="sidebar-wrap">
        <div class="sidebar-content">
            <ul class="sidebar-list" id="sidebar-list">
                <li>
                    <a href="javascript:void(0);"><i class="icon-font">&#xe003;</i>常用操作</a>
                    <ul class="sub-menu">
                    	<li><a href="{__PW_PATH__}{__ADMIN_FILE__}?file=config&action=base" target="iframe-wrap"><i class="icon-font">&#xe017;</i>基本配置</a></li>
						<li><a href="{__PW_PATH__}{__ADMIN_FILE__}?file=config&action=safety" target="iframe-wrap"><i class="icon-font">&#xe057;</i>安全验证</a></li>
                    	{if Module::isModuleInstalled('wechat')}
                        <li><a href="{__PW_PATH__}{__ADMIN_FILE__}?mod=wechat&file=config&action=config" target="iframe-wrap"><i class="icon-font">&#xe018;</i>微信接口配置</a></li>
                        {/if}
                        {if Module::isModuleInstalled('member')}
                        <li><a href="{__PW_PATH__}{__ADMIN_FILE__}?mod=member&file=member&action=member" target="iframe-wrap"><i class="icon-font">&#xe014;</i>会员管理</a></li>
                        <li><a href="{__PW_PATH__}{__ADMIN_FILE__}?mod=member&file=member&action=level" target="iframe-wrap"><i class="icon-font">&#xe031;</i>会员等级管理</a></li>
                        {/if}
                        {if Module::isModuleInstalled('pc')}
                        <li><a href="{__PW_PATH__}{__ADMIN_FILE__}?mod=pc&file=config&action=base" target="iframe-wrap"><i class="icon-font">&#xe048;</i>网站配置</a></li>
                        <li><a href="{__PW_PATH__}{__ADMIN_FILE__}?mod=pc&file=config&action=template" target="iframe-wrap"><i class="icon-font">&#xe006;</i>模板风格</a></li>
                        {/if}
                    </ul>
                </li>
				<li>
                    <a href="javascript:void(0);"><i class="icon-font">&#xe032;</i>数据库</a>
                    <ul class="sub-menu">
                        <li><a href="{__PW_PATH__}{__ADMIN_FILE__}?file=db&action=export" target="iframe-wrap"><i class="icon-font">&#xe010;</i>数据备份</a></li>
                        <li><a href="{__PW_PATH__}{__ADMIN_FILE__}?file=db&action=import" target="iframe-wrap"><i class="icon-font">&#xe046;</i>数据还原</a></li>
                        <li><a href="{__PW_PATH__}{__ADMIN_FILE__}?file=db&action=repair" target="iframe-wrap"><i class="icon-font">&#xe045;</i>优化修复</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
    <!--/sidebar-->
    <div class="main-wrap" id="main-wrap">
    	<div class="loading-div" id="loading-div"><img src="{__PW_PATH__}statics/images/large-loading.gif"></div>
		<iframe frameborder="0" name="iframe-wrap" id="iframe-wrap" scrolling="1" width="100%" src="{__PW_PATH__}{__ADMIN_FILE__}?file=index&action=main"></iframe>
    </div>
    <!--/main-->
    <div class="clearcache"><a href="{__PW_PATH__}{__ADMIN_FILE__}?file=index&action=cache" target="iframe-wrap">清理缓存</a></div>
</div>

</body>
</html>