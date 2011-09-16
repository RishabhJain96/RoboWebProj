<?php
session_start();
if ((isset($_SESSION['robo']))) {
	header('Location: dashboard.php');
}
/*$db_user = "yroot";
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

*/
if(isset($_POST['login'])) {
	if(isset($_POST['pwd'])) {
		if(isset($_POST['username'])) {
			$password = ($_POST['pwd']);
			$username = $_POST['username'];
			function __autoload($class)
			{
				require_once $class . '.php';
			}
			$login = new login(new relationalDbConnections('RoboticsSIS', 'mysql', 'yroot', 'cytopic'));
			if($login->checkLogin($username, $password))
			{
				$_SESSION['robo'] = "$username";
				header('Location: dashboard.php');
			}
				//$_SESSION['robo'] = "$username";
				//header('Location: dashboard.php');
			//	break;
			
			//print_r($username_login);
			/*$q = mysql_query("SELECT * FROM RoboUsers WHERE Username='$username_login'");
			if ($q == 0) print '0';
			$r = mysql_num_rows($q);
			if($r > 0) {
				$result = mysql_query("SELECT password FROM RoboUsers WHERE UserPassword='$md5_password'");
				$result2 = mysql_fetch_array($result);
				$database_password = $result2['password'];
				if($md5_password == $database_password) {
					$_SESSION['robo'] = "$username_login";
					header("Location: http://cytopic.net/robotics/dashboard.php");
					break;
				} else {
					$true_false = true;
					echo '<p>Your password is incorrect.</p>';
				}
			} else {
				$other_true = true;
				echo '<p>Your username does not exist.</p>';
			}*/
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
			<h1>Login</h1>
			<form id="loginForm" method="post" name="loginForm" action="">
				<fieldset>
					<label for="username">Username </label>
					<input type="text" name="username" id="username" class="bigform" value=""/>
				</fieldset>
				<fieldset>
					<label id="password" >Password </label>
					<input type="password" name="pwd" class="bigform" id="password"value="" />
				</fieldset>
				<fieldset>
				<input name="login" type="submit" class="login" value="login" />
				</fieldset>
			</form>
			<p><a href="registration.php">Don't have an account yet? Register!</a></p>
		</div>
	</div>
	<footer>
	</footer>
</body>
</html>
