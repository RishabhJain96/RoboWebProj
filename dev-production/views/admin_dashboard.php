<?php
include "autoloader.php";

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
if (!$api->isAdmin($username))
{
	header('Location: index.php');
	exit;
}
date_default_timezone_set('America/Los_Angeles'); // all times are in PST
if (isset($_GET['sheldon'])) {
	$api->inputCheckIn("12erich");
	echo "Welcome back, Dr. Cooper.";
	exit;
}
?>
<!DOCTYPE html>
<head>
	<meta charset="utf-8">
	<title>Robotics 1072 Dashboard - Admin</title>
	<meta name="description" content="">
	<meta name="author" content="">
	<!-- <link rel="stylesheet" type="text/css" href="style3.css">
	<link rel="stylesheet" type="text/css" href="reset.css"> -->
	
	<style>
	.clearfix:before, .clearfix:after {
		content: "";
		display: table;
	}
	.clearfix:after {
		clear: both;
	}
	.clearfix {
		zoom: 1;
	}

	html, body, div, span, applet, object, iframe, h1, h2, h3, h4, h5, h6, p, blockquote, pre, a, abbr, acronym, address, big, cite, code, del, dfn, em, font, img, ins, kbd, q, s, samp, small, strike, strong, sub, sup, tt, var, b, u, i, center, dl, dt, dd, ol, ul, li, fieldset, form, label, legend, table, caption, tbody, tfoot, thead, tr, th, td {
		margin: 0;
		padding: 0;
		font-size: 100%;
		vertical-align: baseline;
		border: 0;
		outline: 0;
		background: transparent;
	}
	a {
		text-decoration: none;
		color: #000;
	}
	ol, ul {
		list-style: none;
	}

	blockquote, q {
		quotes: none;
	}

	:focus {
		outline: 0;
	}
	input {
		border: none;
	}
	table {
		border-collapse: collapse;
		border-spacing: 0;
	}

	/* main */
	html, body {
	font-family: Helvetica, Arial, "MS Trebuchet", sans-serif;
	height: 100%;
	background-color: #EEE;
	}

	#floater {
	position: relative;
	float: left;
	height: 50%;
	margin-bottom: -260px;
	width: 1px;
	}
	
	#dashboardWindow {
		background-color: #333;
		color: #FFF;
		width: 1050px;
		margin: 1.5em auto;
		position: relative;
		clear: left;
		padding: 2em;
	}
	#dashboardWindow div {
		margin-bottom: 1.5em;
	}
	#dashboardWindow h2 {
		font-size: 1.5em;
	}
	#login_status {
		float: right;
		margin-bottom: 1em;
	}
	#login_status form input.logout {
		padding: 5px 15px;
		font-size: 0.9em;
		background-color: #FFF;
		float: right;
	}
	#login_status form input.logout:active {
		background-color: #888;
		color: #FFF;
	}
	#dashboardWindow h1 {
		font-size: 2em;
		font-weight: bold;
		clear: both;
	}
	#dashboard-checkin {
		margin-top: 2.5em;
	}
	#checkin-header form {
		float: right;
	}
	#checkin-header form input.checkin {
		padding: 0.5em 1.5em;
		font-size: 1.25em;
		background-color: #FFF;
		font-weight: bold;
	}
	#checkin-header form input.checkin:active {
		background-color: #888;
		color: #FFF;
	}
	#checkin-header h2 {
		float: left;
	}
	
	#navbar {
		float: left;
	}
	#navbar ul li {
		display: inline;
	}
	#navbar ul li a {
		color: #FFF;
		padding: 0.75em 1.25em;
	}
	#navbar ul li a:hover {
		text-decoration: underline;
	}
	/* table css */
	#order_table tr th
	{
		width: 260px;
		background-color: #555;
		padding: 0.5em 0;
	}
	#order_table tr th.th_alt
	{
		background-color: #444;
	}
	#order_table tr.data td
	{
		background-color: #666;
		width: 260px;
	}
	#order_table tr.data td.td_alt
	{
		background-color: #777;
	}
	#order_table tr.data_alt td
	{
		background-color: #444;
		width: 260px;
	}	
	
	
	</style>
	
</head>
<body>
	<div id="mainWrapper">
		<div id="floater"></div>
		<div id="dashboardWindow" class="clearfix">
			<div id="nav">
				<div id="navbar">
					<ul>
						<li><a href="dashboard.php">Home</a></li>
						<li><a href="profilepage.php">My Profile</a></li>
						<li><a href="viewmyforms.php">Purchase Orders</a></li>
						<li><a href="admin_dashboard.php">Admin</a></li>
					</ul>
				</div> <!-- end of navbar -->
				<div id="login_status">
					<p>Logged in as: <?php echo $_SESSION['robo']; // echos the username?></p>
					<form method="post" name="form" action="">
					<fieldset>
						<input name="logout" type="submit" class="logout" value="Logout" />
					</fieldset>
					</form>
				</div> <!-- end of login_status -->
			</div> <!-- end of nav section -->
			
			<h1>The Harker School - Robotics Team 1072</h1>
			
			<br />
			
			<div id="contentSections">
				<div id="mainContent">
					<div id="selectdate-form">
						<form method="post" name="form4" action="" style="float:right">
						<fieldset>
							<p>
								Username: 
								<input name="usersearched" type="text" class="" value="" />
								<input name="searchuser" type="submit" class="searchuser" value="Get this user's check-ins" />
							</p>
						</fieldset>
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
									$lastyear = intval($year)-1;
									$nextyear = intval($year)+1;
									for ($i=$lastyear; $i <= $nextyear; $i++)
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
								<input name="getcheckins" type="submit" class="getdate" value="Get check-ins" />
							</p>
						</fieldset>
						</form>
					</div> <!-- end of selectdate-form -->
					<h2>People who checked in that day:</h2>
					<table id="order_table" class="clearfix">
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
							$size = count($arr_usernames);
							if(empty($arr_usernames))
							{
								echo "<br />";
								echo "<p>There are no checkins for the selected date: $month/$day/$year.</p>";
							}
							else
							{
								if($size == 1) // allows appropriate grammar; person vs. people
									echo "<p>$size person checked in on $month/$day/$year.</p>";
								else
									echo "<p>$size people checked in on $month/$day/$year.</p>";
							}
							for($i = 0; $i < count($arr_usernames); $i++)
							{
								$cl = "";
								if ($i % 2 == 0) // allows table to alternate colors
								{
									$cl = "data";
								}
								else
								{
									$cl = "data_alt";
								}
								echo "<tr class=\"" . $cl . "\"><td>" . $arr_usernames[$i] . "</td><td>" . $arr_texttimes[$i] . "</td></tr>\n";
							}
						}
						// the following code is only for getting the checkins of a specific user
						if (isset($_POST['searchuser']))
						{
							if (isset($_POST['usersearched']))
							{
								$username = $_POST['usersearched'];
								$result = $api->getCheckIns($username);
								//echo $result;
								$table = json_decode($result);
								if (count($table) > 0) {
									echo "Showing check-ins for $username.";
								}
								// show only past 10 check-ins
								$numcheckins = min(10,count($table));
								for($i = 0; $i < $numcheckins; $i++)
								{
									echo "<li>".$table[$i]."</li>";
									//echo "<br />";
								}
							}
							else
							{
								echo "<p>Please specify a valid username.</p>";
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
							//if ($i % 2 == 0) // allows table to alternate colors
							//{
							//	$cl = "r1";
							//}
							//else
							//{
							//	$cl = "r2";
							//}
							echo "<tr class=\"" . $cl . "\"><td>" . $arr_emails[$i] . ",</td></tr>";
						}
					}
					?>
					<br />
					</table>
				</div><!-- mainContent -->
			</div>
		
		</div>
	</div> <!--! end of #mainWrapper -->

</body>
</html>