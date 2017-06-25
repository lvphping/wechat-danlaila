/**
 * 换一张验证码 
 */
function getCaptcha(captchaid)
{
	$('#'+captchaid).attr('src',PW_PATH+'api/captcha/index.php?t='+Math.random());
	return ;
}

/**
 * 跳转链接 
 */
function urlRedirect(url)
{
	if(url=='')
	{
		self.history.back();
	}
	else 
	{
		self.location.href=url;
	}
}

/**
 * 自动调整高度 
 */
function autoHeight(objid,offset)
{
	try{
			$('#'+objid).height(($(window).height()-offset));
		}catch(e){}
}

/**
 * 图片上传切换表单action 
 */
function selectImageBtn(id,originalname)
{
	$('#upload_file'+id).parents('form').attr('action',PW_PATH+'api/upload/index.php?action=imageupload&originalname='+originalname+'&upload_hidden_val=upload_hidden_val'+id+'&upload_file=upload_file'+id+'&upload_loading=upload_loading'+id+'&img_demo=img_demo'+id);
	$('#upload_file'+id).parents('form').attr('target','upload_iframe'+id);
	$('#upload_path'+id).text($("#upload_file"+id).val());
	$('#upload_loading'+id).html('<img src="'+PW_PATH+'statics/images/loading.gif" />');
	$('#upload_file'+id).parents('form').submit();
}

/**
 * 视频上传切换表单action 
 */
function selectVideoBtn(id)
{
	$('#video_file'+id).parents('form').attr('action',PW_PATH+'api/upload/index.php?action=videoupload&video_file=video_file'+id+'&video_value=video_value'+id+'&video_upload_btn=video_upload_btn'+id);
	$('#video_file'+id).parents('form').attr('target','video_upload_iframe'+id);
	$('#video_upload_btn'+id).text('上传中...');
	$('#video_upload_btn'+id).removeClass('video_upload_btn');
	$('#video_upload_btn'+id).addClass('video_upload_btn_loading');
	$('#video_file'+id).parents('form').submit();
}

/**
 * 附件上传切换表单action 
 */
function selectAttachmentBtn(id)
{
	$('#attachment_file'+id).parents('form').attr('action',PW_PATH+'api/upload/index.php?action=attachmentupload&attachment_file=attachment_file'+id+'&attachment_value=attachment_value'+id+'&attachment_upload_btn=attachment_upload_btn'+id);
	$('#attachment_file'+id).parents('form').attr('target','attachment_upload_iframe'+id);
	$('#attachment_upload_btn'+id).text('上传中...');
	$('#attachment_upload_btn'+id).removeClass('attachment_upload_btn');
	$('#attachment_upload_btn'+id).addClass('attachment_upload_btn_loading');
	$('#attachment_file'+id).parents('form').submit();
}

/**
 * 安全证书切换表单action 
 */
function selectFormfileBtn(id)
{
	$('#formfile_upload_btn'+id).text('已选择');
}

function doSubmit(id,action)
{
	$('#'+id).attr('action',action);
	$('#'+id).attr('target','_self');
	$('#'+id).submit();
}

function getCityOption(provinceid,cityid)
{
	var postUrl=PW_PATH+'api/area/index.php?action=getcity&provinceid='+provinceid;
	$.get(postUrl, function(data){
		$('#'+cityid).html(data);
		$('#'+cityid).attr('disabled',false);
	});
}

function getAreaOption(cityid,areaid)
{
	var postUrl=PW_PATH+'api/area/index.php?action=getarea&cityid='+cityid;
	$.get(postUrl, function(data){
		$('#'+areaid).html(data);
		$('#'+areaid).attr('disabled',false);
	});
}

/**
 * 全选/反选
 */
$(function(){
           $("#checkAll").click(function() {
                $('input[la="checkbox"]').attr("checked",this.checked); 
            });
        });

function isCheckboxChecked()
{
	var re=false;
	$('input[la="checkbox"]').each(function(){
	   if($(this).attr('checked'))
	   {
		   re=true;
		   return true;
	   }
	 });
	
	return re;
}
 
function panelToggle(id,no,panel)
{
	for(var i=1;i<=no;i++)
	{
		$('#title_'+panel+'_'+i).removeClass('hover');
		$('#content_'+panel+'_'+i).hide();
	}
	$('#title_'+panel+'_'+id).addClass('hover');
	$('#content_'+panel+'_'+id).show();
}

var icount=1;
function showToast(msg,url,duration)
{
	if(icount>1)
	{
		return false;	
	}
	icount++;
	duration=isNaN(duration)?1000:duration;
	msg=msg?msg:'操作成功';
	var m = document.createElement('div');
	m.innerHTML = msg;
	m.style.cssText="width:60%; min-width:150px; background:#000; opacity:0.5; min-height:40px; color:#fff; line-height:40px; text-align:center; border-radius:5px; position:fixed; top:40%; left:20%; z-index:999999;font-size:.6rem; font-weight:normal;";
    document.body.appendChild(m);
	setTimeout(function() {
		var d = 0.5;
        m.style.webkitTransition = '-webkit-transform ' + d + 's ease-in, opacity ' + d + 's ease-in';
        m.style.opacity = '0';
		icount=1;
		setTimeout(function() { document.body.removeChild(m) }, d * 1000); if(url){self.top.location.href=url;}else{return false;}
	}, duration);
}
