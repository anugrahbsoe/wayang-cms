<?php
session_start();
require_once('../wy_controlls/wy_get_ipaddress.php');
class install{
	public $array_install;
	public $connect;
	public function _setValue($varname,$value){
		$this->array_install[$varname]=$value;
	}
	public function _getetValue(){
		return $this->array_install;
	}
	public function _checkDB($dbhost,$dbuser,$dbpassword,$dbname){
		$mysqli=new mysqli($dbhost,$dbuser,$dbpassword,$dbname);
		if(!$mysqli->connect_errno)
			return true;
		else
			return false;
	}
	public function _runScriptSQL(){
		$file="cms.sql";
		$dbfile = fopen($file, "r+"); 
		$dbquery = fread($dbfile, filesize($file)); 
		$queryArray=explode(';',$dbquery);

		if (!$dbfile){
			return false;
		}else{
			$msg="Installation Complete. Do not forget delete installation folder. Enjoy with Wayang CMS";
			$exec=new mysqli($this->array_install['db_host'],$this->array_install['db_user'],$this->array_install['db_password'],$this->array_install['db_name']);
			foreach ($queryArray as $query) {
            	if (strlen($query)>3){
           			$result=$exec->query($query);
					if(!$result){
						$msg=$exec->error;
						break;
					}else{
						continue;
					}
				}
			}
			
			if($result==true){
				date_default_timezone_get();
				$user=$exec->query("INSERT INTO `wy_user`(`user_name`, `user_password`, `user_full_name`, `user_email`, `user_level`, `user_date_register`, `user_ipaddress_register`) 
				VALUES ('".$this->array_install['username']."','".sha1($this->array_install['password'].">}{|(&^%@#$%d^&)(}{5(*&^%?$#!~G%")."','".$this->array_install['username']."','".$this->array_install['Email']."','sysadmin','".date("Y-m-d H:i:s")."','".ip()."')");
				
				$setting=$exec->query("INSERT INTO `wy_site_profile`(`site_name`, `site_tag_line`, `site_url`, `email_address`, `use_widget`)
				 VALUES ('".$this->array_install['site_name']."','".$this->array_install['tag_line']."','".$this->array_install['site_url']."','".$this->array_install['Email']."','".$this->array_install['widget_use']."')");
				
				$post=$exec->query("INSERT INTO `wy_post` (`post_id`, `post_title`, `post_category`, `post_content`, `post_tag`, `post_user`, `post_date`, `post_ip`) 
				VALUES (1, 'Welcome', 'General', '<p>Welcome to Wayang CMS v1.5. This CMS build for learning and it&#39;s under GPL license&nbsp;</p>\r\n\r\n<p>This is your first post. You can modify it. Enjoy with this cms.</p>\r\n\r\n<p>regards</p>\r\n\r\n<p>Wayang CMS</p>\r\n', 'new post', 'Wayang CMS', '".date("Y-m-d H:i:s")."', '".ip()."')");

				file_put_contents("../wy_config.php","<?php

//define the database name
define(\"dbname\",\"".$this->array_install['db_name']."\");
						
//define the database username
define(\"dbuser\",\"".$this->array_install['db_user']."\");
						
//define the database password use by username
define(\"dbpassword\",\"".$this->array_install['db_password']."\");
						
//define database host/database server host to use
define(\"dbhost\",\"".$this->array_install['db_host']."\");
						
//define time zone
date_default_timezone_get();
						
//define salt password
define(\"salt\",\">}{|(&^%@#$%d^&)(}{5(*&^%?$#!~G%\");
						
?>");
				file_put_contents("../.htaccess","Options -Indexes
ErrorDocument 404 ".$this->array_install['site_url']."
ErrorDocument 403 ".$this->array_install['site_url']);
				return array('status'=>$msg);
			}else{
				return array('status'=>$msg);
			}
		}
	}
	public function _install(){
		$this->connect=$this->_checkDB($this->array_install['db_host'],$this->array_install['db_user'],$this->array_install['db_password'],$this->array_install['db_name']);
		if($this->connect==true){
			$this->connect=$this->_runScriptSQL();
			if($this->connect==true){
				return array(
					"msg"=>$this->connect['status'],
					"status"=>"ok");
			}else{
				return array(
					"msg"=>$this->connect['status'],
					"status"=>"fail");
			}
		}
		elseif($this->connect==false){
			return array(
					"msg"=>"Could not run install script, check all sending data throug installation step!",
					"status"=>"fail");
		}
	}
}
if(!isset($_GET['install'])){
	header('Location:?install=step1');
}
function delete($val){
	return $new=addslashes($val);
}
?>
<!DOCTYPE HTML>
<html>
<link rel='icon' href='../wy_files/images/wy-icon.ico' type='image/x-icon' />
<script type='text/javascript' src='../wy_files/js/jquery-latest.js'></script>
<title>Intaller | Wayang CMS</title>
<style>
#logo {
	text-align: center;
    width: 420px;
	margin: 0 auto;
	padding: 5px;
}
#form_container {
	color: #333;
 	background: #CCC;
  	background: -moz-linear-gradient(#fff, #CCC);
  	background: -o-linear-gradient(#fff, #CCC);
  	background: -webkit-linear-gradient(#fff, #CCC);
	text-align: left;
	width: 430px;
	padding: 10px;
	margin: 0 auto;
    -moz-border-radius: 10px;
    -webkit-border-radius: 10px;
    -o-border-radius: 10px;
    border-radius: 10px;
	 -moz-border-radius: 0px 0px 7px 7px;
  -webkit-border: 0px 0px 7px 7px;
  -webkit-box-shadow: rgba(0, 0, 0, 0.5) 0px 0px 5px;
  -moz-box-shadow: rgba(0, 0, 0, 0.5) 0px 0px 5px;
  box-shadow: rgba(0, 0, 0, 0.5) 0px 0px 5px;
}
#form_container #form {
	text-align: left;
	margin: 0;
	padding: 20px 10px 20px 10px;
}
#form_container #form_msg {
	background: #CCC;
  	background: -moz-linear-gradient(#fff, #6F0);
  	background: -o-linear-gradient(#fff, #6F0);
  	background: -webkit-linear-gradient(#fff, #6F0);
    text-align: center;
    padding: 10px;
    margin: 0 0 1px 0;
    -moz-border-radius: 10px;
    -webkit-border-radius: 10px;
    -o-border-radius: 10px;
    border-radius: 10px;
}
#form_container #extra_info {
	background-color: #D3D3D3;
	text-align: left;
	padding: 10px;
	margin: 1px 0 0 0;
    -moz-border-radius: 10px;
    -webkit-border-radius: 10px;
    -o-border-radius: 10px;
    border-radius: 10px;
}
a, a:visited {
	color: #060;
	text-decoration: underline;
}
a:hover {
	text-decoration: none;
}
</style>
<body >
<div id='logo'><img src='../wy_files/images/wy-logo.png' alt='Wayang Guardian' border='0' /></div>
<div id='form_container'>
<div id='form_msg'><span style='font-size:14px;'><strong>Welcome to Wayang CMS Installer</strong></span><br>Please enter your website detail below to configuration and setting.</div>  <div id='form'> 
   <?php 
   $data=new install();
   
   if(isset($_GET['install']) && delete($_GET['install'])=="step1"){
		?>
		<script type='text/javascript'>
		function check()
		{
			var a=document.forms["step1"]["site_name"].value;
			var b=document.forms["step1"]["tag_line"].value;
			var c=document.forms["step1"]["site_url"].value;
			var d=document.forms["step1"]["Email"].value;
			var e=document.forms["step1"]["widget_use"].value;
			if (a==null || a=="" || b==null || b=="" || c==null || c=="" || d==null || d=="" || e==null || e=="" )
			{
				$("#report").html("<font color='red'>Could not process with empty field</font>");
				return false;
			}
		}
		function checkEmail()
		{
		var x=document.forms["step1"]["Email"].value;
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
		function check_url() {
		 var theurl=document.step1.site_url.value;
		 var tomatch= /http:\/\/[A-Za-z0-9\.-]{3,}\.[A-Za-z]{3}/
		 if (tomatch.test(theurl))
		 {
				$("#report").html("<font color='red'></font>");
				return false;
		 }
		 else
		 {
			 $("#report").html("<font color='red'>URL not valid</font>");
				return false; 
		 }
		}
		</script>
				<form action='?install=step2' method='post' name='step1' id='step1' onSubmit="return check();">
				  <table width='100%' border='0' cellspacing='0' cellpadding='5'>
					<tr>
					  <td width='30%' align='left' valign='middle'><strong>Site Name</strong></td>
					  <td align='left' valign='middle'><input type='text' id='site_name' name='site_name' size='35' /></td>
					</tr>
					<tr>
					  <td width='30%' align='left' valign='middle'><strong>Site Tag Line</strong></td>
					  <td align='left' valign='middle'><input type='text' id='tag_line' name='tag_line' size='35'/></td>
					</tr>
					<tr>
					  <td width='30%' align='left' valign='middle'><strong>Site URL</strong></td>
					  <td align='left' valign='middle'><input type='text' id='site_url' name='site_url' size='35' onKeyUp="return check_url();"/></td>
					</tr>
					<tr>
					  <td width='30%' align='left' valign='middle'><strong>Email Address</strong></td>
					  <td align='left' valign='middle'><input type='email' id='Email' name='Email' size='35' onKeyUp="return checkEmail();" /></td>
					</tr>
					<tr>
					  <td width='30%' align='left' valign='middle'><strong>Use Widget on Sidebar</strong></td>
					  <td align='left' valign='middle'><select name='widget_use' id='widget_use' style='width:245px;'>
									<option value=''>--Choose Active--</option>
									<option value='True' selected>Active</option>
									<option value='False'>Non Active</option>
									</select></td>
					</tr>
					<tr>
					  <td width='30%' align='left' valign='middle'>&nbsp;</td>
					  <td align='left' valign='middle'>
						<input type='submit' value='Next' style='width:100px;height:30px'/>
					  </td>
					</tr>
                    <tr>
					  <td width='30%' align='left' valign='middle' colspan="2">
                      <span id='report' name='report'></span>
					  </td>
					</tr> 
				  </table>
				</form>
                <?php
				
	}elseif(isset($_GET['install']) && delete($_GET['install'])=="step2"){
		$_SESSION['step1']=array(
						'site_name'=>delete($_POST['site_name']),
						'tag_line'=>delete($_POST['tag_line']),
						'site_url'=>delete($_POST['site_url']),
						'Email'=>delete($_POST['Email']),
						'widget_use'=>delete($_POST['widget_use']));
		?>
		<script type='text/javascript'>
		function check2()
		{
			var a=document.forms["step2"]["db_host"].value;
			var b=document.forms["step2"]["db_name"].value;
			var c=document.forms["step2"]["db_user"].value;
			var d=document.forms["step2"]["db_password"].value;
			if (a==null || a=="" || b==null || b=="" || c==null || c=="" || d==null || d=="" )
			{
				$("#report").html("<font color='red'>Could not process with empty field</font>");
				return false;
			}
		}
		</script>
        		<form action='?install=step3' method='post' name='step2' id='step2' onSubmit="return check2();">
				  <table width='100%' border='0' cellspacing='0' cellpadding='5'>
					<tr>
					  <td width='30%' align='left' valign='middle'><strong>Database Host</strong></td>
					  <td align='left' valign='middle'><input type='text' id='db_host' name='db_host' size='35' /></td>
					</tr>
					<tr>
					  <td width='30%' align='left' valign='middle'><strong>Database Name</strong></td>
					  <td align='left' valign='middle'><input type='text' id='db_name' name='db_name' size='35'/></td>
					</tr>
					<tr>
					  <td width='30%' align='left' valign='middle'><strong>Username</strong></td>
					  <td align='left' valign='middle'><input type='text' id='db_user' name='db_user' size='35'/></td>
					</tr>
					<tr>
					  <td width='30%' align='left' valign='middle'><strong>User Password</strong></td>
					  <td align='left' valign='middle'><input type='password' id='db_password' name='db_password' size='35'/></td>
					</tr>
					<tr>
					  <td width='30%' align='left' valign='middle'>&nbsp;</td>
					  <td align='left' valign='middle'>
						<input type='submit' value='Next' style='width:100px;height:30px'/>
					  </td>
					</tr>
                    <tr>
					  <td width='30%' align='left' valign='middle' colspan="2">
                      <span id='report' name='report'></span>
					  </td>
					</tr>
				  </table>
				</form>
                <?php
	}elseif(isset($_GET['install']) && delete($_GET['install'])=="step3"){
		$_SESSION['step2']=array(
						'db_host'=>delete($_POST['db_host']),
						'db_name'=>delete($_POST['db_name']),
						'db_user'=>delete($_POST['db_user']),
						'db_password'=>delete($_POST['db_password']));
		?>
        <form action='?install=step4' method='post' name='install' id='install'>
				  <table width='100%' border='0' cellspacing='0' cellpadding='5'>
                  <tr>
					  <td width='30%' align='left' valign='middle'><strong>Username</strong></td>
					  <td align='left' valign='middle'><input type='text' id='username' name='username' size='35' value="sysadmin" /></td>
					</tr>
					<tr>
					  <td width='30%' align='left' valign='middle'><strong>Password</strong></td>
					  <td align='left' valign='middle'><input type='text' id='password' name='password' size='35' value="sysadmin"/></td>
					</tr>
					<tr>
					  <td width='30%' align='left' valign='middle' style='text-align:center;' colspan='2'>
						<input type='submit' value='Next' style='width:100px;height:30px'/>
					  </td>
					</tr>
				  </table>
				</form>
                <?php
	}elseif(isset($_GET['install']) && delete($_GET['install'])=="step4"){
		$_SESSION['step3']=array(
						'username'=>delete($_POST['username']),
						'password'=>delete($_POST['password']));
		?>
        <form action='?install=step5' method='post' name='install' id='install'>
				  <table width='100%' border='0' cellspacing='0' cellpadding='5'>
					<tr>
					  <td width='30%' align='left' valign='middle' style='text-align:center;' colspan='2'>Required Data Complete. Continue to Install Step?</td>
					</tr>
					<tr>
					  <td width='30%' align='left' valign='middle' style='text-align:center;' colspan='2'>
						<input type='submit' value='Install CMS' style='width:100px;height:30px'/>
					  </td>
					</tr>
				  </table>
				</form>
                <?php
	}elseif(isset($_GET['install']) && delete($_GET['install'])=="step5"){
		$data->_setValue('site_name',$_SESSION['step1']['site_name']);
		$data->_setValue('tag_line',$_SESSION['step1']['tag_line']);
		$data->_setValue('site_url',$_SESSION['step1']['site_url']);
		$data->_setValue('Email',$_SESSION['step1']['Email']);
		$data->_setValue('widget_use',$_SESSION['step1']['widget_use']);
		$data->_setValue('db_host',$_SESSION['step2']['db_host']);		
		$data->_setValue('db_user',$_SESSION['step2']['db_user']);
		$data->_setValue('db_password',$_SESSION['step2']['db_password']);
		$data->_setValue('db_name',$_SESSION['step2']['db_name']);
		$data->_setValue('username',$_SESSION['step3']['username']);
		$data->_setValue('password',$_SESSION['step3']['password']);
		$result=$data->_install();
		echo "<form action='?install=step4' method='post' name='install' id='install'>
				  <table width='100%' border='0' cellspacing='0' cellpadding='5'>
					
					  ";
					  if($result['status']=="ok"){
						  echo "
						  <tr>
					  <td width='30%' align='left' valign='middle' style='text-align:center;' colspan='2'/>".$result['msg']."</td>
					</tr>
					<tr><td width='30%' align='left' valign='middle' style='text-align:center;' colspan='2'>
						<input type='button' value='Login' style='width:100px;height:30px' onClick='window.location=\"../wy_login.php\"'/>
					  </td></tr>";
					  }elseif($result['status']=="fail"){
						  echo "
						  <tr>
					  <td width='30%' align='left' valign='middle' style='text-align:center;' colspan='2'/>".$result['msg']."</td>
					</tr>
					<tr><td width='30%' align='left' valign='middle' style='text-align:center;' colspan='2'>
						<input type='button' value='Reinstall' style='width:100px;height:30px' onClick='window.location=\"?install=step1\"'/>
					  </td></tr>";
					  }
					  echo"
				  </table>
				</form>";
	}
echo "
  </div>
  <div id='extra_info'>
    <table width='100%' border='0' cellspacing='0' cellpadding='0'>
      <tr>
        <td align='left' valign='middle'></td>
        <td align='right' valign='middle'>Powered by <a href='http://www.wayang-cms.org/' target='_new'>Wayang CMS</a></td>
      </tr>
    </table>
  </div>
 </div>
</body>
</html>";

?>