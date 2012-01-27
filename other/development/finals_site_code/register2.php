<?PHP 
include("ConnectMysql.php");

if(isset($_POST['register'])) {
	$username = $_POST['username'];
	$password = $_POST['pwd'];
	$english = $_POST['English'];
	$spanish = $_POST['Spanish'];
	$q = mysql_query("SELECT * FROM notes WHERE username = '$username'") OR die (mysql_error());
	$r = mysql_num_rows($q);
	if ($r > 0) {
		echo "Sorry Username taken";
	} else {
	$handle = fopen("register.txt", "a");
	$sometext = "\n".$_POST['username'].",";
	fwrite($handle, $sometext);
	$sometext2 = " ".md5($_POST['pwd']);
	fwrite($handle, $sometext2);
	fclose($handle);
	mysql_query("INSERT INTO notes (username, english, spanish) VALUES ('$username', '$english', '$spanish') ") or die (mysql_error());
	header("Location: login.php");
	}
}
?>

<html>
<head><title></title>
</head>
<body>
	<form id="loginForm" name="loginForm" method="post" action="">
	    <p>
	        <label for="username" class="adminloginlabel">Username:</label>
	        <input type="text" name="username" id="username" />
	    </p>
	    <p>
	        <label for="textfield" class="adminloginlabel">Password:</label>
	        <input type="password" name="pwd" id="pwd" />
	    </p>
	    <p>
			<p>Choose Your Classes</p><p>
		        <label for="username" class="adminloginlabel">English</label>
		        <input type="checkbox" name="English" id="English" />
		    </p>
		    <p>
		        <label for="textfield" class="adminloginlabel">Spanish</label>
		        <input type="checkbox" name="Spanish" id="Spanish" />
		    </p>
		    <p>
	        <input name="register" type="submit" id="login" value="Register" />
	    </p>
	</form>
</body>
</html>