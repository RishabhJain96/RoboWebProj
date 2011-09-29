<?php
function __autoload($class)
{
	require_once $class . '.php';
}
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

$username = $_SESSION['robo'];
$api = new roboSISAPI();
if ($api->getUserType($username) != "Admin")
{
	header('Location: index.php');
	exit;
}
date_default_timezone_set('America/Los_Angeles'); // all times are in PST
?>
<!DOCTYPE html>
<head>
	<meta charset="utf-8">
	<title>Robotics 1072 Dashboard - Admin</title>
	<meta name="description" content="">
	<meta name="author" content="">
	<link rel="stylesheet" type="text/css" href="style3.css">
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
						<li><a href="admin_dashboard.php">Admin</a></li>
					</ul>
				</nav>
				
				
			</div>
		</div>
		<div id="contentSections">
			<div id="mainContent">
				<div id="selectdate-form">
					<form method="post" name="form4" action="" style="float:right">
					<!-- <fieldset>
						<p>
							Username: 
							<input name="usersearched" type="text" class="" value="12bobj" />
							<input name="searchuser" type="submit" class="searchuser" value="Get this user's checkins" />
						</p>
					</fieldset> -->
					</form>
					<form method="post" name="form3" action="">
					<fieldset>
						<p>
							Choose a date:
							<select name="dateselected">
								<!--Generate the following with php.-->
								<?php
								$username = $_SESSION['robo'];
								$api = new roboSISAPI();
								// currently only allows picking the current day
								$timestamp = date("l, F j"); // of format Friday, September 23
								$timestamp2 = date("Ymd"); // of format 20110923
								echo "<option value=\"today\">" . $timestamp . "</option>";
								//echo "<option value=\"yesterday\">Yesterday</option>";
								?>
							</select>
							<input name="getdate" type="submit" class="getdate" value="Get Check-Ins" />
						</p>
					</fieldset>
					</form>
				</div>
				<h2>People who checked in that day:</h2>
				<table class="clearfix">
					<!-- note to the php writer: the rows in the table alternate in color with alternating classes. -->
					<?php
					if(isset($_POST['getdate']))
					{
						$username = $_SESSION['robo'];
						$api = new roboSISAPI();
						//$timestamp = date("l, F j"); // of format Friday, September 23
						$timestamp2 = date("Ymd"); // of format 20110923
						$arr_names = $api->getUsersCheckedInForDate($timestamp2);
						$arr_names = json_decode($arr_names);
						for($i = 0; $i < count($arr_names); $i+=2)
						{
							echo "<tr class=\"r1\"><td>" . $arr_names[$i] . "</td></tr>";
							if (!is_null($arr_names[$i+1]))
							{
								echo "<tr class=\"r2\"><td>" . $arr_names[$i+1] . "</td></tr>";
							}
							//<tr class="r1"><td>Bob Jones</td><td>9-28-11 11:30pm</td></tr>
							//echo "<br />";
						}
					}
					?>
				</table>
			</div><!-- mainContent -->
		</div>
		
	</div>
	<footer>

	</footer>
</div> <!--! end of #container -->

</body>
</html>