{php !defined('IN_MANAGE') && exit('Access Denied!');use Admin\Admin;use phpWeChat\Form;use phpWeChat\MySql;}
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
          <div class="crumb-list"><i class="icon-font"></i><a href="{__PW_PATH__}{__ADMIN_FILE__}?file=index&action=main">管理首页</a><span class="crumb-step">&gt;</span><span class="crumb-name"><a href="{__PW_PATH__}{__ADMIN_FILE__}?mod={$mod}&file={$file}&action={$action}">后台管理员</a></span></div>
      </div>
      <div class="result-wrap">
              <div class="admin-nav">
                <h2>后台管理员</h2>
                <div class="nav">
                    <a href="{__PW_PATH__}{__ADMIN_FILE__}?mod={$mod}&file={$file}&action=manage" class="hover">管理员管理</a>
                    {if $_roleid==-1}<a href="{__PW_PATH__}{__ADMIN_FILE__}?mod={$mod}&file={$file}&action=add">添加管理员</a>{/if}
                    {if $_roleid==-1}<a href="{__PW_PATH__}{__ADMIN_FILE__}?mod={$mod}&file={$file}&action=role">角色管理</a>{/if}
                </div>
                <div class="admin-tips">
                   管理权权限由不同的管理角色决定，分配管理权限进进入<a href="{__PW_PATH__}{__ADMIN_FILE__}?mod={$mod}&file={$file}&action=role">角色管理</a>进行操作。
                </div>
              </div>
              <div class="result-content">
                  <table class="result-tab" width="100%">
                      <tr>
                        <th class="tc" width="5%">ID</th>
                        <th width="18%">管理账户</th>
                        <th width="13%">所属角色</th>
					    <th width="20%">最后登录时间</th>
					    <th width="14%">状态</th>
                        <th width="30%">管理</th>
                    </tr>
                      {if is_array($data)}
                      {loop $data $r}
                      {php $r=(array)$r;}
                      <tr>
                          <td class="tc">{$r['userid']}</td>
                          <td>
                          <a href="{__PW_PATH__}{__ADMIN_FILE__}?mod={$mod}&file={$file}&action=edit&userid={$r['userid']}">{$r['username']}</a>
                          </td>
                          <td>{if $r['roleid']==-1}超级管理员{else}{Admin::getRole($r['roleid'],'name')}{/if}</td>
						  <td>{date('Y-m-d H:i:s',$r['lastlogintime'])}</td>
                          <td>{php echo $r['status']?'<font style="color:#339933">正常</font>':'<font style="color:#999999; font-style:italic">已锁定</font>';}</td>
                           <td>
                           	  {if $_roleid==-1}
						  	  {if !$r['status']}
							  <a href="{__PW_PATH__}{__ADMIN_FILE__}?mod={$mod}&file={$file}&action=setstatus&status=1&userid={$r['userid']}">解除锁定</a>
                              &nbsp;&nbsp;&nbsp;
                              {else}
                              {if $r['userid']!=$_userid}
                              <a href="{__PW_PATH__}{__ADMIN_FILE__}?mod={$mod}&file={$file}&action=setstatus&status=0&userid={$r['userid']}">锁定操作</a>
                              &nbsp;&nbsp;&nbsp;
                              {else}
                              <font style="color:#999999">锁定操作</font>
                              &nbsp;&nbsp;&nbsp;
                              {/if}
							  {/if}
                              {/if}
                          	  <a href="{__PW_PATH__}{__ADMIN_FILE__}?mod={$mod}&file={$file}&action=edit&userid={$r['userid']}">编辑</a>
                              {if $_roleid==-1 && $r['userid']!=$_userid}
                              &nbsp;&nbsp;&nbsp;
                              <a href="{__PW_PATH__}{__ADMIN_FILE__}?mod={$mod}&file={$file}&action=delete&userid={$r['userid']}">删除</a>
                              {/if}
                          </td>
                      </tr>
                     {/loop}
                     <tr>
                     	<td colspan="6">
                        	{php echo Admin::$mPageString;}
                        </td>
                     </tr>
                     {else}
                     <tr>
                     	<td colspan="6" style="text-align:center">暂无设置管理员。</td>
                     </tr>
                     {/if}
                  </table>
              </div>
      </div>
  </div>
    <div class="statics-footer"> Powered by phpWeChat V{__PHPWECHAT_VERSION__}{__PHPWECHAT_RELEASE__} © , Processed in {php echo microtime()-$PW['time_start'];} second(s) , {MySql::$mQuery} queries <a href="#">至顶端↑</a></div>
</body>
</html>