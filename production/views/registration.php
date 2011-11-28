<!doctype html>
<head>
	<meta charset="utf-8">
	<title>Robotics 1072 Registration</title>
	<meta name="description" content="">
	<meta name="author" content="">
	<link rel="stylesheet" type="text/css" href="stylesheets/style.css">
</head>
<body>
	<div id="floater"></div>
	<div id="loginWindowWrap" class="clearfix">
		<div id="loginWindow">
			<h1>Register</h1>
			<form id="loginForm" method="post" name="loginForm" action="">
				<fieldset>
					<label for="username">Harker Username </label>
					<input type="text" name="username" id="username" class="bigform" value=""/>
				</fieldset>
				<fieldset>
					<label id="password" >Password </label>
					<input type="password" name="pwd" id="password" class="bigform" value="" />
				</fieldset>
				<fieldset>
					<label for="phonenum">Cell-Phone (###) ###-####</label>
					<input type="text" name="phonenum" id="username" class="bigform" value=""/>
				</fieldset>
				<fieldset>
				<input name="register" type="submit" class="register" value="register" />
				</fieldset>
				<?php
				// autoloader code
				// loads classes as needed, eliminates the need for a long list of includes at the top
				spl_autoload_register(function ($className) { 
				    $possibilities = array( 
				        '../controllers'.DIRECTORY_SEPARATOR.$className.'.php', 
				        '../back_end'.DIRECTORY_SEPARATOR.$className.'.php', 
				        '../views'.DIRECTORY_SEPARATOR.$className.'.php', 
				        $className.'.php' 
				    ); 
				    foreach ($possibilities as $file) { 
				        if (file_exists($file)) { 
				            require_once($file); 
				            return true; 
				        } 
				    } 
				    return false; 
				});
				
				if (isset($_POST['register']))
				{
					$username = $_POST['username'];
					$password = $_POST['pwd'];
					$phonenumber = $_POST['phonenum'];
					
					if($username =="")
					{
						exit("Please complete all fields and try again.");
					}
					if($phonenumber == "")
					{
						exit("Please complete all fields and try again.");
					}
					$username = strtolower($username);
					$register = new register();
					if ($register->register($username, $password, $phonenumber))
					{
						echo '<p>Congratulations! Your account has been set up and you may now <a href="index.php">login</a>.</p>';
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
