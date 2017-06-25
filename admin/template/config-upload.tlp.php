{php !defined('IN_MANAGE') && exit('Access Denied!');use phpWeChat\Form;use phpWeChat\Upload;use phpWeChat\MySql;}
<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>phpWeChat后台管理</title>
    <link rel="stylesheet" type="text/css" href="{__PW_PATH__}admin/template/css/common.css"/>
    <link rel="stylesheet" type="text/css" href="{__PW_PATH__}admin/template/css/main.css"/>
    <link rel="stylesheet" type="text/css" href="{__PW_PATH__}statics/core.css?t=1"/>
    <link rel="stylesheet" type="text/css" href="{__PW_PATH__}statics/reveal/reveal.css"/>
    <script language="javascript" type="text/javascript">
		var PW_PATH='{__PW_PATH__}';
	</script>
	<script src="{__PW_PATH__}statics/jquery/jquery-1.12.2.min.js" language="javascript"></script>
    <script src="{__PW_PATH__}statics/reveal/jquery.reveal.js" language="javascript"></script>
	<script src="{__PW_PATH__}statics/core.js" language="javascript"></script>
    <script type="text/javascript" src="{__PW_PATH__}admin/template/js/libs/modernizr.min.js"></script>
    <script language="javascript">
		//$(document).ready(function(){
		//	alert($('.common-radio:checked').val());
		//	$('.common-radio').click(function(){
		//		alert($('.common-radio:checked').val());
		//	});
		//});
	</script>
</head>
<body>
    <div class="ifame-main-wrap">
        <div class="crumb-wrap">
            <div class="crumb-list"><i class="icon-font"></i><a href="{__PW_PATH__}{__ADMIN_FILE__}?file=index&action=main">管理首页</a><span class="crumb-step">&gt;</span><span class="crumb-name">上传配置</span></div>
        </div>
        <div class="result-wrap">
            <form action="" method="post" name="mysubmitform" id="mysubmitform" enctype="multipart/form-data">
            	<input type="hidden" value="1" name="dosubmit">
                <div class="config-items">
                    <div class="config-title">
                        <h1><i class="icon-font">&#xe018;</i>上传配置</h1>
                    </div>
                    <div class="result-content">
                        <table width="100%" class="insert-tab">
                            <tbody>
                                <tr class="formtr">
                                    <th class="formth" width="20%"><i class="require-red">*</i>上传文件大小限制：</th>
                                    <td class="formtd"><input type="text" value="{$PW['upload_size_limit']}" size="2" name="info[upload_size_limit]" class="common-text"> M</td>
                                </tr>

                                <tr class="formtr">
                                    <th class="formth"><i class="require-red">*</i>图片生成缩略图大小：</th>
                                    <td class="formtd">
                                    中等缩略图宽度 <input type="text" value="{$PW['upload_thumb1_width']}" size="2" name="info[upload_thumb1_width]" class="common-text"> Px
                                    &nbsp;&nbsp;
                                    小缩略图宽度 <input type="text" value="{$PW['upload_thumb2_width']}" size="2" name="info[upload_thumb2_width]" class="common-text"> Px
                                    </td>
                                </tr>
                                <tr class="formtr">
                                    <th class="formth"><i class="require-red">*</i>图片是否启用水印：</th>
                                    <td class="formtd">
                                    <label><input type="radio" class="common-radio" onClick="$('#uploadwatermarkwidthheight').show();" name="info[upload_watermark_enable]" {php echo $PW['upload_watermark_enable']?' checked="checked"':'';} value="1" />启用</label> &nbsp;&nbsp;
                                    <label><input type="radio" class="common-radio" onClick="$('#uploadwatermarkwidthheight').hide();" name="info[upload_watermark_enable]" {php echo $PW['upload_watermark_enable']?'':' checked="checked"';} value="0" />禁用</label></td>
                                </tr>
                                <tbody id="uploadwatermarkwidthheight" style="display:{$PW['upload_watermark_enable']?'':'none'}">
                                <tr class="formtr">
                                    <th class="formth"><i class="require-red">*</i>图片加水印限制：</th>
                                    <td class="formtd">
                                    原图宽度大于 <input type="text" value="{$PW['upload_watermark_width']}" size="2" name="info[upload_watermark_width]" class="common-text"> Px
                                    &nbsp;&nbsp;
                                    原图高度大于 <input type="text" value="{$PW['upload_watermark_height']}" size="2" name="info[upload_watermark_height]" class="common-text"> Px
                                    </td>
                                </tr>
                                <tr class="formtr">
                                    <th class="formth" width="20%"><i class="require-red">*</i>水印透明度：</th>
                                    <td class="formtd"><input type="text" value="{$PW['upload_watermark_pct']}" size="2" name="info[upload_watermark_pct]" class="common-text">
                                    <font style="color:#CCC; font-size:12px">范围为 1~100 的整数，数值越小水印图片越透明</font>
                                    </td>
                                </tr>
                                <tr class="formtr">
                                    <th class="formth" width="20%"><i class="require-red">*</i>水印添加位置：</th>
                                    <td class="formtd">
                                     <table cellspacing="1" cellpadding="4" width="40%">
                                      <tr><td><label><input type="radio" class="common-radio" name="info[upload_watermark_pos]" value="1" {php echo $PW['upload_watermark_pos']==1?' checked="checked"':'';}>顶部居左</label></td><td><label><input type="radio" class="common-radio" name="info[upload_watermark_pos]" value="2" {php echo $PW['upload_watermark_pos']==2?' checked="checked"':'';}>顶部居中</label></td><td><label><input type="radio" class="common-radio" name="info[upload_watermark_pos]" value="3" {php echo $PW['upload_watermark_pos']==3?' checked="checked"':'';}>顶部居右</label></td></tr>
                                      <tr><td><label><input type="radio" class="common-radio" name="info[upload_watermark_pos]" value="4" {php echo $PW['upload_watermark_pos']==4?' checked="checked"':'';}>中部居左</label></td><td><label><input type="radio" class="common-radio" name="info[upload_watermark_pos]" value="5" {php echo $PW['upload_watermark_pos']==5?' checked="checked"':'';}>中部居中</label></td><td><label><input type="radio" class="common-radio" name="info[upload_watermark_pos]" value="6" {php echo $PW['upload_watermark_pos']==6?' checked="checked"':'';}>中部居右</label></td></tr>
                                      <tr><td><label><input type="radio" class="common-radio" name="info[upload_watermark_pos]" value="7" {php echo $PW['upload_watermark_pos']==7?' checked="checked"':'';}>底部居左</label></td><td><label><input type="radio" class="common-radio" name="info[upload_watermark_pos]" value="8" {php echo $PW['upload_watermark_pos']==8?' checked="checked"':'';}>底部居中</label></td><td><label><input type="radio" class="common-radio" name="info[upload_watermark_pos]" value="9"  {php echo $PW['upload_watermark_pos']==9?' checked="checked"':'';}>底部居右</label></td></tr>
                                      </table>
                                    </td>
                                </tr>
                                <tr class="formtr">
                                    <th class="formth"><i class="require-red">*</i>水印图片：</th>
                                    <td class="formtd">
                                    {php echo Form::image('水印图片','upload_watermark',$PW['upload_watermark']);}
                                    </td>
                                </tr>

                                </tbody>
                                <tr class="formtr">
                                    <th class="formth"></th>
                                    <td class="formtd">
                                        <input type="button" onClick="doSubmit('mysubmitform','')" value="提 交" class="submit-button">
                                        <input type="button" value="返 回" onClick="history.go(-1)" class="reset-button">
                                    </td>
                                </tr>
                            </tbody></table>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="statics-footer"> Powered by phpWeChat V{__PHPWECHAT_VERSION__}{__PHPWECHAT_RELEASE__} © , Processed in {php echo microtime()-$PW['time_start'];} second(s) , {MySql::$mQuery} queries <a href="#">至顶端↑</a></div>
</body>
</html>