<?PHP
session_start();
include("ConnectMysql.php");
include("variables.php");

if(isset($_SESSION['logged_in'])) {

}
else {
	header("Location: http://cytopic.net/semester2finals/login.php");
	exit;
}
?>
<html>
<head><title></title>
</head>
<body>
	<?PHP
		for($i = 0; $i < 2; $i++) {
			$end_result = $explode_user[$i];
			if($end_result == "English") {
				echo "Yadadadadad this is English crap!";
			}
			if($end_result == "Spanish") {
				echo "Yadadadadad this is Spanish crap!";
			}
		}
	
	?>
	
</body>
</html>