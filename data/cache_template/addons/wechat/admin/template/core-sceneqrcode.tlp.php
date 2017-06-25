<?php !defined('IN_MANAGE') && exit('Access Denied!');use Wechat\WeChatManage;use Wechat\Wechat;use phpWeChat\Form;use phpWeChat\MySql;?>
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
          <div class="crumb-list"><i class="icon-font"></i><a href="<?php echo defined('PW_PATH')?PW_PATH:'{__PW_PATH__}';?><?php echo defined('ADMIN_FILE')?ADMIN_FILE:'{__ADMIN_FILE__}';?>?file=index&action=main">管理首页</a><span class="crumb-step">&gt;</span><span class="crumb-name"><a href="<?php echo defined('PW_PATH')?PW_PATH:'{__PW_PATH__}';?><?php echo defined('ADMIN_FILE')?ADMIN_FILE:'{__ADMIN_FILE__}';?>?mod=<?php echo isset($mod)?$mod:'';?>&file=<?php echo isset($file)?$file:'';?>&action=<?php echo isset($action)?$action:'';?>">二维码营销</a></span></div>
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
                      <?php $no=1;if(is_array($data))foreach($data as $r){?>
                      <tr>
                          <td class="tc"><input name="ids[<?php echo isset($r['scene_id'])?$r['scene_id']:'';?>]" checked="checked" la="checkbox" value="<?php echo isset($r['scene_id'])?$r['scene_id']:'';?>" type="checkbox"></td>
                          <td><?php echo isset($r['scene_id'])?$r['scene_id']:'';?></td>
                          <td><input type="text" class="common-text" size="24" value="<?php echo isset($r['scene_name'])?$r['scene_name']:'';?>" name="scene_names[<?php echo isset($r['scene_id'])?$r['scene_id']:'';?>]"></td>
						  <td><input type="text" class="common-text" size="24" value="<?php echo isset($r['keyword'])?$r['keyword']:'';?>" name="keywords[<?php echo isset($r['scene_id'])?$r['scene_id']:'';?>]"></td>
						  <td><?php if($r['typeid']==1) { ?>永久二维码<?php }else{ ?>临时二维码<?php }?></td>
						  <td><?php if($r['ticket']) { ?><a href="<?php echo WeChatManage::sceneqrcodeView($r['scene_id']);?>" target="_blank">查看二维码</a><?php }else{ ?><a href="<?php echo defined('PW_PATH')?PW_PATH:'{__PW_PATH__}';?><?php echo defined('ADMIN_FILE')?ADMIN_FILE:'{__ADMIN_FILE__}';?>?mod=wechat&file=core&action=sceneqrcode_create&id=<?php echo isset($r['scene_id'])?$r['scene_id']:'';?>">获取二维码</a><?php }?></td>
						  <td><?php echo date('Y-m-d H:i:s',$r['createtime']);?></td>
						  <td><?php if($r['expiretime']) { ?><?php echo date('Y-m-d H:i:s',$r['expiretime']);?><?php }else{ ?>永久有效<?php }?></td>
						  <td><?php echo isset($r['scan_times'])?$r['scan_times']:'';?> 次</td>
						  <td><?php echo isset($r['scan_fans'])?$r['scan_fans']:'';?> 次</td>
                          <td>
                              <!--<a href="<?php echo defined('PW_PATH')?PW_PATH:'{__PW_PATH__}';?><?php echo defined('ADMIN_FILE')?ADMIN_FILE:'{__ADMIN_FILE__}';?>?mod=<?php echo isset($mod)?$mod:'';?>&file=<?php echo isset($file)?$file:'';?>&action=sceneqrcode_scan&id=<?php echo isset($r['scene_id'])?$r['scene_id']:'';?>">查看扫描统计</a>
							  &nbsp;&nbsp;&nbsp;-->
							  <a href="<?php echo defined('PW_PATH')?PW_PATH:'{__PW_PATH__}';?><?php echo defined('ADMIN_FILE')?ADMIN_FILE:'{__ADMIN_FILE__}';?>?mod=<?php echo isset($mod)?$mod:'';?>&file=<?php echo isset($file)?$file:'';?>&action=<?php echo isset($action)?$action:'';?>&job=delete&ids=<?php echo isset($r['scene_id'])?$r['scene_id']:'';?>">删除</a>
                          </td>
                      </tr>
                     <?php $no++;}?>
					 <tr>
                     	<td colspan="11">
                        	<?php echo WeChatManage::$mPageString;?>
                        </td>
                     </tr>
                  </table>
              </div>
          </form>
		   <form action="" method="post" name="mysubmitform" id="mysubmitform" enctype="multipart/form-data">
            	<input type="hidden" value="1" name="dosubmit">
                <div class="config-items" style="margin-top:8px">
                    <div class="config-title">
                        <h1><a href="<?php echo defined('PW_PATH')?PW_PATH:'{__PW_PATH__}';?><?php echo defined('ADMIN_FILE')?ADMIN_FILE:'{__ADMIN_FILE__}';?>?mod=<?php echo isset($mod)?$mod:'';?>&file=<?php echo isset($file)?$file:'';?>&action=<?php echo isset($action)?$action:'';?>"><i class="icon-font">&#xe026;</i>添加分组</a></h1>
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
    <div class="statics-footer"> Powered by phpWeChat V<?php echo defined('PHPWECHAT_VERSION')?PHPWECHAT_VERSION:'{__PHPWECHAT_VERSION__}';?><?php echo defined('PHPWECHAT_RELEASE')?PHPWECHAT_RELEASE:'{__PHPWECHAT_RELEASE__}';?> © , Processed in <?php echo microtime()-$PW['time_start'];?> second(s) , <?php echo MySql::$mQuery;?> queries <a href="#">至顶端↑</a></div>
</body>
</html>