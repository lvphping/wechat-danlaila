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
	<script src="{__PW_PATH__}statics/jquery/jquery-1.12.2.min.js" language="javascript"></script>
    <script src="{__PW_PATH__}statics/reveal/jquery.reveal.js" language="javascript"></script>
	<script src="{__PW_PATH__}statics/core.js" language="javascript"></script>
    <script type="text/javascript" src="{__PW_PATH__}admin/template/js/libs/modernizr.min.js"></script>
    <script language="javascript" type="text/javascript">
		var PW_PATH='{__PW_PATH__}';
	</script>
    <style type="text/css">
		.btn-post{clear:both; margin:10px 10px; height:30px;}
		.a-btn-post{background:#44b549 ; color:#FFFFFF; font-size: 14px;font-weight: 400; display:block; width:125px; height:30px; line-height:30px; text-align:center;border-radius: 4px; float:left; margin-right:20px }
		.a-btn-post:hover{color:#FFFFFF; background:#339933}
	</style>
</head>
<body>
<div class="ifame-main-wrap">
      <div class="crumb-wrap">
          <div class="crumb-list"><i class="icon-font"></i><a href="{__PW_PATH__}{__ADMIN_FILE__}?file=index&action=main">管理首页</a><span class="crumb-step">&gt;</span><span class="crumb-name"><a href="{__PW_PATH__}{__ADMIN_FILE__}?mod={$mod}&file={$file}&action={$action}">客服功能</a></span></div>
      </div>
      <div class="result-wrap">
          <form name="myform" id="myform" action="" method="post">
              <input type="hidden" value="delete" name="job" id="job">
              <div class="admin-nav">
                <h2>客服功能</h2>
                <div class="nav">
                    <a href="{__PW_PATH__}{__ADMIN_FILE__}?mod=wechat&file=core&action=youaskservice" class="hover">全部客服</a>
                    <a href="{__PW_PATH__}{__ADMIN_FILE__}?mod=wechat&file=core&action=youaskservice_onlie">在线客服</a>
                    <a href="{__PW_PATH__}{__ADMIN_FILE__}?mod=wechat&file=core&action=youaskservice_add">添加客服</a>
                </div>
                <div class="admin-tips">
                   绑定后的客服帐号，可以<a href="https://mpkf.weixin.qq.com/" target="_blank">登录在线客服功能</a>，进行客服沟通。详情查看<a href="http://kf.qq.com/faq/120911VrYVrA160126Nvi6NN.html" target="_blank">使用说明</a>。
                </div>
              </div>
              <div class="btn-post">
				<a href="{__PW_PATH__}{__ADMIN_FILE__}?mod=wechat&file=core&action=youaskservice_synchronism" class="a-btn-post" ><i class="icon-font">&#xe025;</i> 同步微信客服</a>
			  </div>
              <div class="result-content">
                  <table class="result-tab" width="100%">
                      <tr>
                        <th class="tc" width="7%"><input id="checkAll" class="common-checkbox" checked="checked" title="全选/反选" type="checkbox"></th>
                        <th width="24%">客服编号</th>
                        <th width="31%">客服昵称</th>
					    <th width="26%">客服工号</th>
					    <th width="12%">管理</th>
                    </tr>
                      {loop $data $r}
                      <tr>
                          <td class="tc"><input name="ids[{$r['id']}]" class="common-checkbox" checked="checked" la="checkbox" value="{$r['id']}" type="checkbox"></td>
                          <td>
                          {$r['kf_account']}
                          </td>
                          <td>{$r['kf_nick']}</td>
						  <td>{$r['kf_id']}</td>
                          <td>
                              <a href="{__PW_PATH__}{__ADMIN_FILE__}?mod={$mod}&file={$file}&action={$action}&job=delete&ids={$r['id']}">删除</a>
                          </td>
                      </tr>
                     {/loop}
                     <tr>
                     	<td colspan="5">
                        	{php echo WeChatManage::$mPageString;}
                        </td>
                     </tr>
                  </table>
              </div>
          </form>
      </div>
  </div>
    <div class="statics-footer"> Powered by phpWeChat V{__PHPWECHAT_VERSION__}{__PHPWECHAT_RELEASE__} © , Processed in {php echo microtime()-$PW['time_start'];} second(s) , {MySql::$mQuery} queries <a href="#">至顶端↑</a></div>
</body>
</html>