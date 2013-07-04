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
	if($_POST['widget_visible']=="" || $_POST['widget_title']==""){
		header('Location:../mod_widget.php?message=errordata');
	}
	else{
		$wvisible=xss_clean(delete($_POST['widget_visible']));
		$wtitle=xss_clean(delete($_POST['widget_title']));
		$query="UPDATE `wy_widget` SET `widget_visible`='".$wvisible."' WHERE `widget_title`='".$wtitle."'";
		if(!$raw=$conn->query($query)){
			header('Location:../mod_widget.php?message=errorsql');
		}else{
			if($raw){
				header('Location:../mod_widget.php?message=success');
			}
			else{
				header('Location:../mod_widget.php?message=errorsql');
			}
		}
	}
}

?>