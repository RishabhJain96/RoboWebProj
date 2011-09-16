<?php
$db_user = "yroot";
$db_pass = "cytopic";
$db_databse = "RoboticsSIS";
$db_host = "mysql";
$db_connect = mysql_connect ($db_host, $db_user, $db_pass);
$db_select = mysql_select_db ($db_database);

function form($data) { // Prevents SQL Injection
   global $db_connect;
   $data = ereg_replace("[\'\")(;|`,<>]", "", $data);
   $data = mysql_real_escape_string(trim($data), $db_connect);
   return stripslashes($data);
}

session_start();
if ((isset($_SESSION['robo']))) {
  header('Location: http://cytopic.net/robotics/dashboard.php');
}

if(isset($_POST['login'])) {
	if(isset($_POST['pwd'])) {
		if(isset($_POST['username'])) {
			$md5_password = md5($_POST['pwd']);
			$q = mysql_query("SELECT * FROM RoboUsers WHERE Username='$username_login'");
			$r = mysql_num_rows($q);
			if($r > 0) {
				$result = mysql_query("SELECT password FROM RoboUsers WHERE UserPassword='$username_login'");
				$result2 = mysql_fetch_array($result);
				$database_password = $result2['password'];
				if($md5_password == $database_password) {
					$_SESSION['robo'] = "$username_login";
					header("Location: http://cytopic.net/robotics/dashboard.php");
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
<!doctype html>
<head>
	<meta charset="utf-8">
	<title></title>
	<meta name="description" content="">
	<meta name="author" content="">
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
	<div id="floater"></div>
	<div id="loginWindowWrap" class="clearfix">
		<div id="loginWindow">
			<form id="loginForm" method="post" name="loginForm" action="">
				<fieldset>
					<label for="username">Username </label>
					<input type="text" name="username" id="username" class="bigform" value="username"/>
				</fieldset>
				<fieldset>
					<label id="password" >Password </label>
					<input type="password" name="pwd" class="bigform" id="password"value="password" />
				</fieldset>
				<fieldset>
				<input name="login" type="submit" class="login" value="login" />
				</fieldset>
			</form>
			<p><a href="http://cytopic.net/robotics/registrationform.php">Don't have an account yet? Register!</a></p>
		</div>
	</div>
	<footer>
	</footer>
</body>
</html>
