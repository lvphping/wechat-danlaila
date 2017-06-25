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
    <script src="{__PW_PATH__}statics/reveal/jquery.reveal.js" language="javascript"></script>
	<script src="{__PW_PATH__}statics/core.js" language="javascript"></script>
    <script type="text/javascript" src="{__PW_PATH__}admin/template/js/libs/modernizr.min.js"></script>
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
		.material-list ul li .appmsg_thumb_wrp{width:268px; height:80px; margin:0px auto;overflow: hidden;background-repeat: no-repeat; background-position: center center;background-size: cover; position:relative}
		.material-list ul li .appmsg_thumb_wrp .left-div{
		background:url("{__PW_PATH__}addons/wechat/admin/template/images/voice.gif"); width:60px; height:60px; margin:10px; float:left}
		.material-list ul li .appmsg_thumb_wrp .right-div{float:left; margin:10px 0px; width:180px;}
		
		.material-list ul li .modify{background:#F6F6F6;width:100%;border-top:1px solid #F3F3F3; height:auto; overflow:auto}
		.material-list ul li .modify a{display:block; background:#F6F6F6; width:150px; height:44px; line-height:44px; font-size:16px; text-align:center; color:#999}
		.material-list ul li .modify a:hover{color:#444}
		
		.material-list ul li .modify .view{float:left;border-right:1px solid #E8E8E8; width:149px;}
		.material-list ul li .modify .delete{float:right;}
		
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
				<a href="{__PW_PATH__}{__ADMIN_FILE__}?mod=wechat&file=config&action=material">图文素材</a>
				<a href="{__PW_PATH__}{__ADMIN_FILE__}?mod=wechat&file=config&action=material_image">图片</a>
				<a href="{__PW_PATH__}{__ADMIN_FILE__}?mod=wechat&file=config&action=material_voice" class="hover">语音</a>
				<a href="{__PW_PATH__}{__ADMIN_FILE__}?mod=wechat&file=config&action=material_video">视频</a>
			</div>
			<div class="btn-post">
				<form action="" method="post"  enctype="multipart/form-data" name="voice_material_upload" id="voice_material_upload"> 
					<input type="hidden" name="dosubmit" value="1">
					<input type="file" style="display:none" onChange="$('#voice_material_upload').submit();" name="voice_material" id="voice_material">
				</form>
				<a href="javascript:void(0);" onClick="if(!confirm('由于音频文件较大，本地上传很可能失败！强烈建议您去微信公共号官方平台上传语音素材！')){return false;}else{$('#voice_material').click();}" class="a-btn-post"><i class="icon-font">&#xe026;</i> 上传语音素材</a>
				<a href="{__PW_PATH__}{__ADMIN_FILE__}?mod=wechat&file=config&action=voice_material_synchronism" class="a-btn-post" style="width:165px"><i class="icon-font">&#xe025;</i> 一键同步语音素材库</a>
			</div>
			{if $data}
			<ul id="container">
			{loop $data $r}
				<li>
					<div class="date">{date('m月d日',$r['created_at'])}</div>
					<div class="appmsg_thumb_wrp">
						<div class="left-div"></div>
						<div class="right-div">{$r['name']}</div>
					</div>
					<div class="modify"><a href="{format_url($r['local_url'])}" target="_blank" title="播放" class="view"><i class="icon-font">&#xe035;</i></a><a onClick="if(!confirm('确定删除该语音素材吗？')){return false;}" href="{__PW_PATH__}{__ADMIN_FILE__}?mod=wechat&file=config&action=material_delete&type=Voice&id={$r['id']}" class="delete"><i class="icon-font">&#xe061;</i></a></div>
				</li>
			{/loop}
			</ul>
			
			<div class="page-string">{php echo WeChatManage::$mPageString;}</div>
			{else}
			<div class="nodata">暂无视频素材，请上传。</div>
			{/if}
		</div>
    </div>
	 <div class="statics-footer"> Powered by phpWeChat V{__PHPWECHAT_VERSION__}{__PHPWECHAT_RELEASE__} © , Processed in {php echo microtime()-$PW['time_start'];} second(s) , {MySql::$mQuery} queries <a href="#">至顶端↑</a></div>
</body>
</html>