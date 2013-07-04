<?php
session_start();
require_once('../../wy_config.php');
require_once('../../wy_connection.php');
require_once('../../wy_controlls/wy_quote_handler.php');
require_once('../../wy_controlls/wy_xss_clean.php');

if(!isset($_SESSION['wysystem']) && !isset($_SESSION['loggedip']) && !isset($_SESSION['wylevel']) && !isset($_SESSION['wydatelog']) 
|| $_SESSION['wysystem']=="" || $_SESSION['wysystem']==NULL && $_SESSION['loggedip']=="" || $_SESSION['loggedip']==NULL 
&& $_SESSION['wylevel']="" || $_SESSION['wylevel']==NULL && $_SESSION['wydatelog']="" || $_SESSION['wydatelog']==NULL){
	session_destroy();
	header('Location:../../wy_login.php');
}
else{
	if($_POST['site_name']=="" || $_POST['site_tag_line']=="" || $_POST['use_widget']=="" || $_POST['email_address']==""){
		header('Location:../mod_setting.php?message=errordata');
	}
	else{
		$query="UPDATE `wy_site_profile` SET `site_name`='".$_POST['site_name']."',`site_tag_line`='".$_POST['site_tag_line']."',`use_widget`='".$_POST['use_widget']."',`email_address`='".$_POST['email_address']."' WHERE `site_url`='".$_POST['site_url']."'";
		if(!$raw=$conn->query($query)){
			header('Location:../mod_setting.php?message=errorsql');
		}else{
			if($raw){
				header('Location:../mod_setting.php?message=success');
			}
			else{
				header('Location:../mod_setting.php?message=errorsql');
			}
		}
	}
}

?>