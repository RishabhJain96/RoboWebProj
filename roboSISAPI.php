<?php
/**
 * Robotics Student Information System General API
 * This api can be instantiated like so, if a dbconnection object has not yet been created:
 * $varname = new roboSISAPI(new relationalDbConnections('RoboticsSIS', 'localhost:8889', 'root', 'root'));
 * This api currently supports inputing a checkin, getting an array of all checkins, and getting a userID for a given username
 */

class roboSISAPI
{
	protected $_dbConnection;
/*	$table; // RoboUsers, UserBadges
	$username;
	$timestamp; // for check-ins only
	$columnforid;
	$id;
	$attribute; // can be null
*/	
	public function __construct($dbConnection)
	{
		$this->_dbConnection = $dbConnection;
	}
	
	/**
	 * $timestamp: pass the timestamp of the check in as a parameter
	 * $id: the id of the user who is checking in (can be obtained using getUserID)
	 */
	public function inputCheckIn($timestamp, $id)
	{
		$table = "UserHistories";
		$columnforid = "UserID";
		$arrayTime = array("HistoryTimeStamp" => $timestamp);
		$this->_dbConnection->insertIntoTable($table, "RoboUsers", $columnforid, $id, "UserID", $arrayTime);
	}
	
	/**
	 * returns: an array in JSON format of timestamps for all previous checkins for the given user
	 * $id: the UserID of the user to get the check-ins
	 */
	public function getCheckIns($id)
	{
		$resourceid = $this->_dbConnection->selectFromTable("UserHistories", "UserID", $id);

		$array = $this->_dbConnection->formatQueryResults($resourceid, "HistoryTimeStamp");
		if (is_null($array[0])) // NOTE: can't destinguish between null value in table and invalid attribute parameter (both return array with single, null element)
		{
			error_log("");
			print 'NULL VALUE OR INVALID ATTRIBUTE';
			return false;
		}
		
		$outputcontent = json_encode($array);
		return $outputcontent;
		//echo $outputcontent; // prints out JSON data to page
	}
	
	/**
	 * $username must already exist in the database
	 * returns: the id, as an int, of the user with the given username
	 */
	public function getUserID($username)
	{
		$resourceid = $this->_dbConnection->selectFromTable("RoboUsers", "UserName", $username);

		$array = $this->_dbConnection->formatQueryResults($resourceid, "UserID");
		if (is_null($array[0])) // NOTE: can't destinguish between null value in table and invalid attribute parameter (both return array with single, null element)
		{
			error_log("");
			print 'NULL VALUE OR INVALID ATTRIBUTE';
			return false;
		}
		
		return $array[0];
	}

/* IGNORE EVERYTHING BELOW THIS LINE
$table = $_GET["table"]; // RoboUsers, UserBadges
//$username = $_GET["username"];
$timestamp = $_GET["timestamp"]; // for check-ins only
$columnforid = $_GET["columnforid"];
$id = $_GET["id"];
$attribute = $_GET["attribute"]; // can be null
*/
/*if (!is_null($timestamp)) // means that call is a check-in
{
	$columnforid = "UserID";
	$table = "UserHistories";
	$arrayTime = array("HistoryTimeStamp" => $timestamp);
	$dbConnection->insertIntoTable($table, "RoboUsers", $columnforid, $id, "UserID", $arrayTime);
	//printf(time());
	return false;
}*/
/*
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
*/
}
?>