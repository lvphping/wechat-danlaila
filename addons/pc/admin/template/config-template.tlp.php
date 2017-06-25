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
</head>
<body>
    <div class="ifame-main-wrap">
        <div class="crumb-wrap">
            <div class="crumb-list"><i class="icon-font"></i><a href="{__PW_PATH__}{__ADMIN_FILE__}?file=index&action=main">管理首页</a><span class="crumb-step">&gt;</span><span class="crumb-name">模板配置</span></div>
        </div>
        <div class="result-wrap">
            <form action="" method="post" name="mysubmitform" id="mysubmitform" enctype="multipart/form-data">
            	<input type="hidden" value="1" name="dosubmit">
                <div class="config-items">
                    <div class="config-title">
                        <h1><i class="icon-font">&#xe00a;</i>模板配置</h1>
                    </div>
                    <div class="result-content">
                        <table width="100%" class="insert-tab" style="border:0px">
                            <tbody>
                                <tr class="formtr">
                                    <td>
                                    <ul class="tlp_preview">
                                    {loop $previews $preview}
                                    {php $path=substr($preview,strlen(PW_ROOT));$_path=explode('/',str_replace('\\','/',$path));}
                                    	<li {php echo $PW['pc_template']==$_path[3]?'class="hover"':'';}>
                                        <label {if $PW['pc_template']==$_path[3]} style="cursor:not-allowed"{/if}>
                                        <img src="{__SITE_URL__}{$path}">
                                        <input type="radio" name="info[pc_template]" class="common-radio" {if $PW['pc_template']==$_path[3]} style="cursor:not-allowed"{else}onClick="$('#mysubmitform').submit();"{/if} value="{$_path[3]}" {php echo $PW['pc_template']==$_path[3]?'checked':'';}>{$_path[3]}</label>
                                        </label>
                                        </li>
                                    {/loop}
                                    </ul>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="statics-footer"> Powered by phpWeChat V{__PHPWECHAT_VERSION__}{__PHPWECHAT_RELEASE__} © , Processed in {php echo microtime()-$PW['time_start'];} second(s) , {MySql::$mQuery} queries <a href="#">至顶端↑</a></div>
</body>
</html>