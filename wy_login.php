<?php
session_start();
require_once('wy_config.php');
require_once('wy_connection.php');
require_once('wy_controlls/wy_quote_handler.php');
require_once('wy_controlls/wy_get_ipaddress.php');
require_once('wy_controlls/wy_xss_clean.php');
require_once('wy_controlls/wy_user_agent.php');

if(isset($_SESSION['wysystem']) && isset($_SESSION['loggedip']) && isset($_SESSION['wylevel']) && isset($_SESSION['wydatelog'])){
	header('Location:sysadministrator/');
}
?>
<link rel='icon' href='wy_files/images/wy-icon.ico' type='image/x-icon' />
<title>Login | Wayang CMS</title>
<link rel='stylesheet' type='text/css' href='wy_files/css/guardian.css'/>

<body >
<div id='logo'><a href='wy_login.php'><img src='wy_files/images/wy-logo.png' alt='Junior Guardian' border='0' /></a></div>
<div id='login_container'>
<div id='login_msg'><span style='font-size:14px;'><strong>Welcome Back</strong></span><br>Please enter your username below to access admin area.</div>  <div id='login'>
    <form action='wy_controlls/wy_login.php' method='post' name='frmlogin' id='frmlogin'>
      <table width='100%' border='0' cellspacing='0' cellpadding='5'>
        <tr>
          <td width='30%' align='right' valign='middle'><strong>Username</strong></td>
          <td align='left' valign='middle'><input type='text' name='username' size='30' class='login_inputs' required="Please fill this field"/></td>
        </tr>
        <tr>
          <td width='30%' align='right' valign='middle'><strong>Password</strong></td>
          <td align='left' valign='middle'><input type='password' name='password' size='30' class='login_inputs' required="Please fill this field"/></td>
          <input type='hidden' name='ip' value='<?php echo ip();?>'/>
        </tr>
        <tr>
          <td width='30%' align='right' valign='middle'>&nbsp;</td>
          <td align='left' valign='middle'>
          	<input type='submit' value='Login'/>
                <input type='reset' value='Reset' />
          </td>
        </tr>
        <tr>
            <td colspan='2' class="login_error">
            	<?php
				if(isset($_GET['login_attempt']) && delete($_GET['login_attempt'])==1){
					echo "<span >Incorrect Username or Password</span>";
				}
				?>
            </td>
        </tr>
      </table>
    </form>
  </div>
  <div id='extra_info'>
    <table width='100%' border='0' cellspacing='0' cellpadding='0'>
      <tr>
        <td align='left' valign='middle'>IP Address Logged: <strong><?php echo ip();?></strong></td>
        <td align='right' valign='middle'>Powered by <a href='http://www.wayang-cms.org/' target='_new'>Wayang CMS</a></td>
      </tr>
    </table>
  </div>
</div>
</body>

