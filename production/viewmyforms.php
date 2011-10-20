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
					<h2>Purchase Order Forms - View My Forms</h2>
					<ul>
						<li><a href="submitform.php">Submit a Form</a></li>
						<li class="form-selected">View My Forms</li>
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
				<div id="formstable">
					<table>
						<tr id="header">
							<th>OrderID</th>
							<th>Status</th>
							<th>Subteam</th>
							<th>Date Submitted</th>
							<th>Date Approved</th>
							<th>Reason For Purchase</th>
							<th>Shipping &amp; Handling</th>
							<th>Tax</th>
							<th>Estimated Total Price</th>
							<th>Vendor Name</th>
							<th>Vendor Email</th>
							<th>Vendor Address</th>
							<th>Vendor Phone Number</th>
							<th>Admin Comment</th>
							<th>Admin Approved</th>
							<th>Admin Username</th>
							<th>Locked</th>
						</tr>
						<?php
						$controller = new financeController();
						$username = $_SESSION['robo'];
						$orders = $controller->getUsersOrders($username);
						//$orders = json_decode($orders);
						//print count($orders);
						//print_r($orders);
						
						// function to allow each order value to be processed if null right before being displayed
						function refineOrderVal($orderVal)
						{
							if ($orderVal === "0")
								return "NO";
							if ($orderVal === "1")
								return "YES";
							if (is_null($orderVal))
								return "N/A";
							else
								return $orderVal;
						}
						
						for ($i=0; $i < count($orders); $i++)
						{
							//echo "<a href=\"vieworder.php?id=" . $orders[$i][0]["OrderID"] . "\">";
							echo "<tr class=\"data\">";
							echo "<td><a id=\"clickableid\" href=\"vieworder.php?id=" . $orders[$i][0]["OrderID"] . "\">" . $orders[$i][0]["OrderID"] . "</a></td>";
							echo "<td><a id=\"clickableid\" href=\"editform.php?id=" . $orders[$i][0]["OrderID"] . "\">" . refineOrderVal($orders[$i][0]["Status"]) . "</td>";
							echo "<td>" . refineOrderVal($orders[$i][0]["UserSubteam"]) . "</td>";
							echo "<td>" . refineOrderVal($orders[$i][0]["EnglishDateSubmitted"]) . "</td>";
							echo "<td>" . refineOrderVal($orders[$i][0]["EnglishDateApproved"]) . "</td>";
							echo "<td>" . refineOrderVal($orders[$i][0]["ReasonForPurchase"]) . "</td>";
							echo "<td>" . $orders[0]["ShippingAndHandling"] . "</td>";
							echo "<td>" . $orders[0]["TaxPrice"] . "</td>";
							echo "<td>" . $orders[0]["EstimatedTotalPrice"] . "</td>";
							echo "<td>" . refineOrderVal($orders[$i][0]["PartVendorName"]) . "</td>";
							echo "<td>" . refineOrderVal($orders[$i][0]["PartVendorEmail"]) . "</td>";
							echo "<td>" . refineOrderVal($orders[$i][0]["PartVendorAddress"]) . "</td>";
							echo "<td>" . refineOrderVal($orders[$i][0]["PartVendorPhoneNumber"]) . "</td>";
							echo "<td>" . refineOrderVal($orders[$i][0]["AdminComment"]) . "</td>";
							echo "<td>" . refineOrderVal($orders[$i][0]["AdminApproved"]) . "</td>";
							echo "<td>" . refineOrderVal($orders[$i][0]["AdminUsername"]) . "</td>";
							echo "<td>" . refineOrderVal($orders[$i][0]["Locked"]) . "</td>";
							echo "</tr>";
							//echo "</a>";
						}
						?>
					</table>
				</div>
				</div>
			</div>
			
		</div>
		<footer>
		</footer>
	</div>
</body>
</html>
