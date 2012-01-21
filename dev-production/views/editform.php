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
	header('Location: submitform.php'); // if there is no order to edit, redirects to new form page
	exit;
}
$orderID = $_GET['id'];
$username = $_SESSION['robo'];
$controller = new financeController();
if ($controller->isLocked($orderID))
{
	header("Location: vieworder.php?id=$orderID");
}
$orders = $controller->getOrder($orderID);
$orderslist = $controller->getOrdersList($orderID);
//print_r($orderslist);
if (isset($_POST['submit']) || isset($_POST['update'])) // update database regardless of save or submit
{
	// if any values are null, it will simply write null values to db, perfectly allowable
	//$firstname = $_POST['firstname'];
	//$lastname = $_POST['lastname'];
	//$email = $_POST['email'];
	//$cellphone = $_POST['cellphone'];
	$precision = $_POST['precision'];
	$subteam = $_POST['subteam'];
	$vendorname = $_POST['vendorname'];
	$vendorphone = $_POST['vendorphone'];
	$vendoremail = $_POST['vendoremail'];
	$vendoraddress = $_POST['vendoraddress'];
	$reason = $_POST['reason'];
	$neworderslist = $_POST['part']; // does not overwrite orderslist array
	$fulltotal = 0.0; // init val to floating point zero
	//print_r($orderslist);
	$fulllist = array();
	for ($i=0; $i < 10; $i++) // iterates full partstable, puts each row into an array with proper formatting in fulllist
	{
		$partnum = $neworderslist[$i]["partnum"];
		$partname = $neworderslist[$i]["partname"];
		$partsubsystem = $neworderslist[$i]["partsubsystem"];
		$partprice = $neworderslist[$i]["partprice"];
		if ($precision != "true")
		{
			$partprice = sprintf("%01.2f", $partprice); // makes sure partprice only has 2 decimals
		}
		$partprice = floatval($partprice); // turns string into float
		$partquantity = $neworderslist[$i]["partquantity"];
		$partquantity = intval($partquantity); // makes sure part quantity is an int
		// $parttotal = $neworderslist[$i]["parttotal"];
		$parttotal = floatval($partquantity) * $partprice; // parttotal will be a float
		if ($precision != "true")
		{
			$parttotal = sprintf("%01.2f", $parttotal); // makes sure parttotal only has 2 decimals
		}
		$parttotal = floatval($parttotal); // turns string into float
		$fulltotal = $fulltotal + $parttotal;
		if ($precision != "true")
		{
			$fulltotal = sprintf("%01.2f", $fulltotal); // makes sure fulltotal only has 2 decimals
		}
		$fulltotal = floatval($fulltotal); // turns string into float
		if ($i < count($orderslist)) // prevents undefined offset errors
			$uid = $orderslist[$i]["UniqueEntryID"]; 
		if (!empty($partnum) || !empty($partname) || !empty($partsubsystem) || !empty($partprice) || !empty($partquantity) ) // if any element is not empty, will input
		{
			$fulllist[] = array("PartNumber" => $partnum, "PartName" => $partname, "PartSubsystem" => $partsubsystem, "PartIndividualPrice" => $partprice, "PartQuantity" => $partquantity, "PartTotalPrice" => $parttotal, "UniqueEntryID" => $uid);
		}
	}
	$shippinghandling = $_POST['shippinghandling'];
	if (is_null($shippinghandling) || empty($shippinghandling)) $shippinghandling = 0.0;
	$shippinghandling = floatval($shippinghandling); // ensures floating point number
	$tax = 0.0825 * $fulltotal;
	if ($precision != "true")
	{
		$tax = sprintf("%01.2f", $tax); // makes sure tax price only has 2 decimals
	}
	$tax = floatval($tax); // turns string into float
	$etotal = $fulltotal + $tax + $shippinghandling;
	if ($precision != "true")
	{
		$etotal = sprintf("%01.2f", $etotal); // makes sure etotal only has 2 decimals
	}
	$etotal = floatval($etotal); // turns string into float
	$neworders = array(
		"Username" => $username,
		"UserSubteam" => $subteam,
		"ReasonForPurchase" => $reason,
		"ShippingAndHandling" => $shippinghandling,
		"TaxPrice" => $tax,
		"EstimatedTotalPrice" => $etotal,
		"PartVendorName" => $vendorname,
		"PartVendorEmail" => $vendoremail,
		"PartVendorAddress" => $vendoraddress,
		"PartVendorPhoneNumber" => $vendorphone
	);
//	print_r($neworders);
//	print_r($fulllist);
	// calls controller to input
	$controller->updateOrder($orderID, $neworders, $fulllist);
}
if (isset($_POST['submit'])) // only if submitting
{
	$controller->submitForAdminApproval($orderID);
	header("Location: vieworder.php?id=$orderID");
}
if (isset($_POST['update'])) // only specific action needed if updating is to refresh the page
{
	header("Location: editform.php?id=$orderID");
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
	<!-- This script allows new rows to be added to the parts table as needed -->
	<script type="text/javascript" charset="utf-8">
		function newRow()
		{
			
		}
		function deleteRow(id)
		{
			
		}
	</script>
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
						if ($api->getUserType($username) == "Admin")
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
					<h2>Purchase Order Forms - Edit Order #<?php echo $_GET['id'];// displays the shown orderID number ?></h2>
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
				<div id="forms-submit">
					<form id="orderform" method="post" action="">
							<!-- Temp block - only needed until profile section is complete -->
							<!-- <fieldset>
								<label for="name">First Name</label>
								<input type="text" name="firstname" id="fname" class="field" value=""/>
							</fieldset>
							
							<fieldset>
								<label for="name">Last Name</label>
								<input type="text" name="lastname" id="lname" class="field" value=""/>
							</fieldset>
							
							<fieldset>
								<label for="email">Email</label>
								<input type="text" name="email" id="email" class="field" value=""/>
							</fieldset>
							
							<fieldset>
								<label for="cellphone">Cell Phone Number</label>
								<input type="text" name="cellphone" id="cellphone" class="field" value=""/>
							</fieldset>
							 -->
							<?php
							// getting all variables
							$subteam = $orders[0]["UserSubteam"];
							$vendorname = $orders[0]["PartVendorName"];
							$vendorphone = $orders[0]["PartVendorPhoneNumber"];
							$vendoremail = $orders[0]["PartVendorEmail"];
							$vendoraddress = $orders[0]["PartVendorAddress"];
							$reason = $orders[0]["ReasonForPurchase"];
							$shippinghandling = $orders[0]["ShippingAndHandling"];
							
							
							echo "<fieldset id=\"subteam_select\">\n";
							echo "<label for=\"subteam\">Subteam</label>\n";
							echo "<fieldset>\n";
							if (is_null($subteam) || empty($subteam))
							{
								echo "<input type=\"radio\" name=\"subteam\" value=\"Mechanical\" /> M\n";
								echo "<input type=\"radio\" name=\"subteam\" value=\"Electronics\" /> E\n";
								echo "<input type=\"radio\" name=\"subteam\" value=\"Programming\" /> P\n";
								echo "<input type=\"radio\" name=\"subteam\" value=\"Operational\" /> O\n";
							}
							if ($subteam == "Mechanical")
							{
								echo "<input type=\"radio\" name=\"subteam\" value=\"Mechanical\" checked=\"checked\" /> M\n";
								echo "<input type=\"radio\" name=\"subteam\" value=\"Electronics\" /> E\n";
								echo "<input type=\"radio\" name=\"subteam\" value=\"Programming\" /> P\n";
								echo "<input type=\"radio\" name=\"subteam\" value=\"Operational\" /> O\n";
							}
							if ($subteam == "Electronics")
							{
								echo "<input type=\"radio\" name=\"subteam\" value=\"Mechanical\" /> M\n";
								echo "<input type=\"radio\" name=\"subteam\" value=\"Electronics\" checked=\"checked\" /> E\n";
								echo "<input type=\"radio\" name=\"subteam\" value=\"Programming\" /> P\n";
								echo "<input type=\"radio\" name=\"subteam\" value=\"Operational\" /> O\n";
							}
							if ($subteam == "Operational")
							{
								echo "<input type=\"radio\" name=\"subteam\" value=\"Mechanical\" /> M\n";
								echo "<input type=\"radio\" name=\"subteam\" value=\"Electronics\" /> E\n";
								echo "<input type=\"radio\" name=\"subteam\" value=\"Programming\" /> P\n";
								echo "<input type=\"radio\" name=\"subteam\" value=\"Operational\" checked=\"checked\" /> O\n";
							}
							if ($subteam == "Programming")
							{
								echo "<input type=\"radio\" name=\"subteam\" value=\"Mechanical\" /> M\n";
								echo "<input type=\"radio\" name=\"subteam\" value=\"Electronics\" /> E\n";
								echo "<input type=\"radio\" name=\"subteam\" value=\"Programming\" checked=\"checked\" /> P\n";
								echo "<input type=\"radio\" name=\"subteam\" value=\"Operational\" /> O\n";
							}
							echo "</fieldset>\n";
							echo "</fieldset>\n";
							
							//<!-- End temp block -->
							
							echo "<fieldset>\n
							 	<label id=\"vendorname\">Vendor Name</label>\n
							 	<input type=\"text\" name=\"vendorname\" id=\"vendorname\" class=\"field\" value=\"$vendorname\" />\n
							 </fieldset>\n
							 <fieldset>\n
							 	<label id=\"vendorphone\" >Vendor Phone Number</label>\n
							 	<input type=\"text\" name=\"vendorphone\" id=\"vendorphone\" class=\"field\" value=\"$vendorphone\" />\n
							 </fieldset>\n
							 <fieldset>\n
							 	<label for=\"vendoremail\">Vendor Email</label>\n
							 	<input type=\"text\" name=\"vendoremail\" id=\"vendoremail\" class=\"field\" value=\"$vendoremail\"/>\n
							 </fieldset>\n
							 <fieldset>\n
							 	<label id=\"vendoraddress\" >Vendor Address</label>\n
							 	<input type=\"text\" name=\"vendoraddress\" id=\"vendoraddress\" class=\"field\" value=\"$vendoraddress\" />\n
							 </fieldset>\n
							 <fieldset id=\"reason\">\n
							 	<p>Reason For Purchase</p>\n
							 	<textarea class=\"form_textarea\" name=\"reason\">$reason</textarea>\n
							 </fieldset>\n";
							
							$checked = "";
							$totalpricestring = (string)($orders[0]["EstimatedTotalPrice"]);
							$totalpricestring = substr($totalpricestring, strpos($totalpricestring, "."));
							
							if (strlen($totalpricestring) > 3)
							{
								$checked = "checked"; // determines is user has previously checked the "greater precision" box
							}
							
							echo "<!-- option to increase precision -->\n
							<fieldset>\n
								<input type=\"checkbox\" name=\"precision\" id=\"precision\" value=\"true\" $checked/> Allow more digits (prevents rounding to nearest cent)
							</fieldset>
							
							 <div id=\"order_table\">\n
							 	<table>\n
							 		<tr id=\"partnumber\">\n
							 			<th class=\"th_alt\">Part #</th>\n
							 			<th>Part Name</th>\n
							 			<th class=\"th_alt\">Subsystem</th>\n
							 			<th>$ / Unit</th>\n
							 			<th class=\"th_alt\" id=\"quantity\">Quantity</th>\n
							 		</tr>";
									
									
									for ($i=0; $i < count($orderslist); $i++)
									{
										$id = "order"."$i";
										$name = "part["."$i"."]";
										$class = "";
										if ($i % 2 == 1) // allows row style to alternate
											$class = "data_alt";
										else
											$class = "data";
										// each row has id="order0" in numerical order
										// input names are in format "part[0][partnum]", with 0 and partnum varying for each row and for each column
										$partnum = $orderslist[$i]["PartNumber"];
										$partname = $orderslist[$i]["PartName"];
										$subsystem = $orderslist[$i]["PartSubsystem"];
										$partprice = $orderslist[$i]["PartIndividualPrice"];
										$quantity = $orderslist[$i]["PartQuantity"];
										echo "<tr id=\"$id\" class=\"$class\">\n";
										echo "<td><fieldset><input type=\"text\" class=\"order_table_field\" name=\"$name"."[partnum]\" value=\"$partnum\" /></fieldset></td>\n";
										echo "<td class=\"td_alt\"><fieldset><input type=\"text\" class=\"order_table_field\" name=\"$name"."[partname]\" value=\"$partname\" /></fieldset></td>\n";
										echo "<td><fieldset><input type=\"text\" class=\"order_table_field\" name=\"$name"."[partsubsystem]\" value=\"$subsystem\" /></fieldset></td>\n";
										echo "<td class=\"td_alt\"><fieldset><input type=\"text\" class=\"order_table_field\" name=\"$name"."[partprice]\" value=\"$partprice\" /></fieldset></td>\n";
										echo "<td class=\"quantity\"><fieldset><input type=\"text\" class=\"order_table_field\" name=\"$name"."[partquantity]\" value=\"$quantity\" /></fieldset></td>\n";
										//echo "<td class=\"td_alt\"><fieldset><input type=\"text\" class=\"order_table_field\" name=\"$name"."[parttotal]\" /></fieldset></td>\n";
										echo "</tr>\n";
									}
							 		// orderslist table generation
							 		for ($i=count($orderslist); $i < 10; $i++)
									{
										$id = "order"."$i";
										$name = "part["."$i"."]";
										$class = "";
										if ($i % 2 == 1) // allows row style to alternate
											$class = "data_alt";
										else
											$class = "data";
										// each row has id="order0" in numerical order
										// input names are in format "part[0][partnum]", with 0 and partnum varying for each row and for each column
										echo "<tr id=\"$id\" class=\"$class\">\n";
										echo "<td><fieldset><input type=\"text\" class=\"order_table_field\" name=\"$name"."[partnum]\" /></fieldset></td>\n";
										echo "<td class=\"td_alt\"><fieldset><input type=\"text\" class=\"order_table_field\" name=\"$name"."[partname]\" /></fieldset></td>\n";
										echo "<td><fieldset><input type=\"text\" class=\"order_table_field\" name=\"$name"."[partsubsystem]\" /></fieldset></td>\n";
										echo "<td class=\"td_alt\"><fieldset><input type=\"text\" class=\"order_table_field\" name=\"$name"."[partprice]\" /></fieldset></td>\n";
										echo "<td class=\"quantity\"><fieldset><input type=\"text\" class=\"order_table_field\" name=\"$name"."[partquantity]\" /></fieldset></td>\n";
										//echo "<td class=\"td_alt\"><fieldset><input type=\"text\" class=\"order_table_field\" name=\"$name"."[parttotal]\" /></fieldset></td>\n";
										echo "</tr>\n";
									}
									
							echo "</table>\n
							</div>\n";
							
							
							/*<!--
							<fieldset>
								<input type="radio" name="subteam" value="M" /> YES
								<input type="radio" name="subteam" value="E" /> NO
							</fieldset>
							
							<fieldset>
								<input type="checkbox" name="verify" value="agree" /> I have agreed to the Terms and Conditions 
							</fieldset>
							-->*/
							echo "<fieldset>\n
								<label for=\"shippinghandling\">Shipping and Handling</label>\n
								<input type=\"text\" name=\"shippinghandling\" id=\"shippinghandling\" class=\"field\" value=\"$shippinghandling\"/>\n
							</fieldset>\n";
							
							?>
							
							<div id="form-submitbuttons">
							<fieldset>
								<input name="submit" type="submit" class="submit" value="submit" />
							</fieldset>
							<fieldset>
								<input name="update" type="submit" class="save" value="update" />
							</fieldset>
							<!-- save: input, then go to editform page
							     submit: input, goes to submitForApproval -->
							</div>
							
					</form>
				</div>
			</div>
			
		</div>
		<footer>
		</footer>
	</div>
</body>
</html>