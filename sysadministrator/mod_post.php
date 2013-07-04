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
        Dashboard <img src="images/current.png"/> Manage Post
        </div>
    </div>
<div id="wrapper">
   <!--body-->
	<?php require_once('mod_menu.php');?>
	<div id="usercontent">
    	<div class="usercontenttitle"><strong>Dashboard Work Form</strong></div>
        <div class="listrecent">
        <?php
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
		$sqlpage='SELECT COUNT(*) as jumData FROM `wy_post`';
		$resultpost  = $conn->query($sqlpage);
		$data     = $resultpost->fetch_assoc();
		$jumData = $data['jumData'];
		// query SQL untuk menampilkan data perhalaman sesuai offset
		if(!isset($_GET['action']) && !isset($_GET['post_id']) && !isset($_GET['message'])){
			$query="SELECT * FROM `wy_post` ORDER BY post_id desc LIMIT ".$offset.", ".$dataPerPage."";
			$row=$conn->query($query);
			$post=$row->num_rows;
			?>
			<table width="100%">
			<th id="head" class="head">Post Title</th>
			<th id="head" class="head">Post Category</th>
            <th id="head" class="head">Post User</th>
			<th id="head" class="head" width="150">Post Controll</th>
			<?php
			if($post==0)
			{
				echo "<tr><td colspan=6 class='data'>No data found in database</td></tr>";
			}
			else
			{
				while($post=$row->fetch_array()){
					echo "<tr>
					<td class='data'>".$post['post_title']."</td>
					<td class='data'>".$post['post_category']."</td>
					<td class='data'>".$post['post_user']."</td>
					<td class='data' ><a href='?see=post&action=editpost&post_id=".$post['post_id']."'> <img src='images/super-mono-3d-part2-02.png' class='imgaction'/></a>
					<a href='?see=post&action=deletepost&post_id=".$post['post_id']."'> <img src='images/super-mono-3d-part2-96.png' class='imgaction'/> </a></td></tr>";
				}
			}
			// menentukan jumlah halaman yang muncul berdasarkan jumlah semua data
			$jumPage = ceil($jumData/$dataPerPage);
			echo "<div id='paging'>";
			// menampilkan link previous
			echo "Pages (".$jumPage.") : ";
			if ($noPage > 1) echo  "<a class='page' href='?see=post&page=".($noPage-1)."'>&lt;&lt; Prev</a>";
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
						echo " <a class='page' href='?see=post&page=".$page."'>".$page."</a> ";
					$showPage = $page;          
				}
			}
			// menampilkan link next
			if ($noPage < $jumPage) echo "<a class='page' href='?see=post&page==".($noPage+1)."'>Next &gt;&gt;</a>";
			echo "</div>";
		?>
		</table>
		<h3>Add new post : </h3><a href='?see=post&action=newpost'><img src="images/super-mono-3d-part2-93.png"/></a><br>
		<?php
		}
		elseif(isset($_GET['see']) && isset($_GET['action']) && isset($_GET['post_id']) && delete($_GET['action'])=='editpost')
		{	
			$queryc="SELECT `category_id`, `category_title`, `category_visible`, `category_date_add` FROM `wy_category` WHERE `category_visible`='True'";
			$rawc=$conn->query($queryc);
			$category=$rawc->num_rows;
			
			$post_id=$conn->real_escape_string($_GET['post_id']);
			$queryp="SELECT `post_id`, `post_title`, `post_category`, `post_content`, `post_tag`, `post_user`, `post_date`, `post_ip` FROM `wy_post` WHERE `post_id`='".$post_id."'";
			$rawp=$conn->query($queryp);
			$post=$rawp->fetch_assoc();			
			?>
			<script>
			$(document).ready(function(){
				
				$("#categorylist").change(function(){
					txt = document.getElementById('post_category'); 
					txt.value = $("#categorylist").val();
				});
			});
			</script>
			
			<?php
				
			echo "
				<form id='edit' name='edit'>
				<input type=\"hidden\" name=\"post_id\" value='".$post['post_id']."'/>
				<label ><strong>Post Title</strong></label><br />
				<input type='text' name='post_title' style='width:400px;' value='".$post['post_title']."'/><br/>
				<label ><strong>Post Category</strong></label><br />
				<input type='text' id='post_category' name='post_category' value='".$post['post_category']."' style='width:275px;' readonly='readonly'/><strong> Choose Category :</strong> ";
				
				echo" <select id='categorylist' name=\"categorylist\" style='width:200px;'><option>--Choose Category--</option>";
				if ($category!=0){
					while($category=$rawc->fetch_array()){
						echo "<option value=\"". $category['category_title']."\">". $category['category_title']."</option>";
					}
				}else{
					echo "<option value=\"none\">No Category</option>";	
				}
			echo"</select><br/>
				<label ><strong>Full Content</strong></label><br />
				<textarea id='content' name='post_content' rows='30' cols='80'>".htmlspecialchars($post['post_content'])."</textarea><br/>
				<label ><strong>Post Tags</strong></label><br />
				<input type='text' name='post_tag' style='width:350px;' value='".$post['post_tag']."'/> *Use comma to separate<br/>
				<input type='hidden' name='edit' value='true'/>
				<script type='text/javascript' src='js/configeditor.js'></script>
				</form>";
			echo "<input type='image' src='images/super-mono-3d-part2-95.png' formaction='wy_controlls/mod_post.php' form='edit' formmethod='post' formenctype='multipart/form-data' class='imgaction'/>
				<input type='image' src='images/super-mono-3d-part2-85.png' onClick=\"window.location.href='mod_post.php';\" class='imgaction'/>";
				
		}elseif(isset($_GET['see']) && isset($_GET['action']) && delete($_GET['action'])=='newpost')
		{
			$queryc="SELECT `category_id`, `category_title`, `category_visible`, `category_date_add` FROM `wy_category` WHERE `category_visible`='True'";
			$rawc=$conn->query($queryc);
			$category=$rawc->num_rows;	
			?>
			<script>
			$(document).ready(function(){
				
				$("#categorylist").change(function(){
					txt = document.getElementById('post_category'); 
					txt.value = $("#categorylist").val();
				});
			});
			</script>
			
			<?php
				
			echo "
				<form id='add' name='add'>
				<label ><strong>Post Title</strong></label><br />
				<input type='text' name='post_title' style='width:400px;' value=''/><br/>
				<label ><strong>Post Category</strong></label><br />
				<input type='text' id='post_category' name='post_category' value='' style='width:275px;' readonly='readonly'/><strong> Choose Category :</strong> ";
				
				echo" <select id='categorylist' name=\"categorylist\" style='width:200px;'><option value=''>--Choose Category--</option>";
				if ($category!=0){
					while($category=$rawc->fetch_array()){
						echo "<option value=\"". $category['category_title']."\">". $category['category_title']."</option>";
					}
				}else{
					echo "<option value=\"none\">No Category</option>";	
				}
			echo"</select><br/>
				<label ><strong>Full Content</strong></label><br />
				<textarea id='content' name='post_content' rows='30' cols='80'></textarea><br/>
				<label ><strong>Post Tags</strong></label><br />
				<input type='text' name='post_tag' style='width:350px;' value=''/> *Use comma to separate<br/>
				<input type='hidden' name='add' value='true'/>
				<script type='text/javascript' src='js/configeditor.js'></script>
				</form>";
			echo "<input type='image' src='images/super-mono-3d-part2-95.png' formaction='wy_controlls/mod_post.php' form='add' formmethod='post' formenctype='multipart/form-data'/>
				<input type='image' src='images/super-mono-3d-part2-85.png' onClick=\"window.location.href='mod_post.php';\" />";
		}
		elseif(isset($_GET['see']) && isset($_GET['action']) && isset($_GET['post_id']) && delete($_GET['action'])=='deletepost'){
			$post_id=$conn->real_escape_string($_GET['post_id']);
			$query="SELECT `post_id`, `post_title`, `post_category`, `post_content`, `post_tag`, `post_user`, `post_date`, `post_ip` FROM `wy_post` WHERE `post_id`='".$post_id."'";
			$row=$conn->query($query);
			$data=$row->fetch_array();
			echo "Delete this post ".$data['post_title']."?<br>";
			echo "<input type='image' src='images/super-mono-3d-part2-95.png' onClick=\"window.location.href='wy_controlls/mod_post.php?deletepost=true&post_id=".$data['post_id']."';\" class='imgaction'/>
					<input type='image' src='images/super-mono-3d-part2-85.png' onClick=\"window.location.href='mod_post.php';\" class='imgaction' />";
		}
		elseif(!isset($_GET['action']) && !isset($_GET['see']) && !isset($_GET['post_id']) && isset($_GET['message'])){
			$msg=delete(xss_clean($_GET['message']));
			if($msg=="errordata"){
				echo "<img src='images/super-mono-3d-part2-57.png'/> Cannot process with empty data! Back to <a href='mod_post.php'>Post Menu.</a>";
			}elseif($msg=="errorsql"){
				echo "<img src='images/super-mono-3d-part2-57.png'/> Cannot process query with empty data or data not valid! Back to <a href='mod_post.php'>Post Menu.</a>";
			}elseif($msg=="success"){
				echo "<img src='images/super-mono-3d-part2-57.png'/> Data saved! Back to <a href='mod_post.php'>Post Menu.</a>";
			}else{
				header("Location:mod_post.php");
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