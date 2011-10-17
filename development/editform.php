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
if (is_null($_GET['id']))
{
	header('Location: submitform.php'); // if there is no order to edit, redirects to new form page
	exit;
}
// Will accept url parameter id=123 to get orderID
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
   "http://www.w3.org/TR/html4/loose.dtd">

<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>Harker Robotics 1072</title>
	
	<link rel="stylesheet" href="form.css" type="text/css" />
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
						<li><a href="submitform.php">Submit a Form</a></li>
						<li><a href="viewmyforms.php">View My Forms</a></li>
						<li><a href="viewallforms.php">View All Forms</a></li>
						<?php
						$username = $_SESSION['robo'];
						$api = new roboSISAPI();
						if ($api->getUserType($username) == "Admin")
						{
							echo '<li><a href="adminviewpending.php">View Pending</a></li>';
						}
						?>
					</ul>
				</div>
				<div id="forms-submit">
					<form id="orderform" method="post">
							<fieldset>
								<label for="name">Name</label>
								<input type="text" name="name" id="name" class="field" value=""/>
							</fieldset>
							
							<fieldset>
								<label for="email">Email</label>
								<input type="text" name="email" id="email" class="field" value=""/>
							</fieldset>
							
							<fieldset>
								<label for="cellphone">Cell Phone Number</label>
								<input type="text" name="cellphone" id="cellphone" class="field" value=""/>
							</fieldset>
							
							<fieldset id="subteam_select">
								<label for="subteam">Subteam</label>
								<fieldset>
								<input type="radio" name="subteam" value="M" /> M
								<input type="radio" name="subteam" value="E" /> E
								<input type="radio" name="subteam" value="P" /> P
								<input type="radio" name="subteam" value="O" /> O
								</fieldset>
							</fieldset>
							
							
							<fieldset>
								<label id="vendorname">Vendor Name</label>
								<input type="text" name="vendorname" id="vendorname" class="field" value="" />
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
							<fieldset id="reason">
								<p>Reason For Purchase</p>
								<textarea class="form_textarea">Default</textarea>
							</fieldset>
							
							<!-- Come up with schema to generate the following table, multidimensional arrays in html name? -->
							<div id="order_table">
								<table>
									<tr id="partnumber">
										<th class="th_alt">Part #</th>
										<th>Part Name</th>
										<th class="th_alt">Subsystem</th>
										<th>$ / Unit</th>
										<th class="th_alt" id="quantity">Quantity</th>
										<th>Total</th>
									</tr>
									<tr class="data">
										<td><fieldset><input type="text" class="order_table_field" /></fieldset></td>
										<td class="td_alt">
										<fieldset>
											<input type="text" class="order_table_field" />
										</fieldset>
										</td>
										<td><fieldset><input type="text" class="order_table_field" /></fieldset></td>
										<td class="td_alt"><fieldset><input type="text" class="order_table_field" /></fieldset></td>
										<td class="quantity"><fieldset><input type="text" class="order_table_field" /></fieldset></td>
										<td class="td_alt"><fieldset><input type="text" class="order_table_field" /></fieldset></td>
									</tr>
									<tr class="data_alt">
										<td><fieldset><input type="text" class="order_table_field" /></fieldset></td>
										<td class="td_alt"><fieldset><input type="text" class="order_table_field" /></fieldset></td>
										<td><fieldset><input type="text" class="order_table_field" /></fieldset></td>
										<td class="td_alt"><fieldset><input type="text" class="order_table_field" /></fieldset></td>
										<td class="quantity"><fieldset><input type="text" class="order_table_field" /></fieldset></td>
										<td class="td_alt"><fieldset><input type="text" class="order_table_field" /></fieldset></td>
									</tr>
									<tr class="data">
										<td><fieldset><input type="text" class="order_table_field" /></fieldset></td>
										<td class="td_alt"><fieldset><input type="text" class="order_table_field" /></fieldset></td>
										<td><fieldset><input type="text" class="order_table_field" /></fieldset></td>
										<td class="td_alt"><fieldset><input type="text" class="order_table_field" /></fieldset></td>
										<td class="quantity"><fieldset><input type="text" class="order_table_field" /></fieldset></td>
										<td class="td_alt"><fieldset><input type="text" class="order_table_field" /></fieldset></td>
									</tr>
									<tr class="data_alt">
										<td><fieldset><input type="text" class="order_table_field" /></fieldset></td>
										<td class="td_alt"><fieldset><input type="text" class="order_table_field" /></fieldset></td>
										<td><fieldset><input type="text" class="order_table_field" /></fieldset></td>
										<td class="td_alt"><fieldset><input type="text" class="order_table_field" /></fieldset></td>
										<td class="quantity"><fieldset><input type="text" class="order_table_field" /></fieldset></td>
										<td class="td_alt"><fieldset><input type="text" class="order_table_field" /></fieldset></td>
									</tr>
									<tr class="data">
										<td><fieldset><input type="text" class="order_table_field" /></fieldset></td>
										<td class="td_alt"><fieldset><input type="text" class="order_table_field" /></fieldset></td>
										<td><fieldset><input type="text" class="order_table_field" /></fieldset></td>
										<td class="td_alt"><fieldset><input type="text" class="order_table_field" /></fieldset></td>
										<td class="quantity"><fieldset><input type="text" class="order_table_field" /></fieldset></td>
										<td class="td_alt"><fieldset><input type="text" class="order_table_field" /></fieldset></td>
									</tr>
									<tr class="data_alt">
										<td><fieldset><input type="text" class="order_table_field" /></fieldset></td>
										<td class="td_alt"><fieldset><input type="text" class="order_table_field" /></fieldset></td>
										<td><fieldset><input type="text" class="order_table_field" /></fieldset></td>
										<td class="td_alt"><fieldset><input type="text" class="order_table_field" /></fieldset></td>
										<td class="quantity"><fieldset><input type="text" class="order_table_field" /></fieldset></td>
										<td class="td_alt"><fieldset><input type="text" class="order_table_field" /></fieldset></td>
									</tr>
									<tr class="data">
										<td><fieldset><input type="text" class="order_table_field" /></fieldset></td>
										<td class="td_alt"><fieldset><input type="text" class="order_table_field" /></fieldset></td>
										<td><fieldset><input type="text" class="order_table_field" /></fieldset></td>
										<td class="td_alt"><fieldset><input type="text" class="order_table_field" /></fieldset></td>
										<td  class="quantity"><fieldset><input type="text" class="order_table_field" /></fieldset></td>
										<td class="td_alt"><fieldset><input type="text" class="order_table_field" /></fieldset></td>
									</tr>
									<tr class="data_alt">
										<td><fieldset><input type="text" class="order_table_field" /></fieldset></td>
										<td class="td_alt"><fieldset><input type="text" class="order_table_field" /></fieldset></td>
										<td><fieldset><input type="text" class="order_table_field" /></fieldset></td>
										<td class="td_alt"><fieldset><input type="text" class="order_table_field" /></fieldset></td>
										<td  class="quantity"><fieldset><input type="text" class="order_table_field" /></fieldset></td>
										<td class="td_alt"><fieldset><input type="text" class="order_table_field" /></fieldset></td>
									</tr>
									<tr class="data">
										<td><fieldset><input type="text" class="order_table_field" /></fieldset></td>
										<td class="td_alt"><fieldset><input type="text" class="order_table_field" /></fieldset></td>
										<td><fieldset><input type="text" class="order_table_field" /></fieldset></td>
										<td class="td_alt"><fieldset><input type="text" class="order_table_field" /></fieldset></td>
										<td class="quantity"><fieldset><input type="text" class="order_table_field" /></fieldset></td>
										<td class="td_alt"><fieldset><input type="text" class="order_table_field" /></fieldset></td>
									</tr>
									<tr class="data_alt">
										<td><fieldset><input type="text" class="order_table_field" /></fieldset></td>
										<td class="td_alt"><fieldset><input type="text" class="order_table_field" /></fieldset></td>
										<td><fieldset><input type="text" class="order_table_field" /></fieldset></td>
										<td class="td_alt"><fieldset><input type="text" class="order_table_field" /></fieldset></td>
										<td  class="quantity"><fieldset><input type="text" class="order_table_field" /></fieldset></td>
										<td class="td_alt"><fieldset><input type="text" class="order_table_field" /></fieldset></td>
									</tr>
									
								</table>
							</div>
							
							
							<fieldset>
								<input type="radio" name="subteam" value="M" /> YES
								<input type="radio" name="subteam" value="E" /> NO
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