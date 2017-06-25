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
	<script src="{__PW_PATH__}statics/ZeroClipboard/ZeroClipboard.js" language="javascript"></script>

</head>
<body>
    <div class="ifame-main-wrap">
        <div class="crumb-wrap">
            <div class="crumb-list"><i class="icon-font"></i><a href="{__PW_PATH__}{__ADMIN_FILE__}?file=index&action=main">管理首页</a><span class="crumb-step">&gt;</span><span class="crumb-name">公众号配置</span></div>
        </div>
        <div class="result-wrap">
                <div class="config-items">
                   <div class="admin-nav">
                        <h2>公众号配置</h2>
                        <div class="nav">
                            <a href="{__PW_PATH__}{__ADMIN_FILE__}?mod=wechat&file=config&action=config">公众号配置</a>
                            <a href="{__PW_PATH__}{__ADMIN_FILE__}?mod=wechat&file=config&action=interface" class="hover">公共号接入</a>
                        </div>
                        <div class="admin-tips">
                        	请登录 <a href="https://mp.weixin.qq.com/" target="_blank">微信公共号</a>，进入右侧 最下面 <a href="https://mp.weixin.qq.com/" target="_blank">开发→基本配置</a> 处配置以上参数。
                        </div>
                    </div>
                    <div class="result-content">
                        <table width="100%" class="insert-tab">
                            <tbody>
                                <tr class="formtr">
                                    <th class="formth" width="20%"><i class="require-red">*</i>URL：</th>
                                    <td class="formtd">{format_url(U(MOD))}</td>
                                </tr>
                                <tr class="formtr">
                                    <th class="formth"><i class="require-red">*</i>Token：</th>
                                    <td class="formtd"><span id="wechat_token">{$PW['wechat_token']}</span></td>
                                </tr>
                                <tr class="formtr">
                                    <th class="formth"><i class="require-red">*</i>EncodingAESKey：</th>
                                    <td class="formtd"><span id="wechat_encodingaeskey">{$PW['wechat_encodingaeskey']}</span></td>
                                </tr>
								<tr class="formtr">
                                    <th class="formth"></th>
                                    <td class="formtd">
                                        <input type="button" value="配置好了" onClick="self.location.href='{__PW_PATH__}{__ADMIN_FILE__}?mod=wechat&file=config&action=config'" class="submit-button">
                                    </td>
                                </tr>
                            </tbody></table>
					
                    </div>
                </div>
        </div>
    </div>
    <div class="statics-footer"> Powered by phpWeChat V{__PHPWECHAT_VERSION__}{__PHPWECHAT_RELEASE__} © , Processed in {php echo microtime()-$PW['time_start'];} second(s) , {MySql::$mQuery} queries <a href="#">至顶端↑</a></div>
</body>
</html>