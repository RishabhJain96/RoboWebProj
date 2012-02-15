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
if (is_null($_GET['id']))
{
	header('Location: viewallforms.php'); // if there is no order to edit, redirects to new form page
	exit;
}
$orderID = $_GET['id'];
$username = $_SESSION['robo'];
$controller = new financeController();
//if ($controller->isLocked($orderID))
//{
//	header("Location: vieworder.php?id=$orderID");
//}
$error = "";
$orders = $controller->getOrder($orderID);
//$orderslist = $controller->getOrdersList($orderID);
//print_r($orderslist);
if (isset($_POST['update'])) // only specific action needed if updating is to refresh the page
{
	$submittingUsername = $_POST['username'];
	if ($controller->isValidUsername($submittingUsername)) {
		$controller->setSubmittingUser($orderID, $submittingUsername);
		header("Location: changeorder.php?id=$orderID");
		exit;
	}
	else {
		$error = "<p>$submittingUsername is not a valid username. Please enter a valid username.</p>";
	}
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
			
			<?php include "navbar.php"; ?>
			
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
							$username = $orders[0]["Username"]; // current submitting user
							
							echo "<fieldset>\n
								<label for=\"username\">Submitting User</label>\n
								<input type=\"text\" name=\"username\" id=\"username\" class=\"field\" value=\"$username\"/>\n
							</fieldset>\n";
							echo $error;
							?>
							
							<div id="form-submitbuttons">
							<fieldset>
								<input name="update" type="submit" class="save" value="update" />
							</fieldset>
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