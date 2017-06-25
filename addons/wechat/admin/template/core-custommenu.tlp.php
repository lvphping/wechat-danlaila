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
	<script language="javascript" type="text/javascript">
	function selectTr(id)
	{
		for(var i=1;i<=6;i++)
		{
			$('#select_'+i).hide();
		}
		
		$('#select_'+id).show();
	}
	
	{if $id}
	$(document).ready(function(){ 
		$('#parentid').val({$data['parentid']});
		$('#typeid').val({$data['typeid']});
		$('#event').val('{$data['event']}');
		
		for(var i=1;i<=6;i++)
		{
			$('#select_'+i).hide();
		}
		
		$('#select_{$data['typeid']}').show();
	});
	{/if}
	</script>
</head>
<body>
<div class="ifame-main-wrap">
      <div class="crumb-wrap">
          <div class="crumb-list"><i class="icon-font"></i><a href="{__PW_PATH__}{__ADMIN_FILE__}?file=index&action=main">管理首页</a><span class="crumb-step">&gt;</span><span class="crumb-name"><a href="{__PW_PATH__}{__ADMIN_FILE__}?mod={$mod}&file={$file}&action={$action}">自定义菜单</a></span></div>
      </div>
      <div class="result-wrap">
          <form name="myform" id="myform" action="" method="post">
              <input type="hidden" value="delete" name="job" id="job">
              <div class="result-title">
                  <div class="result-list">
				  	  <a href="{__PW_PATH__}{__ADMIN_FILE__}?mod={$mod}&file={$file}&action=create_menu"><i class="icon-font">&#xe062;</i>生成自定义菜单</a>
					  &nbsp;&nbsp;&nbsp;
				  	  <a href="###" onClick="if(isCheckboxChecked()){$('#job').val('edit');$('#myform').submit();}else{alert('请选择要'+$(this).text()+' 的项目！')}"><i class="icon-font">&#xe065;</i>修改菜单项</a>
					  &nbsp;&nbsp;&nbsp;
                      <a href="###" onClick="if(isCheckboxChecked()){$('#job').val('delete');$('#myform').submit();}else{alert('请选择要'+$(this).text()+' 的项目！')}"><i class="icon-font">&#xe037;</i>删除菜单项</a>
                  </div>
              </div>
              <div class="result-content">
                  <table class="result-tab" width="100%">
                      <tr>
                        <th class="tc" width="6%"><input id="checkAll" class="common-checkbox" checked="checked" title="全选/反选" type="checkbox"></th>
                          <th width="29%">菜单名称</th>
						  <th width="6%">排序</th>
						  <th width="17%">菜单类型</th>
						  <th width="32%">菜单数值</th>
						  <th width="10%">管理</th>
                      </tr>
                      {loop WeChatManage::menuList(0) $r}
                      <tr>
                          <td class="tc"><input name="ids[{$r['id']}]" class="common-checkbox" la="checkbox"  checked="checked" value="{$r['id']}" type="checkbox"></td>
                          <td style="padding-left:8px; text-align:left"><input type="text" class="common-text" name="names[{$r['id']}]" value="{$r['name']}" size="24" /></td>
                          <td title="{$r['name']}"><input type="text" class="common-text" name="orderbys[{$r['id']}]" value="{$r['orderby']}" size="4" /></td>
						  <td>{WeChatManage::menuTypeName($r['typeid'])}</td>
						  <td>{php $key=WeChatManage::menuTypeEvent($r['typeid']);echo $r['typeid']==3?$eventname[$r[$key]]:$r[$key];}</td>
                          <td>
                              <a href="{__PW_PATH__}{__ADMIN_FILE__}?mod={$mod}&file={$file}&action={$action}&id={$r['id']}">编辑</a>
							  &nbsp;&nbsp;&nbsp;&nbsp;
							 
							  <a onClick="if(!confirm('确定删除菜单【{$r['name']}】？')){return false;}" href="{__PW_PATH__}{__ADMIN_FILE__}?mod={$mod}&file={$file}&action={$action}&job=delete&ids={$r['id']}">删除</a>
                          </td>
                      </tr>
					  {loop WeChatManage::menuList($r['id']) $r2}
					  <tr>
                          <td class="tc"><input name="ids[{$r2['id']}]" class="common-checkbox" la="checkbox"  checked="checked" value="{$r2['id']}" type="checkbox"></td>
                          <td class="child-left" style="text-align:left"><input type="text" class="common-text" name="names[{$r2['id']}]" value="{$r2['name']}" size="24" /></td>
                          <td title="{$r2['name']}"><input type="text" class="common-text" name="orderbys[{$r2['id']}]" value="{$r2['orderby']}" size="4" /></td>
						  <td>{WeChatManage::menuTypeName($r2['typeid'])}</td>
						  <td>{php $key=WeChatManage::menuTypeEvent($r2['typeid']);echo $r2['typeid']==3?$eventname[$r2[$key]]:$r2[$key];}</td>
                          <td>
                               <a href="{__PW_PATH__}{__ADMIN_FILE__}?mod={$mod}&file={$file}&action={$action}&id={$r2['id']}">编辑</a>
							  &nbsp;&nbsp;&nbsp;&nbsp;
							 
							  <a onClick="if(!confirm('确定删除菜单【{$r2['name']}】？')){return false;}" href="{__PW_PATH__}{__ADMIN_FILE__}?mod={$mod}&file={$file}&action={$action}&job=delete&ids={$r2['id']}">删除</a>
                          </td>
                      </tr>
					  {/loop}
                     {/loop}
                  </table>
              </div>
          </form>
		   <form action="" method="post" name="mysubmitform" id="mysubmitform" enctype="multipart/form-data">
            	<input type="hidden" value="1" name="dosubmit">
				{if $id}
				<input type="hidden" value="{$id}" name="id">
				{/if}
                <div class="config-items" style="margin-top:8px">
                    <div class="config-title">
                        <h1><a href="{__PW_PATH__}{__ADMIN_FILE__}?mod={$mod}&file={$file}&action={$action}"><i class="icon-font">&#xe026;</i>{if $id}编辑{else}添加{/if}菜单项</a></h1>
                    </div>
                    <div class="result-content">
                        <table width="100%" class="insert-tab">
                            <tbody>
								<tr class="formtr">
                                    <th class="formth" width="20%"><i class="require-red">*</i>父菜单：</th>
                                    <td class="formtd">
                                 	<select name="info[parentid]" id="parentid">
										<option value="0" selected="selected">作为根菜单</option>	
										{loop WeChatManage::menuList(0) $r}
										<option value="{$r['id']}">{$r['name']}</option>	
										{/loop}	
									</select>
									<br>
									<font style="color:#FF3300; font-size:12px">微信端最多创建3 个一级菜单，每个一级菜单下最多可以创建 5 个二级菜单，菜单最多支持两层。</font>
                                    </td>
                                </tr>
                            	<tr class="formtr">
                                    <th class="formth" width="20%"><i class="require-red">*</i>菜单名称：</th>
                                    <td class="formtd">
                                 	<input type="text" class="common-text" name="info[name]" value="{$data['name']}" size="24" />
                                    </td>
                                </tr>
								<tr class="formtr">
                                    <th class="formth" width="20%"><i class="require-red">*</i>显示排序：</th>
                                    <td class="formtd">
                                 	<input type="text" class="common-text" value="{$data['orderby']}" name="info[orderby]" size="4" />
                                    </td>
                                </tr>
								<tr class="formtr">
                                    <th class="formth" width="20%"><i class="require-red">*</i>菜单类型：</th>
                                    <td class="formtd">
                                 	<select name="info[typeid]" id="typeid" onChange="selectTr($(this).val())">
										<option value="1" selected="selected">关键词回复菜单</option>		
										<option value="6">网页授权登录</option>
										<option value="2">url链接菜单</option>
										<option value="3">微信扩展菜单</option>
										<option value="4">一键拨号菜单</option>
										<option value="5">位置地图</option>
									</select>
                                    </td>
                                </tr>
								<tr class="formtr" id="select_1">
                                    <th class="formth" width="20%"><i class="require-red">*</i>要触发的关键字：</th>
                                    <td class="formtd">
									<input type="text" class="common-text" value="{$data['keyword']}" name="info[keyword]" size="24" />
									</td>
								</tr>
								<tr class="formtr" id="select_2" style="display:none">
                                    <th class="formth" width="20%"><i class="require-red">*</i>链接 URL：</th>
                                    <td class="formtd">
									<input type="text" class="common-text" value="{$data['url']}" name="info[url]" size="60" />
									</td>
								</tr>
								<tr class="formtr" id="select_3" style="display:none">
                                    <th class="formth" width="20%"><i class="require-red">*</i>菜单事件：</th>
                                    <td class="formtd">
									<select name="info[event]" id="event">
										<option value="scancode_waitmsg">扫码带提示</option>
										<option value="scancode_push">扫码推事件</option>
										<option value="pic_sysphoto">系统拍照发图</option>
										<option value="pic_photo_or_album">拍照或者相册发图</option>
										<option value="pic_weixin">微信相册发图</option>
										<option value="location_select">发送位置</option>
									</select>
									</td>
								</tr>
								<tr class="formtr" id="select_4" style="display:none">
                                    <th class="formth" width="20%"><i class="require-red">*</i>联系电话号码：</th>
                                    <td class="formtd">
									<input type="text" class="common-text" value="{$data['telephone']}" name="info[telephone]" size="24" />
									</td>
								</tr>
								<tr class="formtr" id="select_5" style="display:none">
                                    <th class="formth" width="20%"><i class="require-red">*</i>地图坐标：</th>
                                    <td class="formtd">
									{php echo Form::map('地图坐标','location',$data['location'])}
									</td>
								</tr>
								<tr class="formtr" id="select_6" style="display:none">
                                    <th class="formth" width="20%"><i class="require-red">*</i>登录后跳转URL：</th>
                                    <td class="formtd">
									<input type="text" class="common-text" value="{$data['redirect_url']}" name="info[redirect_url]" size="60" />
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