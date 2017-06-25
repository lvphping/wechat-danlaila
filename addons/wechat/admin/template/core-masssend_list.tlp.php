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
          <div class="crumb-list"><i class="icon-font"></i><a href="{__PW_PATH__}{__ADMIN_FILE__}?file=index&action=main">管理首页</a><span class="crumb-step">&gt;</span><span class="crumb-name"><a href="{__PW_PATH__}{__ADMIN_FILE__}?mod={$mod}&file={$file}&action={$action}">群发信息</a></span></div>
      </div>
      <div class="result-wrap">
          <form name="myform" id="myform" action="" method="post">
              <input type="hidden" value="delete" name="job" id="job">
              <div class="admin-nav">
                <h2>群发信息</h2>
                        <div class="nav">
                            <a href="{__PW_PATH__}{__ADMIN_FILE__}?mod={$mod}&file={$file}&action=masssend">群发信息</a>
                            <a href="{__PW_PATH__}{__ADMIN_FILE__}?mod={$mod}&file={$file}&action={$action}" class="hover">已发送</a>
                        </div>
                <div class="admin-tips">
                    为保障用户体验，微信公众平台严禁恶意营销以及诱导分享朋友圈，严禁发布色情低俗、暴力血腥、政治谣言等各类违反法律法规及相关政策规定的信息。一旦发现，我们将严厉打击和处理。
                </div>
              </div>
              <div class="result-title">
                  <div class="result-list">
                      <a href="###" onClick="if(isCheckboxChecked()){$('#job').val('delete');$('#myform').submit();}else{alert('请选择要'+$(this).text()+' 的项目！')}"><i class="icon-font">&#xe037;</i>批量删除</a>
                  </div>
              </div>
              <div class="result-content">
                  <table class="result-tab" width="100%">
                      <tr>
                        <th class="tc" width="1%"><input id="checkAll" class="common-checkbox" checked="checked" title="全选/反选" type="checkbox"></th>
                        <th width="3%">ID</th>
                        <th width="16%">发送对象</th>
                        <th width="18%">发送状态</th>
					    <th width="12%">发送时间</th>
                        <th width="13%">消息类型</th>
                        <th width="29%">内容</th>
					    <th width="8%">管理</th>
                    </tr>
                      {loop $data $r}
                      <tr>
                          <td class="tc"><input name="ids[{$r['id']}]" class="common-checkbox" checked="checked" la="checkbox" value="{$r['id']}" type="checkbox"></td>
                          <td>{$r['id']}</td>
                          <td>{php echo $r['is_to_all']?'<font style="color:#009933">全部粉丝</font>':WeChatManage::getGroup($r['tag_id'],'name');}</td>
                          <td>{php echo !$r['errcode']?'<font style="color:#009933">发送成功</font>':'<font style="color:#ff3300">发送失败</font>';}</td>
						  <td>{date('Y-m-d H:i:s',$r['sendtime'])}</td>
						  <td>{$type[$r['msgtype']]}</td>
                          <td>
                          {if $r['msgtype']=='text'}
                          {$r['content']}
                          {elseif $r['msgtype']=='image'}
                          {php $info=WeChatManage::getImageMaterialByMediaId($r['media_id']);}
                          <div  style="padding:0px; position:relative; width:160px; margin:8px auto; overflow:hidden; height:160px;">
                              <img src="{$info['local_url']}" style="width:160px; height:160px;" />
                              <span style="background:#000; color:#FFFFFF; padding:0px 5px; width:100%; position:absolute; bottom:0px; left:0px;filter:alpha(opacity=50); -moz-opacity:0.50; opacity:0.50;">{sub_string($info['name'],20,'...')}</span>
                          </div>
                          {elseif $r['msgtype']=='media'}
                          {php $info=WeChatManage::getMaterialByMediaId($r['media_id']);}
                          <div  style="padding:0px; position:relative; width:160px; margin:8px auto; overflow:hidden; height:160px;">
                              <img src="{$info['PicUrl']}" style="width:160px; height:160px;" />
                              <span style="background:#000; color:#FFFFFF; padding:0px 5px; width:100%;position:absolute; bottom:0px; left:0px;filter:alpha(opacity=50); -moz-opacity:0.50; opacity:0.50;">{sub_string($info['Title'],20,'...')}</span>
                          </div>
                          {elseif $r['msgtype']=='voice'}
                          {php $info=WeChatManage::getVoiceMaterialByMediaId($r['media_id']);}
                          语音：<a href="{$info['local_url']}" target="_blank">{$info['name']}</a>
                          {elseif $r['msgtype']=='video'}
                          {php $info=WeChatManage::getVideoMaterialByMediaId($r['media_id']);}
                          视频：<a href="{$info['remote_url']}" target="_blank">{$info['name']}</a>
                          {/if}
                          </td>
                          <td>
                              <a href="{__PW_PATH__}{__ADMIN_FILE__}?mod={$mod}&file={$file}&action={$action}&job=delete&ids={$r['id']}">删除</a>
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