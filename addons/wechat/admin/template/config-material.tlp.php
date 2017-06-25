{php !defined('IN_MANAGE') && exit('Access Denied!');use Wechat\WeChatManage;use Wechat\Wechat;use phpWeChat\Form;use phpWeChat\MySql;}
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
	
	<script language="javascript" type="text/javascript">
		var PW_PATH='{__PW_PATH__}';
	</script>
	<script src="{__PW_PATH__}statics/jquery/jquery-1.12.2.min.js" language="javascript"></script>
	<!--[if lt IE 9]>
		<script src="{__PW_PATH__}statics/html5.js"></script>
	<![endif]-->
    <script src="{__PW_PATH__}statics/reveal/jquery.reveal.js" language="javascript"></script>
	<script src="{__PW_PATH__}statics/BlocksIt/blocksit.min.js"></script>
	<script src="{__PW_PATH__}statics/core.js" language="javascript"></script>
    <script type="text/javascript" src="{__PW_PATH__}admin/template/js/libs/modernizr.min.js"></script>
	<script>
	$(document).ready(function() {
		 
		
		//blocksit define
		$(window).load( function() {
			$('#container').BlocksIt({
				numOfCol: 5,
				offsetX: 8,
				offsetY: 8,
				blockElement: '.grid'
			});
		});
		
		//window resize
		var currentWidth = 1100;
		$(window).resize(function() {
			var winWidth = $(window).width();
			var conWidth;
			if(winWidth < 660) {
				conWidth = 440;
				col = 2
			} else if(winWidth < 880) {
				conWidth = 660;
				col = 3
			} else if(winWidth < 1100) {
				conWidth = 880;
				col = 4;
			} else {
				conWidth = 1100;
				col = 5;
			}
			
			if(conWidth != currentWidth) {
				currentWidth = conWidth;
				$('#container').width(conWidth);
				$('#container').BlocksIt({
					numOfCol: col,
					offsetX: 8,
					offsetY: 8
				});
			}
		});
	});
	</script>
	<style type="text/css">
		.material-list{width:98%; margin:10px auto; clear:both}
		.material-list h2 {font-size: 16px;padding: 0 10px; margin:0px;line-height: 25px; font-weight:400}
		
		.material-list .nav{ margin:20px 10px; border-bottom:1px solid #F3F3F3; clear:both; height:40px;}
		.material-list .nav a{display:block; float:left; padding:10px 30px;font-size: 14px;font-weight: 400; color:#333333}
		.material-list .nav a:hover{border-bottom:#44b549 3px solid}
		.material-list .nav a.hover{border-bottom:#44b549 3px solid}
		
		.material-list ul{position:relative;list-style:none; clear:both; width:100%}
		.material-list ul li{width:300px; border:1px solid #F3F3F3; height:auto; overflow:auto; position:relative; float:left; margin:10px;}
		.material-list ul li a{color:#333333}
		.material-list ul li .date{width:268px;font-size:12px; color:#999999; margin:8px auto; margin-bottom:0px}
		.material-list ul li .title{width:268px;margin:0px auto;font-size: 14px;font-weight: 400; line-height:2em; padding:10px 0px}
		.material-list ul li .desc{width:268px;margin:0px auto;font-size: 14px;font-weight: 400; line-height:2em; padding:10px 0px}
		.material-list ul li .appmsg_thumb_wrp{width:268px; height:160px; margin:0px auto;overflow: hidden;background-repeat: no-repeat; background-position: center center;background-size: cover; position:relative}
		.material-list ul li .appmsg_thumb_wrp span{position:absolute; width:100%; left:0px; bottom:0px; background: rgba(0,0,0,0.5)!important; color:#FFFFFF; font-size:14px; padding:3px 4px}
		.material-list ul li .appmsg_thumb_wrp span a{color:#FFFFFF}
		.material-list ul li .modify{background:#F6F6F6;width:100%;border-top:1px solid #F3F3F3; height:auto; overflow:auto}
		.material-list ul li .modify a{display:block; background:#F6F6F6; width:150px; height:44px; line-height:44px; font-size:16px; text-align:center; color:#999}
		.material-list ul li .modify a:hover{color:#444}
		
		.material-list ul li .modify .edit{float:left;border-right:1px solid #E8E8E8; width:149px;}
		.material-list ul li .modify .delete{float:right;}
		
		.material-list ul li .small-div{border-bottom:1px solid #F3F3F3; clear:both; clear:both; width:268px; padding:10px 0px; margin:0px auto; height:80px;}
		.material-list ul li .small-div .small-div-title{float:left; width:180px; line-height:2em;font-size: 14px;font-weight: 400;}
		.material-list ul li .small-div .small-div-image{float:right; width:80px; height:80px}
		.material-list ul li .small-div .small-div-image img{width:80px; height:80px}
		
		.page-string{clear:both; width:98%; margin:10px auto}
		
		.btn-post{clear:both; margin:10px 10px; height:30px;}
		.a-btn-post{background:#44b549 ; color:#FFFFFF; font-size: 14px;font-weight: 400; display:block; width:125px; height:30px; line-height:30px; text-align:center;border-radius: 4px; float:left; margin-right:20px }
		.a-btn-post:hover{color:#FFFFFF; background:#339933}
	</style>
</head>
<body>
    <div class="ifame-main-wrap">
        <div class="crumb-wrap">
            <div class="crumb-list"><i class="icon-font"></i><a href="{__PW_PATH__}{__ADMIN_FILE__}?file=index&action=main">管理首页</a><span class="crumb-step">&gt;</span><span class="crumb-name">素材管理</span></div>
        </div>
        <div class="material-list">
			<h2>素材管理</h2>
			<div class="nav">
				<a href="{__PW_PATH__}{__ADMIN_FILE__}?mod=wechat&file=config&action=material" class="hover">图文素材</a>
				<a href="{__PW_PATH__}{__ADMIN_FILE__}?mod=wechat&file=config&action=material_image">图片</a>
				<a href="{__PW_PATH__}{__ADMIN_FILE__}?mod=wechat&file=config&action=material_voice">语音</a>
				<a href="{__PW_PATH__}{__ADMIN_FILE__}?mod=wechat&file=config&action=material_video">视频</a>
			</div>
			<div class="btn-post">
				<a href="{__PW_PATH__}{__ADMIN_FILE__}?mod=wechat&file=config&action=material_post" class="a-btn-post"><i class="icon-font">&#xe026;</i> 新建图文素材</a>
				<a href="{__PW_PATH__}{__ADMIN_FILE__}?mod=wechat&file=config&action=material_synchronism" class="a-btn-post"><i class="icon-font">&#xe025;</i> 一键同步素材库</a>
			</div>
			{if $data}
			<ul id="container">
			{loop $data $r}
				{php $c=WeChatManage::getMutiMaterial($r['id']);}
				{if $c}
				<li  class="grid">
					<div class="title">{date('Y年m月d日',$r['UpdateTime'])}</div>
					<div class="appmsg_thumb_wrp" style="background-image:url('{format_url($r['PicUrl'])}')"><span><a href="{if $r['Url']}{$r['Url']}{else}{format_url(U('wechat','view',array('id'=>$r['id'])))}{/if}" target="_blank">{$r['Title']}</a></span></div>
					{loop $c $_c}
					<div class="small-div">
						<div class="small-div-title">
							<a href="{if $_c['Url']}{$_c['Url']}{else}{format_url(U('wechat','view',array('id'=>$_c['id'])))}{/if}" target="_blank">{$_c['Title']}</a>
						</div>
						<div class="small-div-image">
							<img src="{format_url($_c['PicUrl'])}"/>
						</div>
					</div>
					{/loop}
					<div class="modify"><a href="{__PW_PATH__}{__ADMIN_FILE__}?mod=wechat&file=config&action=material_edit&id={$r['id']}" class="edit"><i class="icon-font">&#xe002;</i></a><a onClick="if(!confirm('确定删除该图文吗？')){return false;}" href="{__PW_PATH__}{__ADMIN_FILE__}?mod=wechat&file=config&action=material_delete&id={$r['id']}" class="delete"><i class="icon-font">&#xe061;</i></a></div>
				</li>
				{else}
				<li  class="grid">
					<div class="date">{date('m月d日',$r['UpdateTime'])}</div>
					<div class="title"><a href="{if $r['Url']}{$r['Url']}{else}{format_url(U('wechat','view',array('id'=>$r['id'])))}{/if}" target="_blank">{$r['Title']}</a></div>
					<div class="appmsg_thumb_wrp" style="background-image:url('{format_url($r['PicUrl'])}')"></div>
					<div class="desc">{$r['Description']}</div>
					<div class="modify"><a href="{__PW_PATH__}{__ADMIN_FILE__}?mod=wechat&file=config&action=material_edit&id={$r['id']}" class="edit"><i class="icon-font">&#xe002;</i></a><a onClick="if(!confirm('确定删除该图文吗？')){return false;}" href="{__PW_PATH__}{__ADMIN_FILE__}?mod=wechat&file=config&action=material_delete&id={$r['id']}" class="delete"><i class="icon-font">&#xe061;</i></a></div>
				</li>
				{/if}
			{/loop}
			</ul>
			
			<div class="page-string">{php echo WeChatManage::$mPageString;}</div>
			{else}
			<div class="nodata">暂无素材，请上传。</div>
			{/if}
		</div>
    </div>
	 <div class="statics-footer"> Powered by phpWeChat V{__PHPWECHAT_VERSION__}{__PHPWECHAT_RELEASE__} © , Processed in {php echo microtime()-$PW['time_start'];} second(s) , {MySql::$mQuery} queries <a href="#">至顶端↑</a></div>
</body>
</html>