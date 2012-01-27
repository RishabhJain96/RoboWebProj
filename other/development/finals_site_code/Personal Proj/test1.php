<?php
$i = 4;
if ($_POST['add_grocery'] == true) {
	$i++;
	$_POST['add_grocery'] == false;
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
	<input type="text" name="grocery_item_3">
	<?php while ($_POST['add_grocery'] == true) {
		echo "<input type=\"text\" name=\"grocery_item_$i\">";
		}?>
	<input type="submit" name="check">
	<input type="submit" name="add_grocery" value="Add More">
	</form>
</body>
</html>