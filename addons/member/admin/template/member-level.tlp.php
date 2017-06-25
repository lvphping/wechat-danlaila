{php !defined('IN_MANAGE') && exit('Access Denied!');use phpWeChat\Form;use phpWeChat\Member;use phpWeChat\MySql;}
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
	$(document).ready(function(){
		$('#parentid').val({$data['parentid']});
	});
	</script>
</head>
<body>
<div class="ifame-main-wrap">
      <div class="crumb-wrap">
          <div class="crumb-list"><i class="icon-font"></i><a href="{__PW_PATH__}{__ADMIN_FILE__}?file=index&action=main">管理首页</a><span class="crumb-step">&gt;</span><span class="crumb-name"><a href="{__PW_PATH__}{__ADMIN_FILE__}?mod={$mod}&file={$file}&action={$action}">会员等级</a></span></div>
      </div>
      <div class="result-wrap">
          <form name="myform" id="myform" action="" method="post">
              <input type="hidden" value="delete" name="job" id="job">
              <div class="result-title">
                  <div class="result-list">
                      <a href="###" onClick="if(isCheckboxChecked()){$('#job').val('delete');$('#myform').submit();}else{alert('请选择要'+$(this).text()+' 的项目！')}"><i class="icon-font">&#xe037;</i>批量删除</a>
                  </div>
              </div>
              <div class="result-content">
                  <table class="result-tab" width="100%">
                      <tr>
                          <th class="tc" width="5%"><input id="checkAll" class="common-checkbox" checked="checked" title="全选/反选" type="checkbox"></th>
                        <th width="6%">ID</th>
                        <th width="32%">等级名称</th>
                        <th width="46%">积分区间</th>
					    <th width="11%">管理</th>
                    </tr>
                      {loop Member::levelList() $r}
                      <tr>
                          <td class="tc"><input name="levelids[{$r['levelid']}]" class="common-checkbox"  {if $r['levelid'] >0}checked="checked" la="checkbox"{else}disabled{/if} value="{$r['levelid']}" type="checkbox"></td>
                          <td>{$r['levelid']}</td>
                          <td>{if $r['ico']}<img src="{$r['ico']}" style="max-width:55px; max-height:55px;"><br>{/if}{$r['name']}</td>
                          <td>{$r['mincredits']} - {$r['maxcredits']}</td>
                          <td>
                          	  <a href="{__PW_PATH__}{__ADMIN_FILE__}?mod={$mod}&file={$file}&action={$action}&levelid={$r['levelid']}">编辑</a>
                              &nbsp;&nbsp;&nbsp;
                              {if $r['levelid'] >0}<a onClick="if(!confirm('确定要删除该会员等级吗？删除后不可恢复！')){return false;}" href="{__PW_PATH__}{__ADMIN_FILE__}?mod={$mod}&file={$file}&action={$action}&job=delete&levelids={$r['levelid']}">删除</a>
                              {else}<font style="color:#999999">删除</font>{/if}
                          </td>
                      </tr>
                     {/loop}
                  </table>
              </div>
          </form>
		   <form action="" method="post" name="mysubmitform" id="mysubmitform" enctype="multipart/form-data">
            	<input type="hidden" value="1" name="dosubmit">
                <input type="hidden" value="{$levelid}" name="levelid">
                <div class="config-items" style="margin-top:8px">
                    <div class="config-title">
                        <h1><a href="{__PW_PATH__}{__ADMIN_FILE__}?mod={$mod}&file={$file}&action={$action}"><i class="icon-font">&#xe026;</i>{if $levelid}编辑{else}添加{/if}分类</a></h1>
                    </div>
                    <div class="result-content">
                        <table width="100%" class="insert-tab">
                            <tbody>
                            	<tr class="formtr">
                                    <th class="formth"><i class="require-red">*</i>等级名称：</th>
                                    <td class="formtd">
                                 	<input type="text" class="common-text" name="info[name]" value="{$data['name']}" size="36" />
                                    </td>
                                </tr>
								<tr class="formtr">
                                    <th class="formth"><i class="require-red">*</i>等级图标：</th>
                                    <td class="formtd">
                                 	{Form::image('等级图标','ico',$data['ico'])}
                                    </td>
                                </tr>
								<tr class="formtr">
                                    <th class="formth"><i class="require-red">*</i>升级积分区间：</th>
                                    <td class="formtd">
                                 	<input type="text" class="common-text" name="info[mincredits]" size="4" value="{$data['mincredits']}" /> 分 - <input type="text" class="common-text" name="info[maxcredits]" size="4" value="{$data['maxcredits']}" /> 分
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