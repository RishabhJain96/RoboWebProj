<?php
session_start();
if (!(isset($_SESSION['robo'])))
{
	header('Location: index.php');
}

if(isset($_POST['logout']))
{
	unset($_SESSION['robo']);
	header('Location: index.php');
}
$dbArr = file("dbParameters.txt");
$dbArr[0] = str_replace(array("\r", "\r\n", "\n"), '', $dbArr[0]);
$dbArr[1] = str_replace(array("\r", "\r\n", "\n"), '', $dbArr[1]);
$dbArr[2] = str_replace(array("\r", "\r\n", "\n"), '', $dbArr[2]);
$dbArr[3] = str_replace(array("\r", "\r\n", "\n"), '', $dbArr[3]);
if(isset($_POST['checkin']))
{
	$username = $_SESSION['robo'];
	$api = new roboSISAPI(new relationalDbConnections($dbArr[0], $dbArr[1], $dbArr[2], $dbArr[3]));
	date_default_timezone_set('America/Los_Angeles');
	$timestamp = date("l, F j \a\\t g:i a");
	$result = $api->inputCheckIn($timestamp, $api->getUserID($username));
}

?>
<!DOCTYPE html>
<head>
	<meta charset="utf-8">
	<title></title>
	<meta name="description" content="">
	<meta name="author" content="">
	<link rel="stylesheet" type="text/css" href="dashboardstyle.css">
</head>
<body>

<div id="container">
	<header>

	</header>
	<div id="main" role="main">
		<div id="header">
			<h1>Robotics Team 1072 SIS</h1>
			<div id="headerMast">
				<nav>
					<ul>
						<li><a href="">Home</a></li>
						<li><a href="">My Check-Ins</a></li>
						<li><a href="">My Profile</a></li>
					</ul>
				</nav>
				<p>Logged in as: <?php echo $_SESSION['robo']; ?></p>
				<form method="post" name="loginForm" action="">
				<fieldset>
					<input name="logout" type="submit" class="signin-status" value="Logout" />
				</fieldset>
				<fieldset>
					<input name="checkin" type="submit" class="signin-status2" value="Check-In" />
				</fieldset>
				</form>
			</div>
		</div>
		<div id="contentSections">
			<div id="mainContent">
				<h2>Tasks</h2>
				<p class="clearfix">
					<p>Tasks will be used at a later date.</p>
					<?php
					//code to get subteam tasks will eventually go here
					//$dbArr = file("dbParameters.txt");
					//$dbArr[0] = str_replace(array("\r", "\r\n", "\n"), '', $dbArr[0]);
					//$dbArr[1] = str_replace(array("\r", "\r\n", "\n"), '', $dbArr[1]);
					//$dbArr[2] = str_replace(array("\r", "\r\n", "\n"), '', $dbArr[2]);
					//$dbArr[3] = str_replace(array("\r", "\r\n", "\n"), '', $dbArr[3]);
					//$api = new roboSISAPI(new relationalDbConnections($dbArr[0], $dbArr[1], $dbArr[2], $dbArr[3]));
					//$result = $api->getCheckins($api->getUserId());
					//$json = '["Time 5","Time 4","Time 3","Time 2","Time 1"]';
					?>
				</p>
				<h2>General Information</h2>
				<p class="clearfix">
					<p>Welcome to Robotics!</p>
				</p>

			</div><!-- mainContent -->
			
			<div id="rightPanel">
				<h2>Recent Check-Ins</h2>
				<p class="clearfix">
					<ul>
						<?php
						function __autoload($class)
						{
							require_once $class . '.php';
						}
						$dbArr = file("dbParameters.txt");
						$dbArr[0] = str_replace(array("\r", "\r\n", "\n"), '', $dbArr[0]);
						$dbArr[1] = str_replace(array("\r", "\r\n", "\n"), '', $dbArr[1]);
						$dbArr[2] = str_replace(array("\r", "\r\n", "\n"), '', $dbArr[2]);
						$dbArr[3] = str_replace(array("\r", "\r\n", "\n"), '', $dbArr[3]);
						$username = $_SESSION['robo'];
						$api = new roboSISAPI(new relationalDbConnections($dbArr[0], $dbArr[1], $dbArr[2], $dbArr[3]));
						$result = $api->getCheckIns($api->getUserID($username));
						//echo $result;
						$table = json_decode($result);
						$len = count($table);
						for($i = 0; $i < $len; $i++)
							echo "<li>".$table[$i]."</li>";
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
