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
<script type="text/javascript" src="js/ckeditor.js"></script>
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
        Dashboard <img src="images/current.png"/> Manage Page
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
				if(!isset($_GET['action']) && !isset($_GET['page_id']) && !isset($_GET['message'])){
					$query='SELECT * FROM wy_page';
					$row=$conn->query($query);
					$data=$row->num_rows;
					?>
					<table width="100%">
					<th id="head" class="head">Page Name</th>
					<th id="head" class="head" >Page Visible</th>
					<th id="head" class="head" width="150">Page Controll</th>
					<?php
					
					if($data==0)
					{
						echo "<tr><td colspan=6 class='data'>No data found in database</td></tr>";
					}
					else
					{
						while($data=$row->fetch_array()){
							echo "<tr>
							<td class='data'>".$data['page_name']."</td>
							<td class='data'>".$data['page_visible']."</td>
							<td class='data' ><a href='?see=page&action=editpage&page_id=".$data['page_id']."'> <img src='images/super-mono-3d-part2-02.png' class='imgaction'/></a>
							<a href='?see=page&action=deletepage&page_id=".$data['page_id']."'> <img src='images/super-mono-3d-part2-96.png' class='imgaction'/> </a></td></tr>";
						}
					}
					?>
					</table>
					<h3>Add new page : </h3><a href='?see=page&action=newpage'><img src="images/super-mono-3d-part2-93.png"/></a><br>
					<?php
				}
				elseif(isset($_GET['see']) && isset($_GET['action']) && isset($_GET['page_id']) && delete($_GET['action'])=='editpage')
				{
					//jika ada parameter action edit di url
					$page_id=$conn->real_escape_string($_GET['page_id']);
					$queryp="SELECT `page_id`, `page_name`, `page_visible`, `page_module`, `page_content`, `page_date_add` FROM `wy_page` WHERE `page_id`='".$page_id."'";
					$rawp=$conn->query($queryp);
					$page=$rawp->fetch_assoc();
					
					$querym="SELECT `module_id`, `module_name`, `module_date_add` FROM `wy_module`";
					$rawm=$conn->query($querym);
					$module=$rawm->num_rows;
					
					?>
					
					<script>
					$(document).ready(function(){
						
						$("#page_active").change(function(){
							txt = document.getElementById('page_visible'); 
							txt.value = $("#page_active").val();
						});
					});
					</script>
					
					<script>
					$(document).ready(function(){
						
						$("#modulelist").change(function(){
							txt = document.getElementById('page_module'); 
							txt.value = $("#modulelist").val();
						});
					});
					</script>
					<?php
						
					echo "
						<form id='edit' id='edit'>
						<input type='hidden' name='page_id' value='".$page['page_id']."'/>
						<label ><strong>Page Name</strong></label><br />
						<input type='text' name='page_name' style='width:275px;' value='".$page['page_name']."'/><br/>
						<label ><strong>Page Visible</strong></label><br />
						<input type='text' id='page_visible' name='page_visible' style='width:275px;' value='".$page['page_visible']."' readonly='readonly'/> Choose Visible: 
						<select name='page_active' id='page_active' style='width:275px;'>
						<option value=''>Choose Active</option>
						<option value='True'>Active</option>
						<option value='False'>Non Active</option>
						</select><br/>
						<label ><strong>Page Module</strong></label><br />
						<input type='text' id='page_module' name='page_module' style='width:275px;' value='".$page['page_module']."' readonly='readonly'/> Choose Module : 
						<select id='modulelist' name=\"modulelist\" style='width:200px;'><option value=''>--Choose Module--</option>";
						if ($module!=0){
							while($module=$rawm->fetch_array()){
								echo "<option value=\"". $module['module_name']."\">". $module['module_name']."</option>";
							}
						}else{
							echo "<option value=\"none\">No Category</option>";	
						}
					echo"</select> *Leave blank if not use module<br/>
						<label ><strong>Page Content</strong> *Leave blank if use module</label><br />
						<textarea id='content' name='page_content' rows='50' cols='100%'>".htmlspecialchars($page['page_content'])."</textarea><br/>
						<input type='hidden' name='edit' value='true'/>
						<script type='text/javascript' src='js/configeditor.js'></script>
						</form>";
					echo "<input type='image' src='images/super-mono-3d-part2-95.png' formaction='wy_controlls/mod_page.php' form='edit' formmethod='post' formenctype='multipart/form-data' class='imgaction'/>
						<input type='image' src='images/super-mono-3d-part2-85.png' onClick=\"window.location.href='mod_page.php';\" class='imgaction' />";
					
				}elseif(isset($_GET['see']) && isset($_GET['action']) && delete($_GET['action'])=='newpage')
				{
					//jika ada parameter action edit di url
					$querym="SELECT `module_id`, `module_name`, `module_date_add` FROM `wy_module`";
					$rawm=$conn->query($querym);
					$module=$rawm->num_rows;
					
					?>
					
					<script>
					$(document).ready(function(){
						
						$("#page_active").change(function(){
							txt = document.getElementById('page_visible'); 
							txt.value = $("#page_active").val();
						});
					});
					</script>
					
					<script>
					$(document).ready(function(){
						
						$("#modulelist").change(function(){
							txt = document.getElementById('page_module'); 
							txt.value = $("#modulelist").val();
						});
					});
					</script>
					<?php
						
					echo "
						<form id='add' id='add'>
						<label ><strong>Page Name</strong></label><br />
						<input type='text' name='page_name' style='width:275px;' value=''/><br/>
						<label ><strong>Page Visible</strong></label><br />
						<input type='text' id='page_visible' name='page_visible' style='width:275px;' value='' readonly='readonly'/> Choose Visible : 
						<select name='page_active' id='page_active' style='width:275px;'>
						<option value=''>Choose Active</option>
						<option value='True'>Active</option>
						<option value='False'>Non Active</option>
						</select><br />
						<label ><strong>Page Module</strong></label><br />
						<input type='text' id='page_module' name='page_module' style='width:275px;' value='' readonly='readonly'/> Choose Module : 
						<select id='modulelist' name=\"modulelist\" style='width:200px;'><option value=''>--Choose Module--</option>";
						if ($module!=0){
							while($module=$rawm->fetch_array()){
								echo "<option value=\"". $module['module_name']."\">". $module['module_name']."</option>";
							}
						}else{
							echo "<option value=\"none\">No Category</option>";	
						}
					echo"</select> *Leave blank if not use module<br/>
						<label ><strong>Page Content</strong> *Leave blank if use module</label><br />
						<textarea id='content' name='page_content' rows='50%' cols='100%'></textarea><br/>
						<input type='hidden' name='add' value='true'/>
						<script type='text/javascript' src='js/configeditor.js'></script>
						</form>";
					echo "<input type='image' src='images/super-mono-3d-part2-95.png' formaction='wy_controlls/mod_page.php' form='add' formmethod='post' formenctype='multipart/form-data' class='imgaction'/>
						<input type='image' src='images/super-mono-3d-part2-85.png' onClick=\"window.location.href='mod_page.php';\" class='imgaction' />";
				}
				elseif(isset($_GET['see']) && isset($_GET['action']) && isset($_GET['page_id']) && delete($_GET['action'])=='deletepage'){
					$page_id=$conn->real_escape_string($_GET['page_id']);
					$queryp="SELECT `page_id`, `page_name`, `page_visible`, `page_module`, `page_content`, `page_date_add` FROM `wy_page` WHERE `page_id`='".$page_id."'";
					$rawp=$conn->query($queryp);
					$page=$rawp->fetch_assoc();
					echo "Delete this page ".$page['page_name']."?<br>";
					echo "<input type='image' src='images/super-mono-3d-part2-95.png' onClick=\"window.location.href='wy_controlls/mod_page.php?deletepage=true&page_id=".$page['page_id']."';\" class='imgaction'/>
						<input type='image' src='images/super-mono-3d-part2-85.png' onClick=\"window.location.href='mod_page.php';\" class='imgaction' />";
				}
				elseif(!isset($_GET['action']) && !isset($_GET['see']) && !isset($_GET['page_id']) && isset($_GET['message'])){
					$msg=delete(xss_clean($_GET['message']));
					if($msg=="errordata"){
						echo "<img src='images/super-mono-3d-part2-57.png'/> Cannot process with empty data! Back to <a href='mod_page.php'>Page Menu.</a>";
					}elseif($msg=="errorsql"){
						echo "<img src='images/super-mono-3d-part2-57.png'/> Cannot process query with empty data or data not valid! Back to <a href='mod_page.php'>Page Menu.</a>";
					}elseif($msg=="success"){
						echo "<img src='images/super-mono-3d-part2-57.png'/> Data saved! Back to <a href='mod_page.php'>Page Menu.</a>";
					}elseif($msg=="restrictdata"){
						echo "<img src='images/super-mono-3d-part2-57.png'/> Can not delete this data! Back to <a href='mod_page.php'>Page Menu.</a>";
					}else{
						header("Location:mod_page.php");
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