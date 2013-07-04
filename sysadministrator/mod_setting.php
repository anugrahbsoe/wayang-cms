<?php
session_start();
require_once('../wy_config.php');
require_once('../wy_connection.php');
require_once('../wy_controlls/wy_quote_handler.php');
require_once('../wy_controlls/wy_xss_clean.php');

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
        <?php
		if($_SESSION['wylevel']=='member'){
			echo "<p><label ><strong>You do not have an access to this fiture</strong></label></p>";
		}else{
		   //cek apa ad parameter di url, jika tidak ambil seluruh data page
				if(!isset($_GET['action']) && !isset($_GET['see']) && !isset($_GET['message'])){
					echo "<p><label ><strong>Currently Setting of Your Site</strong></label></p>";
					$query="SELECT `site_name`, `site_tag_line`, `site_url`, `email_address`, `use_widget` FROM `wy_site_profile`";
					$row=$conn->query($query);
					$data=$row->fetch_array();
					echo "<label ><strong>Site Name</strong></label><br />
						<p>".$data['site_name']."</p><br/>
						<label ><strong>Site Tag Line</strong></label><br />
						<p>".$data['site_tag_line']."</p><br/>
						<label ><strong>Site URL</strong></label><br />
						<p>".$data['site_url']."</p><br/>
						<label ><strong>Email Address</strong></label><br />
						<p>".$data['email_address']."</p><br/>
						<label ><strong>Use Widget</strong></label><br />
						<p>".$data['use_widget']."</p>
						";
					echo "<a href='?see=setting&action=editsetting'><img src='images/super-mono-3d-part2-02.png' class='imgaction'/> Edit Setting?</a>";
				}
				elseif(isset($_GET['see']) && isset($_GET['action']) && delete($_GET['action'])=='editsetting' && !isset($_GET['message']))
				{
					//jika ada parameter action edit di url
					$query="SELECT `site_name`, `site_tag_line`, `site_url`, `email_address`, `use_widget` FROM `wy_site_profile`";
					$row=$conn->query($query);
					$data=$row->fetch_array();
					?>
					 <script>
						$(document).ready(function(){
							
							$("#widget").change(function(){
								txt = document.getElementById("use_widget"); 
								txt.value = $("#widget").val();
							});
						});
						</script>
					<?php
					echo "			
						<form id='edit' id='edit'>
						<label ><strong>Site Name</strong></label><br />
						<input type='text' name='site_name' style='width:275px;' value='".$data['site_name']."'/><br/>
						<label ><strong>Site Tag Line</strong></label><br />
						<input type='text' name='site_tag_line'' style='width:275px;' value='".$data['site_tag_line']."'/><br/>
						<label ><strong>Site URL</strong></label><br />
						<input type='text' name='site_url' style='width:275px;' value='".$data['site_url']."' readonly='readonly'/><br/>
						<label ><strong>Email Address</strong></label><br />
						<input type='text' name='email_address' style='width:275px;' value='".$data['email_address']."'/><br/>
						<label ><strong>Use Widget</strong></label><br />
						<input type='text' id='use_widget' name='use_widget' style='width:275px;' value='".$data['use_widget']."' readonly='readonly'/> Choose Use Widget: 
						<select name='widget' id='widget' style='width:275px;'>
						<option value=''>Choose Active</option>
						<option value='True'>Active</option>
						<option value='False'>Non Active</option>
						</select>
						</form>";
					echo "<input type='image' src='images/super-mono-3d-part2-95.png' formaction='wy_controlls/mod_setting.php' form='edit' formmethod='post' formenctype='multipart/form-data' class='imgaction'/>
						<input type='image' src='images/super-mono-3d-part2-85.png' onClick=\"window.location.href='mod_setting.php';\" class='imgaction' />";
					
				}
				elseif(!isset($_GET['action']) && !isset($_GET['see']) && isset($_GET['message'])){
					$msg=delete(xss_clean($_GET['message']));
					if($msg=="errordata"){
						echo "<img src='images/super-mono-3d-part2-57.png'/> Cannot process with empty data! Back to <a href='mod_setting.php'>Setting Menu.</a>";
					}elseif($msg=="errorsql"){
						echo "<img src='images/super-mono-3d-part2-57.png'/> Cannot process query with empty data or data not valid! Back to <a href='mod_setting.php'>Setting Menu.</a>";
					}elseif($msg=="success"){
						echo "<img src='images/super-mono-3d-part2-57.png'/> Data saved! Back to <a href='mod_setting.php'>Setting Menu.</a>";
					}else{
						header("Location:mod_setting.php");
					}
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