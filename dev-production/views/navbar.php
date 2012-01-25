<?php
echo "<div id=\"nav\">
				<div id=\"navbar\">
					<ul>
						<li><a href=\"dashboard.php\">Home</a></li>
						<li><a href=\"profilepage.php\">My Profile</a></li>
						<li><a href=\"viewmyforms.php\">Purchase Orders</a></li>
						<li><a href=\"billOfMaterials.php\">Bill Of Materials</a></li>";
						$username = $_SESSION['robo'];
						$api = new roboSISAPI();
						if ($api->isAdmin($username))
						{
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
			</div>
			<h1>The Harker School - Robotics Team 1072</h1>";
?>