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
</head>
<body>
<div class="ifame-main-wrap">
      <div class="crumb-wrap">
          <div class="crumb-list"><i class="icon-font"></i><a href="{__PW_PATH__}{__ADMIN_FILE__}?file=index&action=main">管理首页</a><span class="crumb-step">&gt;</span><span class="crumb-name"><a href="{__PW_PATH__}{__ADMIN_FILE__}?mod={$mod}&file={$file}&action={$action}">模板消息-模板管理</a></span></div>
      </div>
      <div class="result-wrap">
          <form name="myform" id="myform" action="" method="post">
              <input type="hidden" value="delete" name="job" id="job">
              <div class="result-title">
                  <div class="result-list">
				  	  <a href="{__PW_PATH__}{__ADMIN_FILE__}?mod={$mod}&file={$file}&action=load_tmplmsg"><i class="icon-font">&#xe010;</i>同步微信模板</a>
					  &nbsp;&nbsp;&nbsp;
                      <a href="###" onClick="if(isCheckboxChecked()){$('#job').val('delete');$('#myform').submit();}else{alert('请选择要'+$(this).text()+' 的项目！')}"><i class="icon-font">&#xe037;</i>批量删除模板</a>
                  </div>
              </div>
              <div class="result-content">
                  <table class="result-tab" width="100%">
                      <tr>
                        <th class="tc" width="3%"><input id="checkAll" class="common-checkbox" checked="checked" title="全选/反选" type="checkbox"></th>
                          <th width="28%">模板ID</th>
                          <th width="11%">标题</th>
						  <th width="9%">一级行业</th>
						  <th width="9%">二级行业</th>
						  <th width="15%">模板内容</th>
						  <th width="21%">内容示例</th>
						  <th width="4%">管理</th>
                      </tr>
                      {loop $data $r}
                      <tr>
                          <td class="tc"><input name="template_ids[{$r['template_id']}]" class="common-checkbox" la="checkbox"  checked="checked" value="{$r['template_id']}" type="checkbox"></td>
                          <td>{$r['template_id']}</td>
                          <td title="{$r['title']}">{$r['title']}</td>
						  <td>{$r['primary_industry']}</td>
						  <td>{$r['deputy_industry']}</td>
						  <td style="text-align:left">{nl2br($r['content'])}</td>
						  <td style="text-align:left">{nl2br($r['example'])}</td>
                          <td>
                             <a href="{__PW_PATH__}{__ADMIN_FILE__}?mod={$mod}&file={$file}&action={$action}&job=delete&template_ids={$r['template_id']}">删除</a>
                          </td>
                      </tr>
                     {/loop}
					 <tr>
                     	<td colspan="8">
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