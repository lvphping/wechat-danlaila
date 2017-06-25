<?php !defined('IN_MANAGE') && exit('Access Denied!');use phpWeChat\Form;use phpWeChat\MySql;?>
<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>phpWeChat后台管理</title>
    <link rel="stylesheet" type="text/css" href="<?php echo defined('PW_PATH')?PW_PATH:'{__PW_PATH__}';?>admin/template/css/common.css"/>
    <link rel="stylesheet" type="text/css" href="<?php echo defined('PW_PATH')?PW_PATH:'{__PW_PATH__}';?>admin/template/css/main.css"/>
    <link rel="stylesheet" type="text/css" href="<?php echo defined('PW_PATH')?PW_PATH:'{__PW_PATH__}';?>statics/core.css"/>
    <link rel="stylesheet" type="text/css" href="<?php echo defined('PW_PATH')?PW_PATH:'{__PW_PATH__}';?>statics/reveal/reveal.css"/>
	<script src="<?php echo defined('PW_PATH')?PW_PATH:'{__PW_PATH__}';?>statics/jquery/jquery-1.12.2.min.js" language="javascript"></script>
    <script src="<?php echo defined('PW_PATH')?PW_PATH:'{__PW_PATH__}';?>statics/reveal/jquery.reveal.js" language="javascript"></script>
	<script src="<?php echo defined('PW_PATH')?PW_PATH:'{__PW_PATH__}';?>statics/core.js" language="javascript"></script>
    <script type="text/javascript" src="<?php echo defined('PW_PATH')?PW_PATH:'{__PW_PATH__}';?>admin/template/js/libs/modernizr.min.js"></script>
    <script language="javascript" type="text/javascript">
		var PW_PATH='<?php echo defined('PW_PATH')?PW_PATH:'{__PW_PATH__}';?>';
	</script>
</head>
<body>
    <div class="ifame-main-wrap">
        <div class="crumb-wrap">
            <div class="crumb-list"><i class="icon-font"></i><a href="<?php echo defined('PW_PATH')?PW_PATH:'{__PW_PATH__}';?><?php echo defined('ADMIN_FILE')?ADMIN_FILE:'{__ADMIN_FILE__}';?>?file=index&action=main">管理首页</a><span class="crumb-step">&gt;</span><span class="crumb-name">模板配置</span></div>
        </div>
        <div class="result-wrap">
            <form action="" method="post" name="mysubmitform" id="mysubmitform" enctype="multipart/form-data">
            	<input type="hidden" value="1" name="dosubmit">
                <div class="config-items">
                    <div class="config-title">
                        <h1><i class="icon-font">&#xe00a;</i>模板配置</h1>
                    </div>
                    <div class="result-content">
                        <table width="100%" class="insert-tab" style="border:0px">
                            <tbody>
                                <tr class="formtr">
                                    <td>
                                    <ul class="tlp_preview">
                                    <?php $no=1;if(is_array($previews))foreach($previews as $preview){?>
                                    <?php $path=substr($preview,strlen(PW_ROOT));$_path=explode('/',str_replace('\\','/',$path));?>
                                    	<li <?php echo $PW['pc_template']==$_path[3]?'class="hover"':'';?>>
                                        <label <?php if($PW['pc_template']==$_path[3]) { ?> style="cursor:not-allowed"<?php }?>>
                                        <img src="<?php echo defined('SITE_URL')?SITE_URL:'{__SITE_URL__}';?><?php echo isset($path)?$path:'';?>">
                                        <input type="radio" name="info[pc_template]" class="common-radio" <?php if($PW['pc_template']==$_path[3]) { ?> style="cursor:not-allowed"<?php }else{ ?>onClick="$('#mysubmitform').submit();"<?php }?> value="<?php echo isset($_path[3])?$_path[3]:'';?>" <?php echo $PW['pc_template']==$_path[3]?'checked':'';?>><?php echo isset($_path[3])?$_path[3]:'';?></label>
                                        </label>
                                        </li>
                                    <?php $no++;}?>
                                    </ul>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="statics-footer"> Powered by phpWeChat V<?php echo defined('PHPWECHAT_VERSION')?PHPWECHAT_VERSION:'{__PHPWECHAT_VERSION__}';?><?php echo defined('PHPWECHAT_RELEASE')?PHPWECHAT_RELEASE:'{__PHPWECHAT_RELEASE__}';?> © , Processed in <?php echo microtime()-$PW['time_start'];?> second(s) , <?php echo MySql::$mQuery;?> queries <a href="#">至顶端↑</a></div>
</body>
</html>