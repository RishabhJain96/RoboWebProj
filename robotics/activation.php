<?php
/**
 * This is a small procedural page to set the activated field for the user's account
 */
function __autoload($class)
{
	require_once $class . '.php';
}

$code = $_GET["acode"];
$dbArr = file("dbParameters.txt");
$dbArr[0] = str_replace(array("\r", "\r\n", "\n"), '', $dbArr[0]);
$dbArr[1] = str_replace(array("\r", "\r\n", "\n"), '', $dbArr[1]);
$dbArr[2] = str_replace(array("\r", "\r\n", "\n"), '', $dbArr[2]);
$dbArr[3] = str_replace(array("\r", "\r\n", "\n"), '', $dbArr[3]);
$dbConnection = new relationalDbConnections($dbArr[0], $dbArr[1], $dbArr[2], $dbArr[3]);

$resourceid = $dbConnection->selectFromTable("RoboUsers", "ActivationCode", $acode);
$arr = $dbConnection->formatQueryResults($resourceid, "ActivationCode");

// this runs if the activation code is not in the database, which is what happens after a user activates his/her account for the first time.
if (!is_null($arr[0])) // NOTE: can't destinguish between null value in table and invalid attribute parameter (both return array with single, null element)
{
	error_log("This account has already been activated.");
	echo 'This account has already been activated.';
	return false; // cuts execution early
}


$bool = 1; // any nonzero value to indicated activated status
$stuffing = "Activated"; // clears the activation code field for clarity
$array = array("Activated" => $bool, "ActivationCode" => $stuffing);
$dbConnection->updateTable("RoboUsers", "RoboUsers", "ActivationCode", $code, "UserID", $array, "ActivationCode = '$code'");

echo 'Congratulations! Your account has been activated.';

?>