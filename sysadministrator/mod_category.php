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
        Dashboard <img src="images/current.png"/> Manage Category
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
				if(!isset($_GET['action']) && !isset($_GET['category_id']) && !isset($_GET['message'])){
					$query='SELECT `category_id`, `category_title`, `category_visible`, `category_date_add` FROM `wy_category` ORDER BY `category_visible` desc';
					$row=$conn->query($query);
					$data=$row->num_rows;
					?>
					<table width="100%">
					<th id="head" class="head">Category Title</th>
					<th id="head" class="head" >Category Visible</th>
					<th id="head" class="head" width="150">Category Controll</th>
					<?php
					if($data==0)
					{
						echo "<tr><td colspan=6 class='data'>No data found in database</td></tr>";
					}
					else
					{
						while($data=$row->fetch_array()){
							echo "<tr>
							<td class='data'>".$data['category_title']."</td>
							<td class='data'>".$data['category_visible']."</td>
							<td class='data' ><a href='?see=category&action=editcategory&category_id=".$data['category_id']."'> <img src='images/super-mono-3d-part2-02.png' class='imgaction'/></a>
							<a href='?see=category&action=deletecategory&category_id=".$data['category_id']."'> <img src='images/super-mono-3d-part2-96.png' class='imgaction'/> </a></td></tr>";
						}
					}
					?>
					</table>
					<h3>Add new category : </h3><a href='?see=category&action=newcategory'><img src="images/super-mono-3d-part2-93.png"/></a><br>
					<?php
				}
				elseif(isset($_GET['see']) && isset($_GET['action']) && isset($_GET['category_id']) && delete($_GET['action'])=='editcategory')
				{
					//jika ada parameter action edit di url
					$category=$conn->real_escape_string($_GET['category_id']);
					$query="SELECT `category_id`, `category_title`, `category_visible`, `category_date_add` FROM `wy_category` WHERE `category_id`='".$category."'";
					$row=$conn->query($query);
					$data=$row->fetch_array();
					?>
					<script>
					$(document).ready(function(){
						
						$("#category_active").change(function(){
							txt = document.getElementById('category_visible'); 
							txt.value = $("#category_active").val();
						});
					});
					</script>
					<?php
						
					echo "
						<form id='edit' name='edit'>
						<input type='hidden' name='category_id' value='".$data['category_id']."'/>
						<label ><strong>Category Title</strong></label><br />
						<input type='text' name='category_title' style='width:275px;' value='".$data['category_title']."'/><br/>
						<label ><strong>Page Visible</strong></label><br />
						<input type='text' id='category_visible' name='category_visible' style='width:275px;' value='".$data['category_visible']."' readonly='readonly'/> Choose Visible: 
						<select name='category_active' id='category_active' style='width:275px;'>
						<option value=''>Choose Active</option>
						<option value='True'>Active</option>
						<option value='False'>Non Active</option>
						</select><br/>
						<input type='hidden' name='edit' value='true'/>
						</form>";
					echo "<input type='image' src='images/super-mono-3d-part2-95.png' formaction='wy_controlls/mod_category.php' form='edit' formmethod='post' formenctype='multipart/form-data' class='imgaction'/>
						<input type='image' src='images/super-mono-3d-part2-85.png' onClick=\"window.location.href='mod_category.php';\" class='imgaction' />";
					
				}elseif(isset($_GET['see']) && isset($_GET['action']) && delete($_GET['action'])=='newcategory')
				{
					?>
					 <script>
					$(document).ready(function(){
						
						$("#category_active").change(function(){
							txt = document.getElementById('category_visible'); 
							txt.value = $("#category_active").val();
						});
					});
					</script>
					<?php
						
					echo "
						<form id='add' name='add'>
						<input type='hidden' name='category_id' value=''/>
						<label ><strong>Category Title</strong></label><br />
						<input type='text' name='category_title' style='width:275px;' value=''/><br/>
						<label ><strong>Page Visible</strong></label><br />
						<input type='text' id='category_visible' name='category_visible' style='width:275px;' value='' readonly='readonly'/> Choose Visible: 
						<select name='category_active' id='category_active' style='width:275px;'>
						<option value=''>Choose Active</option>
						<option value='True'>Active</option>
						<option value='False'>Non Active</option>
						</select><br/>
						<input type='hidden' name='add' value='true'/>
						</form>";
					echo "<input type='image' src='images/super-mono-3d-part2-95.png' formaction='wy_controlls/mod_category.php' form='add' formmethod='post' formenctype='multipart/form-data' class='imgaction'/>
						<input type='image' src='images/super-mono-3d-part2-85.png' onClick=\"window.location.href='mod_category.php';\" class='imgaction' />";
				}
				elseif(isset($_GET['see']) && isset($_GET['action']) && isset($_GET['category_id']) && delete($_GET['action'])=='deletecategory')
				{
					$category=$conn->real_escape_string($_GET['category_id']);
					$query="SELECT `category_id`, `category_title`, `category_visible`, `category_date_add` FROM `wy_category` WHERE `category_id`='".$category."'";
					$row=$conn->query($query);
					$data=$row->fetch_array();
					echo "Delete this category ".$data['category_title']."?<br>";
					echo "<input type='image' src='images/super-mono-3d-part2-95.png' onClick=\"window.location.href='wy_controlls/mod_category.php?deletecategory=true&category_id=".$data['category_id']."';\" class='imgaction'/>
						<input type='image' src='images/super-mono-3d-part2-85.png' onClick=\"window.location.href='mod_category.php';\" class='imgaction' />";
				}
				elseif(!isset($_GET['action']) && !isset($_GET['see']) && !isset($_GET['category_id']) && isset($_GET['message'])){
					$msg=delete(xss_clean($_GET['message']));
					if($msg=="errordata"){
						echo "<img src='images/super-mono-3d-part2-57.png'/> Cannot process with empty data! Back to <a href='mod_category.php'>Category Menu.</a>";
					}elseif($msg=="errorsql"){
						echo "<img src='images/super-mono-3d-part2-57.png'/> Cannot process query with empty data or data not valid! Back to <a href='mod_category.php'>Category Menu.</a>";
					}elseif($msg=="success"){
						echo "<img src='images/super-mono-3d-part2-57.png'/> Data saved! Back to <a href='mod_category.php'>Category Menu.</a>";
					}else{
						header("Location:mod_category.php");
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