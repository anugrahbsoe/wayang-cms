<?php
require_once('../wy_config.php');
require_once('../wy_connection.php');
require_once('wy_xss_clean.php');
require_once('wy_quote_handler.php');
require_once('wy_get_ipaddress.php');
$op = $_GET['action'];
if($op == "getComment"){
	$post_id=$conn->real_escape_string($_GET['post_id']);
	$sqlcom="SELECT `comment_name`, `comment_content` FROM `wy_comment` WHERE `post_id`='".$post_id."'";
	$raw=$conn->query($sqlcom);
	$rawcek=$raw->num_rows;
	if($rawcek!=0){
		echo "<div ><p>Comment for this article :<p></div>";
		while ($rawdata=$raw->fetch_array()){
			echo "<div class='commentData'>";
			echo "<div class='ppcomment'><img src='wy_files/images/photo.png'></div>";
			echo "<div class='isicomment'>";
			echo "<strong>".$rawdata['comment_name']."</strong> sent comment : <br/>";	
			echo "<p style='margin-left:10px;'>".$rawdata['comment_content']."</p>";
			echo "</div></div>";
		}
	}else{
		echo "<div class='commentData'>
			no commentar for this article ...</div>";
	}
}
?>