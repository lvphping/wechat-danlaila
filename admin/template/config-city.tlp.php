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
          <div class="crumb-list"><i class="icon-font"></i><a href="{__PW_PATH__}{__ADMIN_FILE__}?file=index&action=main">管理首页</a><span class="crumb-step">&gt;</span><span class="crumb-name">城市管理</span></div>
      </div>
      <div class="result-wrap">
          <form name="myform" id="myform" action="" method="post">
              <input type="hidden" value="delete" name="job" id="job">
              <div class="result-title">
                  <div class="result-list">
                  	  <a href="{__PW_PATH__}{__ADMIN_FILE__}?mod=&file={$file}&action=pro"><i class="icon-font">&#xe041;</i>省份管理</a>
                      <a href="{__PW_PATH__}{__ADMIN_FILE__}?mod=&file={$file}&action=city_add"><i class="icon-font">&#xe026;</i>添加城市</a>
                      <a href="###" onclick="if(isCheckboxChecked()){$('#job').val('delete');$('#myform').submit();}else{alert('请选择要'+$(this).text()+' 的项目！')}"><i class="icon-font">&#xe024;</i>批量删除</a>
                  </div>
              </div>
              <div class="result-content">
                  <table class="result-tab" width="100%">
                      <tr>
                          <th class="tc" width="5%"><input id="checkAll" class="common-checkbox" checked="checked" title="全选/反选" type="checkbox"></th>
                          <th>ID</th>
                          <th>所属省份</th>
                          <th>名称</th>
                          
                          <th>拼音</th>
                          <th>首字母</th>
                          <th>操作</th>
                      </tr>
                      {loop $data $r}
                      <tr>
                          <td class="tc"><input name="ids[{$r['id']}]" class="common-checkbox" checked="checked" la="checkbox" value="{$r['id']}" type="checkbox"></td>
                          <td>{$r['id']}</td>
                          
                          <td>{Area::getPro($r['proid'],'name')}</td>
                          <td title="{$r['name']}">{$r['name']}</td>
                          <td>{$r['spell']}</td>
                          <td>{$r['initial']}</td>
                          <td>
                          	  <a href="{__PW_PATH__}{__ADMIN_FILE__}?mod=&file={$file}&action=area&pro={$r['proid']}&city={$r['id']}">子区域</a>
                              <a href="{__PW_PATH__}{__ADMIN_FILE__}?mod=&file={$file}&action=city_edit&id={$r['id']}">修改</a>
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