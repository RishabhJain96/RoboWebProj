<?
	header("P3P: CP='CAO PSA OUR'");
	session_start();

	include_once('config.php');

if ($_SESSION["login"] != "") {
	mysql_connect($host,$username,$password);
	mysql_select_db($database);
	$query = "SELECT * FROM $usertablename WHERE username='" . $_SESSION["login"] . "' AND password='" . md5($_SESSION["passwd"]) . "'";
	$result = mysql_query($query);
	if (mysql_num_rows($result) == 0) {
		header("Location: auth.php?backTo=$PHP_SELF");
	} else {
		$userInfo = mysql_fetch_assoc($result);
		if ($userInfo["level"] < $REQUSRACCESSLVL) {
			header("Location: auth.php?errorTxt=You+do+not+have+the+proper+access+level+for+this+page&backTo=$PHP_SELF");
		} else {
			// We're all clear to continue ...
		}
	}
} else {
	if ($REQUSRACCESSLVL > 0) {
		header("Location: auth.php?backTo=$PHP_SELF");
	}
}
?>
