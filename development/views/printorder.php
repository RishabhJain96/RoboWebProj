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
	
	<!-- <link rel="stylesheet" href="form.css" type="text/css" /> -->
	<link rel="stylesheet" href="stylesheets/print.css" type="text/css" />
</head>
<body>
	<div id="mainWrapper">
		<div id="floater"></div>
		<div id="dashboardWindow" class="clearfix">
			
			<div id="dashboard-checkin" class="clearfix">
				
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
				echo '<h2>Order ID: '.$orders[0]["OrderID"] .'</h2>';
				echo '<h3>Submitted by: '. refineOrderVal($orders[0]["Username"]) .'</h3>';
				echo '<div class="right">';
				echo '<h3>Reason for Purchase</h3>';
				echo '<p>' .refineOrderVal($orders[0]["ReasonForPurchase"]). '</p>';
				echo '<br />';
				echo '<h3>Admin Comments</h3>';
				echo '<p>'.refineOrderVal($orders[0]["AdminComment"]).'</p>';
				echo '</div>';
				//echo '<h3 id="vendorName">' .refineOrderVal($orders[0]["PartVendorName"]). '</h3>';
				echo "<p>Subteam: ".refineOrderVal($orders[0]["UserSubteam"]).'</p>';
			//	echo '<ul id="form_viewform_info">';
				//echo '<p>Status: ' .refineOrderVal($orders[0]["Status"]). '</p>';
				echo '<p>Submitted on: '. refineOrderVal($orders[0]["EnglishDateSubmitted"]) .'</p>';
				//echo '<p>Admin Approved: ' .refineOrderVal($orders[0]["AdminApproved"]). '</p>';
				echo '<p>Approved on: '. refineOrderVal($orders[0]["EnglishDateApproved"]) . ' by ' .refineOrderVal($orders[0]["AdminUsername"]). '</p>';
				//else if ($orders[0]["AdminApproved"] === "0")
				//{
				//	echo '<p>Rejected on: '. refineOrderVal($orders[0]["EnglishDateApproved"]) . ' by ' .refineOrderVal($orders[0]["AdminUsername"]). '</p>';
				//}
				//echo '<p>Locked: '.refineOrderVal($orders[0]["Locked"]).'</p>';
			//	echo '</ul>';
				echo "<br />";
				echo '<div id="form_viewform_contact">';
						//echo '<ul>';
							echo '<p>Vendor Name: '.refineOrderVal($orders[0]["PartVendorName"]) .'</p>';
							echo '<p>Vendor Address: '.refineOrderVal($orders[0]["PartVendorAddress"]).'</p>';
							echo '<p>Vendor E-mail Address: '.refineOrderVal($orders[0]["PartVendorEmail"]).'</p>';
							echo '<p>Vendor Phone Number: '.refineOrderVal($orders[0]["PartVendorPhoneNumber"]) .'</p>';
						//echo '</ul>';
					echo '</div>';
				
				echo '</div>
				<div id="formstable">
					<ul>
						<li>Shipping &amp; Handling: $'.$orders[0]["ShippingAndHandling"] .'</li>
						<li>Tax: $'.$orders[0]["TaxPrice"].'</li>
						<li>Estimated Total Price: $'.$orders[0]["EstimatedTotalPrice"].'</li>
					</ul>';
				echo '<table>
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
					echo '</table>';
				echo '</div>';
				echo "<br />";
				echo 'Signature___________________________________________';
						?>
			</div>
			
		</div>
		<footer>
		</footer>
	</div>
</body>
</html>
