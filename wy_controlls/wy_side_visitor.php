<?php
require_once('wy_get_ipaddress.php');
require_once('wy_user_agent.php');

$ua=getBrowser();
$browser= "Browser: " . $ua['name'] . " " . $ua['version'] . " on " .$ua['platform'] . " reports: " . $ua['userAgent'];

$queryi="INSERT INTO `wy_visitor`(`visitor_ipaddress`, `visitor_user_agent`, `visitor_date`, `visitor_online`, `visitor_online_status`) 
  VALUES ('".ip()."','".$browser."',CURDATE(),CURRENT_TIMESTAMP,1) ON DUPLICATE KEY UPDATE `visitor_online`=CURRENT_TIMESTAMP, `visitor_online_status`=1";
$row=$conn->query($queryi);
$_SESSION['visitor']=array("visitorip"=>ip(),"visitor_ua"=>$browser);

$queryoff="UPDATE `wy_visitor` SET `visitor_online_status`=0 WHERE `visitor_online` < NOW() - INTERVAL 1 HOUR";
$rawcekoff=$conn->query($queryoff);


$qt="SELECT COUNT(*) as num FROM `wy_visitor`";
$rawdata=$conn->query($qt);
$dataresult=$rawdata->fetch_assoc();
$total = $dataresult['num'];

$qd="SELECT COUNT(*) as num FROM `wy_visitor` WHERE `visitor_date` = CURDATE()";
$rawdata=$conn->query($qd);
$dataresult=$rawdata->fetch_assoc();
$today = $dataresult['num'];

$qy="SELECT COUNT(*) as num FROM `wy_visitor` WHERE `visitor_date` = DATE_ADD(CURDATE(), INTERVAL -1 DAY)";
$rawdata=$conn->query($qy);
$dataresult=$rawdata->fetch_assoc();
$yesterday = $dataresult['num'];

$qo="SELECT COUNT(*) as num FROM `wy_visitor` WHERE `visitor_online_status`=1";
$rawdata=$conn->query($qo);
$dataresult=$rawdata->fetch_assoc();
$online = $dataresult['num'];


echo "
<div id='site_sidebar'>
	<div class='side_head'>
		<p>Traffic Visitor</p>
	</div>
	<div class='side_content'>
		<div class='visitor'>
			<img src='wy_files/images/total-visitor.png'/> Total Visitor : ".$total."<br>
			<img src='wy_files/images/online-green-icon.png'/> Total Online : ".$online."<br>
			<img src='wy_files/images/total-online.png'/> Visitor Today : ".$today."<br>
			<img src='wy_files/images/offline-icon.png'/> Visitor Yesterday : ".$yesterday."<br>
			<img src='wy_files/images/online-red-icon.png'/> Your IP : ".$_SESSION['visitor']['visitorip']."<br>
		</div>
	</div>
</div>";
?>