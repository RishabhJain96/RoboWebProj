<?php
session_start();
if (!(isset($_SESSION['robo']))) {
	header('Location: index.php');
  }
?>
<!DOCTYPE html>
<head>
	<meta charset="utf-8">
	<title></title>
	<meta name="description" content="">
	<meta name="author" content="">
	<link rel="stylesheet" type="text/css" href="dashboardstyle.css">
</head>
<body>

<div id="container">
	<header>

	</header>
	<div id="main" role="main">
		<div id="header">
			<h1>Robotics Team 1072 SIS</h1>
			<div id="headerMast">
				<nav>
					<ul>
						<li><a href="">Home</a></li>
						<li><a href="">My Check-Ins</a></li>
						<li><a href="">My Profile</a></li>
					</ul>
				</nav>
				<!-- hi devin :D -->
				<span class="signin-status"><a href="">Log Out</a></span>
			</div>
		</div>
		<div id="contentSections">
			<div id="mainContent">
				<h2>Tasks</h2>
				<p class="clearfix">
					<?php
					//code to get subteam tasks will eventually go here
					//$api = new roboSISAPI(new relationalDbConnections('RoboticsSIS', 'localhost:8889', 'root', 'root'));
					//$result = $api->getCheckins($api->getUserId());
					//$json = '["Time 5","Time 4","Time 3","Time 2","Time 1"]';
					?>
				</p>
				<h2>General Information</h2>
				<p class="clearfix">
					Welcome to Robotics!
				</p>

			</div><!-- mainContent -->
			
			<div id="rightPanel">
				<h2>Check-Ins</h2>
				<p class="clearfix">
					<ul>
						<?php
						function __autoload($class)
						{
							require_once $class . '.php';
						}
						//$username = $_SESSION['robo'];
						$username = "12rohits";
						//$api = new roboSISAPI(new relationalDbConnections('RoboticsSIS', 'localhost:8889', 'root', 'root'));
						//$result = $api->getCheckIns($api->getUserID($username));
						//$table = json_decode($result);
						//$len = count($table);
						//for($i = 0; $i < $len; $i++)
						//	echo "<li>".$table[$i]."</li>";
						?>
					</ul>
				</p>
			</div>
		</div>
		
	</div>
	<footer>

	</footer>
</div> <!--! end of #container -->

</body>
</html>
