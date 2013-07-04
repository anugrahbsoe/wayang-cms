<?php
session_start();
require_once('../wy_config.php');
require_once('../wy_connection.php');
require_once('../wy_controlls/wy_quote_handler.php');

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
        Dashboard <img src="images/current.png"/> Manage Widget
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
				if(!isset($_GET['action']) && !isset($_GET['widget_id'])){
					$query="SELECT * FROM `wy_widget` ORDER BY `widget_visible` DESC";
					$row=$conn->query($query);
					$data=$row->num_rows;
					?>  
					<table width="100%">
					<th id="head" class="head">Widget Title</th>
					<th id="head" class="head" >Widget Visible</th>
					<th id="head" class="head" width="150">Widget Controll</th>
					<?php
					while($data=$row->fetch_array()){
						echo "<tr>
							<td class='data'>".$data['widget_title']."</td>
							<td class='data'>".$data['widget_visible']."</td>
							<td class='data' ><a href='?see=widget&action=editwidget&widget_id=".$data['widget_id']."'> <img src='images/super-mono-3d-part2-02.png' class='imgaction'/></a>
							</td></tr>";
					}
					?>
					</table>
					
					<?php
				}
				elseif(isset($_GET['see']) && isset($_GET['action']) && isset($_GET['widget_id']) && delete($_GET['action'])=='editwidget')
				{
					//jika ada parameter action edit di url
					$widget=$conn->real_escape_string($_GET['widget_id']);
					$query="SELECT `widget_id`, `widget_title`, `widget_visible`, `widget_module` FROM `wy_widget` WHERE `widget_id`='".$widget."'";
					$row=$conn->query($query);
					$data=$row->fetch_array();
					?>
					<script>
					$(document).ready(function(){
						
						$("#widget_active").change(function(){
							txt = document.getElementById('widget_visible'); 
							txt.value = $("#widget_active").val();
						});
					});
					</script>
					<?php
						
					echo "
						<form id='edit' id='edit'>
						<input type='hidden' name='widget_id' value='".$data['widget_id']."'/>
						<label ><strong>Widget Title</strong></label><br />
						<input type='text' name='widget_title' style='width:275px;' value='".$data['widget_title']."' readonly='readonly'/><br/>
						<label ><strong>Page Visible</strong></label><br />
						<input type='text' id='widget_visible' name='widget_visible' style='width:275px;' value='".$data['widget_visible']."' readonly='readonly'/> Choose Visible: 
						<select name='widget_active' id='widget_active' style='width:275px;'>
						<option value=''>Choose Active</option>
						<option value='True'>Active</option>
						<option value='False'>Non Active</option>
						</select><br/>
						<script type='text/javascript' src='js/configeditor.js'></script>
						</form>";
					echo "<input type='image' src='images/super-mono-3d-part2-95.png' formaction='wy_controlls/mod_widget.php' form='edit' formmethod='post' formenctype='multipart/form-data' class='imgaction'/>
						<input type='image' src='images/super-mono-3d-part2-85.png' onClick=\"window.location.href='mod_widget.php';\" class='imgaction' />";
					
				}elseif(!isset($_GET['action']) && !isset($_GET['see']) && !isset($_GET['widget_id']) && isset($_GET['message'])){
					$msg=delete(xss_clean($_GET['message']));
					if($msg=="errordata"){
						echo "<img src='images/super-mono-3d-part2-57.png'/> Cannot process with empty data! Back to <a href='mod_widget.php'>Widget Menu.</a>";
					}elseif($msg=="errorsql"){
						echo "<img src='images/super-mono-3d-part2-57.png'/> Cannot process query with empty data or data not valid! Back to <a href='mod_widget.php'>Widget Menu.</a>";
					}elseif($msg=="success"){
						echo "<img src='images/super-mono-3d-part2-57.png'/> Data saved! Back to <a href='mod_widget.php'>Widget Menu.</a>";
					}else{
						header("Location:mod_widget.php");
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