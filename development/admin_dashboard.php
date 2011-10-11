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
						<!-- <li><a href="">My Profile</a></li> -->
						<li><a href="purchase_page.php">Purchase Orders</a></li>
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
							Choose a date: Month
							<select name="month">
								<?php
								$month = date("n"); // 1 to 12
								for ($i=1; $i <= 12; $i++)
								{
									if ($i == $month)
									{
										echo "<option value=\"" . $i . "\" selected=\"selected\">" . $i . "</option>\n";
									}
									else
									{
										echo "<option value=\"" . $i . "\">" . $i . "</option>\n";
									}
								}
								?>
							</select>
							Day
							<select name="day">
								<?php
								$day = date("j"); // 1 to 31
								for ($i=1; $i <= 31; $i++)
								{
									if ($i == $day)
									{
										echo "<option value=\"" . $i . "\" selected=\"selected\">" . $i . "</option>\n";
									}
									else
									{
										echo "<option value=\"" . $i . "\">" . $i . "</option>\n";
									}
								}
								?>
							</select>
							Year
							<select name="year">
								<?php
								$year = date("Y"); // 2011 - 2021
								for ($i=2011; $i <= 2021; $i++)
								{
									if ($i == $year)
									{
										echo "<option value=\"" . $i . "\" selected=\"selected\">" . $i . "</option>\n";
									}
									else
									{
										echo "<option value=\"" . $i . "\">" . $i . "</option>\n";
									}
								}
								?>
							<input name="getcheckins" type="submit" class="getdate" value="Get Check-Ins" />
						</p>
					</fieldset>
					</form>
				</div>
				<h2>People who checked in that day:</h2>
				<table class="clearfix">
					<!-- note to the php writer: the rows in the table alternate in color with alternating classes. -->
					<?php
					if(isset($_POST['getcheckins']))
					{
						// ensures proper formatting for api function
						$month = intval($_POST['month']);
						if($month<10) $month = "0".$month;
						$day = intval($_POST['day']);
						if($day<10) $day = "0".$day;
						$year = intval($_POST['year']);
						$api = new roboSISAPI();
						$numericdate = "$year" . "$month" . "$day";
						$arr_checkins = $api->getUsersCheckedInForDate($numericdate); // 2D Array
						$arr_checkins = json_decode($arr_checkins);
						// splits the array for easier management
						$arr_usernames = $arr_checkins[0];
						$arr_texttimes = $arr_checkins[1];
						// checks if arrays are empty
						if(empty($arr_usernames))
						{
							echo "<br />";
							echo "<p>There are no checkins for the selected date: $month/$day/$year.</p>";
						}
						for($i = 0; $i < count($arr_usernames); $i++)
						{
							$cl = "";
							if ($i % 2 == 0) // allows table to alternate colors
							{
								$cl = "r1";
							}
							else
							{
								$cl = "r2";
							}
							echo "<tr class=\"" . $cl . "\"><td>" . $arr_usernames[$i] . "</td><td>" . $arr_texttimes[$i] . "</td></tr>\n";
						}
					}
					?>
				</table>
				<br />
				<h2>Email List</h2>
				<table class="clearfix">
					<form method="post" name="form5" action="">
						<fieldset>
							<input name="emails" type="submit" class="getdate" value="Get Emails" />
						</fieldset>
					</form>
				<?php
				if (isset($_POST['emails']))
				{
					$api = new roboSISAPI();
					$arr_emails = $api->getAllEmails();
					$arr_emails = json_decode($arr_emails);
					$numemails = count($arr_emails);
					echo "<p>There are $numemails emails currently in the database.</p>";
					
					for($i = 0; $i < $numemails; $i++)
					{
						$cl = "";
						if ($i % 2 == 0) // allows table to alternate colors
						{
							$cl = "r1";
						}
						else
						{
							$cl = "r2";
						}
						echo "<tr class=\"" . $cl . "\"><td>" . $arr_emails[$i] . ",</td></tr>";
					}
				}
				?>
				<br />
				</table>
			</div><!-- mainContent -->
		</div>
		
	</div>
	<footer>

	</footer>
</div> <!--! end of #container -->

</body>
</html>