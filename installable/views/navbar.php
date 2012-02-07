<?php
include "../setup/constants.php"; // contains teamName, schoolName, and other team/school-specific values that will be used throughout the system.
echo "<div id=\"nav\">
				<div id=\"navbar\">
					<ul>
						<li><a href=\"dashboard.php\">Home</a></li>
						<li><a href=\"profilepage.php\">My Profile</a></li>
						<li><a href=\"viewmyforms.php\">Purchase Orders</a></li>";
						$username = $_SESSION['robo'];
						$api = new roboSISAPI();
						if ($api->isAdmin($username))
						{
							echo '<li><a href="billOfMaterials.php">Bill Of Materials</a></li>';
							echo '<li><a href="admin_dashboard.php">Admin</a></li>';
						}
					echo "</ul>
				</div>
				<div id=\"login_status\">
					<p>Logged in as: ";
					echo $_SESSION['robo']; // echos the username
					echo "</p>
					<form method=\"post\" name=\"form\" action=\"\">
					<fieldset>
						<input name=\"logout\" type=\"submit\" class=\"logout\" value=\"Logout\" />
					</fieldset>
					</form>
				</div> <!-- end of login_status -->
			</div>";
echo "		<h1>$schoolName - $teamName</h1>";
?>