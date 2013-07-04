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
        Logged with user: <?php echo $_SESSION['wysystem']." as ".$_SESSION['wylevel'].", "; echo $_SESSION['wydatelog'];?>. <a href="../wy_controllsl/wy_login.php?action=logout" >Log Out? <img src="images/super-mono-3d-part2-91.png"/></a>
        </div>
        <div id="siteposition" class="siteposition">
        Dashboard <img src="images/current.png"/> Manage User
        </div>
    </div>
<div id="wrapper">
  <?php require_once('mod_menu.php');?>
	<div id="usercontent">
    	<div class="usercontenttitle"><strong>Dashboard Work Form</strong></div>
        <div class="listrecent">
        <?php
		if($_SESSION['wylevel']=='member'){
			echo "<p><label ><strong>You do not have an access to this fiture</strong></label></p>";
		}else{
			// jumlah data yang akan ditampilkan per halaman
			$dataPerPage = 10;
			// apabila $_GET['page'] sudah didefinisikan, gunakan nomor halaman tersebut, 
			// sedangkan apabila belum, nomor halamannya 1.
			if(isset($_GET['page']))
			{
				$noPage = $_GET['page'];
			} 
			else $noPage = 1;
			// perhitungan offset
			$offset = ($noPage - 1) * $dataPerPage;
			// mencari jumlah semua data dalam tabel 
			$sqlpage='SELECT COUNT(*) as jumData FROM `wy_user`';
			$resultpost  = $conn->query($sqlpage);
			$data     = $resultpost->fetch_assoc();
			$jumData = $data['jumData'];
			// query SQL untuk menampilkan data perhalaman sesuai offset
			if(!isset($_GET['action']) && !isset($_GET['post_id']) && !isset($_GET['message'])){
				$query="SELECT * FROM `wy_user` ORDER BY `user_id` DESC LIMIT ".$offset.", ".$dataPerPage."";
				$row=$conn->query($query);
				$user=$row->num_rows;
				?>
				<table width="100%">
				<th id="head" class="head">Username</th>
				<th id="head" class="head">User Email</th>
				<th id="head" class="head">User Level</th>
				<th id="head" class="head" width="150">User Controll</th>
				<?php
				if($user==0)
				{
					echo "<tr><td colspan=6 class='data'>No data found in database</td></tr>";
				}
				else
				{
					while($data=$row->fetch_array()){
						echo "<tr>
						<td class='data'>".$data['user_name']."</td>
						<td class='data'>".$data['user_email']."</td>
						<td class='data'>".$data['user_level']."</td>
						<td class='data' ><a href='?see=user&action=edituser&user_id=".$data['user_id']."'> <img src='images/super-mono-3d-part2-02.png' class='imgaction'/></a>
						<a href='?see=user&action=deleteuser&user_id=".$data['user_id']."'> <img src='images/super-mono-3d-part2-96.png' class='imgaction'/> </a></td></tr>";
					}
				}
				// menentukan jumlah halaman yang muncul berdasarkan jumlah semua data
				$jumPage = ceil($jumData/$dataPerPage);
				echo "<div id='paging'>";
				// menampilkan link previous
				echo "Pages (".$jumPage.") : ";
				if ($noPage > 1) echo  "<a class='page' href='?see=user&page=".($noPage-1)."'>&lt;&lt; Prev</a>";
				// memunculkan nomor halaman dan linknya
				$showPage=0;
				for($page = 1; $page <= $jumPage; $page++)
				{
					if ((($page >= $noPage - 3) && ($page <= $noPage + 3)) || ($page == 1) || ($page == $jumPage)) 
					{   
						if (($showPage == 1) && ($page != 2))
							echo "..."; 
						if (($showPage != ($jumPage - 1)) && ($page == $jumPage))
							echo "...";
						if ($page == $noPage)
							echo " <b>".$page."</b> ";
						else 
							echo " <a class='page' href='?see=user&page=".$page."'>".$page."</a> ";
						$showPage = $page;          
					}
				}
				// menampilkan link next
				if ($noPage < $jumPage) echo "<a class='page' href='?see=user&page==".($noPage+1)."'>Next &gt;&gt;</a>";
				echo "</div>";
			?>
			</table>
			<h3>Add new user : </h3><a href='?see=user&action=newuser'><img src="images/super-mono-3d-part2-93.png"/></a><br>
			<?php
			}
			elseif(isset($_GET['see']) && isset($_GET['action']) && isset($_GET['user_id']) && delete($_GET['action'])=='edituser')
			{	
				$user_id=$conn->real_escape_string($_GET['user_id']);
				$queryp="SELECT `user_id`, `user_name`, `user_password`, `user_full_name`, `user_email`, `user_level`, `user_date_register`, `user_ipaddress_register` FROM `wy_user` WHERE `user_id`='".$user_id."'";
				$rawp=$conn->query($queryp);
				$user=$rawp->fetch_assoc();			
				?>
				<script>
				$(document).ready(function(){
					
					$("#levellist").change(function(){
						txt = document.getElementById('user_level'); 
						txt.value = $("#levellist").val();
					});
				});
				</script>
				
				<?php
					
				echo "
					<form id='edit' name='edit'>
					<input type='hidden' name='user_id' value='".$user['user_id']."'/>
					<input type='hidden' name='edit' value='true'/>
					<label ><strong>Username</strong></label><br />
					<input type='text' name='user_name' value='".$user['user_name']."' style='width:300px;'/><br/>
					<label ><strong>User Password</strong></label> <br />
					<input type='password' name='user_password' style='width:300px;' /> * Leave blank for not reset<br/>
					<label ><strong>Full Name</strong></label><br />
					<input type='text' name='full_name' value='".$user['user_full_name']."' style='width:300px;'/><br/>
					<label ><strong>Email</strong></label><br />
					<input type='text' name='user_email' value='".$user['user_email']."' style='width:300px;'/><br/>
					<label ><strong>User Level</strong></label><br />
					<input type='text' id='user_level' name='user_level' style='width:300px;' value='".$user['user_level']."' readonly='readonly'/> Choose Level: 
					<select name='levellist' id='levellist' style='width:275px;'>
						<option value=''>Choose Level</option>
						<option value='sysadmin'>Sys Administrator</option>
						<option value='member'>Member</option>
					</select>
					</form>";
				echo "<input type='image' src='images/super-mono-3d-part2-95.png' formaction='wy_controlls/mod_user.php' form='edit' formmethod='post' formenctype='multipart/form-data' class='imgaction'/>
					<input type='image' src='images/super-mono-3d-part2-85.png' onClick=\"window.location.href='mod_user.php';\" class='imgaction' />";
					
			}elseif(isset($_GET['see']) && isset($_GET['action']) && delete($_GET['action'])=='newuser')
			{
				?>
				<script>
				$(document).ready(function(){
					
					$("#levellist").change(function(){
						txt = document.getElementById('user_level'); 
						txt.value = $("#levellist").val();
					});
				});
				</script>
				
				<?php
					
				echo "
					<form id='add' name='add'>
					<input type='hidden' name='add' value='true'/>
					<label ><strong>Username</strong></label><br />
					<input type='text' name='user_name' style='width:300px;'/><br/>
					<label ><strong>User Password</strong></label><br />
					<input type='password' name='user_password' style='width:300px;'/><br/>
					<label ><strong>Full Name</strong></label><br />
					<input type='text' name='full_name' style='width:300px;'/><br/>
					<label ><strong>Email</strong></label><br />
					<input type='text' name='user_email' style='width:300px;'/><br/>
					<label ><strong>User Level</strong></label><br />
					<input type='text' id='user_level' name='user_level' style='width:275px;' readonly='readonly'/> Choose Level: 
					<select name='levellist' id='levellist' style='width:275px;'>
						<option value=''>Choose Level</option>
						<option value='sysadmin'>Sys Administrator</option>
						<option value='member'>Member</option>
					</select>
					</form>";
				echo "<input type='image' src='images/super-mono-3d-part2-95.png' formaction='wy_controlls/mod_user.php' form='add' formmethod='post' formenctype='multipart/form-data'/>
					<input type='image' src='images/super-mono-3d-part2-85.png' onClick=\"window.location.href='mod_user.php';\" />";
			}
			elseif(isset($_GET['see'])&& isset($_GET['action']) && isset($_GET['user_id']) && delete($_GET['action'])=='deleteuser'){
					$user_id=$conn->real_escape_string($_GET['user_id']);
					$query="SELECT `user_id`, `user_name`, `user_password`, `user_full_name`, `user_email`, `user_level`, `user_date_register`, `user_ipaddress_register` FROM `wy_user` WHERE `user_id`='".$user_id."'";
					$rawp=$conn->query($query);
					$data=$rawp->fetch_assoc();	
					echo "Delete this post ".$data['user_name']."?<br>";
					echo "<input type='image' src='images/super-mono-3d-part2-95.png' onClick=\"window.location.href='wy_controlls/mod_user.php?deletepost=true&user_id=".$data['user_id']."';\" class='imgaction'/>
						<input type='image' src='images/super-mono-3d-part2-85.png' onClick=\"window.location.href='mod_user.php';\" class='imgaction' />";
			}
			elseif(!isset($_GET['action']) && !isset($_GET['see']) && !isset($_GET['user_id']) && isset($_GET['message'])){
				$msg=delete(xss_clean($_GET['message']));
				if($msg=="errordata"){
					echo "<img src='images/super-mono-3d-part2-57.png'/> Cannot process with empty data! Back to <a href='mod_user.php'>User Menu.</a>";
				}elseif($msg=="errorsql"){
					echo "<img src='images/super-mono-3d-part2-57.png'/> Cannot process query with empty data or data not valid! Back to <a href='mod_user.php'>User Menu.</a>";
				}elseif($msg=="success"){
					echo "<img src='images/super-mono-3d-part2-57.png'/> Data saved! Back to <a href='mod_user.php'>User Menu.</a>";
				}else{
					header("Location:mod_user.php");
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