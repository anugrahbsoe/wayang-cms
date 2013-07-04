<?php
session_start();
require_once('../wy_config.php');
require_once('../wy_connection.php');
require_once('../wy_controlls//wy_quote_handler.php');
require_once('../wy_controlls//wy_xss_clean.php');

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
<link rel='stylesheet' type='text/css' href='css/bootstrap.min.css'/>
<script type="text/javascript" src="../wy_files/js/jquery-latest.js"></script>
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
        Dashboard <img src="images/current.png"/> Manage Setting
        </div>
    </div>
<div id="wrapper">
   <!--body-->
	<?php
	//panggil menu
	 require_once('mod_menu.php');?>      
     <div id="usercontent">
    	<div class="usercontenttitle"><strong>Dashboard Work Form</strong></div>
        <div class="listrecent">

        <script type="text/javascript">
			var npwd;
			var cpwd;
			$(document).ready(function(){
				$("#cnew_password").keyup(function(){
				npwd = $("#new_password").val();
				cpwd = $("#cnew_password").val();
				if(cpwd!=npwd){
					$("#nreport").html("<font color='red'> <img src='images/super-mono-3d-part2-57.png' width='20' height='20'/> Password not match!</font>");
				}
				else if(cpwd==npwd){
					$("#nreport").html("");
				}
				return false;
				});
			});
		</script>
        <?php
			if(!isset($_GET['message'])){
				echo "			
				<form id='edit' id='edit' >
				<label ><strong>Change password for ".$_SESSION['wysystem']."</strong></label><br />
				<label ><strong>New Password</strong></label><br />
				<input type='password' id='new_password' name='new_password' style='width:275px;' /><br/>
				<label ><strong>Confirm New Password</strong></label><br />
				<input type='password' id='cnew_password' name='cnew_password' style='width:275px;' /><span id='nreport'></span><br/>
				</form>";
			echo "<input type='image' src='images/super-mono-3d-part2-95.png' formaction='wy_controlls/mod_password.php' form='edit' formmethod='post' formenctype='multipart/form-data' class='imgaction'/>
				<input type='image' src='images/super-mono-3d-part2-85.png' onClick=\"window.location.href='mod_password.php';\" class='imgaction' />";
					
			}elseif(isset($_GET['message'])){
				$msg=delete(xss_clean($_GET['message']));
				if($msg=="errordata"){
					echo "<img src='images/super-mono-3d-part2-57.png'/> Cannot process with empty or not match data! Back to <a href='mod_password.php'>Change Password.</a>";
				}elseif($msg=="errorsql"){
					echo "<img src='images/super-mono-3d-part2-57.png'/> Cannot process query with empty data or data not valid! Back to <a href='mod_password.php'>Change Password.</a>";
				}elseif($msg=="success"){
					echo "<img src='images/super-mono-3d-part2-57.png'/> Data saved! Back to <a href='mod_password.php'>Change Password.</a>";
				}else{
					header("Location:mod_password.php");
				}
			}
			
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