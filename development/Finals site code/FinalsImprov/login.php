<?php
include("includes/ConnectMysql.php");
include("includes/variables.php");
session_start();
if(isset($_SESSION['logged_in'])) {
	header("Location: http://finals.cytopic.net/index.php");
}
if(isset($_SESSION['admin'])) {
	header("Location: http://finals.cytopic.net/admin/index.php");
}

if(isset($_POST['login'])) {
	if(isset($_POST['pwd'])) {
		if(isset($_POST['username'])) {
			if($_POST['username'] == "admin") {
				if(md5($_POST['pwd']) == $admin_pwd) {
					$_SESSION['admin'] = "I like pie";
					header("Location: http://finals.cytopic.net/admin/index.php");
					exit;
				}
			} 
			$md5_password = md5($_POST['pwd']);
			$q = mysql_query("SELECT * FROM finals WHERE username='$username_login'");
			$r = mysql_num_rows($q);
			if($r > 0) {
				$result = mysql_query("SELECT password FROM finals WHERE username='$username_login'");
				$result2 = mysql_fetch_array($result);
				$database_password = $result2['password'];
				if($md5_password == $database_password) {
					$_SESSION['logged_in'] = "$username_login";
					header("Location: http://finals.cytopic.net/index.php");
					break;
				} else {
					$true_false = true;
				}
			} else {
				$other_true = true;
			}
		}
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<title>Finals Study Guides - Login</title>
	<meta name="author" content="Abhinav Khanna and Devin Nguyen" />
	<link rel="stylesheet" href="css/style.css" type="text/css" media="screen" />
	
</head>
<body onload="document.loginForm.username.focus();">
	<div id="floater"></div>
	<div id="mainContainer">
		<div class="login">
			<p class="login_header">Login</p>
			<div id="logform">
				
				<form id="loginForm" method="post" name="loginForm" action="">
					<fieldset>
						<label for="username">Username: </label>
						<input type="text" name="username" id="username" class="bigform" />
					</fieldset>
					<fieldset>
						<label id="password" >Password: </label>
						<input type="password" name="pwd" class="bigform" />
					</fieldset>
					<fieldset>
					<input name="login" type="submit" class="login" value="login" />
					</fieldset>
				</form>
				<?php
					if($true_false == true) {
						echo '<span class="login-error">';
						echo "The username or password you entered is not valid. Please try again.";
						echo '</span>';
						$true_false = false;
					}
					if($other_true == true) {
						echo '<span class="login-error">';
						echo "The username you entered is not valid. Please try again.";
						echo '</span>';
						$other_true = false;
					}
				?>
				<p class="login_register"><a href="register.php">Don't have an account? Register.</a></p>
				<p id="login_footer">Created by Abhinav Khanna &amp; Devin Nguyen<br /><br />If you have any questions or comments, please e-mail <span style="color:#CCCCCC;">12devinn@students.harker.org</span> </p>
			</div><!--#logform-->
		</div><!--#login-->
	</div> <!--#mainContainer-->
	<script type="text/javascript">
	var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
	document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
	</script>
	<script type="text/javascript">
	try {
	var pageTracker = _gat._getTracker("UA-11942470-1");
	pageTracker._trackPageview();
	} catch(err) {}</script>
</body>
</html>
