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
          <div class="crumb-list"><i class="icon-font"></i><a href="{__PW_PATH__}{__ADMIN_FILE__}?file=index&action=main">管理首页</a><span class="crumb-step">&gt;</span><span class="crumb-name">{if !$parentid}区域{else}商圈{/if}管理</span></div>
      </div>
      <div class="result-wrap">
          <form name="myform" id="myform" action="" method="post">
              <input type="hidden" value="delete" name="job" id="job">
              <div class="result-title">
                  <div class="result-list">
                      <a href="{__PW_PATH__}{__ADMIN_FILE__}?mod=&file={$file}&action=area_add&city={$city}&parentid={$parentid}"><i class="icon-font">&#xe026;</i>添加{if !$parentid}区域{else}商圈{/if}</a>
                      <a href="###" onClick="if(isCheckboxChecked()){$('#job').val('delete');$('#myform').submit();}else{alert('请选择要'+$(this).text()+' 的项目！')}"><i class="icon-font">&#xe024;</i>批量删除</a>
                  </div>
              </div>
              <div class="result-content">
                  <table class="result-tab" width="100%">
                      <tr>
                          <th class="tc" width="5%"><input id="checkAll" class="common-checkbox" checked="checked" title="全选/反选" type="checkbox"></th>
                          <th>ID</th>
                          
                          <th>所属省份</th>
                          <th>所属城市</th>
                          <th>名称</th>
                          {if $parentid}<th>上级区域</th>{/if}
                          <th>操作</th>
                      </tr>
                      {loop Area::areaList($city,$parentid) $r}
                      <tr>
                          <td class="tc"><input name="ids[{$r['id']}]" class="common-checkbox" checked="checked" la="checkbox" value="{$r['id']}" type="checkbox"></td>
                          <td>{$r['id']}</td>
                          
                          <td>{php echo Area::getPro($r['proid'],'name');}</td>
                          <td>{php echo Area::getCity($r['cityid'],'name');}</td>
                          <td title="{$r['name']}">{$r['name']}</td>
                          {if $parentid}<td>{php echo $r['parentid']?Area::getArea($r['parentid'],'name'):'一级区域';}</td>{/if}
                          <td>
                          	  {if !$parentid}<a href="{__PW_PATH__}{__ADMIN_FILE__}?mod=&file={$file}&action=area&parentid={$r['id']}&city={$city}">街道商圈</a>{/if}
                              <a href="{__PW_PATH__}{__ADMIN_FILE__}?mod=&file={$file}&action=area_edit&id={$r['id']}">修改</a>
                              <a href="{__PW_PATH__}{__ADMIN_FILE__}?mod=&file={$file}&action={$action}&job=delete&ids={$r['id']}">删除</a>
                          </td>
                      </tr>
                     {/loop}
                  </table>
              </div>
          </form>
      </div>
  </div>
    <div class="statics-footer"> Powered by phpWeChat V{__PHPWECHAT_VERSION__}{__PHPWECHAT_RELEASE__} © , Processed in {php echo microtime()-$PW['time_start'];} second(s) , {MySql::$mQuery} queries <a href="#">至顶端↑</a></div>
</body>
</html>