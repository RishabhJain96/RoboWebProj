<?php
session_start();
if ((isset($_SESSION['robo'])))
{
	header('Location: dashboard.php');
}
global $result; // global definition of result
function __autoload($class)
{
	require_once $class . '.php';
}
if(isset($_POST['login']))
{
	if(isset($_POST['pwd']))
	{
		if(isset($_POST['username']))
		{
			$password = ($_POST['pwd']);
			$username = $_POST['username'];
			$login = new login();
			global $result; // allows $results to be used later in script
			$result = $login->checkLogin($username, $password);
			if(!is_string($result))
			{
				$_SESSION['robo'] = "$username";
				header('Location: dashboard.php');
			}
		}
	}
}

echo '<!doctype html>';
echo '<head>';
echo '	<meta charset="utf-8">';
echo '	<title>Robotics 1072 Login</title>';
echo '	<meta name="description" content="">';
echo '	<meta name="author" content="">';
echo '	<link rel="stylesheet" type="text/css" href="style.css">';
//echo '  <link rel="stylesheet" type="text/css" href="reset.css">';
echo '</head>';
echo '<body>';
echo '	<div id="floater"></div>';
echo '	<div id="loginWindowWrap" class="clearfix">';
echo '		<div id="loginWindow">';
echo '			<h1>Login</h1>';
echo '			<form id="loginForm" method="post" name="loginForm" action="">';
echo '				<fieldset>';
echo '					<label for="username">Username </label>';
echo '					<input type="text" name="username" id="username" class="bigform" value=""/>';
echo '				</fieldset>';
echo '				<fieldset>';
echo '					<label id="password" >Password </label>';
echo '					<input type="password" name="pwd" class="bigform" id="password"value="" />';
echo '				</fieldset>';
if (is_string($result))
{
	echo $result; // outputs error message, if login was unsuccessful exists
}
echo '				<fieldset>';
echo '				<input name="login" type="submit" class="login" value="login" />';
echo '				</fieldset>';
echo '			</form>';
echo '			<p><a href="registration.php">Don\'t have an account yet? Register!</a></p>';
echo '		</div>';
echo '	</div>';
echo '	<footer>';
echo '	</footer>';
echo '</body>';
echo '</html>';
?>