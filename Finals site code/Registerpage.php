<?php
session_start();
if (isset($_SESSION['loser'])) {
  header('Location: http://finals.cytopic.net/index.php');
  exit;
  }
?>
<?php
$pwdlength = 0;
$length = "5";
$sometext = "\n".$_POST['username'].",";
$hashedpwd = md5($_POST['pwd']);
	$sometext2 = " ".$hashedpwd;

//Need to fix the String Length Variable with the Error Message; Right now it is commented out!
if(strlen($_POST['pwd']) < $length) { $pwdlength = 1; 
}else {
	$content[] = file("register.php", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
	if ($contents[] == $_POST['username']) { 
		echo "Username taken!";
	}else {
	$handle = fopen("register.txt", "a");
	fwrite($handle, $sometext);
	fwrite($handle, $sometext2);
fclose($handle);
}
}
?> 

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
	"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8" >
	<link rel="stylesheet" type="text/css" href="../css/style.css" />
	<title>Finals Study Guides - Login</title>
</head>
<body>
<div class="center">
	<div id="page">
		
		<div id="mainHeader" class="center">
			<p id="headerText">Finals Study Guides</p>
		</div>
		<div id="loginbutton"><a href="admins/login.php" class="loginbuttonText">Admin Login</a></div>

		<div id="mainNav" class="center">
				<div class="center">
					<ul class="center">
						<li><a href="../index.php">Home</a></li>
						<li><a href="../upload.php">Upload</a></li>
						<li><a href="../display.php">Display</a></li>
						<li><a href="../contact.php">Contact</a></li>
					</ul>
				</div>
		</div>
		
		<div id="mainContent" class="center">
			<p id="mainContentIntro">
				Student Log In
			</p>
			<span class="contentText">
		
			</span>
			<p id="mainContent_" class="contentText">
					Please Register Your Username and Password!
				<form id="loginForm" name="RegisterForum" method="post" action="">
				    <p>
				        <label for="username" class="adminloginlabel">Username:</label>
				        <input type="text" name="username" id="username" />
				    </p>
				    <p>
				        <label for="textfield" class="adminloginlabel">Password:</label>
				        <input type="password" name="pwd" id="pwd" />
				    </p>
				    <p>
				        <input name="register" type="submit" id="login" value="Register" /><p>
						<?PHP 
						if ($_POST['register'] == true) {
						if ($pwdlength == 1) { 
							echo "Password must be at least 5 characters or numbers long!";
						}else {
							echo "\n Thank You For Registering With Finals.cytopic.net, Please click <a href=\"login.php\">here</a> to login. Thanks for Your support!";
						}
						}
						?></p>
				    </p>
				</form>
				
	</div>
	
<?php include("../includes/footer.inc.php"); ?>
		<div id="validation">
		<p>
			<a href="http://jigsaw.w3.org/css-validator/">
				<img style="border:0;width:88px;height:31px;border-style:none;"
		 		src="../images/icons/css.png"
	     		alt="Valid CSS!" 
				/>
			</a>
				<a href="http://validator.w3.org/check?uri=referer">
				<img style="border:0;width:88px;height:31px;border-style:none;"
				   src="../images/icons/html.png"
				   alt="Valid HTML 4.01 Transitional" height="31" width="88">
		 		</a>
	 	</p>
		<a href="http://www.apple.com/" class="mac"><img style="border:0;border-style:none;" src="../images/madeonmac.png" alt="Made on a Mac!" /></a>
		</div>
	</div>
</div>
<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
try {
var pageTracker = _gat._getTracker("UA-6411992-1");
pageTracker._trackPageview();
} catch(err) {}</script>
<script src="http://static.getclicky.com/97827.js" type="text/javascript"></script>
<noscript><p><img alt="Clicky" width="1" height="1" src="http://static.getclicky.com/97827-db12.gif" /></p></noscript>
</body>
</html>