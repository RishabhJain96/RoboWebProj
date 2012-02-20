<?php
include "autoloader.php";
// this page doesn't work
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
if (!isset($_SESSION["billOfMaterials"]))
{
	header('Location: billOfMaterials.php');
	exit;
}

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
				//$controller = new financeController();
				$parts = $_SESSION["billOfMaterials"];
				//print_r($parts);
				//print "\n";
				//print_r($parts[0]);
				//$parts = array_values($parts);
				//print_r($parts);
				$fullTotal = 0.0;
				$total = 0.0;
				echo '<div id="formstable">
					<table>
						<tr id="header">
							<!-- <th>Part #</th> -->
							<th class="th_alt">Part Name</th>
							<th>Subsystem</th>
							<th class="th_alt">$ / Unit</th>
							<th id="quantity">Quantity</th>
							<th class="th_alt">Total</th>
						</tr>';
						for ($i=0; $i < count($parts); $i++)
						{
							$orderslist = $parts[$i];
							$total = floatval($orderslist["price"]) * floatval($orderslist["quantity"]);
							//print_r($parts[$i]);
							//print $i;
							//print_r($orderslist);
							if (!empty($orderslist))
							{
								echo "<tr>";
								//echo "<td class=\"td_alt\">" . $orderslist[0]["PartNumber"] . "</td>";
								echo "<td>" . $orderslist["name"] . "</td>";
								echo "<td>" . $orderslist["subsystem"] . "</td>";
								echo "<td>$" . $orderslist["price"] . "</td>";
								echo "<td>" . $orderslist["quantity"] . "</td>";
								echo "<td>$" . $total . "</td>";
								echo "</tr>";
								$fullTotal += $total;
							}
						}
						
					echo "</table>
					<h3>Total Price: \$$fullTotal</h3>
				</div>";
				?>
			</div>
			
		</div>
		<footer>
		</footer>
	</div>
</body>
</html>
