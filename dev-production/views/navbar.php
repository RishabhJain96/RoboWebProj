<?php
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
							echo '<li><a href="admin_dashboard.php">Admin</a></li>';
						}
					echo "</ul>
				</div>
				<div id=\"login_status\">
					<p>Logged in as: <?php echo $_SESSION['robo']; // echos the username?></p>
					<form method=\"post\" name=\"form\" action=\"\">
					<fieldset>
						<input name=\"logout\" type=\"submit\" class=\"logout\" value=\"Logout\" />
					</fieldset>
					</form>
				</div> <!-- end of login_status -->
			</div>";
?>