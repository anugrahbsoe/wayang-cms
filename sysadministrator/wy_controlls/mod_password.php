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
	if($_POST['new_password']=="" || $_POST['cnew_password']==""){
		header('Location:../mod_password.php?message=errordata');
	}
	else{
		$npwd=xss_clean(delete($_POST['new_password']));
		$cpwd=xss_clean(delete($_POST['cnew_password']));
		$pwd="";
		if($npwd==$cpwd){
			$pwd=$npwd;
			$query="UPDATE `wy_user` SET `user_password`='".sha1($pwd.salt)."' WHERE `user_name`='".$_SESSION['wysystem']."'";
			if(!$raw=$conn->query($query)){
				header('Location:../mod_password.php?message=errorsql');
			}else{	
				if($raw){
					header('Location:../mod_password.php?message=success');
				}
				else{
					header('Location:../mod_password.php?message=errorsql');
				}
			}
		}
		else{
			header('Location:../mod_password.php?message=errordata');
		}
		
	}
}

?>