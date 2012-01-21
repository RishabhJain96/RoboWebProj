<?php
session_start();
// autoloader code
// loads classes as needed, eliminates the need for a long list of includes at the top
spl_autoload_register(function ($className) { 
    $possibilities = array( 
        '../controllers'.DIRECTORY_SEPARATOR.$className.'.php', 
        '../back_end'.DIRECTORY_SEPARATOR.$className.'.php', 
        '../views'.DIRECTORY_SEPARATOR.$className.'.php', 
        $className.'.php' 
    ); 
    foreach ($possibilities as $file) { 
        if (file_exists($file)) { 
            require_once($file); 
            return true; 
        } 
    } 
    return false; 
});

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
// Will accept url parameter id=123 to get orderID
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
   "http://www.w3.org/TR/html4/loose.dtd">

<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>Harker Robotics 1072</title>
	
	<link rel="stylesheet" href="stylesheets/form.css" type="text/css" />
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
						<?php
						$username = $_SESSION['robo'];
						$api = new roboSISAPI();
						if ($api->isAdmin($username))
						{
							echo '<li><a href="admin_dashboard.php">Admin</a></li>';
						}
						?>					
					</ul>
				</div>
				<div id="login_status">
					<p>Logged in as: <?php echo $_SESSION['robo']; // echos the username?></p>
					<form method="post" name="form" action="">
					<fieldset>
						<input name="logout" type="submit" class="logout" value="Logout" />
					</fieldset>
					</form>
				</div> <!-- end of login_status -->
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
						if ($api->isAdmin($username))
						{
							echo '<li><a href="adminviewpending.php">Admin Pending</a></li>';
						}
						if ($api->isMentor($username))
						{
							echo '<li><a href="mentorviewpending.php">Mentor Pending</a></li>';
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
				
				function refineStatus($status)
				{
					// Unfinished, AdminPending, AdminApproved, MentorPending, MentorApproved, AdminRejected, MentorRejected
					if ($status == "AdminPending")
						return "Pending Admin Approval";
					else if ($status == "MentorPending")
						return "Pending Mentor Approval";
					else if ($status == "AdminApproved")
						return "Admin Approved";
					else if ($status == "MentorApproved")
						return "Mentor Approved";
					else if ($status == "AdminRejected")
						return "Admin Rejected";
					else if ($status == "MentorRejected")
						return "Mentor Rejected";
					else
						return $status;
				}
				
				echo '<div id="form_viewform">';
				echo '<h2 id="vendorName">' .refineOrderVal($orders[0]["PartVendorName"]). '</h2>';
				echo '<h3>Order ID: '.$orders[0]["OrderID"] .'</h3>';
				echo "<h3>Subteam: ".refineOrderVal($orders[0]["UserSubteam"]).'</h3>';
				echo '<ul id="form_viewform_info">';
				echo '<li>Status: ' .refineStatus($orders[0]["Status"]). '</li>';
				echo '<li>Submitted by: '. refineOrderVal($orders[0]["Username"]) .'</li>';
				echo '<li>Submitted on: '. refineOrderVal($orders[0]["EnglishDateSubmitted"]) .'</li>';
				echo '<li>Admin Approved: ' .refineOrderVal($orders[0]["AdminApproved"]). '</li>';
				if ($orders[0]["AdminApproved"] === "1")
				{
					echo '<li>Admin Approved on: '. refineOrderVal($orders[0]["EnglishDateApproved"]) . ' by ' .refineOrderVal($orders[0]["AdminUsername"]). '</li>';
				}
				else if ($orders[0]["AdminApproved"] === "0")
				{
					echo '<li>Admin Rejected on: '. refineOrderVal($orders[0]["EnglishDateApproved"]) . ' by ' .refineOrderVal($orders[0]["AdminUsername"]). '</li>';
				}
				echo '<li>Mentor Approved: ' .refineOrderVal($orders[0]["MentorApproved"]). '</li>';
				if ($orders[0]["MentorApproved"] === "1")
				{
					echo '<li>Mentor Approved on: '. refineOrderVal($orders[0]["EnglishDateMentorApproved"]) . '</li>';
				}
				else if ($orders[0]["MentorApproved"] === "0")
				{
					echo '<li>Mentor Rejected on: '. refineOrderVal($orders[0]["EnglishDateMentorApproved"]) . '</li>';
				}
				echo '<li>Locked: '.refineOrderVal($orders[0]["Locked"]).'</li>';
				echo '</ul><div class="viewform_para">';
				echo '<h4>Reason for Purchase</h4>';
				echo '<p>' .refineOrderVal($orders[0]["ReasonForPurchase"]). '</p></div>';
				echo '<div class="viewform_para">
						<h4>Admin Comments</h4>';
				echo '<p>'.refineOrderVal($orders[0]["AdminComment"]).'</p>';
				echo '  <h4>Mentor Comments</h4>';
				echo '<p>'.refineOrderVal($orders[0]["MentorComment"]).'</p>';
				echo '</div>
					
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
				if ($orders[0]["MentorApproved"] === "1")
				{
					$count = $controller->getPrintCount($orders[0]["OrderID"]);
					echo '<div class="forms_display clearfix">';
					echo '<span class="forms_display_viewmore">';
					$plural = "";
					if ($count != 1)
						$plural = "s";
					echo "<p>This order has been printed $count time$plural</p>";
					echo '<a href="';
					echo "printorder.php?id=" . $orders[0]["OrderID"] . "\">";
					echo "Print Order &raquo;</a>";
					echo "</span></div>";
				}
						?>
			</div>
			
		</div>
		<footer>
		</footer>
	</div>
</body>
</html>
