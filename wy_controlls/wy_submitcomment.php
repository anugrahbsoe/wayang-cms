<?php
require_once('../wy_config.php');
require_once('../wy_connection.php');
require_once('wy_xss_clean.php');
require_once('wy_quote_handler.php');
require_once('wy_get_ipaddress.php');
require_once('wy_common.php');
require_once('wy_user_agent.php');
if(isset($_POST['action'])){
	if(isset($_POST['captcha']))
	{
		$security_code = trim($_POST['captcha']);
		$to_check = md5($security_code);
		if($to_check == $_SESSION['security_code'])
		{
			$ua=getBrowser();
			$browser= "Browser: " . $ua['name'] . " " . $ua['version'] . " on " .$ua['platform'] . " reports: " . $ua['userAgent'];
			$sql="INSERT INTO `wy_comment`
			(`comment_name`, `comment_email`, `comment_url`, `comment_content`, `comment_user_agent`, `comment_date`, `comment_user_ipaddres`, `post_id`)
			 VALUES ('".xss_clean(delete($_POST['name']))."','".xss_clean(delete($_POST['email']))."',
			 '".xss_clean(delete($_POST['url']))."','".xss_clean(delete($_POST['comment']))."','".$browser."',
			 '".date("Y-m-d H:i:s")."','".ip()."','".xss_clean(delete($_POST['post_id']))."')";
			$raw=$conn->query($sql);
			$msg;
			if($raw)
			echo "sukses";
			else
			echo "gagal";
		}
		else
		{
			echo "gagal";
		}
	}
}
?>