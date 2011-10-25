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
					<h2>Purchase Order Forms - View All Forms</h2>
					<ul>
						<li><a href="submitform.php">Submit a Form</a></li>
						<li><a href="viewmyforms.php">View My Forms</a></li>
						<li class="form-selected">View All Forms</li>
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

				<div id="forms_displayWrapper">
					<?php
							$controller = new financeController();
							$orders = $controller->getAllOrders();
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

							for ($i=0; $i < count($orders); $i++)
							{
								
								echo '<div class="forms_display"><span class="forms_display_head"><p><strong>';
								echo refineOrderVal($orders[$i]["UserSubteam"]);
								echo '</strong> - <em>Locked</em></p></span><h3>';
								echo "<a href=\"vieworder.php?id=" . $orders[$i][0]["OrderID"] . "\">"."</a>";
								echo refineOrderVal($orders[$i]["PartVendorName"]);
								echo '</a></h3><ul><li><strong>Order ID: </strong>';
								echo $orders[$i]["OrderID"];
								echo '</li><li><strong>Current Status: </strong>';
								echo refineOrderVal($orders[$i]["Status"]);
								echo '</li><li><strong>Submitted by: </strong>';
								echo refineOrderVal($orders[$i]["Username"]);
								echo '</li></ul><span class="forms_display_price">$';
								echo $orders[$i]["EstimatedTotalPrice"];
								echo '</span><span class="forms_display_viewmore"><a href="';
								echo "vieworder.php?id=" . $orders[$i]["OrderID"] . "\">";
								echo 'View More &raquo;</a></span></div>';
							}
							?>
					</div>
				</div>
			</div>
			
		</div>
		<footer>
		</footer>
	</div>
</body>
</html>
