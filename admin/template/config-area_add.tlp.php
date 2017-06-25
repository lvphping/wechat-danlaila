{php !defined('IN_MANAGE') && exit('Access Denied!');use phpWeChat\Area;use phpWeChat\Form;use phpWeChat\Module;use phpWeChat\MySql;}
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
            <div class="crumb-list"><i class="icon-font"></i><a href="{__PW_PATH__}{__ADMIN_FILE__}?file=index&action=main">管理首页</a><span class="crumb-step">&gt;</span><span class="crumb-name">添加{if !$parentid}区域{else}商圈{/if}</span></div>
        </div>
        <div class="result-wrap">
            <form action="" method="post" name="mysubmitform" id="mysubmitform" enctype="multipart/form-data">
            	<input type="hidden" value="1" name="dosubmit">
                <input type="hidden" value="{$city}" name="info[cityid]">
                <div class="config-items">
                    <div class="config-title">
                        <h1><i class="icon-font">&#xe026;</i>添加{if !$parentid}区域{else}商圈{/if}</h1>
                    </div>
                    <div class="result-content">
                        <table width="100%" class="insert-tab">
                            <tbody>
                            	<tr class="formtr">
                                    <th class="formth"><i class="require-red">*</i>所属城市：</th>
                                    <td class="formtd">
                                    {php echo Area::getCity($city,'name');}                                
                                    </td>
                                </tr>
                            	<tr class="formtr">
                                    <th class="formth" width="20%"><i class="require-red">*</i>{if !$parentid}区域{else}商圈{/if}名称：</th>
                                    <td class="formtd">
                                 	<input type="text" class="common-text" name="info[name]" size="24" />
                                    </td>
                                </tr>
                                {if $parentid}
                                <tr class="formtr">
                                    <th class="formth"><i class="require-red">*</i>所属区域：</th>
                                    <td class="formtd">
                                    <select name="info[parentid]">
                                    	{loop Area::areaList($city,0)   $r}
                                        <option value="{$r['id']}">{$r['name']}</option>
                                        {/loop}
                                    </select>                                    
                                    </td>
                                </tr>
                               {/if}
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