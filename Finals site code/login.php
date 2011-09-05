<?php
session_start();
if (isset($_SESSION['loser'])) {
  header('Location: http://cytopic.net/semester2finals/home.php');
  exit;
  }
?>
<?php
if (array_key_exists('login', $_POST)) {
  session_start();
  $textfile = 'register.txt';
    if (file_exists($textfile) && is_readable($textfile)) {
    $users = file($textfile);
	$user_input = $_POST['pwd'];
	$crypt = md5($user_input);
    for ($i = 0; $i < count($users); $i++) {
      $tmp = explode(', ', $users[$i]);
      $users[$i] = array('name' => $tmp[0], 'password' => rtrim($tmp[1]));
	  if ($users[$i]['name'] == $_POST['username'] && $users[$i]['password'] == $crypt) {
	    $_SESSION['loser'] = '';
		$username = $_POST['username'];
		$_SESSION['username'] = $username;
		break;
		}
      }
	if (isset($_SESSION['loser'])) {
	  header('Location: http://finals.cytopic.net/NewIdTesting/index.php');
	  exit;
	  }
	else {
	  $error = 'Invalid username or password.';
	  }
    }
  else {
    $error = 'Login facility unavailable. Please try later.';
    }
  }
?>
		<?php
					$dbhost = "mysql";
					$dbuser = "yroot";
					$dbpass = "cytopic";
					$conn = mysql_connect($dbhost, $dbuser, $dbpass) or die ('Error connecting to mysql');
					$dbname = 'test';
					mysql_select_db($dbname);
					$delete = $_POST['delete_option'];
					$address = "../uploads/";
					$myfile = $address.$delete;
					if($_POST['delete_select']) {
						unlink($myfile);
						$query = "DELETE FROM tester WHERE notes='$delete'";
						mysql_query($query);
					}		
					$count_result = mysql_query("SELECT * FROM tester"); 
					$count_rows = mysql_num_rows($count_result);
				mysql_close($conn);
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
			<?php
					if ($count_rows == 0) {
						echo "There are currently no study guides.";
					} else {
						echo "There are $count_rows study guides available ... once you log in. &nbsp; &nbsp; :)";
					}
			?>
			</span>
			<p id="mainContent_" class="contentText">
			<div id="login_error">
				<?php
				if (isset($error)) {
				  echo "<p>$error</p>";
				  }
				?>
			</div>
				<form id="loginForm" name="loginForm" method="post" action="">
				    <p>
				        <label for="username" class="adminloginlabel">Username:</label>
				        <input type="text" name="username" id="username" />
				    </p>
				    <p>
				        <label for="textfield" class="adminloginlabel">Password:</label>
				        <input type="password" name="pwd" id="pwd" />
				    </p>
				    <p>
				        <input name="login" type="submit" id="login" value="Log in" />
				    </p>
				</form>
				<span class="javascript">Please do not share login information. Thank You.<br /><br /><b>Update:</b>The Finals Site is back up for the Spring Finals! It is currently an unofficial release, so please use the contact form if you notice any problems.  Thanks!<br />
					<br /><b>Update (05-18-09 - 20:16)</b>: I've been asked to add this feature a million times, so here's a beta release of a display page that will list all files currently up. Once you're logged in, go to the display page.  Change the end of your URL from <b>display.php</b> to <b>display-beta.php</b>
					</span>
		</div>
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