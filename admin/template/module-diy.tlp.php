{php !defined('IN_MANAGE') && exit('Access Denied!');use phpWeChat\Form;use phpWeChat\Module;use phpWeChat\MySql;}
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
    <script language="javascript" type="text/javascript">
		var ct=1;
		
		function appendModuleElement()
		{
			var lihtml='<li>菜单图标：<input type="hidden" class="common-text" name="menu[ico]['+ct+']" id="module_diy_demo_ico_value_'+ct+'" value="17" />';
				lihtml+='<span style="cursor:pointer; position:relative";><font id="module_diy_demo_ico_'+ct+'" onClick="$(\'#icolist'+ct+'\').show();"><i class="icon-font">&#xe017;</i></font>';
				lihtml+='<div style="position:absolute; left:0px; top:24px; display:none; background:#FFFFFF; border:#ccc; padding:5px; width:300px; height:250px;" id="icolist'+ct+'">';
						<?php for($i=10;$i<=69;$i++){?>
				lihtml+='<i class="icon-font" style="float:left; margin:8px;" onClick="$(\'#module_diy_demo_ico_'+ct+'\').html(\'<i class=icon-font>&#xe0<?php echo $i;?>;</i>\');$(\'#module_diy_demo_ico_value_'+ct+'\').val(<?php echo $i;?>);$(\'#icolist'+ct+'\').hide();">&#xe0<?php echo $i;?>;</i>';
						<?php }?>
						<?php for($i=1;$i<=9;$i++){?>
				lihtml+='<i class="icon-font" style="float:left; margin:8px;" onClick="$(\'#module_diy_demo_ico_'+ct+'\').html(\'<i class=icon-font>&#xe00<?php echo $i;?>;</i>\');$(\'#module_diy_demo_ico_value_'+ct+'\').val(0<?php echo $i;?>);$(\'#icolist'+ct+'\').hide();">&#xe00<?php echo $i;?>;</i>';
						<?php }?>
				lihtml+='</div>';
				lihtml+='</span> &nbsp;&nbsp;菜单名称：<input type="text" placeholder="菜单名称" class="common-text" name="menu[name]['+ct+']" size="24" />';
				lihtml+='<br>菜单URL：{__ADMIN_FILE__}?mod=<span class="nowmodaction">'+$('#folder').val()+'</span>&file=<span class="nowmodaction">'+$('#folder').val()+'</span>&action= <input type="text" class="common-text" name="menu[action]['+ct+']" placeholder="菜单action" size="8" /></li>';
			$('.menubg').append(lihtml);
			
			ct++;
		}
	</script>
    <style type="text/css">
		.menubg li{background:#F9F9F9; padding:5px; line-height:2em; border:#E6E6E6 1px solid; margin:5px 0px; width:550px; font-size:12px}
		.icon-font:hover{color:#FF9900}
	</style>
</head>
<body>
    <div class="ifame-main-wrap">
        <div class="crumb-wrap">
            <div class="crumb-list"><i class="icon-font"></i><a href="{__PW_PATH__}{__ADMIN_FILE__}?file=index&action=main">管理首页</a><span class="crumb-step">&gt;</span><span class="crumb-name">自定义模块</span></div>
        </div>
        <div class="result-wrap">
            <form action="" method="post" name="mysubmitform" id="mysubmitform" enctype="multipart/form-data">
            	<input type="hidden" value="1" name="dosubmit">
                <div class="config-items">
                    <div class="config-title">
                        <h1><i class="icon-font">&#xe026;</i>自定义模块</h1>
                    </div>
                    <div class="result-content">
                        <table width="100%" class="insert-tab">
                            <tbody>
                            	<tr class="formtr">
                                    <th class="formth" width="20%"><i class="require-red">*</i>上级模块：</th>
                                    <td class="formtd">
                                 	<select name="info[parentkey]">
                                        {loop Module::moduleList(0)  $r}
                                        {if $r['folder']}
                                        <option value="{$r['key']}">{$r['name']}</option>
                                        {/if}
                                        {/loop}
                                    </select>
                                    </td>
                                </tr>
                            	<tr class="formtr">
                                    <th class="formth"><i class="require-red">*</i>模块名称：</th>
                                    <td class="formtd">
                                 	<input type="text" class="common-text" name="info[name]" size="24" />
                                    </td>
                                </tr>
                                <tr class="formtr">
                                    <th class="formth"><i class="require-red">*</i>模块文件夹：</th>
                                    <td class="formtd">
                                 	<input type="text" class="common-text" name="info[folder]" id="folder" size="24" onKeyUp="$('.nowmodaction').text($(this).val());" />
                                    </td>
                                </tr>
                                <tr class="formtr">
                                    <th class="formth"><i class="require-red">*</i>管理菜单：</th>
                                    <td class="formtd">
									<ul class="menubg">
									<li>
                                    菜单图标：<input type="hidden" class="common-text" name="menu[ico][0]" id="module_diy_demo_ico_value_0" value="17" />
                                    <span style="cursor:pointer; position:relative";><font id="module_diy_demo_ico_0" onClick="$('#icolist0').show();"><i class="icon-font">&#xe017;</i></font>
                                    <div style="position:absolute; left:0px; top:24px; display:none; background:#FFFFFF; border:#ccc; padding:5px; width:300px; height:250px;" id="icolist0">
                                    <?php for($i=10;$i<=69;$i++){?>
                                    <i class="icon-font" style="float:left; margin:8px;" onClick="$('#module_diy_demo_ico_0').html('<i class=icon-font>&#xe0<?php echo $i;?>;</i>');$('#module_diy_demo_ico_value_0').val(<?php echo $i;?>);$('#icolist0').hide();">&#xe0<?php echo $i;?>;</i>
                                    <?php }?>
                                    <?php for($i=1;$i<=9;$i++){?>
                                    <i class="icon-font" style="float:left; margin:8px;" onClick="$('#module_diy_demo_ico_0').html('<i class=icon-font>&#xe00<?php echo $i;?>;</i>');$('#module_diy_demo_ico_value_0').val(0<?php echo $i;?>);$('#icolist0').hide();">&#xe00<?php echo $i;?>;</i>
                                    <?php }?>
                                    </div>
                                    </span> &nbsp;&nbsp;菜单名称：<input type="text" value="配置选项" class="common-text" name="menu[name][0]" size="24" />
                                    <br>
                                    菜单URL：{__ADMIN_FILE__}?mod=<span class="nowmodaction"></span>&file=<span class="nowmodaction"></span>&action= <input type="text" class="common-text" name="menu[action][0]" value="config" size="8" />  
                                    &nbsp;&nbsp;
                                    <a href="javascript:void(0);" onClick="appendModuleElement();">增加菜单项</a>
                                    </li>
                                    </ul>
                                    <a href="javascript:void(0);" onMouseOver="$('#module_diy_demo_img').show();" onMouseOut="$('#module_diy_demo_img').hide();" style="position:relative">参数说明<img id="module_diy_demo_img" src="{__PW_PATH__}admin/template/images/module_diy_demo.jpg" style="position:absolute; display:none; top:24px; left:0px"></a>
                                    </td>
                                </tr>
                                <tr class="formtr">
                                    <th class="formth"><i class="require-red">*</i>模块排序：</th>
                                    <td class="formtd">
                                 	<input type="text" class="common-text" name="info[orderby]" value="0" size="4" />
                                    <font style="color:#CCC; font-size:12px">数值越小越靠前</font>
                                    </td>
                                </tr>
                                <tr class="formtr">
                                    <th class="formth"><i class="require-red">*</i>模块作者：</th>
                                    <td class="formtd">
                                 	<input type="text" class="common-text" name="info[author]" size="36" />
                                    </td>
                                </tr>
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