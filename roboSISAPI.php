<?php
/**
 * Robotics Student Information System General API
 * Make this object oriented!!!!!
 * format of call: http://localhost:8888/roboSISAPI.php?&id=$id&timestamp=$timestamp
 */

/**
 * test case: http://localhost:8888/roboSISAPI.php?id=1&timestamp=9999
 */

// The function __autoload is the method for loading all the classes being used in the script. Use it at the beginning of every php main page.
function __autoload($class)
{
	require_once $class . '.php';
}

$databaseName = "RoboticsSIS";
$dbhost = "localhost:8889";
$dbuser = "root";
$dbpass = "root";

$dbConnection = new relationalDBConnections($databaseName, $dbhost, $dbuser, $dbpass);

$table = $_GET["table"]; // RoboUsers, UserBadges
//$username = $_GET["username"];
$timestamp = $_GET["timestamp"]; // for check-ins only
$columnforid = $_GET["columnforid"];
$id = $_GET["id"];
$attribute = $_GET["attribute"]; // can be null

if (!is_null($timestamp)) // means that call is a check-in
{
	$columnforid = "UserID";
	$table = "UserHistories";
	$arrayTime = array("HistoryTimeStamp" => $timestamp);
	$dbConnection->insertIntoTable($table, "RoboUsers", $columnforid, $id, "UserID", $arrayTime);
	//printf(time());
	return false;
}

// the following code handles read calls
$resourceid;
switch ($id)
{
	case 'all': // case may not be necessary, included just in case
		$resourceid = $dbConnection->selectFromTable($table);
		break;
	default: // assumes id is set to the specific id of the student/counselor
		$resourceid = $dbConnection->selectFromTable($table, $columnforid, $id);
		break;
}

$array;
if (!empty($attribute))
{
	$array = $dbConnection->formatQueryResults($resourceid, $attribute);
	if (is_null($array[0])) // NOTE: can't destinguish between null value in table and invalid attribute parameter (both return array with single, null element)
	{
		error_log("This attribute is null for the query and id you have specified.");
		print 'NULL VALUE OR INVALID ATTRIBUTE';
		return false;
	}
}
else // attribute is unspecified, gets data for all attributes
{
	$array = $dbConnection->formatQueryResults($resourceid);
}

$outputcontent = $array;
$outputcontent = json_encode($outputcontent);
echo $outputcontent; // prints out JSON data to page

?>