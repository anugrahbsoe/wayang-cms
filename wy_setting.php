<?php
/*load all site setting
site profile(title, tag line, setting side bar(widget recent post, category, visitor, sitepartner)*/
$row=$conn->query('SELECT `site_name`, `site_tag_line`, `site_url`, `email_address`, `use_widget` FROM wy_site_profile');
$data=$row->fetch_assoc();

//mengecek apakah memakain widget sidebar atau tidak
if($data['use_widget']=='True'){
	$css='contenthalf';
}else
	$css='contentfull';
define('email',$data['email_address']);
define('site_name',$data['site_name']);
define('site_tag_line',$data['site_tag_line']);
define('site_url',$data['site_url']);
//load module bila sidebar aktif
$rowmod=$conn->query('SELECT * FROM wy_widget WHERE widget_visible=\'True\'');
$datamod=$rowmod->fetch_array();

//loag page mene(menu bar)
$rowmenu=$conn->query('SELECT * FROM wy_page WHERE page_visible=\'True\'');
$datamenu=$rowmenu->fetch_array();
?>