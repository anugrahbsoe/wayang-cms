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
	$query="";
	if(isset($_POST['add']) && $_POST['add']=="true"){
		if($_POST['page_name']=="" || $_POST['page_visible']==""){
			header('Location:../mod_page.php?message=errordata');
		}else{
		$query="INSERT INTO `wy_page`(`page_name`, `page_visible`, `page_module`, `page_content`, `page_date_add`)
			VALUES ('".xss_clean(delete($_POST['page_name']))."','".xss_clean(delete($_POST['page_visible']))."','".xss_clean(delete($_POST['page_module']))."','".$_POST['page_content']."','".date("Y-m-d H:i:s")."')";
		}
	}elseif(isset($_POST['edit']) && $_POST['edit']=="true"){
		$query="UPDATE `wy_page` SET `page_name`='".xss_clean(delete($_POST['page_name']))."',`page_visible`='".xss_clean(delete($_POST['page_visible']))."',
		`page_module`='".xss_clean(delete($_POST['page_module']))."',`page_content`='".$_POST['page_content']."' WHERE `page_id`='".$_POST['page_id']."'";
	}elseif(isset($_GET['deletepage']) && xss_clean(delete($_GET['deletepage']))=="true" && isset($_GET['page_id'])){
		if(delete($_GET['page_id'])==1){
			header('Location:../mod_page.php?message=restrictdata');
		}
		elseif(delete($_GET['page_id'])!=1){
			$query="DELETE FROM `wy_page` WHERE `page_id`='".delete($_GET['page_id'])."'";
		}
	}
	if(!$raw=$conn->query($query)){
		if(delete($_GET['page_id'])==1){
			header('Location:../mod_page.php?message=restrictdata');
		}
		else{
		header('Location:../mod_page.php?message=errorsql');
		}
	}else{
		if($raw){
			header('Location:../mod_page.php?message=success');
		}
		else{
			header('Location:../mod_page.php?message=errorsql');
		}
	}
}

?>