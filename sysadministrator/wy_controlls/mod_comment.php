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
	if($_GET['deletecomment']=="" || $_GET['comment_id']==""){
		header('Location:../mod_widget.php?message=errordata');
	}
	else{
		$cid=xss_clean(delete($_GET['comment_id']));
		$query="DELETE FROM `wy_comment` WHERE `comment_id`='".$cid."'";
		if(!$raw=$conn->query($query)){
			header('Location:../mod_comment.php?message=errorsql');
		}else{
			if($raw){
				header('Location:../mod_comment.php?message=success');
			}
			else{
				header('Location:../mod_comment.php?message=errorsql');
			}
		}
	}
}

?>