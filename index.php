<?php
session_start();
if (!file_exists('wy_config.php')) {

	echo "<script>document.location.href = 'wy_install/';</script>";
}
else {

//meng include file2 penting
require_once('wy_config.php');
require_once('wy_connection.php');
require_once('wy_setting.php');
require_once('wy_controlls/wy_xss_clean.php');
require_once('wy_controlls/wy_quote_handler.php');
echo "
<!DOCTYPE html> 
<html>
<head>
  <title>".site_name." | ".site_tag_line."</title>
  <link rel='icon' href='wy_files/images/wy-icon.ico'/>
  <meta name='description' content='".site_tag_line."' />
  <meta name='keywords' content='website keywords, website keywords' />
  <meta http-equiv='content-type' content='text/html; charset=windows-1252' />
  <link rel='stylesheet' type='text/css' href='wy_files/css/style.css' />
  <!-- modernizr enables HTML5 elements and feature detects -->
  <script type='text/javascript' src='wy_files/js/modernizr-2.6.1.min.js'></script>
  <script type='text/javascript' src='wy_files/js/jquery-latest.js'></script>
</head>
<body onload='new_captcha();'>
	<div id='wrapper'>
    	<div id='banner'>
		<a href='".site_url."' ><img src='wy_files/images/wy_logo.png' alt='".site_tag_line."'/></a>
        </div>
		";
		//memanggil file menu
        require('wy_controlls/wy_menu.php');
       echo" <div class='slideshow'>
	    	<ul class='slideshow'>
          		<li class='show'><img width='980' height='250' src='wy_files/images/home_1.jpg' alt='&quot;Enter your caption here&quot;' /></li>
          		<li><img width='980' height='250' src='wy_files/images/home_2.jpg' alt='&quot;Enter your caption here&quot;' /></li>
        	</ul> 
	  	</div>
        
		<div id='site_".$css."'>";
		//mengecek apakah ada menu page yang terplih atau tidak
		if(!isset($_GET['page_id']) && !isset($_GET['category_id']) && !isset($_GET['author']) && !isset($_GET['post_id']) && !isset($_GET['searchkey'])){
			require_once('wy_controlls/wy_home.php');
		}elseif(isset($_GET['page_id']) && !isset($_GET['category_id']) && !isset($_GET['author']) && !isset($_GET['post_id']) && !isset($_GET['searchkey'])){
			//bilah terpilih, data modul menu page di ambil
			$page=$conn->real_escape_string($_GET['page_id']); //<-menangkal serangan sql injection
			$rescontent=$conn->query("SELECT * FROM wy_page WHERE page_id='".$page."'");
			$content=$rescontent->fetch_assoc();
			if($content['page_module']=="" || $content['page_module']==NULL){
				echo "
				<div class='".$css."_imagetext'>
					".$content['page_content']."
				</div><!--close content_imagetext-->
				";
				
			}elseif($content['page_module']!="" || $content['page_module']!=NULL){
				require_once("wy_controlls/wy_".$content['page_module'].".php");			
			}

		}elseif(isset($_GET['category_id']) && !isset($_GET['page_id'])  && !isset($_GET['author']) && !isset($_GET['post_id']) && !isset($_GET['searchkey'])){
			$category_id=$conn->real_escape_string(xss_clean($_GET['category_id'])); 
			$rescontent=$conn->query("SELECT `wy_post`.`post_id`, `wy_post`.`post_title`, `wy_post`.`post_user`, DATE_FORMAT(`wy_post`.`post_date`,'%d %M %Y') as date FROM `wy_post`, `wy_category`
WHERE  `wy_post`.`post_category`=`wy_category`.`category_title` AND `wy_category`.`category_id`='".$category_id."' ORDER BY `post_id`");
			$content=$rescontent->num_rows;
			if($content==0){
				echo "<div class='content_title'>
						No data found!.
					</div>";
			}else{
				while($content=$rescontent->fetch_array()){
					echo "<div class='".$css."_imagetext'>
					<div class='content_title'>
						<a href='?post_id=".$content['post_id']."'>".$content['post_title']."</a><br>
						<h4>Posted by <a href='?author=".$content['post_user']."'>".$content['post_user']." </a>, ".$content['date']."</h4>
					</div></div> <br>";
				}
			}
			
		}elseif(isset($_GET['author']) && !isset($_GET['page_id'])  && !isset($_GET['category_id']) && !isset($_GET['post_id']) && !isset($_GET['searchkey'])){
			$author=$conn->real_escape_string(xss_clean($_GET['author'])); 
			$rescontent=$conn->query("SELECT `post_id`, `post_title`, `post_user`, DATE_FORMAT(`post_date`,'%d %M %Y') as date FROM `wy_post` WHERE `post_user`='".$author."' ORDER BY `post_id`");
			$content=$rescontent->num_rows;
			if($content==0){
				echo "<div class='content_title'>
						No data found!.
					</div>";
			}else{
				while($content=$rescontent->fetch_array()){
					echo "<div class='".$css."_imagetext'>
					<div class='content_title'>
						<a href='?post_id=".$content['post_id']."'>".$content['post_title']."</a><br>
						<h4>Posted by <a href='?author=".$content['post_user']."'>".$content['post_user']." </a>, ".$content['date']."</h4>
					</div></div> <br>";
				}
			}		
			
		}elseif(isset($_GET['post_id']) && !isset($_GET['page_id'])  && !isset($_GET['category_id']) && !isset($_GET['author']) && !isset($_GET['searchkey'])){
			$post_id=$conn->real_escape_string(xss_clean($_GET['post_id'])); 
			$rescontent=$conn->query("SELECT `wy_post`.`post_id`, `wy_post`.`post_title`, `wy_post`.`post_category`, `wy_post`.`post_content`, `wy_post`.`post_tag`, `wy_post`.`post_user`, DATE_FORMAT(`wy_post`.`post_date`,'%d %M %Y') as date, `wy_category`.`category_id` FROM `wy_post`, `wy_category`
WHERE  `wy_post`.`post_category`=`wy_category`.`category_title` AND `post_id`='".$post_id."'");
			$content=$rescontent->num_rows;
			if($content==0){
				echo "<div class='content_title'>
						No data found!.
					</div>";
			}else{
				while($content=$rescontent->fetch_assoc()){
					echo "
					<div class='".$css."_imagetext'>
						<div class='content_title'>
							".$content['post_title']."<br>
							<h4>Posted by <a href='?author=".$content['post_user']."'>".$content['post_user']." </a>, ".$content['date']."</h4>
							<h4>Category <a href='?catedory_id=".$content['category_id']."'>".$content['post_category']."</a>, Tags : ".$content['post_tag']."</h4>
						</div>
						".$content['post_content'];
					echo "</div><!--close content_imagetext-->";
				}
				echo "<div class='".$css."_imagetext'>";
				?>
                <script type="text/javascript">
                var name;
				var email;
				var comment;
				var url;
				var captcha;
				var data;
				var post_id;
				$(document).ready(function(){
					$("#commentall").load("wy_controlls/wy_comment.php","action=getComment&post_id="+<?php echo $_GET['post_id'];?>);
					$("#sendcomment").click(function(e){
						e.preventDefault();
						name = $("#cname").val();
						email = $("#cemail").val();
						comment = $("#ccomment").val();
						captcha = $("#txtCaptcha").val();
						post_id = $("#post_id").val()
						url = $("#curl").val();
						if( name =="" || email == "" || comment == "" ||captcha == "" ){
							$("#report").html("<img src='wy_files/images/error.png' />Could not sending comment..");
						}else{
							data = "&post_id="+post_id+"&name="+name+"&email="+email+"&url="+url+"&comment="+comment+"&captcha="+captcha;
							$("#report").html("<img src='wy_files/images/loading.gif' /> Sending comment... ");
							$.ajax({
								type : "post",
								url: "wy_controlls/wy_submitcomment.php",
								data: "action=add"+data,
								cache: false,
								success: function(msg){
									if(msg=="sukses"){
										$("#report").html("<img src='wy_files/images/tick.png' /> Comment sent...");
										$("#cname").val("");
										$("#cemail").val("");
										$("#ccomment").val("");
										$("#curl").val("");
										$("#txtCaptcha").val("");
										img = document.getElementById('captcha'); 
										img.src = 'wy_controlls/wy_image.php';
										$("#commentall").load("wy_controlls/wy_comment.php","action=getComment&post_id="+post_id);
									}else{
										$("#report").html("<img src='wy_files/images/error.png' /> Error Message : "+msg);
										img = document.getElementById('captcha'); 
										img.src = 'wy_controlls/wy_image.php';
									}
								}
							});	
						}
					});
				}); 
                    </script>
                <script type='text/javascript'>
				function checkName()
				{
					var a=document.forms["commentForm"]["name"].value;
					if (a==null || a=="" || a.length<2)
					{
						$("#report").html("<font color='red'>Required minimal two carachter name field</font>");
						return false;
					}else{
						$("#report").html("");
						return true;
					}
				}
				function checkComment()
				{
					var a=document.forms["commentForm"]["ccomment"].value;
					if (a==null || a=="")
					{
						$("#report").html("<font color='red'>Can not leave blank comment field</font>");
						return false;
					}else{
						$("#report").html("");
						return true;
					}
				}
				function checkCaptcha()
				{
					var a=document.forms["commentForm"]["txtCaptcha"].value;
					if (a==null || a=="")
					{
						$("#report").html("<font color='red'>Can not leave blank captcha field</font>");
						return false;
					}else{
						$("#report").html("");
						return true;
					}
				}
				function checkEmail()
				{
				var x=document.forms["commentForm"]["email"].value;
				var atpos=x.indexOf("@");
				var dotpos=x.lastIndexOf(".");
				if (atpos<1 || dotpos<atpos+2 || dotpos+2>=x.length)
				  {
						$("#report").html("<font color='red'>Email not valid</font>");
						return false;
				  }
				  else{
					  $("#report").html("<font color='red'></font>");
						return true;
				  }
				}
				</script>
                <script type="text/javascript">
				<!--
				function new_captcha()
				{
				var c_currentTime = new Date();
				var c_miliseconds = c_currentTime.getTime();
				
				document.getElementById('captcha').src = 'wy_controlls/wy_image.php?x='+ c_miliseconds;
				}
				-->
				</script>
                
                <div id="commentall"></div>
                <form id="commentForm" name="commentForm" >
                    <p>
                    <label for="nama">Name</label>
                    </p>
					<p>
                	<input type="text" id="cname" name="name" minlength="2" onKeyUp="return checkName();"/><em>*</em>
                    </p>
					<p>
                    <label for="email">Email</label>
                    </p>
					<p>
                    <input type="text" id="cemail" name="email" onKeyUp="return checkEmail();" /><em>*</em>
                    </p>
					<p>
                    <label for="url">Site</label>
                    </p>
					<p>
                    <input type="text" id="curl" name="url" class="url input_field"/>
                    </p>
					<p>
                    <label for="pesan">Your Comment</label>
                    </p>
					<p>
                    <textarea id="ccomment" name="ccomment" rows="10" cols="60" style="max-width:600px" ></textarea><em>*</em>
                    </p>
					<p>
                    <label for="txtCaptcha">Captcha</label>
                    </p>
					<p>
                    <img border="0" id="captcha" src="wy_controlls/wy_image.php" alt="" > <a href="JavaScript: new_captcha();"> &nbsp;<img border="0" alt="" src="wy_files/images/refresh_24x24.png" align="bottom"></a>
                    </p>
					<p>
                    <input id="txtCaptcha" type="text" name="txtCaptcha" maxlength="10" size="32"  onKeyUp="return checkCaptcha();"/><em>* </em>
                    </p>
					<p>
                    <input type="hidden" id="post_id" value="<?php echo $post_id; ?>"/>
                   	</p>
					<p>
                   	<input type="submit" id="sendcomment"  value="Send Comment" style="margin-left:0px;float:left; width:130px;"/>
                    </p>
					<p>
                    <input type="reset" value="Reset" style="margin-left:10px; width:130px;"/>
                    </p>
					<p>
                    <span id="report"></span>
                    </p>
                </form>	
                <?php
				echo "</div><!--close content_imagetext-->";
			}		
			
			
		}elseif(isset($_GET['searchkey']) && !isset($_GET['page_id'])  && !isset($_GET['category_id']) && !isset($_GET['post_id']) && !isset($_GET['author'])){
			$searchkey=delete(xss_clean($_GET['searchkey'])); 
			$rescontent=$conn->query("SELECT `post_id`, `post_title`, `post_user`, DATE_FORMAT(`post_date`,'%d %M %Y') as date FROM `wy_post` WHERE `post_title` LIKE '%".$searchkey."%' OR `post_content` LIKE '%".$searchkey."' OR `post_tag` LIKE '%".$searchkey."' ORDER BY `post_id`");
			$content=$rescontent->num_rows;
			if($content==0){
				echo "<div class='content_title'>
						No data found!.
					</div>";
			}else{
				while($content=$rescontent->fetch_array()){
					echo "<div class='".$css."_imagetext'>
					<div class='content_title'>
						<a href='?post_id=".$content['post_id']."'>".$content['post_title']."</a><br>
						<h4>Posted by <a href='?author=".$content['post_user']."'>".$content['post_user']." </a>, ".$content['date']."</h4>
					</div></div> <br>";
				}
			}			
		}
        
       echo	" </div><!--close site_content-->";
	   echo	" <div id='sidebody'> <!--open side bar-->";
	   //memanggil module widget widget
		if($css=='contenthalf'){
			do {
				include("wy_controlls/wy_side_".$datamod["widget_module"].".php");
			}while($datamod=$rowmod->fetch_array());
		}
   echo "</div><!--close side bar-->
 </div><!--close wrapper-->";
		include('wy_footer.php');
echo "
</body>
</html>";
}

?>

