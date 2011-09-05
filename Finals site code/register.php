<?php
include("ConnectMysql.php"); // Includes the db and form info.
if (!isset($_POST['submit'])) { // If the form has not been submitted.
	echo "<form method=\"POST\">";
	echo "Username:<input name=\"username\" type=\"text\" />";
	echo "Password:<input name=\"password\" type=\"password\" />";
	echo "Email:<input name=\"email\" type=\"text\" />";
	echo "Classes: <select name=\"class\"> <option>Class 1</option></select>";
	echo "<input type=\"submit\" name=\"submit\" value=\"submit\" />";
	echo "</form>";
} else { // The form has been submitted.
	$username = form($_POST['username']);
	$password = md5($_POST['password']); // Encrypts the password.
	$email = form($_POST['email']);
	$class = $_POST['class'];
 
	if (($username == "") || ($password == "") || ($email == "") || ($class == "")) { // Checks for blanks.
		exit("There was a field missing, please correct the form.");
	}
 
	$q = mysql_query("SELECT * FROM `notes` WHERE username = '$username' OR Email = '$email'") or die (mysql_error()); // mySQL Query
	$r = mysql_num_rows($q); // Checks to see if anything is in the db.
 
	if ($r > 0) { // If there are users with the same username/email.
		exit("That username/email is already registered!");
	} else {
		mysql_query("INSERT INTO `notes` (username,password,Email,class) VALUES ('$username','$password','$email','$class')") or die (mysql_error()); // Inserts the user.
		header("Location: login.php"); // Back to login.
	}
}
mysql_close($db_connect); // Closes the connection.
?>