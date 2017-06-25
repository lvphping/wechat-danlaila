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
          <div class="crumb-list"><i class="icon-font"></i><a href="{__PW_PATH__}{__ADMIN_FILE__}?file=index&action=main">管理首页</a><span class="crumb-step">&gt;</span><span class="crumb-name"><a href="{__PW_PATH__}{__ADMIN_FILE__}?mod={$mod}&file={$file}&action={$action}">二维码营销</a></span></div>
      </div>
      <div class="result-wrap">
          <form name="myform" id="myform" action="" method="post">
              <input type="hidden" value="delete" name="job" id="job">
              <div class="result-title">
                  <div class="result-list">
				  	  <a href="###" onClick="if(isCheckboxChecked()){$('#job').val('edit');$('#myform').submit();}else{alert('请选择要'+$(this).text()+' 的项目！')}"><i class="icon-font">&#xe065;</i>批量编辑二维码</a>
					  &nbsp;&nbsp;&nbsp;
                      <a href="###" onClick="if(isCheckboxChecked()){$('#job').val('delete');$('#myform').submit();}else{alert('请选择要'+$(this).text()+' 的项目！')}"><i class="icon-font">&#xe037;</i>批量删除二维码</a>
                  </div>
              </div>
              <div class="result-content">
                  <table class="result-tab" width="100%">
                      <tr>
                        <th class="tc" width="2%"><input id="checkAll" class="common-checkbox" checked="checked" title="全选/反选" type="checkbox"></th>
                          <th width="6%">场景值ID</th>
                          <th width="13%">场景名称</th>
						  <th width="14%">触发关键词</th>
						  <th width="9%">类型</th>
						  <th width="9%">二维码</th>
						  <th width="10%">生成时间</th>
						  <th width="10%">有效时间</th>
						  <th width="7%">扫描次数</th>
						  <th width="7%">扫描人数</th>
						  <th width="13%">管理</th>
                      </tr>
                      {loop $data $r}
                      <tr>
                          <td class="tc"><input name="ids[{$r['scene_id']}]" checked="checked" la="checkbox" value="{$r['scene_id']}" type="checkbox"></td>
                          <td>{$r['scene_id']}</td>
                          <td><input type="text" class="common-text" size="24" value="{$r['scene_name']}" name="scene_names[{$r['scene_id']}]"></td>
						  <td><input type="text" class="common-text" size="24" value="{$r['keyword']}" name="keywords[{$r['scene_id']}]"></td>
						  <td>{if $r['typeid']==1}永久二维码{else}临时二维码{/if}</td>
						  <td>{if $r['ticket']}<a href="{WeChatManage::sceneqrcodeView($r['scene_id'])}" target="_blank">查看二维码</a>{else}<a href="{__PW_PATH__}{__ADMIN_FILE__}?mod=wechat&file=core&action=sceneqrcode_create&id={$r['scene_id']}">获取二维码</a>{/if}</td>
						  <td>{date('Y-m-d H:i:s',$r['createtime'])}</td>
						  <td>{if $r['expiretime']}{date('Y-m-d H:i:s',$r['expiretime'])}{else}永久有效{/if}</td>
						  <td>{$r['scan_times']} 次</td>
						  <td>{$r['scan_fans']} 次</td>
                          <td>
                              <!--<a href="{__PW_PATH__}{__ADMIN_FILE__}?mod={$mod}&file={$file}&action=sceneqrcode_scan&id={$r['scene_id']}">查看扫描统计</a>
							  &nbsp;&nbsp;&nbsp;-->
							  <a href="{__PW_PATH__}{__ADMIN_FILE__}?mod={$mod}&file={$file}&action={$action}&job=delete&ids={$r['scene_id']}">删除</a>
                          </td>
                      </tr>
                     {/loop}
					 <tr>
                     	<td colspan="11">
                        	{php echo WeChatManage::$mPageString;}
                        </td>
                     </tr>
                  </table>
              </div>
          </form>
		   <form action="" method="post" name="mysubmitform" id="mysubmitform" enctype="multipart/form-data">
            	<input type="hidden" value="1" name="dosubmit">
                <div class="config-items" style="margin-top:8px">
                    <div class="config-title">
                        <h1><a href="{__PW_PATH__}{__ADMIN_FILE__}?mod={$mod}&file={$file}&action={$action}"><i class="icon-font">&#xe026;</i>添加分组</a></h1>
                    </div>
                    <div class="result-content">
                        <table width="100%" class="insert-tab">
                            <tbody>
                            	<tr class="formtr">
                                    <th class="formth" width="20%"><i class="require-red">*</i>场景名称：</th>
                                    <td class="formtd">
                                 	<input type="text" class="common-text" name="info[scene_name]" size="36" />
									<br>
									<font style="color:#FF3300; font-size:12px">微信目前只允许添加10000个永久二维码</font>
                                    </td>
                                </tr>
								<tr class="formtr">
                                    <th class="formth" width="20%">触发关键词：</th>
                                    <td class="formtd">
                                 	<input type="text" class="common-text" name="info[keyword]" size="36" />
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