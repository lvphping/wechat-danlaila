<?php !defined('IN_MANAGE') && exit('Access Denied!');use phpWeChat\Form;use phpWeChat\Module;use phpWeChat\MySql;?>
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
            <div class="crumb-list"><i class="icon-font"></i><a href="{__PW_PATH__}{__ADMIN_FILE__}?file=index&action=main">管理首页</a><span class="crumb-step">&gt;</span><a href="?mod=&file=module&action=manage&parentkey=<?php echo isset($_parentmodule['key'])?$_parentmodule['key']:'';?>">模块管理</a><span class="crumb-step">&gt;</span><span class="crumb-name">关联数据表</span></div>
        </div>
        <div class="result-wrap">

                <div class="config-items">
                    <div class="config-title">
                        <h1><i class="icon-font">&#xe026;</i>关联数据表</h1>
                    </div>
                    <div class="result-content">
						<ul class="toggle-ul">
							<?php $no=1;if(is_array(Module::moduleList($_parentmodule['key'])))foreach(Module::moduleList($_parentmodule['key']) as $r){?>
                      		<?php if($r['folder']) { ?>
							<li<?php if($modulekey==$r['key']) { ?> class="hover"<?php }?> <?php if($no==1) { ?> style="border-left:#F3F3F3 1px solid"<?php }?>><a href="{__PW_PATH__}{__ADMIN_FILE__}?mod=&file=module&action=tables&modulekey=<?php echo isset($r['key'])?$r['key']:'';?>"><?php echo isset($r['name'])?$r['name']:'';?></a></li>
							<?php }?>
                     		<?php $no++;}?>
						</ul>
                        <table class="result-tab" width="100%">

						  <tr>
							  <th width="23%">数据表名</th>
							  <th width="50%">所属模块</th>
							  <th width="27%">操作</th>
						  </tr>
						  <?php $no=1;if(is_array($_modulemodel))foreach($_modulemodel as $tbname){?>
						  <tr>
							  <td><a href="{__PW_PATH__}{__ADMIN_FILE__}?mod=&file={$file}&action=createsql&tbname={php echo substr($tbname,strlen(DB_PRE));}">{__DB_PRE__}{php echo substr($tbname,strlen(DB_PRE));}</a></td>
							  <td><?php echo Module::getModuleByKey($modulekey,'name');?></td>
							  <td>
								  <a href="{__PW_PATH__}{__ADMIN_FILE__}?mod=&file={$file}&action=createsql&tbname={php echo substr($tbname,strlen(DB_PRE));}">查看创建表SQL</a>
							  </td>
						  </tr>
						 <?php $no++;}?>
					  </table>
                    </div>
                </div>
        </div>
    </div>
    <div class="statics-footer"> Powered by phpWeChat V{__PHPWECHAT_VERSION__}{__PHPWECHAT_RELEASE__} © , Processed in {php echo microtime()-$PW['time_start'];} second(s) , {MySql::$mQuery} queries <a href="#">至顶端↑</a></div>
</body>
</html>