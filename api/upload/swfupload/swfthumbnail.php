<?php
	// This script accepts an ID and looks in the user's session for stored thumbnail name.
	// It then streams the data to the browser from the file
	
	// Work around the Flash Player Cookie Bug
	
	if (isset($_POST["PHPSESSID"])) {
		session_id($_POST["PHPSESSID"]);
	}

	define('D_TMP_ROOT', str_replace("\\", '/',substr(dirname(__FILE__),0,-20)));
	include D_TMP_ROOT.'include/common.inc.php';

	$image_id = isset($_GET["id"]) ? preg_replace('/[^a-z0-9\.\/\\\\\_\-]/i','',$_GET["id"]) : false;

	$image_id=str_replace('..','',$image_id);
	if(!is_image($image_id)  ||  !$image_id)
	{
		header("Content-type: image/jpeg") ;
		header("Content-length: " . filesize(PW_ROOT."statics/swfupload/images/error.gif"));
		flush();
		readfile(PW_ROOT."statics/swfupload/images/error.gif");
		exit(0);
	}
	
	if ($image_id === false) 
	{
		header("HTTP/1.1 500 Internal Server Error");
		echo "No ID";
		exit(0);
	}

	if (substr($image_id,0,7)!='http://' && !file_exists(PW_ROOT. $image_id)) 
	{
		header("HTTP/1.1 404 Not found");
		exit(0);
	}

	if(substr($image_id,0,7)!='http://')
	{
		header("Content-type: image/jpeg") ;
		header("Content-length: " . filesize(PW_ROOT.$image_id));
		flush();
		readfile(PW_ROOT.$image_id);
	}
	else
	{
		header('location:'.$image_id);
	}
	exit(0);
?>