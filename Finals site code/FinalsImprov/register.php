<?php
include("includes/ConnectMysql.php");
include("includes/variables.php");
session_start();

if(isset($_SESSION['logged_in'])) {
	header("Location: http://finals.cytopic.net/");
	exit;
}
else {
	if(isset($_SESSION['admin'])) {
		header("Location: http://finals.cytopic.net/admin/index.php");
	}
}


?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Finals Study Guides - Register</title>
	<meta name="author" content="Abhinav Khanna and Devin Nguyen" />
	<link rel="stylesheet" href="css/style.css" type="text/css" media="screen" />
	
</head>
<body onload="document.loginForm.email.focus();">
	<div id="floater"></div>
	<div id="mainContainer">
		<div id="login">
			<p class="login_header">Register</p>
			<div id="logform">
				<form id="loginForm" name="loginForm" method="post" action="">
					<fieldset>
						<label>Your E-mail: </label>
						<input type="text" name="email" class="bigform" id="register-email" />@students.harker.org
					</fieldset>
					<fieldset>
					<input name="register" type="submit" class="login" value="register" />
					</fieldset>
				</form>
				<?php
				if(isset($_POST['register'])) {
					if($string_length > 4) {
						$q = mysql_query("SELECT * FROM finals WHERE email = '$to'") OR die (mysql_error());
						$r = mysql_num_rows($q);
						if ($r > 0) {
							echo '<span class="login-error">';
							echo "Sorry, the e-mail address you entered has already been used.";
							echo '</span>';
						} else {
							$true_false = true;
							mysql_query("INSERT INTO finals (username, email, classes, password, activationCode) VALUES ('', '$to', '', '', '$activation_code') ") or die (mysql_error());
							mail($to, $subject, $message);
					}
					} else {
						$other_true = true;
					}
				}
				if($true_false == true) {
					echo '<span class="login-error">';
					echo "Please check your email for an activation link.";
					echo '</span>';
				}
				if($other_true == true) {
					echo '<span class="login-error">';
					echo "The e-mail address you entered is not valid. Please try again.";
					echo '</span>';
				}

				?>
<p class="login_register"><a href="login.php">Already have an account? Login.</a></p>
				<p id="login_footer">
					Created by Abhinav Khanna &amp; Devin Nguyen<br /><br />
					If you have any questions or comments, please e-mail <span style="color:#CCCCCC;">12devinn@students.harker.org</span>
				</p>			
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
