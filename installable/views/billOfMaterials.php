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
	if (empty($itemsPartOfBill) || is_null($itemsPartOfBill)) {
		echo "<p>Please select the parts you would like to include.</p>";
	}
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
	
	<script type="text/javascript" charset="utf-8">
		<!--
		function checkAll(field) 
		{
		for (i = 0; i < field.length; i++)
			field[i].checked = true ;
		}

		function uncheckAll(field) 
		{
		for (i = 0; i < field.length; i++)
			field[i].checked = false ;
		}
		// -->
	</script>
	
	<link rel="stylesheet" href="stylesheets/form.css" type="text/css" />
</head>
<body>
	<div id="mainWrapper">
		<div id="floater"></div>
		<div id="dashboardWindow" class="clearfix">
			
			<?php include "navbar.php"; ?>
			<div id="formstable">
			<form method="POST" name="listform" action"">
				<input type="button" name="CheckAll" value="select all"
				onClick="checkAll(document.listform.materials)">
				<input type="button" name="UnCheckAll" value="unselect all"
				onClick="uncheckAll(document.listform.materials)">
				<table>
					<tr id="header">
						<th>&#x2713;</th>
						<!-- <th>Part #</th> -->
						<th class="th_alt">Part Name</th>
						<th>Subsystem</th>
						<th class="th_alt">$ / Unit</th>
						<th id="quantity">Quantity</th>
						<th class="th_alt">Total</th>
					</tr>
				<?php
					$controller = new financeController();
					$allOrderParts = $controller->getAllOrdersListParts();
					//$fullTotal = 0.0;
					//print_r($allOrderParts);
					for($i = 0; $i < count($allOrderParts); $i++)
					{
						$id = $allOrderParts[$i]['OrderListID'];
						$number = $allOrderParts[$i]['PartNumber'];
						$name = $allOrderParts[$i]['PartName'];
						$subsystem = $allOrderParts[$i]['PartSubsystem'];
						$price = $allOrderParts[$i]['PartIndividualPrice'];
						$quantity = $allOrderParts[$i]['PartQuantity'];
						$total = $allOrderParts[$i]['PartTotalPrice'];
						echo "<tr>
							<td><input type=\"checkbox\" id=\"materials\" name=\"materials[$i]\" value=\"$id\" /></td>
							<!-- <td class=\"td_alt\">$number</td> -->
							<td>$name</td>
							<td class=\"td_alt\">$subsystem</td>
							<td>$price</td>
							<td class=\"td_alt\">$quantity</td>
							<td>$total</td>
						</tr>";
						//echo "<td>Number of Items: <input type=\"text\" name=\"materials\" /></td></tr>";
						//$fullTotal += floatval($total);
					}
					echo "</table>";
				//echo "<h3>Total Price: \$$fullTotal</h3>";
				//$_SESSION['fullTotal'] = $fullTotal;
				?>
			<input type="submit" name="billOfMaterials" value="submit" />
			</form>
			</div>
		</div>
		<footer>
		</footer>
	</div>
</body>
</html>
