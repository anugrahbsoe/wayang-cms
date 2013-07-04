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
        Dashboard <img src="images/current.png"/> Manage Comment
        </div>
    </div>
<div id="wrapper">
   <!--body-->
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
			$sqlc='SELECT COUNT(*) as jumData FROM `wy_comment`';
			$resultc  = $conn->query($sqlc);
			$data     = $resultc->fetch_assoc();
			$jumData = $data['jumData'];
			// query SQL untuk menampilkan data perhalaman sesuai offset
			if(!isset($_GET['action']) && !isset($_GET['comment_id']) && !isset($_GET['see']) && !isset($_GET['message'])){
				$query="SELECT `comment_id`, `comment_name`, `comment_email`, `comment_url`, `comment_content`, `comment_user_agent`, `comment_date`, `comment_user_ipaddres`, `post_title` FROM `wy_comment`, `wy_post` WHERE `wy_comment`.`post_id`=`wy_post`.`post_id` ORDER BY `comment_id` DESC LIMIT ".$offset.", ".$dataPerPage."";
				$row=$conn->query($query);
				$comment=$row->num_rows;
				?>
				<table width="100%">
				<th id="head" class="head">Comment Sender</th>
				<th id="head" class="head" >Comment Content</th>
				<th id="head" class="head" >Comment on Post</th>
				<th id="head" class="head" width="150">Comment Controll</th>
				<?php
				if($comment==0)
				{
					echo "<tr><td colspan=6 class='data'>No data found in database</td></tr>";
				}
				else
				{
					while($comment=$row->fetch_array()){
						echo "<tr>
						<td class='data'>".$comment['comment_name']."</td>
						<td class='data'>".$comment['comment_content']."</td>
						<td class='data'>".$comment['post_title']."</td>
						<td class='data' ><a href='?see=comment&action=deletecomment&comment_id=".$comment['comment_id']."'> <img src='images/super-mono-3d-part2-96.png' class='imgaction'/> </a></td></tr>";
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
			<?php
			}
			elseif(isset($_GET['see']) && isset($_GET['action']) && isset($_GET['comment_id']) && xss_clean(delete($_GET['action']))=="deletecomment" && !isset($_GET['message'])){
				$comment=$conn->real_escape_string($_GET['comment_id']);
				$query="SELECT `comment_id`, `comment_name` FROM `wy_comment` WHERE `comment_id`='".$comment."'";
				$row=$conn->query($query);
				$datacomment=$row->fetch_assoc();
				echo "Delete this comment from ".$datacomment['comment_name']."?<br>";
				echo "<input type='image' src='images/super-mono-3d-part2-95.png' onClick=\"window.location.href='wy_controlls/mod_comment.php?deletecomment=true&comment_id=".$datacomment['comment_id']."';\" class='imgaction'/>
					<input type='image' src='images/super-mono-3d-part2-85.png' onClick=\"window.location.href='mod_comment.php';\" class='imgaction' />";
			}elseif(!isset($_GET['action']) && !isset($_GET['see']) && !isset($_GET['comment_id']) && isset($_GET['message'])){
				$msg=delete(xss_clean($_GET['message']));
				if($msg=="errordata"){
					echo "<img src='images/super-mono-3d-part2-57.png'/> Cannot process with empty data! Back to <a href='mod_comment.php'>Comment Menu.</a>";
				}elseif($msg=="errorsql"){
					echo "<img src='images/super-mono-3d-part2-57.png'/> Cannot process query with empty data or data not valid! Back to <a href='mod_comment.php'>Comment Menu.</a>";
				}elseif($msg=="success"){
					echo "<img src='images/super-mono-3d-part2-57.png'/> Data saved! Back to <a href='mod_comment.php'>Comment Menu.</a>";
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
</div>  <!--end wrapper -->
</body>
</html>
<?php } ?>