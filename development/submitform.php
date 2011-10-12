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
function __autoload($class)
{
	require_once $class . '.php';
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
   "http://www.w3.org/TR/html4/loose.dtd">

<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>Harker Robotics 1072</title>
	
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
		width: 640px;
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
	
	#forms h2 {
		font-size: 1.75em;
	}
	#forms ul {
		margin-top: 1.5em;
	}
	#forms ul li {
		display: inline;
		margin-right: 3em;
	}
	#forms ul li a {
		color: #FFF;
		background-color: #444;
		padding: 0.6em 1em;
		font-size: 1.1em;
	}
	#forms ul li a:hover {
		text-decoration: underline;
	}
	#forms ul li.form-selected {
		padding: 0.6em 1em;
		font-size: 1.1em;
		background-color: #555;
		font-weight: bold;
	}
	
	#forms-submit form {
		margin-top: 40px;
	}
	#forms-submit fieldset {
		margin-bottom: 1em;
		font-size: 1.25em;
	}
	#forms-submit fieldset label {
		padding-right: 1em;
	}
	#forms-submit fieldset input.field {
		font-size: 1em;
		padding: 0.5em;
		width: 275px;
	}
	#forms-submit fieldset input.save, input.submit {
		padding: 0.5em;
		font-size: 1em;
	}
	#forms-submit fieldset select.options {
		font-size: 1em;
		width: 150px;
	}
	#forms-submit fieldset textarea.form_textarea {
		width: 375px;
		min-height: 150px;
	}
	
	#form-submitbuttons fieldset {
		display: inline;
		margin-right: 1.5em;
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
						<!-- <li><a href="">My Profile</a></li> -->
						<li><a href="viewmyforms.php">Purchase Orders</a></li>
						<?php
						$username = $_SESSION['robo'];
						$api = new roboSISAPI();
						if ($api->getUserType($username) == "Admin")
						{
							echo '<li><a href="admin_dashboard.php">Admin</a></li>';
						}
						?>					
					</ul>
				</div>
				<div id="login_status">
					<form method="post" name="form" action="">
					<fieldset>
					<input name="logout" type="submit" class="logout" value="Logout" />
					</fieldset>
					</form>
					<p>Logged in as: <?php echo $_SESSION['robo']; // echos the username?></p>
				</div>
			</div>
			
			<h1>The Harker School - Robotics Team 1072</h1>
			
			<div id="dashboard-checkin" class="clearfix">
				<div id="forms" class="clearfix">
					<h2>Purchase Order Forms - Submit a Form</h2>
					<ul>
						<li class="form-selected">Submit a Form</li>
						<li><a href="viewmyforms.php">View My Forms</a></li>
						<li><a href="viewallforms.php">View All Forms</a></li>
						<?php
						$username = $_SESSION['robo'];
						$api = new roboSISAPI();
						if ($api->getUserType($username) != "Admin")
						{
							echo '<li><a href="adminviewpending.php">View Pending</a></li>';
						}
						?>
					</ul>
				</div>
				<div id="forms-submit">
					<form id="orderform">
							<fieldset>
								<label for="vendorname">Vendor Name</label>
								<input type="text" name="vendorname" id="vendorname" class="field" value=""/>
							</fieldset>
							<fieldset>
								<label id="vendorphone" >Vendor Phone Number</label>
								<input type="text" name="vendorphone" id="vendorphone" class="field" value="" />
							</fieldset>
							<fieldset>
								<label for="vendoremail">Vendor Email</label>
								<input type="text" name="vendoremail" id="vendoremail" class="field" value=""/>
							</fieldset>
							<fieldset>
								<label id="vendoraddress" >Vendor Address</label>
								<input type="text" name="vendoraddress" id="vendoraddress" class="field" value="" />
							</fieldset>
							<fieldset>
								<p>Reason For Purchase</p>
								<textarea class="form_textarea">Default</textarea>
							</fieldset>
							<fieldset>
								<input type="radio" name="yes" value="1" /> YES
								<input type="radio" name="no" value="0" /> NO
							</fieldset>
							
							<fieldset>
								<input type="checkbox" name="verify" value="agree" /> I have agreed to the Terms and Conditions 
							</fieldset>
							
							<div id="form-submitbuttons">
							<fieldset>
							<input name="submit" type="submit" class="submit" value="submit" />
							</fieldset>
							<fieldset>
								<input name="save" type="submit" class="save" value="save" />
							</fieldset>
							<!-- save: input, then go to edit
							     submit: input, goes to submitForApproval -->
							</div>
					</form>
					<?php
					// code to input/save to database here
					
					?>
				</div>
			</div>
			
		</div>
		<footer>
		</footer>
	</div>
</body>
</html>