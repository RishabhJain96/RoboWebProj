<?php

include('header.php');

if (isset($_POST['submit'])) {

	$conn = mysql_connect("".$_POST['db_host']."", "".$_POST['db_username']."", "".$_POST['db_password']."") or die("The credentials you supplied are invalid. Please try again.");

	if ($conn) {
		mysql_select_db("".$_POST['db_name']."", $conn) or die();
	}

	if (table_exists(users, "".$_POST['db_name'])."") {
		echo "<b>The PO system is already installed. Please delete the file \"install.php\".</b>";
	}

	else {
	
	$db_config = "database.php";
	$db_handle = fopen($db_config, "w") or die("can't open file");

	$db_host = "".$_POST['db_host']."";
	$db_name = "".$_POST['db_name']."";
	$db_username = "".$_POST['db_username']."";
	$db_password = "".$_POST['db_password']."";

	$data = "<?php \$conn = mysql_connect(\"$db_host\", \"$db_username\", \"$db_password\") or die(); \n mysql_select_db(\"$db_name\", \$conn) or die(); ?>";

	fwrite($db_handle, $data);
	fclose($db_handle);

			
		$clients = "CREATE TABLE RoboUsers (
			UserID int(5) NOT NULL AUTO_INCREMENT,
			first_name varchar(30),
			last_name varchar(30),
			ph_num varchar(12),
			email_addr varchar(200),
			PRIMARY KEY (client_id)
		)";

		$projects = "CREATE TABLE projects (
			project_id int(3) NOT NULL AUTO_INCREMENT,
			client_id int(5),
			id int(5),
			project_name varchar(25),
			project_desc varchar(150),
			complete_time varchar(10),
			spent_time varchar(10),
			deadline date NOT NULL DEFAULT '0000-00-00',
			on_time varchar(1),
			complete int(1) NOT NULL DEFAULT '0',
			ftp_host varchar(30),
			ftp_username varchar(25),
			ftp_pass varchar(25),
			PRIMARY KEY (project_id)
		)";

		$users = "CREATE TABLE users (
			id int(5) NOT NULL AUTO_INCREMENT,
			username varchar(30),
			password varchar(32),
			first_name varchar(30),
			last_name varchar(30),
			role int(1),
			email_addr varchar(200),
			ph_num varchar(12),
			logged_in int(1) NOT NULL DEFAULT '0',
			PRIMARY KEY (id)
		)";

		$tasks = "CREATE TABLE tasks (
			task_id int(3) NOT NULL AUTO_INCREMENT,
			project_id int(3),
			dev_id int(5),
			task_name varchar(25),
			complete_time varchar(10),
			spent_time varchar(10),
			complete int(1) NOT NULL DEFAULT '0',
			task_desc varchar(150),
			notes longtext,
			PRIMARY KEY (task_id)
		)";

		$roles = "CREATE TABLE roles (
			role int(1),
			role_name varchar(30),
			PRIMARY KEY (role)
		)";

		$admin_role = "INSERT INTO roles (role, role_name) VALUES ('1', 'Administrator')";
		$manager_role = "INSERT INTO roles (role, role_name) VALUES ('2', 'Manager')";
		$dev_role = "INSERT INTO roles (role, role_name) VALUES ('3', 'Developer')";

		$administrator = "INSERT INTO users (username, password, first_name, last_name, role) VALUES ('Administrator', '5f4dcc3b5aa765d61d8327deb882cf99', 'John', 'Doe', '1')";

		//Create tables
		mysql_query($clients);
		mysql_query($projects);
		mysql_query($users);
		mysql_query($tasks);
		mysql_query($roles);

		//Populate roles table
		mysql_query($admin_role);
		mysql_query($manager_role);
		mysql_query($dev_role);

		//Create Administrator account, password "password"
		mysql_query($administrator);

	echo "<div id=\"contentContainer\">

		<div class=\"header\">
			Install Purchase Order System
		</div>

		<div class=\"content\">";

		echo "The PO system has been installed successfully. You may now log in <a href=\"index.php\">here</a> with the username \"Administrator\" and the password \"password\".";

	echo   "</div> <!--end content-->";

	echo "</div>";

	}
}

else {

	echo "<div id=\"contentContainer\">

		<div class=\"header\">
			Install Purchase Order System
		</div>

		<div class=\"content\">";

        echo 		"<center>";
        echo 		"<table border=\"0\" width=\"400\"><tr><td>";

        echo 		"<form enctype=\"multipart/form-data\" action=\"\" method=\"post\">\n";

	echo 		"<label for=\"db_host\">MySQL Hostname: </label><input type=\"text\" value=\"\" maxlength=\"150\" name=\"db_host\"/><br />\n";

        echo 		"<label for=\"db_name\">MySQL Database Name: </label><input type=\"text\" value=\"\" maxlength=\"150\" name=\"db_name\"><br />\n";

        echo 		"<label for=\"db_username\">MySQL Database Username: </label><input type=\"text\" value=\"\" maxlength=\"150\" name=\"db_username\"><br />\n";

        echo 		"<label for=\"db_password\">MySQL Database Password: </label><input type=\"password\" value=\"\" maxlength=\"150\" name=\"db_password\"/><br /><br />\n";

 	echo 		"<input type=\"submit\" name=\"submit\" value=\"Install Purchase Order System\" class=\"send\"/>";
 
	echo 		"</form>";

	echo 		"</td></tr></table>";
	echo 		"</center>";

	echo   "</div> <!--end content-->";

	echo "</div>";

}

include('footer.php');

?>
