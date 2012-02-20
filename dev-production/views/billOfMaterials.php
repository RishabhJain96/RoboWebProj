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

if(isset($_POST["billOfMaterials"]))
{
	$itemsPartOfBill = $_POST["materials"];
	$selected = array();
	for ($i=0; $i < count($itemsPartOfBill); $i++)
	{
		if (array_key_exists("checked", $itemsPartOfBill[$i])) // works around array index undefined error
		{
			if ($itemsPartOfBill[$i]["checked"] === "yes")
			{
				$selected[] = $itemsPartOfBill[$i];
			}
		}
	}
	$_SESSION["billOfMaterials"] = $selected;
	header("Location: printBillOfMaterials.php");
}

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
		
		function calculateTotal(priceID, quantityName, totalID)
		{
			var part_total = 0.0;
			
			// Get the price
			var item_price = parseFloat(document.getElementById(priceID).innerHTML);
			
			// Get the quantity
			var item_quantity = parseFloat(document.listform.elements[quantityName].value);
			
			part_total = item_quantity * item_price;
			
			var cell = document.getElementById(totalID);
			cell.innerHTML = part_total;
			
			//form.elements[elementNum][total].value = round_decimals(part_total, 2);
		}
		
		function round_decimals(original_number, decimals)
		{
		    var result1 = original_number * Math.pow(10, decimals);
		    var result2 = Math.round(result1);
		    var result3 = result2 / Math.pow(10, decimals);
		    return result3;
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
						$arrName = "materials[$i]";
						$id = $allOrderParts[$i]['OrderListID'];
						$number = $allOrderParts[$i]['PartNumber'];
						$name = $allOrderParts[$i]['PartName'];
						$subsystem = $allOrderParts[$i]['PartSubsystem'];
						$price = $allOrderParts[$i]['PartIndividualPrice'];
						$quantity = $allOrderParts[$i]['PartQuantity'];
						$total = $allOrderParts[$i]['PartTotalPrice'];
						echo "<tr>
							<td><input type=\"checkbox\" id=\"materials\" name=\"$arrName"."[checked]\" value=\"yes\" /></td>
							<!-- <td class=\"td_alt\">$number</td> -->
							<td id=\"$arrName"."[name]\" name=\"$arrName"."[name]\">$name</td>
							<fieldset><input type=\"hidden\" name=\"$arrName"."[name]\" value=\"$name\" /></fieldset>
							<td class=\"td_alt\" id=\"$arrName"."[subsystem]\" name=\"$arrName"."[subsystem]\">$subsystem</td>
							<fieldset><input type=\"hidden\" name=\"$arrName"."[subsystem]\" value=\"$subsystem\" /></fieldset>
							<td id=\"$arrName"."[price]\" name=\"$arrName"."[price]\">$price</td>
							<fieldset><input type=\"hidden\" name=\"$arrName"."[price]\" value=\"$price\" /></fieldset>
							<td class=\"quantity\"><fieldset><input type=\"text\" name=\"$arrName"."[quantity]\" value=\"$quantity\" onChange=\"calculateTotal($arrName"."[price],$arrName"."[quantity],$arrName"."[total])\" /></fieldset></td>
							<td id=\"$arrName"."[total] name=\"$arrName"."[total]>$total</td>
							<fieldset><input type=\"hidden\" name=\"$arrName"."[total]\" value=\"$total\" /></fieldset>
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
