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
if (is_null($_GET['id']))
{
	header('Location: viewmyforms.php'); // if there is no order to view, redirects to viewmyforms page
	exit;
}
function __autoload($class)
{
	require_once $class . '.php';
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
					<h2>Purchase Order Forms - View Order #<?php echo $_GET['id'];// displays the shown orderID number ?></h2>
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
				
				<?php
				$controller = new financeController();
				$orderID = $_GET['id'];
				$orders = $controller->getOrder($orderID);
				$orderslist = $controller->getOrdersList($orderID);
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
					if (empty($orderVal))
						return "N/A";
					else
						return $orderVal;
				}
				
				echo '<div id="form_viewform">';
				echo '<h2 id="vendorName">' .refineOrderVal($orders[0]["PartVendorName"]). '</h2>';
				echo '<h3>Order ID: '.$orders[0]["OrderID"] .'</h3>';
				echo "<h3>Subteam: ".refineOrderVal($orders[0]["UserSubteam"]).'</h3>';
				echo '<ul id="form_viewform_info">';
				echo '<li>Status: ' .refineOrderVal($orders[0]["Status"]). '</li>';
				echo '<li>Submitted by: '. refineOrderVal($orders[0]["Username"]) .'</li>';
				echo '<li>Submitted on: '. refineOrderVal($orders[0]["EnglishDateSubmitted"]) .'</li>';
				echo '<li>Admin Approved: ' .refineOrderVal($orders[0]["AdminApproved"]). '</li>';
				if ($orders[0]["AdminApproved"] === "1")
				{
					echo '<li>Approved on: '. refineOrderVal($orders[0]["EnglishDateApproved"]) . ' by ' .refineOrderVal($orders[0]["AdminUsername"]). '</li>';
				}
				else if ($orders[0]["AdminApproved"] === "0")
				{
					echo '<li>Rejected on: '. refineOrderVal($orders[0]["EnglishDateApproved"]) . ' by ' .refineOrderVal($orders[0]["AdminUsername"]). '</li>';
				}
				echo '<li>Locked: '.refineOrderVal($orders[0]["Locked"]).'</li>';
				echo '</ul><div class="viewform_para">';
				echo '<h4>Reason for Purchase</h4>';
				echo '<p>' .refineOrderVal($orders[0]["ReasonForPurchase"]). '</p></div>';
				echo '<div class="viewform_para">
						<h4>Comments</h4>';
				echo '<p>'.refineOrderVal($orders[0]["AdminComment"]).'</p>
					</div>
					
					<div id="form_viewform_contact">
						<ul>';
							echo '<li>Vendor Name: '.refineOrderVal($orders[0]["PartVendorName"]) .'</li>';
							echo '<li>Vendor Address: '.refineOrderVal($orders[0]["PartVendorAddress"]).'</li>';
							echo '<li>Vendor E-mail Address: '.refineOrderVal($orders[0]["PartVendorEmail"]).'</li>';
							echo '<li>Vendor Phone Number: '.refineOrderVal($orders[0]["PartVendorPhoneNumber"]) .'</li>
						</ul>
					</div>
				</div>
				<div id="formstable">
					<table>
						<tr id="header">
							<th>Shipping &amp; Handling</th>
							<th class="th_alt">Tax</th>
							<th>Estimated Total Price</th>
						</tr>
						<tr class="data">';
							echo '<td>$'.$orders[0]["ShippingAndHandling"] .'</td>';
							echo '<td class="td_alt">$'.$orders[0]["TaxPrice"].'</td>';
							echo '<td>$'.$orders[0]["EstimatedTotalPrice"].'</td>
							</tr>
					</table>
					<table>
						<tr id="header">
							<th>Part #</th>
							<th class="th_alt">Part Name</th>
							<th>Subsystem</th>
							<th class="th_alt">$ / Unit</th>
							<th id="quantity">Quantity</th>
							<th class="th_alt">Total</th>
						</tr>';
						for ($i=0; $i < count($orderslist); $i++)
						{
							echo "<tr class=\"data\">";
							echo "<td>" . refineOrderVal($orderslist[$i]["PartNumber"]) . "</td>";
							echo "<td>" . refineOrderVal($orderslist[$i]["PartName"]) . "</td>";
							echo "<td>" . refineOrderVal($orderslist[$i]["PartSubsystem"]) . "</td>";
							echo "<td>$" . $orderslist[$i]["PartIndividualPrice"] . "</td>";
							echo "<td>" . $orderslist[$i]["PartQuantity"] . "</td>";
							echo "<td>$" . $orderslist[$i]["PartTotalPrice"] . "</td>";
							echo "</tr>";
						}
					echo '</table>
				</div>';
				if ($orders[0]["AdminApproved"] === "1")
				{
					echo '<div class="forms_display clearfix">';
					echo '<span class="forms_display_viewmore"><a href="';
					echo "printorder.php?id=" . $orders[0]["OrderID"] . "\">";
					echo 'Print Order &raquo;</a></span></div>';
				}
						?>
			</div>
			
		</div>
		<footer>
		</footer>
	</div>
</body>
</html>
