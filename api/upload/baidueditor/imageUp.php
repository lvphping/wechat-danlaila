<?php
    include substr(dirname(__FILE__),0,-23)."/include/common.inc.php";
    include dirname(__FILE__)."/Uploader.class.php";
    //上传配置
    $config = array(
        "savePath" => "upload/images/" ,             //存储文件夹
        "maxSize" => 20480 ,                   //允许的文件最大尺寸，单位KB
        "allowFiles" => array( ".gif" , ".png" , ".jpg" , ".jpeg" , ".bmp" )  //允许的文件格式
    );
    //上传文件目录
    $Path = "upload/images/";


    //背景保存在临时目录中
    $config[ "savePath" ] = $Path;
	$config[ "urlPath" ] = $Path;
    $up = new Uploader( "upfile" , $config );
    $type = $_REQUEST['type'];
    $callback=$_GET['callback'];

    $info = $up->getFileInfo();
	

    /**
     * 返回数据
     */
    if($callback) {
        echo '<script>'.$callback.'('.json_encode($info).')</script>';
    } else {
        echo json_encode($info);
    }
