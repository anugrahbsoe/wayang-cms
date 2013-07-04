<?php
$conn=new mysqli(dbhost,dbuser,dbpassword,dbname);
if($conn->connect_errno)
	echo 'could not connect to the specify server with error "'.$conn->connect_error.'"';
?>