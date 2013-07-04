<?php
function delete($val){
	$arr=array("'",'-');
	return $new=str_replace($arr,"",$val);
	}
?>