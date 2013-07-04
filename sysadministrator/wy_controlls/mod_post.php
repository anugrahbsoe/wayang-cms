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
		if($_POST['post_title']=="" || $_POST['post_category']=="" || $_POST['post_content']=="" || $_POST['post_tag']==""){
			header('Location:../mod_post.php?message=errordata');
		}else{
		$query="INSERT INTO `wy_post`(`post_title`, `post_category`, `post_content`, `post_tag`, `post_user`, `post_date`, `post_ip`)
		VALUES ('".xss_clean(delete($_POST['post_title']))."','".xss_clean(delete($_POST['post_category']))."','".$_POST['post_content']."','".xss_clean(delete($_POST['post_tag']))."','".$_SESSION['wysystem']."','".date("Y-m-d H:i:s")."','".$_SESSION['loggedip']."')";
		}
	}elseif(isset($_POST['edit']) && $_POST['edit']=="true"){
		$query="UPDATE `wy_post` SET `post_title`='".xss_clean(delete($_POST['post_title']))."',`post_category`='".xss_clean(delete($_POST['post_category']))."',`post_content`='".$_POST['post_content']."',
		`post_tag`='".xss_clean(delete($_POST['post_tag']))."' WHERE `post_id`='".xss_clean(delete($_POST['post_id']))."'";
	}elseif(isset($_GET['deletepost']) && xss_clean(delete($_GET['deletepost']))=="true" && isset($_GET['post_id'])){
		$query="DELETE FROM `wy_post` WHERE `post_id`='".delete($_GET['post_id'])."'";
	}
	if(!$raw=$conn->query($query)){
		header('Location:../mod_post.php?message=errorsql');
	}else{
		if($raw){
			header('Location:../mod_post.php?message=success');
		}
		else{
			header('Location:../mod_post.php?message=errorsql');
		}
	}
}

?>