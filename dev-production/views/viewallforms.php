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
					<h2>Purchase Order Forms - View All Forms</h2>
					<ul>
						<li><a href="submitform.php">Submit a Form</a></li>
						<li><a href="viewmyforms.php">View My Forms</a></li>
						<li class="form-selected">View All Forms</li>
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
							
							if (count($orders) == 0)
							{
								echo "<br />";
								echo '<p>There are currently no orders in the database.</p>';
							}

							for ($i=0; $i < count($orders); $i++)
							{
								
								echo '<div class="forms_display clearfix"><span class="forms_display_head"><p><strong>';
								echo refineOrderVal($orders[$i]["UserSubteam"]);
								echo "</strong> - <em>" . refineStatus($orders[$i]["Status"]) . "</em></p></span><h3>";
								echo "<a href=\"vieworder.php?id=" . $orders[$i]["OrderID"] . "\">";
								echo refineOrderVal($orders[$i]["PartVendorName"]);
								echo '</a></h3><ul><li><strong>Order ID: </strong>';
								echo $orders[$i]["OrderID"];
								echo '</li><li><strong>Current Status: </strong>';
								echo refineStatus($orders[$i]["Status"]);
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
