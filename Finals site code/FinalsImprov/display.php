<?PHP
session_start();
include("includes/ConnectMysql.php");
include("includes/variables.php");

if(isset($_SESSION['logged_in'])) {

}
else {
	header("Location: http://cytopic.net/semester2finals/login.php");
	exit;
}
$class_array = array("English", "Spanish", "Japanese");	


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
   "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
	<title>Finals Study Guides - Home</title>
</head>
<body>
	<?PHP 
		for($i = 0; $i < 8; $i++) {
			$end_result = $explode_user[$i];
			foreach($class_array as $v) {
				if($end_result == $v) {
					$query0 = mysql_query("SELECT * FROM classes WHERE class = '$v'");
					while($rows = mysql_fetch_array($query0)) {
						$data = $rows['fileName'];
						$data2 = $rows['username'];
						echo "<p>$data2</p>";
						echo "<p><a href=\"uploads/$data\">$data</a></p>";
						
					}
				}
			}
		}
?>
</body>
</html>