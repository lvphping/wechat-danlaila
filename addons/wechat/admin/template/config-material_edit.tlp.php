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
		.wechat-editor{width:98%; margin:16px auto; clear:both; clear:both}
		.wechat-editor .editor-left{width:310px; float:left; border:1px solid #e7e7eb;background:#f8f8f8; padding-bottom:10px}
		.wechat-editor .editor-left h1{font-size:14px; font-weight:normal; padding:10px;}
		.wechat-editor .editor-left .all-tuwen{width:290px; margin:0px auto; border:1px solid #e7e7eb; background:#FFFFFF; padding-bottom:10px;}
		.wechat-editor .editor-left .all-tuwen .big-tuwen{width:270px; margin:10px auto; margin-bottom:0px; position:relative; overflow:hidden}
		.wechat-editor .editor-left .all-tuwen .big-tuwen span{position:absolute; width:100%; left:0px; bottom:0px; background: rgba(0,0,0,0.5)!important; color:#FFFFFF; font-size:14px; padding:3px 4px}
		.wechat-editor .editor-left .all-tuwen .big-tuwen img{width:270px; height:150px;}
		
		.big-tuwen-hover{ position:absolute; left:0px; top:0px; width:100%; height:100%; background:#333;filter:alpha(opacity=50); text-align:center; padding-top:25%; -moz-opacity:0.50; opacity:0.50;display:none;  }
		.big-tuwen-hover a{border:#ffffff 1px solid; text-decoration:none; color:#FFFFFF; padding:4px; }
		
		.wechat-editor .editor-left .all-tuwen .big-tuwen:hover .big-tuwen-hover{display:block}
		
		.wechat-editor .editor-left .small-tuwen{width:270px; margin:0px auto;  border-top:0px; background:#FFFFFF; padding:10px 0px;border-bottom:1px solid #e7e7eb; position:relative; height:65px;clear:both;overflow:hidden;}
		.wechat-editor .editor-left .small-tuwen .small-title{float:left; width:190px;}
		.wechat-editor .editor-left .small-tuwen .small-img{float:right; width:65px;}
		.wechat-editor .editor-left .small-tuwen .small-img img{width:65px; height:65px;}
		
		.small-tuwen-hover{ position:absolute; left:0px; top:0px; width:100%; height:100%; background:#333;filter:alpha(opacity=50); color:#FFFFFF; text-align:center; padding-top:35px; -moz-opacity:0.50; opacity:0.50; display:none; }
		.small-tuwen-hover span{font-size:18px; font-weight:bold; padding:0px 5px; cursor:pointer; margin-top:-5px}
		.small-tuwen-hover a{border:#ffffff 1px solid; text-decoration:none; color:#FFFFFF; padding:4px;}
		
		.wechat-editor .editor-left .small-tuwen:hover .small-tuwen-hover{display:block}
		
		.wechat-editor .editor-left .add-tuwen{width:270px; margin:0px auto; background:#f8f8f8}
		.wechat-editor .editor-left .add-tuwen a{width:266px; border:2px dashed #e7e7eb; border-top:0px;display:block; height:55px; line-height:55px; text-align:center; margin:0px auto; color:#1b1b1b}
		
		.wechat-editor .editor-middel{float:left; margin-left:18px;border:1px solid #F3F3F3; position:relative; width:655px}
		.wechat-editor .editor-middel .arrow{ position:absolute; left:-29px; top:115px;}
		span.btn-from-media{position:absolute; top:9px; right:10px}
		span.btn-from-media a{border:#e7e7eb 1px solid; display:block; width:125px; height:30px; text-align:center;color:#333333; line-height:30px}
		
		.wechat-editor .editor-right{ float:left; width:190px; margin-left:16px}
		.wechat-editor .editor-right h1{font-size:14px; font-weight:normal; padding:10px;}
		.wechat-editor .editor-right ul{clear:both}
		.wechat-editor .editor-right ul li a{display:block; border:#e7e7eb 1px solid; font-size:15px; padding-left: 50px;line-height: 40px; color:#333333; background:url({__PW_PATH__}addons/wechat/admin/template/images/appmsg_new_z2ac3d8.png) 20px 12px no-repeat}
		.wechat-editor .editor-right ul li a:hover{background-position:20px -212px; border:#09bb07 1px solid; color:#09bb07}
		
		.reveal-modal ul{clear:both; list-style:none; overflow:auto; width:875px; height:505px;}
		.reveal-modal ul li{border:#e7e7eb 1px solid; padding:3px; float:left; margin:5px; margin-right:5px;}
		.reveal-modal ul li img{width:152px; height:150px}
		
	</style>
</head>
<body>
    <div class="ifame-main-wrap">
        <div class="crumb-wrap">
            <div class="crumb-list"><i class="icon-font"></i><a href="{__PW_PATH__}{__ADMIN_FILE__}?file=index&action=main">管理首页</a><span class="crumb-step">&gt;</span><span class="crumb-name">修改图文素材</span></div>
        </div>
        <div class="wechat-editor">
			<form action="" method="post" name="mysubmitform" id="mysubmitform" enctype="multipart/form-data">
			<input type="hidden" name="dosbumit" value="1">
			<input type="hidden" name="id" value="{$id}">
			<div class="editor-left">
				<h1>图文列表</h1>
				<div class="all-tuwen">
					<div class="big-tuwen">
						<input type="hidden" name="Title[0]" value="{$parentdata['Title']}" id="title_0" />
						<input type="hidden" name="Author[0]" value="{$parentdata['Author']}" id="author_0" />
						<input type="hidden" name="Description[0]" value="{$parentdata['Description']}" id="description_0" />
						<input type="hidden" name="PicUrl[0]" value="{$parentdata['PicUrl']}" id="picurl_0" />
						<input type="hidden" name="thumb_media_id[0]" value="{$parentdata['thumb_media_id']}" id="thumb_media_id_0" />
						<input type="hidden" name="content[0]" value="{htmlspecialchars($parentdata['content'])}" id="content_0" />
						<input type="hidden" name="urls[0]" value="{$parentdata['Url']}" id="url_0" />
						<img id="img_0" src="{format_url($parentdata['PicUrl'])}" /><span id="title_span_0">{$parentdata['Title']}</span>
						<div class="big-tuwen-hover">
							<a href="javascript:void(0);" onClick="editMsg(0);">编辑</a>
						</div>
					</div>
					{loop $childdata $key $r}
					{php $key=$key+1;}
					<div class="small-tuwen" data-id="{$key}" id="small-tuwen-{$key}">
						<input type="hidden" name="Title[{$key}]" value="{$r['Title']}" id="title_{$key}">
						<input type="hidden" name="Author[{$key}]" value="{$r['Author']}" id="author_{$key}">
						<input type="hidden" name="Description[{$key}]" value="{$r['Description']}" id="description_{$key}">
						<input type="hidden" name="PicUrl[{$key}]" value="{$r['PicUrl']}" id="picurl_{$key}">
						<input type="hidden" name="thumb_media_id[{$key}]" value="{$r['thumb_media_id']}" id="thumb_media_id_{$key}">
						<input type="hidden" name="content[{$key}]" value="{htmlspecialchars($r['content'])}" id="content_{$key}">
						<input type="hidden" name="urls[{$key}]" value="{$r['urls']}" id="url_{$key}">
						<div class="small-title">
						<span id="title_span_{$key}">{$r['Title']}</span>
						</div>
						<div class="small-img">	
						<img id="img_{$key}" src="{format_url($r['PicUrl'])}">
						</div>
						<div class="small-tuwen-hover"><span style="float:left; margin-left:8px" onClick="moveUp({$key});">↑</span>	<a href="javascript:void(0);" onClick="editMsg({$key});">编辑</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:void(0);" onClick="deleteMsg({$key});">删除</a>  <span style="float:right; margin-right:8px" onClick="moveDown({$key});">↓</span>
						</div>
					</div>
					{/loop}
					<div class="add-tuwen">
						<a href="javascript:void(0);"><img  src="{__PW_PATH__}addons/wechat/admin/template/images/tuwen_editor_add.png" /></a>
					</div>
				</div>
			</div>
			<div class="editor-middel">
				<span class="arrow"><img src="{__PW_PATH__}addons/wechat/admin/template/images/area_arrow.png"></span>
				<table width="100%" class="insert-tab">
					<tbody>
						<tr class="formtr">
							<th class="formth" width="20%"><i class="require-red">*</i>标题：</th>
							<td class="formtd"><input type="text"  style="width:493px;" value="{$parentdata['Title']}" name="p_title" id="p_title" class="common-text"></td>
						</tr>
						<tr class="formtr">
							<th class="formth">作者：</th>
							<td class="formtd"><input type="text" size="32" name="p_author" value="{$parentdata['Author']}" id="p_author" class="common-text"></td>
						</tr>
						<tr class="formtr">
							<th class="formth"><i class="require-red">*</i>封面图片：</th>
							<td class="formtd" style="position:relative">
							{php echo Form::image('封面图片','p_picurl',format_url($parentdata['PicUrl']));}
							<span class="btn-from-media"><a href="javascript:void(0);" data-reveal-id="material-dialog-thumb"  data-animation="fade">从素材库添加</a></span>
							</td>
						</tr>
						<tr class="formtr">
							<th class="formth">摘要：</th>
							<td class="formtd">
							<textarea class="common-textarea" style="width:493px; height:120px;" name="p_description" id="p_description">{$parentdata['Description']}</textarea>
							</td>
						</tr>
						<tr class="formtr">
							<th class="formth"><i class="require-red">*</i>正文：</th>
							<td class="formtd" style="padding:10px">
							{php echo Form::baiduEditor('正文','p_content',$parentdata['content']);}
							
							</td>
						</tr>
						<tr class="formtr">
							<th class="formth" width="20%">外链：</th>
							<td class="formtd"><input type="text" style="width:493px;" name="p_url" id="p_url" value="{$parentdata['Url']}" class="common-text"></td>
						</tr>
						<tr class="formtr">
							<th class="formth"></th>
							<td class="formtd">
								<input type="button" onClick="if(checkForm()){doSubmit('mysubmitform','');}else{return false}" value="提 交" class="submit-button">
								<input type="button" value="返 回" onClick="history.go(-1)" class="reset-button">
							</td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="editor-right">
				<h1>多媒体素材</h1>
				<ul>
					<li><a href="javascript:void(0);" data-reveal-id="material-dialog"  data-animation="fade">图片</a></li>
				</ul>
			</div>
			</form>
		</div>
    </div>
	<script language="javascript" type="text/javascript">
	var curCount;
	var cur={php echo sizeof($childdata)+1};
	var nowcur=0;
		
	$(document).ready(function(){
		if(screen.width<1600)
		{
			$('.editor-right').hide();
		}	
		
		$("#p_title").keyup(function(){
			$('#title_'+nowcur).val($("#p_title").val());
			$('#title_span_'+nowcur).text($("#p_title").val());
		});
		$("#p_author").keyup(function(){
			$('#author_'+nowcur).val($("#p_author").val());
		});
		$("#p_description").keyup(function(){
			$('#description_'+nowcur).val($("#p_description").val());
		});
		$("#p_url").keyup(function(){
			$('#url_'+nowcur).val($("#p_url").val());
		});
		
		$("#upload_iframep_picurl").on("load",function(){
			$('#thumb_media_id_'+nowcur).val('');
			$('#picurl_'+nowcur).val($("#upload_hidden_valp_picurl").val());
			$('#img_'+nowcur).attr('src',$("#upload_hidden_valp_picurl").val());
		});
		
		um.addListener("contentChange",function(){
		
			$('#content_'+nowcur).val(um.getContent());
		});	
		
		$(".add-tuwen").click(function(){
			
			curCount = $('.small-tuwen').size();

			if(curCount>=6)
			{
				$(".add-tuwen").hide();
			}
			
			var html='<div class="small-tuwen" data-id="'+cur+'" id="small-tuwen-'+cur+'">'+
						'<input type="hidden" name="Title['+cur+']" id="title_'+cur+'" />'+
						'<input type="hidden" name="Author['+cur+']" id="author_'+cur+'" />'+
						'<input type="hidden" name="Description['+cur+']" id="description_'+cur+'" />'+
						'<input type="hidden" name="PicUrl['+cur+']" id="picurl_'+cur+'" />'+
						'<input type="hidden" name="thumb_media_id['+cur+']" id="thumb_media_id_'+cur+'" />'+
						'<input type="hidden" name="content['+cur+']" id="content_'+cur+'" />'+
						'<input type="hidden" name="urls['+cur+']" id="url_'+cur+'" />'+
						'<div class="small-title">'+
						'<span  id="title_span_'+cur+'">标题</span>'+
						'</div>'+
						'<div class="small-img">'+
						'	<img id="img_'+cur+'" src="{__PW_PATH__}addons/wechat/admin/template/images/no_cover_pic.png" />'+
						'</div>'+
						'<div class="small-tuwen-hover">'+
						'<span style="float:left; margin-left:8px" onclick="moveUp('+cur+');">↑</span>	<a href="javascript:void(0);" onclick="editMsg('+cur+');">编辑</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:void(0);" onclick="deleteMsg('+cur+');">删除</a>  <span style="float:right; margin-right:8px" onclick="moveDown('+cur+');">↓</span>'+
						'</div>'+
					'</div>';
			$(html).insertBefore($('.add-tuwen'));
			cur++;
		});
	});
	
	function editMsg(id)
	{
		nowcur=id;
		$('#p_title').val($('#title_'+id).val());
		$('#p_author').val($('#author_'+id).val());
		$('#p_description').val($('#description_'+id).val());
		$('#upload_hidden_valp_picurl').val($('#picurl_'+id).val());
		
		if($('#picurl_'+id).val())
		{
			$('#img_demop_picurl').attr('src',$('#picurl_'+id).val());
		}
		else
		{
			$('#img_demop_picurl').attr('src','{__PW_PATH__}statics/images/demo.png');
		}
		
		um.setContent($('#content_'+id).val());
		$('#p_url').val($('#url_'+id).val());
		
		if(id==0)
		{
			var index=id;
		}
		else
		{
			var index=$('#small-tuwen-'+id).index();
		}
		
		if(index==0){
			$('.editor-middel').css('margin-top',0);
		}else if(index==1){
			$('.editor-middel').css('margin-top',110);
		}
		else
		{
			$('.editor-middel').css('margin-top',index*85+42);
		}
	}
	
	function moveUp(id)
	{
		var index=$('#small-tuwen-'+id).index();

		if(index==1)
		{
			var title_0=$('#title_0').val();
			var author_0=$('#author_0').val();
			var description_0=$('#description_0').val();
			var thumb_media_id_0=$('#thumb_media_id_0').val();
			var picurl_0=$('#picurl_0').val();
			var content_0=$('#content_0').val();
			
			$('#title_0').val($('#title_'+id).val());
			$('#author_0').val($('#author_'+id).val());
			$('#description_0').val($('#description_'+id).val());
			$('#thumb_media_id_0').val($('#thumb_media_id_'+id).val());
			$('#picurl_0').val($('#picurl_'+id).val());
			$('#content_0').val($('#content_'+id).val());
			
			if($('#picurl_'+id).val())
			{
				$('#img_0').attr('src',$('#picurl_'+id).val());
			}
			else
			{
				$('#img_0').attr('src','{__PW_PATH__}addons/wechat/admin/template/images/no_cover_pic.png');
			}
			$('#title_span_0').text($('#title_'+id).val());
			
			//$('#small-tuwen-'+id).remove();
			$('#title_'+id).val(title_0);
			$('#author_'+id).val(author_0);
			$('#description_'+id).val(description_0);
			$('#thumb_media_id_'+id).val(thumb_media_id_0);
			$('#picurl_'+id).val(picurl_0);
			$('#content_'+id).val(content_0);
			
			if($('#picurl_0').val())
			{
				$('#img_'+id).attr('src',picurl_0);
			}
			else
			{
				$('#img_'+id).attr('src','{__PW_PATH__}addons/wechat/admin/template/images/no_cover_pic.png');
			}
			$('#title_span_'+id).text(title_0);
		}
		else
		{
			$('#small-tuwen-'+id).prev('.small-tuwen').before($('#small-tuwen-'+id));
		}	
	}
	
	function moveDown(id)
	{
		var index=$('#small-tuwen-'+id).index();

		if(index==$('.small-tuwen').size())
		{
			alert('已经是最后1张！');
		}
		else
		{
			$('#small-tuwen-'+id).next('.small-tuwen').after($('#small-tuwen-'+id));
		}
	}
	
	function deleteMsg(id)
	{
		if(!confirm('确定删除这条图文吗？'))
		{
			return false;
		}
		$('#small-tuwen-'+id).remove();
	}
	
	function checkForm()
	{
		re=true;
		
		if($.trim($('#title_0').val())=='')
		{
			alert('请输入标题！');
			$('#p_title').focus();
			editMsg(0);
			return false;
		}
		
		if($('#picurl_0').val()=='')
		{
			alert('请上传封面图片！');
			editMsg(0);
			return false;
		}
		
		if($('#content_0').val()=='')
		{
			alert('请输入内容！');
			editMsg(0);
			um.focus();
			return false;
		}
			
		$('.small-tuwen').each(function(){
			var id=$(this).attr('data-id');

			if($.trim($('#title_'+id).val())=='')
			{
				alert('请输入标题！');
				$('#p_title').focus();
				editMsg(id);
				re=false;
				return false;
			}
			
			if($('#picurl_'+id).val()=='')
			{
				alert('请上传封面图片！');
				editMsg(id);
				re=false;
				return false;
			}
			
			if($('#content_'+id).val()=='')
			{
				alert('请输入内容！');
				editMsg(id);
				re=false;
				um.focus();
				return false;
			}
		});

		return re;
	}
	
	function insertImageToContent(remote_url)
	{
		um.execCommand('inserthtml', '<img src="'+remote_url+'" />');
		$('.close-reveal-modal').click();
	}
	
	function insertImageToThumb(media_id,local_url)
	{
		$('#thumb_media_id_'+nowcur).val(media_id);
		$('#picurl_'+nowcur).val(local_url);
		$('#img_'+nowcur).attr('src',local_url);
		$('#upload_iframep_picurl').val(local_url);
		$('#img_demop_picurl').attr('src',local_url);
		
		$('.close-reveal-modal').click();
	}
	</script>
	<div id="material-dialog-thumb" class="reveal-modal" style="width:880px; height:550px">
		<h1>图片素材</h1>
		<ul id="image-jquery-data-thumb">
			{loop $imageJuqeryList $r}
				<li><a href="javascript:void(0);" onClick="insertImageToThumb('{$r['media_id']}','{$r['local_url']}')"><img src="{format_url($r['local_url'])}" /></a></li>
			{/loop}
		</ul>
		<a class="close-reveal-modal">&#215;</a>
	</div>
	<div id="material-dialog" class="reveal-modal" style="width:880px; height:550px">
		<h1>图片素材</h1>
		<ul id="image-jquery-data">
			{loop $imageJuqeryList $r}
				<li><a href="javascript:void(0);" onClick="insertImageToContent('{$r['remote_url']}');"><img src="{format_url($r['local_url'])}" /></a></li>
			{/loop}
		</ul>
		<a class="close-reveal-modal">&#215;</a>
	</div>
</body>
</html>