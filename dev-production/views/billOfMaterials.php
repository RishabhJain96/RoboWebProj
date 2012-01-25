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

/*
$controller = new financeController();
if ($controller->isAdminApproved($orderID))
{
	header("Location: adminviewpending.php");
}
if(isset($_POST['approve']))
{
	$comment = $_POST['comment'];
	$controller->setAdminApproval($orderID, true, $username, $comment);
	$controller->submitForMentorApproval($orderID);
	header("Location: adminviewpending.php");
}
if(isset($_POST['reject']))
{
	$comment = $_POST['comment'];
	$controller->setAdminApproval($orderID, false, $username, $comment);
	header("Location: adminviewpending.php");
}
*/
if(isset($_POST["billOfMaterials"]))
{
	$itemsPartOfBill = $_POST["materials"];
	$_SESSION["billOfMaterials"] = $itemsPartOfBill;
	header("Location: printBillOfMaterials.php");
}

// Will accept url parameter (id=number) to get orderID
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
			
			<?php include "navbar.php"; ?>
			
			<form method="POST" name="form" action"">
				<table>
			<?php
				$controller = new financeController();
				$allOrders = $controller->getAllorders();
				for($i = 0; $i < count($allOrders); $i++)
				{
					$id = $allOrders[$i]['OrderID'];
					$vname = $allOrders[$i]['PartVendorName'];
					echo "<tr><td><input type=\"checkbox\" name=\"materials\" value=\"$id\" /></td>
					<td>$vname</td>
					<td>Number of Items: <input type=\"text\" name=\"materials\" /></td></tr>";
					
				//	echo "<div><div style=\"float: left;\"><input type=\"checkbox\" name=\"materials\"value=\"$allOrders[i]['OrderID']\" /></div><div style=\"float: right;\">$allOrders[i]['VendorName']</div></div>";
				}
			
			?>
			
				</table>
			<input type="submit" name="billOfMaterials" value="submit" />
			</form>
		</div>
		<footer>
		</footer>
	</div>
</body>
</html>
