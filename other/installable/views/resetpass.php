<?php
include "autoloader.php";
?>
<!doctype html>
<head>
	<meta charset="utf-8">
	<title></title>
	<meta name="description" content="">
	<meta name="author" content="">
	<link rel="stylesheet" type="text/css" href="stylesheets/style.css">
	<!-- <link rel="stylesheet" type="text/css" href="stylesheets/reset.css"> -->
</head>
<body>
	<div id="floater"></div>
	<div id="loginWindowWrap" class="clearfix">
		<div id="loginWindow">
			<h1>Reset Password</h1>
			<p>If you forgot your password, please talk to an admin.</p>
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
				/**
				 * syntax for admin reset: resetpass.php?adminreset=true&username=12rohits
				 */
				$controller = new register();
				//if (!is_null($_GET['adminreset']))
				//{
				//	$username = $_GET['username'];
				//	$controller->resetPassword($username);
				//}
				
				if (isset($_POST['reset']))
				{
					$username = $_POST['username'];
					// old password is md5'd to allow checking with db
					$oldpassword = md5($_POST['oldpwd']);
					// new password will be md5'd in back-end code
					$newpassword = $_POST['newpwd'];
					
					if($username == "")
					{
						echo "<p>Please complete all fields and try again.</p>";
						//exit("Please complete all fields and try again.");
					}
	
					if ($controller->getPassword($username) != $oldpassword)
					{
						echo "<p>Your old password is incorrect.</p>";
						//exit("Your old password is incorrect.");
					}
					else
					{
						$controller->setPassword($username, $newpassword);
						echo "<p>Successfully changed password</p>";
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
