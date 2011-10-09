<?php
session_start();
if (!(isset($_SESSION['robo'])))
{
	header('Location: index.php');
	exit;
}

if(isset($_POST['logout']))
{
	unset($_SESSION['robo']);
	header('Location: index.php');
	exit;
}

?>
<!DOCTYPE html>
<head>
	<meta charset="utf-8">
	<title>Robotics 1072 Dashboard</title>
	<meta name="description" content="">
	<meta name="author" content="">
	<link rel="stylesheet" type="text/css" href="style2.css">
	<link rel="stylesheet" type="text/css" href="reset.css">
</head>
<body>

<div id="container">
	<header>

	</header>
	<div id="login_status">
		<p>Logged in as: <?php echo $_SESSION['robo']; // echos the username?></p>
		<form method="post" name="form" action="">
		<fieldset>
			<input name="logout" type="submit" class="logout" value="Logout" />
		</fieldset>
		</form>
	</div>
	<div id="main" role="main">
		<div id="header">
			<h1>Robotics Team 1072 SIS</h1>
			<div id="headerMast">
				<nav>
					<ul>
						<li><a href="dashboard.php">Home</a></li>
						<li><a href="">My Check-Ins</a></li>
						<li><a href="">My Profile</a></li>
						<?php
						$username = $_SESSION['robo'];
						$api = new roboSISAPI();
						if ($api->getUserType($username) == "Admin")
						{
							echo '<li><a href="admin_dashboard.php">Admin</a></li>';
						}
						?>
					</ul>
				</nav>
				
				
			</div>
		</div>
		<div id="contentSections">
			<div id="mainContent">
				<h2>Tasks</h2>
				<p class="clearfix">
					<p>Tasks will be used at a later date.</p>
					<?php
					//code to get subteam tasks will eventually go here
					//$api = new roboSISAPI();
					?>
				</p>
				<h2>General Information</h2>
				<p class="clearfix">
					<p>Welcome to Robotics!</p>
				</p>

			</div><!-- mainContent -->
			
			<div id="rightPanel">
				<div id="checkin-form">
					<form method="post" name="form2" action="">
					<fieldset>
						<input name="checkin" type="submit" class="checkin" value="Check-In" />
					</fieldset>
					</form>
				</div>
				<h2>Recent Check-Ins</h2>
				<p class="clearfix">
					<ul>
						<?php
						function __autoload($class)
						{
							require_once $class . '.php';
						}
						$username = $_SESSION['robo'];
						$api = new roboSISAPI();
						if(isset($_POST['checkin']))
						{
							$api->inputCheckIn($username);
						}
						//echo 'here';
						$result = $api->getCheckIns($username);
						//echo $result;
						$table = json_decode($result);
						for($i = 0; $i < count($table); $i++)
						{
							echo "<li>".$table[$i]."</li>";
							//echo "<br />";
						}
						?>
					</ul>
				</p>
			</div>
		</div>
		
	</div>
	<footer>

	</footer>
</div> <!--! end of #container -->

</body>
</html>
