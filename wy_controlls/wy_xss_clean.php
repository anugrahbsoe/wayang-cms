<?php
function xss_clean($input) {
    $str=filter_var($input, FILTER_SANITIZE_STRING);
	return $str; 
}
?>