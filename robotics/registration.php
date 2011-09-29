<!doctype html>
<head>
	<meta charset="utf-8">
	<title>Robotics 1072 Registration</title>
	<meta name="description" content="">
	<meta name="author" content="">
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
	<div id="floater"></div>
	<div id="loginWindowWrap" class="clearfix">
		<div id="loginWindow">
			<h1>Register</h1>
			<form id="loginForm" method="post" name="loginForm" action="">
				<fieldset>
					<label for="username">Username </label>
					<input type="text" name="username" id="username" class="bigform" value=""/>
				</fieldset>
				<fieldset>
					<label id="password" >Password </label>
					<input type="password" name="pwd" id="password" class="bigform" value="" />
				</fieldset>
				<fieldset>
				<input name="register" type="submit" class="register" value="register" />
				</fieldset>
				<?php
				function __autoload($class)
				{
					require_once $class . '.php';
				}
				if (isset($_POST['register']))
				{
					$username = $_POST['username'];
					$password = $_POST['pwd'];

					if($username =="")
					{
						exit("Please complete both fields and try again.");
					}
					
					$register = new register();
					if ($register->register($username, $password))
					{
						echo '<p>Congratulations! Your account has been set up and you may now login.</p>';
					}
				}
				?>
			</form>
		</div>
	</div>
	<footer>
	</footer>
</body>
</html>
