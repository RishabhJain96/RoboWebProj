<?php
if ($_POST['check'] == true) {
	$item = array($_POST['grocery_item_1'], $_POST['grocery_item_2'], $_POST['grocery_item_3']);
	$dbhost = "mysql";
	$dbuser = "yroot";
	$dbpass = "cytopic";
	$conn = mysql_connect($dbhost, $dbuser, $dbpass) or die ('Error connecting to mysql');
	$dbname = 'PriceProj';
	mysql_select_db($dbname);
	$query = "SELECT price FROM PriceProj WHERE item= $item";
	$result=mysql_query($query);
	while($row = mysql_fetch_assoc($result)) {
	$data = $row['price'];
	echo "<p>$data<br >";
}

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
	"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Grocery Shopping</title>
</head>
<body>
	<form method="post">
	<input type="text" name="grocery_item_1">
	<input type="text" name="grocery_item_2">
	<input type="text" name="grocery_item_3">
	<input type="submit" name="check">
	<input type="text" name="grocery_total" value="Add More">
	</form>
</body>
</html>