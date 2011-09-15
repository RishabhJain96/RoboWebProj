<!doctype html>
<head>
	<meta charset="utf-8">
	<title></title>
	<meta name="description" content="">
	<meta name="author" content="">
	<link rel="stylesheet" type="text/css" href="dashboardstyle.css">
</head>
<body>
	<div id="floater"></div>
	<div id="loginWindowWrap" class="clearfix">
		<div id="loginWindow">
			<h1>Register</h1>
			<form id="loginForm" method="post" name="loginForm" action="">
				<fieldset>
					<label for="username">Username </label>
					<input type="text" name="username" id="username" class="bigform" value="username"/>
				</fieldset>
				<fieldset>
					<label id="password" >Password </label>
					<input type="password" name="pwd" id="password" class="bigform" value="password" />
				</fieldset>
				<fieldset>
				<input name="register" type="submit" class="register" value="register" />
				</fieldset>
				<?php
				$username = form($_POST['username']);
				$password = form($_POST['password']);
				
				if(($username =="")) exit("Please complete both fields and try again.");
				
				$register = new roboSISAPI(new relationalDbConnections('RoboticsSIS', 'http://cytopic.net/robotics', 'yroot', 'cytopic'));
				$register->register($username, $password);
				?>
			</form>
		</div>
	</div>
	<footer>
	</footer>
</body>
</html>
