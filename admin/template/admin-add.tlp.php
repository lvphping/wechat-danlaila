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
    <script language="javascript" type="text/javascript">
		var PW_PATH='{__PW_PATH__}';
	</script>
	<script src="{__PW_PATH__}statics/jquery/jquery-1.12.2.min.js" language="javascript"></script>
    <script src="{__PW_PATH__}statics/reveal/jquery.reveal.js" language="javascript"></script>
	<script src="{__PW_PATH__}statics/core.js" language="javascript"></script>
    <script type="text/javascript" src="{__PW_PATH__}admin/template/js/libs/modernizr.min.js"></script>
</head>
<body>
    <div class="ifame-main-wrap">
        <div class="crumb-wrap">
            <div class="crumb-list"><i class="icon-font"></i><a href="{__PW_PATH__}{__ADMIN_FILE__}?file=index&action=main">管理首页</a><span class="crumb-step">&gt;</span><span class="crumb-name">后台管理员</span></div>
        </div>
        <div class="result-wrap">
            <form action="" method="post" name="mysubmitform" id="mysubmitform" enctype="multipart/form-data">
            	<input type="hidden" value="1" name="dosubmit">
                <div class="config-items">
                    <div class="admin-nav">
                        <h2>后台管理员</h2>
                        <div class="nav">
                            <a href="{__PW_PATH__}{__ADMIN_FILE__}?mod={$mod}&file={$file}&action=manage">管理员管理</a>
                            {if $_roleid==-1}<a href="{__PW_PATH__}{__ADMIN_FILE__}?mod={$mod}&file={$file}&action=add" class="hover">添加管理员</a>{/if}
                            {if $_roleid==-1}<a href="{__PW_PATH__}{__ADMIN_FILE__}?mod={$mod}&file={$file}&action=role">角色管理</a>{/if}
                        </div>
                        <div class="admin-tips">
                           管理权权限由不同的管理角色决定，分配管理权限进进入<a href="{__PW_PATH__}{__ADMIN_FILE__}?mod={$mod}&file={$file}&action=role">角色管理</a>进行操作。
                        </div>
                    </div>
                    <div class="result-content">
                        <table width="100%" class="insert-tab">
                            <tbody>
                                <tr class="formtr">
                                    <th class="formth" width="20%"><i class="require-red">*</i>管理员账户：</th>
                                    <td class="formtd"><input type="text" size="36" name="info[username]" class="common-text"></td>
                                </tr>
                                <tr class="formtr">
                                    <th class="formth"><i class="require-red">*</i>管理员密码：</th>
                                    <td class="formtd"><input type="password" size="36" name="info[password]" class="common-text"></td>
                                </tr>
                                <tr class="formtr">
                                    <th class="formth"><i class="require-red">*</i>所属角色：</th>
                                    <td class="formtd">
                                    <select name="info[roleid]">
                                    	<option value="-1">超级管理员</option>
                                        {loop Admin::roleList() $r}
                                        <option value="{$r['roleid']}">{$r['name']}</option>
                                        {/if}
                                    </select>
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
</body>
</html>