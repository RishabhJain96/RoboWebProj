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
	header('Location: billOfMaterials.php'); // if there is no order to view, redirects to viewmyforms page
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
				
				print_r($_SESSION["billOfMaterials"]);
				
				
				?>
			</div>
			
		</div>
		<footer>
		</footer>
	</div>
</body>
</html>
