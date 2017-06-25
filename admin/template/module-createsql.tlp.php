{php !defined('IN_MANAGE') && exit('Access Denied!');use phpWeChat\Form;use phpWeChat\MySql;}
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
	<script src="{__PW_PATH__}statics/ZeroClipboard/ZeroClipboard.js" language="javascript"></script>
</head>
<body>
    <div class="ifame-main-wrap">
        <div class="crumb-wrap">
            <div class="crumb-list"><i class="icon-font"></i><a href="{__PW_PATH__}{__ADMIN_FILE__}?file=index&action=main">管理首页</a><span class="crumb-step">&gt;</span><span class="crumb-name">生成创建表SQL语句</span></div>
        </div>
        <div class="result-wrap">
                <div class="config-items">
                    <div class="config-title">
                        <h1><i class="icon-font">&#xe026;</i>生成创建表SQL语句</h1>
                    </div>
                    <div class="result-content">
                        <table width="100%" class="insert-tab">
                            <tbody>
                            	<tr class="formtr">
                                    <th class="formth" width="20%"><i class="require-red">*</i>数据表：</th>
                                    <td class="formtd">
                                 	<a href="{__PW_PATH__}{__ADMIN_FILE__}?mod=&file=module&action=createsql&tbname={$tbname}">{__DB_PRE__}{$tbname}</a>
                                    </td>
                                </tr>
                                <tr class="formtr">
                                    <th class="formth"><i class="require-red">*</i>创建表SQL：</th>
                                    <td class="formtd">
                                    <textarea class="common-textarea" cols="75" rows="10" name="createsql" id="createsql">{php echo $create_sql['Create Table'];};</textarea><font style="font-size:12px; color:#669933" id="resultmsg"></font>
									<br>
									<font style="font-size:12px; color:#ccc">您可以在 phpMyAdmin 等数据库管理工具里，通过执行以上SQL来创建 {__DB_PRE__}{$tbname} 表。</font>
                                    </td>
                                </tr>
                                <tr class="formtr">
                                    <th class="formth"></th>
                                    <td class="formtd">
                                        <input type="button" onClick="doSubmit('mysubmitform','')" id="copy-botton" value="复制SQL语句" class="submit-button">
                                        <input type="button" value="返 回" onClick="history.go(-1)" class="reset-button">
                                    </td>
                                </tr>
                            </tbody></table>
                    </div>
                </div>
        </div>
    </div>
    <div class="statics-footer"> Powered by phpWeChat V{__PHPWECHAT_VERSION__}{__PHPWECHAT_RELEASE__} © , Processed in {php echo microtime()-$PW['time_start'];} second(s) , {MySql::$mQuery} queries <a href="#">至顶端↑</a></div>
	<script language="javascript" type="text/javascript">
	var clip = new ZeroClipboard.Client(); // 新建一个对象 
		clip.setHandCursor( true ); // 设置鼠标为手型 
		clip.setText($('#createsql').text()); // 设置要复制的文本。 
		// 注册一个 button，参数为 id。点击这个 button 就会复制。 
		//这个 button 不一定要求是一个 input 按钮，也可以是其他 DOM 元素。 
		clip.glue("copy-botton"); // 和上一句位置不可调换 
		clip.addEventListener( "complete", function(){ 
		$('#resultmsg').text("复制成功！"); 
		}); 
	</script>
</body>
</html>