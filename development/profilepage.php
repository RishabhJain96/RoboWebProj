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
$username = $_SESSION['robo'];
$controller = new financeController();
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
		$partprice = sprintf("%01.2f", $partprice); // makes sure partprice only has 2 decimals
		$partprice = floatval($partprice); // turns string into float
		$partquantity = $neworderslist[$i]["partquantity"];
		$partquantity = intval($partquantity); // makes sure part quantity is an int
		// $parttotal = $neworderslist[$i]["parttotal"];
		$parttotal = floatval($partquantity) * $partprice; // parttotal will be a float
		$parttotal = sprintf("%01.2f", $parttotal); // makes sure parttotal only has 2 decimals
		$parttotal = floatval($parttotal); // turns string into float
		$fulltotal = $fulltotal + $parttotal;
		$fulltotal = sprintf("%01.2f", $fulltotal); // makes sure fulltotal only has 2 decimals
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
	$tax = 0.0925 * $fulltotal;
	$tax = sprintf("%01.2f", $tax); // makes sure tax price only has 2 decimals
	$tax = floatval($tax); // turns string into float
	$etotal = $fulltotal + $tax + $shippinghandling;
	$etotal = sprintf("%01.2f", $etotal); // makes sure etotal only has 2 decimals
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
	
	<link rel="stylesheet" href="form.css" type="text/css" />
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
					<h2><?php echo $_SESSION['robo']; ?>&#x27;s Profile</h2>
				</div>
				<div id="forms-submit">
					<form id="orderform" method="post" action="">
							<?php
							// getting all variables
							$subteam = $orders[0]["UserSubteam"];
							$vendorname = $orders[0]["PartVendorName"];
							$vendorphone = $orders[0]["PartVendorPhoneNumber"];
							$vendoremail = $orders[0]["PartVendorEmail"];
							$vendoraddress = $orders[0]["PartVendorAddress"];
							$reason = $orders[0]["ReasonForPurchase"];
							$shippinghandling = $orders[0]["ShippingAndHandling"];
							$usertype = $user['UserType'];
							
							echo "<h2>Type $usertype</h2>";
							
							echo "<br />";
							
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
							 	<label id=\"name\">Full Name</label>\n
							 	<input type=\"text\" name=\"name\" id=\"name\" class=\"field\" value=\"$name\" />\n
							 </fieldset>\n
							 <fieldset>\n
							 	<label id=\"phone\" >Phone Number</label>\n
							 	<input type=\"text\" name=\"phone\" id=\"phone\" class=\"field\" value=\"$phone\" />\n
							 </fieldset>\n
							 <fieldset>\n
							 	<label for=\"email\">Email</label>\n
							 	<input type=\"text\" name=\"email\" id=\"email\" class=\"field\" value=\"$email\"/>\n
							 </fieldset>\n
							 <fieldset>\n
							 	<label id=\"pemail\" >Parent's Email</label>\n
							 	<input type=\"text\" name=\"pemail\" id=\"pemail\" class=\"field\" value=\"$pemail\" />\n
							 </fieldset>\n
							 <fieldset>\n
							 	<label id=\"gradyear\" >Graduation Year</label>\n
							 	<input type=\"text\" name=\"gradyear\" id=\"gradyear\" class=\"field\" value=\"$gradyear\" />\n
							 </fieldset>\n";
							
							
							?>
							
							<div id="form-submitbuttons">
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