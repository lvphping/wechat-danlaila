{php !defined('IN_MANAGE') && exit('Access Denied!');use phpWeChat\Config;use phpWeChat\Form;use phpWeChat\Module;use phpWeChat\MySql;}
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
            <div class="crumb-list"><i class="icon-font"></i><a href="{__PW_PATH__}{__ADMIN_FILE__}?file=index&action=main">管理首页</a><span class="crumb-step">&gt;</span><span class="crumb-name">性能优化</span></div>
        </div>
        <form action="" method="post" name="mysubmitform" id="mysubmitform" enctype="multipart/form-data">
		<input type="hidden" value="1" name="dosubmit">
        <div class="result-wrap">
                <div class="config-items">
                    <div class="config-title">
                        <h1><i class="icon-font">&#xe018;</i>Gzip压缩传输</h1>
                    </div>
                    <div class="result-content">
                        <table width="100%" class="insert-tab">
                            <tbody>
                                <tr class="formtr">
                                    <th class="formth" width="20%"><i class="require-red">*</i>Gzip压缩：</th>
                                    <td class="formtd">
                                    <label><input type="radio" class="common-radio" name="info[config_gzip]" value="1"  {php echo $PW['config_gzip']?'checked="checked"':'';}/>开启</label>
                                    &nbsp;&nbsp;
                                    <label><input type="radio" class="common-radio" name="info[config_gzip]"  value="0"  {php echo !$PW['config_gzip']?'checked="checked"':'';}/>关闭</label>
                                    </td>
                                </tr>
                            </tbody></table>
                    </div>
                </div>
        </div>
		<div class="result-wrap">
                <div class="config-items">
                    <div class="config-title">
                        <h1><i class="icon-font">&#xe018;</i>伪静态设置</h1>
                    </div>
                    <div class="result-content">
                        <table width="100%" class="insert-tab">
                            <tbody>
                                <tr class="formtr">
                                    <th class="formth" width="20%">是否开启伪静态：</th>
                                    <td class="formtd">
                                    <label><input type="radio" class="common-radio" name="info[config_rewrite]" value="1"  {php echo $PW['config_rewrite']?'checked="checked"':'';}/>开启</label>
                                    &nbsp;&nbsp;
                                    <label><input type="radio" class="common-radio" name="info[config_rewrite]"  value="0"  {php echo !$PW['config_rewrite']?'checked="checked"':'';}/>关闭</label>
									<br><font style="color:#CCC; font-size:12px">开启伪静态需要消耗一定的服务器资源，但对SEO是有利的</font>
                                    </td>
                                </tr>
                            </tbody></table>
                    </div>
                </div>
        </div>
        <div class="result-wrap">
                <div class="config-items">
                    <div class="config-title">
                        <h1><i class="icon-font">&#xe018;</i>MemCache优化</h1>
                    </div>
                    <div class="result-content">
                        <table width="100%" class="insert-tab">
                            <tbody>
                                <tr class="formtr">
                                    <th class="formth" width="20%">MemCache服务器主机：</th>
                                    <td class="formtd">
                                    <textarea class="common-textarea" cols="55" rows="5" name="info[memcache_host]">{$PW['memcache_host']}</textarea>
                                    <br><font style="color:#CCC; font-size:12px">每个MemCache服务器主机之间用换行隔开</font>
                                    </td>
                                </tr>
                                <tr class="formtr">
                                    <th class="formth">MemCache服务器端口：</th>
                                    <td class="formtd">
                                    <input type="text" class="common-text" name="info[memcache_port]" value="{$PW['memcache_port']}" size="4" />
                                    <font style="color:#CCC; font-size:12px">默认为11211，一般不需要改</font>
                                    </td>
                                </tr>
                                 <tr class="formtr">
                                    <th class="formth">MemCache服务器超时时间：</th>
                                    <td class="formtd">
                                    <input type="text" class="common-text" name="info[memcache_timeout]" value="{$PW['memcache_timeout']}" size="4" /> 秒
                                    <font style="color:#CCC; font-size:12px">建议为10秒</font>
                                    </td>
                                </tr>
                            </tbody></table>
                    </div>
                </div>
        </div>
        <div class="result-wrap">
                <div class="config-items">
                    <div class="config-title">
                        <h1><i class="icon-font">&#xe018;</i>MySql优化</h1>
                    </div>
                    <div class="result-content">
                        <table width="100%" class="insert-tab">
                            <tbody>
                                <tr class="formtr">
                                    <th class="formth" width="20%">MySql缓存表个数：</th>
                                    <td class="formtd">
                                    <input type="text" class="common-text" name="info[cache_mysql_db]" value="{$PW['cache_mysql_db']}" size="4" /> 张
                                    <font style="color:#CCC; font-size:12px">百万级数据下，建议为10张；千万级数据下，建议为100张</font>
                                    </td>
                                </tr>
                                 <tr class="formtr">
                                    <th class="formth">缓存生命周期：</th>
                                    <td class="formtd">
                                    <input type="text" class="common-text" name="info[cache_ttl]" value="{$PW['cache_ttl']}" size="4" /> 秒
                                    <font style="color:#CCC; font-size:12px">根据业务数据更新频率决定，一般不建议超过1800秒</font>
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
        </div>
        </form>
    </div>
    <div class="statics-footer"> Powered by phpWeChat V{__PHPWECHAT_VERSION__}{__PHPWECHAT_RELEASE__} © , Processed in {php echo microtime()-$PW['time_start'];} second(s) , {MySql::$mQuery} queries <a href="#">至顶端↑</a></div>
</body>
</html>