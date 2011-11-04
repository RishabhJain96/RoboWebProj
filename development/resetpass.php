<!-- THIS PAGE IS NOT YET READY FOR PRODUCTION -->
<!doctype html>
<head>
	<meta charset="utf-8">
	<title></title>
	<meta name="description" content="">
	<meta name="author" content="">
	<link rel="stylesheet" type="text/css" href="style.css">
	<link rel="stylesheet" type="text/css" href="reset.css">
</head>
<body>
	<div id="floater"></div>
	<div id="loginWindowWrap" class="clearfix">
		<div id="loginWindow">
			<h1>Reset Password</h1>
			<form id="loginForm" method="post" name="loginForm" action="">
				<fieldset>
					<label for="username">Username</label>
					<input type="text" name="username" id="username" class="bigform" value=""/>
				</fieldset>
				<fieldset>
					<label id="password" >Old Password</label>
					<input type="password" name="oldpwd" id="password" class="bigform" value="" />
				</fieldset>
				<fieldset>
					<label id="password" >New Password</label>
					<input type="password" name="newpwd" id="password" class="bigform" value="" />
				</fieldset>
				<fieldset>
				<input name="reset" type="submit" class="register" value="reset" />
				</fieldset>
				<?php
				function __autoload($class)
				{
					require_once $class . '.php';
				}
				if (isset($_POST['register']))
				{
					$username = $_POST['username'];
					$oldpassword = $_POST['oldpwd'];
					$newpassword = $_POST['newpwd'];

					if($username =="")
					{
						exit("Please complete all fields and try again.");
					}
					
					$register = new register();
					if ($register->register($username, $password))
					{
						echo '<p>Congratulations! Your password has been changed and you may now login.</p>';
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
