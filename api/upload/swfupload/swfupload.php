<?php
	use phpWeChat\Upload;
	/* Note: This thumbnail creation script requires the GD PHP Extension.  
		If GD is not installed correctly PHP does not render this page correctly
		and SWFUpload will get "stuck" never calling uploadSuccess or uploadError
	 */

	// Get the session Id passed from SWFUpload. We have to do this to work-around the Flash Player Cookie Bug

	if (isset($_POST["PHPSESSID"])) {
		session_id($_POST["PHPSESSID"]);
	}

	define('D_TMP_ROOT', str_replace("\\", '/',substr(dirname(__FILE__),0,-20)));
	include D_TMP_ROOT.'include/common.inc.php';

	echo "FILEID:" . Upload::imageUpload('Filedata');
	exit(0);
?>