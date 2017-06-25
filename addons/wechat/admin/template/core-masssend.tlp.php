<?php !defined('IN_MANAGE') && exit('Access Denied!');use Wechat\WeChatManage;use Wechat\Wechat;use phpWeChat\Form;use phpWeChat\MySql;?>
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
	<script src="{__PW_PATH__}statics/jquery/jquery-1.12.2.min.js" language="javascript"></script>
    <script src="{__PW_PATH__}statics/reveal/jquery.reveal.js" language="javascript"></script>
    <script src="{__PW_PATH__}statics/BlocksIt/blocksit.min.js"></script>
	<script src="{__PW_PATH__}statics/core.js" language="javascript"></script>
    <script type="text/javascript" src="{__PW_PATH__}admin/template/js/libs/modernizr.min.js"></script>
    <script type="text/javascript">
	$(function() {
			/*  在textarea处插入文本--Start */
			(function($) {
				$.fn
						.extend({
							insertContent : function(myValue, t) {
								var $t = $(this)[0];
								if (document.selection) { // ie
									this.focus();
									var sel = document.selection.createRange();
									sel.text = myValue;
									this.focus();
									sel.moveStart('character', -l);
									var wee = sel.text.length;
									if (arguments.length == 2) {
										var l = $t.value.length;
										sel.moveEnd("character", wee + t);
										t <= 0 ? sel.moveStart("character", wee - 2 * t
												- myValue.length) : sel.moveStart(
												"character", wee - t - myValue.length);
										sel.select();
									}
								} else if ($t.selectionStart
										|| $t.selectionStart == '0') {
									var startPos = $t.selectionStart;
									var endPos = $t.selectionEnd;
									var scrollTop = $t.scrollTop;
									$t.value = $t.value.substring(0, startPos)
											+ myValue
											+ $t.value.substring(endPos,
													$t.value.length);
									this.focus();
									$t.selectionStart = startPos + myValue.length;
									$t.selectionEnd = startPos + myValue.length;
									$t.scrollTop = scrollTop;
									if (arguments.length == 2) {
										$t.setSelectionRange(startPos - t,
												$t.selectionEnd + t);
										this.focus();
									}
								} else {
									this.value += myValue;
									this.focus();
								}
							}
						})
			})(jQuery);
		});
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
				conWidth = 880;
				col = 4;
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
	
	function toggle_msg_type(type)
	{
		$('.si-title a').each(function(){
			$(this).removeClass('hover');
		});
		
		$('.si-content').each(function(){
			$(this).hide();
		});
		
		$('#wechat_masssend_msg_type').val(type);
		$('#title_'+type).addClass('hover');
		$('#material-dialog-'+type).show();
	}
	
	</script>
	<style type="text/css">
		.si-editor{ border:1px solid #F3F3F3;}
		.si-editor .si-title{border-bottom:1px solid #F3F3F3; clear:both; padding:10px; height:auto; overflow:auto}
		.si-editor .si-title a{color:#999; display:block; float:left; margin:0px 20px; font-size:15px; font-weight:400}
		.si-editor .si-title a i{margin-right:5px}
		.si-editor .si-title a:hover{color:#1b1b1b}
		.si-editor .si-title a.hover{color:#1b1b1b}
		.si-editor .si-content{min-height:220px; height:auto; overflow:auto; outline:none; padding:20px; line-height:2em}
		.si-editor .si-content .si-add-left{width:49%; float:left; border:2px dashed #F3F3F3; height:220px;}
		.si-editor .si-content .si-add-left:hover{border:2px dashed #D0D0D0}
		.si-editor .si-content .si-add-a{ background:url({__PW_PATH__}addons/wechat/admin/template/images/tuwen_editor_add.png) center center no-repeat; color:#999;width:150px;text-align:center;height:0px; padding-top:30px; line-height:2em; display:block; margin:0px auto; margin-top:100px;}
		.si-editor .si-content .si-add-a:hover{ text-decoration:underline;}
		.si-editor .si-content .si-add-right{width:49%; float:right; border:2px dashed #F3F3F3; height:220px;}
		.si-editor .si-content .si-add-right:hover{border:2px dashed #D0D0D0}
		
		.si-btn{clear:both; margin:20px 0px; padding:20px; padding-top:0px; line-height:2em}
		.si-btn p{padding:10px 0px}
		
		.material-list{width:98%; margin:8px auto; clear:both;}
		.material-list ul{position:relative;list-style:none; clear:both; width:100%}
		.material-list ul li{width:300px; border:1px solid #F3F3F3; height:auto; overflow:auto; position:relative; float:left; margin:10px; cursor:pointer}
		.material-list ul li a{color:#333333}
		.material-list ul li .date{width:268px;font-size:12px; color:#999999; margin:8px auto; margin-bottom:0px}
		.material-list ul li .title{width:268px;margin:0px auto;font-size: 14px;font-weight: 400; line-height:2em; padding:10px 0px}
		.material-list ul li .desc{width:268px;margin:0px auto;font-size: 14px;font-weight: 400; line-height:2em; padding:10px 0px}
		.material-list ul li .appmsg_thumb_wrp{width:268px; height:160px; margin:0px auto;overflow: hidden;background-repeat: no-repeat; background-position: center center;background-size: cover; position:relative}
		.material-list ul li .appmsg_thumb_wrp span{position:absolute; width:100%; left:0px; bottom:0px; background: rgba(0,0,0,0.5)!important; color:#FFFFFF; font-size:14px; padding:3px 4px}
		.material-list ul li .appmsg_thumb_wrp span a{color:#FFFFFF}
		
		.material-list ul li .appmsg_thumb_wrp .left-div{
		background:url("{__PW_PATH__}addons/wechat/admin/template/images/voice.gif"); width:60px; height:60px; margin:10px; float:left}
		.material-list ul li .appmsg_thumb_wrp .right-div{float:left; margin:10px 0px; width:180px;}
		
		.material-list ul li .small-div{border-bottom:1px solid #F3F3F3; clear:both; clear:both; width:268px; padding:10px 0px; margin:0px auto; height:80px;}
		.material-list ul li .small-div .small-div-title{float:left; width:180px; line-height:2em;font-size: 14px;font-weight: 400;}
		.material-list ul li .small-div .small-div-image{float:right; width:80px; height:80px}
		.material-list ul li .small-div .small-div-image img{width:80px; height:80px}
		
		#emoji{position:relative; cursor:pointer}
		.emojidiv{position:absolute; left:0px; bottom:24px; padding:5px; background:#FFFFFF;  border: 1px solid #CCCCCC;box-shadow: 0px 1px 3px #ccc; display:none}
		.emojidiv ul{clear:both; width:700px; padding:0px; margin:0px; clear:both}
		.emojidiv ul li{float:left;width:22px; height:22px; text-align:center; line-height:22px; padding:2px; border:#fff 1px solid; margin:4px; text-align:center; cursor:pointer}
		.emojidiv ul li:hover{border-color:#FFCC00}
	</style>
</head>
<body>
    <div class="ifame-main-wrap">
        <div class="crumb-wrap">
            <div class="crumb-list"><i class="icon-font"></i><a href="{__PW_PATH__}{__ADMIN_FILE__}?file=index&action=main">管理首页</a><span class="crumb-step">&gt;</span><span class="crumb-name">群发信息</span></div>
        </div>
        <div class="result-wrap">
            <form action="" method="post" name="mysubmitform" id="mysubmitform" enctype="multipart/form-data">
            	<input type="hidden" value="1" name="dosubmit">
                <input type="hidden" value="media" name="wechat_masssend_msg_type" id="wechat_masssend_msg_type">
                <input type="hidden" name="wechat_masssend_msg_value" id="wechat_masssend_msg_value">
                <div class="config-items">     
					<div class="admin-nav">
                        <h2>群发信息</h2>
                        <div class="nav">
                            <a href="{__PW_PATH__}{__ADMIN_FILE__}?mod={$mod}&file={$file}&action={$action}" class="hover">群发信息</a>
                            <a href="{__PW_PATH__}{__ADMIN_FILE__}?mod={$mod}&file={$file}&action=masssend_list">已发送</a>
                        </div>
                        <div class="admin-tips">
                        	为保障用户体验，微信公众平台严禁恶意营销以及诱导分享朋友圈，严禁发布色情低俗、暴力血腥、政治谣言等各类违反法律法规及相关政策规定的信息。一旦发现，我们将严厉打击和处理。
                        </div>
                    </div>
                    <div class="si-editor">
                    	<div class="si-title">
                        	<a href="javascript:void(0);" id="title_media" onClick="toggle_msg_type('media');" class="hover"><i class="icon-font">&#xe033;</i>图文消息</a> 
                            <a href="javascript:void(0);" id="title_text" onClick="toggle_msg_type('text');" ><i class="icon-font">&#xe002;</i>文字</a> 
                            <a href="javascript:void(0);" id="title_image" onClick="toggle_msg_type('image');"><i class="icon-font">&#xe029;</i>图片</a> 
                            <a href="javascript:void(0);" id="title_voice" onClick="toggle_msg_type('voice');"><i class="icon-font">&#xe035;</i>语音</a>  
                            <a href="javascript:void(0);" id="title_video" onClick="toggle_msg_type('video');"><i class="icon-font">&#xe044;</i>视频</a>
                        </div>                        
                        <div class="si-content" id="material-dialog-media">
                        	<div class="si-add-left">
                            	<a  href="javascript:void(0);" data-reveal-id="material-dialog"  data-animation="fade" class="si-add-a">从素材库中选择</a>
                            </div>
                            <div class="si-add-right">
                            	<a href="{__PW_PATH__}{__ADMIN_FILE__}?mod=wechat&file=config&action=material_post" class="si-add-a">新建图文消息</a>
                            </div>
                        </div>
                        <div class="si-content" id="material-dialog-text" style="display:none">
                        	<textarea cols="55" rows="6" id="wechat_masssend_msg_text" style="width:850px; height:250px; border:0px; font-family:'Microsoft YaHei';overflow:auto;resize:none; background:none" name="wechat_masssend_msg_text"></textarea>
                            <br>
                            <span id="emoji" onClick="$('#emojidiv').toggle();"><i class="icon-font">&#xe038;</i> 添加表情
							<div class="emojidiv" id="emojidiv">
								<ul>
									{loop WeChatManage::getEmojiList() $r}
									<li onClick="$('#wechat_subscribe_msg_text').insertContent('[{$r['emoji']}]'); "><img src="{__PW_PATH__}addons/wechat/admin/template/images/emoji/{$r['emoji']}.png"></li>
									{/loop}
								</ul>
							</div>
							</span>
                        </div>
                        <div class="si-content" id="material-dialog-image" style="display:none">
                       		<div class="si-add-left">
                            	<a  href="javascript:void(0);" data-reveal-id="image-material-dialog"  data-animation="fade" class="si-add-a">从素材库中选择</a>
                            </div>
                            <div class="si-add-right">
                            	<a href="{__PW_PATH__}{__ADMIN_FILE__}?mod=wechat&file=config&action=material_image" class="si-add-a">新建图片消息</a>
                            </div>
                        </div>

                        <div class="si-content" id="material-dialog-voice" style="display:none">
                       		<div class="si-add-left">
                            	<a  href="javascript:void(0);" data-reveal-id="voice-material-dialog"  data-animation="fade" class="si-add-a">从素材库中选择</a>
                            </div>
                            <div class="si-add-right">
                            	<a href="{__PW_PATH__}{__ADMIN_FILE__}?mod=wechat&file=config&action=material_voice" class="si-add-a">新建语音消息</a>
                            </div>
                        </div>
                        <div class="si-content" id="material-dialog-video" style="display:none">
                       		<div class="si-add-left">
                            	<a  href="javascript:void(0);" data-reveal-id="video-material-dialog"  data-animation="fade" class="si-add-a">从素材库中选择</a>
                            </div>
                            <div class="si-add-right">
                            	<a href="{__PW_PATH__}{__ADMIN_FILE__}?mod=wechat&file=config&action=material_video" class="si-add-a">新建视频消息</a>
                            </div>
                        </div>
                        <div class="si-btn">
                        <p>
                        按用户标签群发：
                        <select name="tag_id">
                        	<option value="-1">全部用户</option>
                            {loop WeChatManage::groupLimitList() $r}
                            <option value="{$r['id']}">{$r['name']}</option>
                            {/loop}
                        </select>
                        </p>
                        <p>
                        <input type="button" onClick="doSubmit('mysubmitform','')" value="群 发" class="submit-button">
                        <input type="button" value="返 回" onClick="history.go(-1)" class="reset-button">
                        </p>
                        </div>
                    </div>
				</div>
           	</form>
        </div>
    </div>
    
    <div id="material-dialog" class="reveal-modal" style="width:95%;  left:320px">
		<h1>图文素材</h1>
        <div class="material-list" style=" height:520px; overflow:auto">
            <ul id="container">
                <?php $no=1;if(is_array(WeChatManage::materialJqueryList(0,100)))foreach(WeChatManage::materialJqueryList(0,25) as $r){?>
                <?php $c=WeChatManage::getMutiMaterial($r['id']);?>
				<?php if($c) { ?>
				<li class="grid" onClick="$('#material-dialog-media').html('<div class=material-list><ul><li data-reveal-id=material-dialog  data-animation=fade>'+$(this).html()+'</li></ul></div>');
                $('#wechat_masssend_msg_value').val('<?php echo $r['media_id'];?>');$('.close-reveal-modal').click();">
					<div class="title"><?php echo date('Y年m月d日',$r['UpdateTime']);?></div>
					<div class="appmsg_thumb_wrp" style="background-image:url('<?php echo format_url($r['PicUrl']);?>')"><span><?php echo isset($r['Title'])?$r['Title']:'';?></span></div>
					<?php $no=1;if(is_array($c))foreach($c as $_c){?>
					<div class="small-div">
						<div class="small-div-title">
							<?php echo isset($_c['Title'])?$_c['Title']:'';?>
						</div>
						<div class="small-div-image">
							<img src="<?php echo format_url($_c['PicUrl']);?>"/>
						</div>
					</div>
					<?php $no++;}?>
				</li>
				<?php }else{ ?>
				<li  class="grid" onClick="$('#material-dialog-media').html('<div class=material-list><ul><li data-reveal-id=material-dialog  data-animation=fade>'+$(this).html()+'</li></ul></div>');
                $('#wechat_masssend_msg_value').val('<?php echo $r['media_id'];?>');$('.close-reveal-modal').click();">
					<div class="date"><?php echo date('m月d日',$r['UpdateTime']);?></div>
					<div class="title"><?php echo isset($r['Title'])?$r['Title']:'';?></div>
					<div class="appmsg_thumb_wrp" style="background-image:url('<?php echo format_url($r['PicUrl']);?>')"></div>
					<div class="desc"><?php echo isset($r['Description'])?$r['Description']:'';?></div>
				</li>
				<?php }?>
                <?php $no++;}?>
            </ul>
        </div>
		<a class="close-reveal-modal">&#215;</a>
	 </div>
     <div id="image-material-dialog" class="reveal-modal" style="width:95%;  left:320px">
		<h1>图片素材</h1>
        <div class="material-list" style=" height:520px; overflow:auto">
            <ul>
            	{loop WeChatManage::imageMaterialJqueryList(1,100) $r}
				<li style="width:155px;" onClick="$('#material-dialog-image').html('<div class=material-list><ul><li style=width:155px; data-reveal-id=image-material-dialog  data-animation=fade>'+$(this).html()+'</li></ul></div>');
                $('#wechat_masssend_msg_value').val('<?php echo $r['media_id'];?>');$('.close-reveal-modal').click();"><img src="{format_url($r['local_url'])}" style="width:155px; height:152px" /></li>
				{/loop}
            </ul>
        </div>
        <a class="close-reveal-modal">&#215;</a>
     </div>
     <div id="voice-material-dialog" class="reveal-modal" style="width:95%;  left:320px">
		<h1>语音素材</h1>
        <div class="material-list" style=" height:520px; overflow:auto">
            <ul>
            	{loop WeChatManage::voiceMaterialJqueryList(1,100) $r}
				<li onClick="$('#material-dialog-voice').html('<div class=material-list><ul><li data-reveal-id=voice-material-dialog  data-animation=fade>'+$(this).html()+'</li></ul></div>');
                $('#wechat_masssend_msg_value').val('<?php echo $r['media_id'];?>');$('.close-reveal-modal').click();">
					<div class="date">{date('m月d日',$r['created_at'])}</div>
					<div class="appmsg_thumb_wrp" style="height:80px;">
						<div class="left-div"></div>
						<div class="right-div">{$r['name']}</div>
					</div>
				</li>
				{/loop}
            </ul>
        </div>
        <a class="close-reveal-modal">&#215;</a>
     </div>
     <div id="video-material-dialog" class="reveal-modal" style="width:95%;  left:320px">
		<h1>视频素材</h1>
        <div class="material-list" style=" height:520px; overflow:auto">
            <ul>
            	{loop WeChatManage::videoMaterialJqueryList(1,100) $r}
				<li onClick="$('#material-dialog-video').html('<div class=material-list><ul><li data-reveal-id=video-material-dialog  data-animation=fade>'+$(this).html()+'</li></ul></div>');
                $('#wechat_masssend_msg_value').val('<?php echo $r['media_id'];?>');$('.close-reveal-modal').click();">
					<div class="title">{date('Y年m月d日',$r['created_at'])}</div>
                    <div class="appmsg_thumb_wrp" style="background-image:url('{__PW_PATH__}addons/wechat/admin/template/images/video.gif');background-size: inherit;"><span><a href="{$r['remote_url']}" target="_blank">{$r['name']}</a></span></div>
				</li>
				{/loop}
            </ul>
        </div>
        <a class="close-reveal-modal">&#215;</a>
     </div>
</body>
</html>