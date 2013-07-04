<?php
session_start();
require_once('../wy_config.php');
require_once('../wy_connection.php');
require_once('../wy_controlls//wy_quote_handler.php');

if(!isset($_SESSION['wysystem']) && !isset($_SESSION['loggedip']) && !isset($_SESSION['wylevel']) && !isset($_SESSION['wydatelog']) 
|| $_SESSION['wysystem']=="" || $_SESSION['wysystem']==NULL && $_SESSION['loggedip']=="" || $_SESSION['loggedip']==NULL 
&& $_SESSION['wylevel']="" || $_SESSION['wylevel']==NULL && $_SESSION['wydatelog']="" || $_SESSION['wydatelog']==NULL){
	session_destroy();
	header('Location:../wy_login.php');
}
else{
?>
<!DOCTYPE html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="icon" href="../wy_files/images/wy-icon.ico" type="image/x-icon" />
<title>Dashboard Admin</title>
<link rel="stylesheet" type="text/css" href="css/user.css"/>
<link rel="stylesheet" href="css/bootstrap.min.css">
</head>
<body bgproperties="fixed">

	<!--header-->
    <div id="header">
    	<a href="index.php">
        	<img src="images/logo.png" alt="" style="margin:5px 10px 0 5px;float:left;"/>
        </a>
        <div class="sitetitle">
        Wayang CMS Administration
        </div>
        <div class="headermenu">
        Logged with user: <?php echo $_SESSION['wysystem']." as ".$_SESSION['wylevel'].", "; echo $_SESSION['wydatelog'];?>. <a href="../wy_controlls/wy_login.php?action=logout" >Log Out? <img src="images/super-mono-3d-part2-91.png"/></a>
        </div>
        <div id="siteposition" class="siteposition">
        Dashboard <img src="images/current.png"/> Home
        </div>
    </div>
<div id="wrapper">
   <!--body-->
	<?php require_once('mod_menu.php');?>
	<div id="usercontent">
    	<div class="usercontenttitle"><strong>Dashboard Work Form</strong></div>
        <div class="listrecent">
        <?php
		echo "<p>Welcome ".$_SESSION['wysystem'].", below is currently statictic of your site. Enjoyt it!</p>";
		require_once('mod_all.php');
		?>
        </div>
    </div>  
    <!--footer-->
    <div id="footer">
    	<div class="copyright">
            <p>&copy; 2012. all right reserved
            <br/>
             powered by <a href="http://www.wayang-cms.org" class="copyright"/>Wayang CMS</a></p>
    	</div>
  	</div>
</div>   
</body>
</html>
<?php } ?>