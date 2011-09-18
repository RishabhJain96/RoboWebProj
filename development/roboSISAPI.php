<?php
/**
 * Robotics Student Information System General API
 * This api currently supports inputing a checkin, getting an array of all checkins, and getting a userID for a given username
 */

class roboSISAPI
{
	protected $_dbConnection;
	
	public function __construct()
	{
		$this->_dbConnection = dbUtils::getConnection();
		$this->_connection = $this->_dbConnection->open_db_connection();
	}
	
	// GENERAL FUNCTIONS
	
	/**
	 * $username must already exist in the database
	 * returns: the id, as an int, of the user with the given username
	 */
	public function getUserID($username)
	{
		$resourceid = $this->_dbConnection->selectFromTable("RoboUsers", "Username", $username);

		$array = $this->_dbConnection->formatQueryResults($resourceid, "UserID");
		if (is_null($array[0])) // NOTE: can't destinguish between null value in table and invalid attribute parameter (both return array with single, null element)
		{
			error_log("");
			//echo 'The username '$username' does not exist';
			return false;
		}
		
		return $array[0];
	}
	
	// CHECK-IN FUNCTIONS
	
	/**
	 * $timestamp: pass the timestamp of the check in as a parameter
	 * $id: the id of the user who is checking in (can be obtained using getUserID)
	 */
	public function inputCheckIn($timestamp, $username)
	{
		$id = $this->getUserID($username);
		$table = "UserHistories";
		$columnforid = "UserID";
		$arrayTime = array("HistoryTimeStamp" => $timestamp);
		$this->_dbConnection->insertIntoTable($table, "RoboUsers", $columnforid, $id, "UserID", $arrayTime);
		return true;
	}
	
	/**
	 * returns: an array in JSON format of timestamps for all previous checkins for the given user, with most recent check-in first
	 * $id: the UserID of the user to get the check-ins
	 */
	public function getCheckIns($username)
	{
		$id = $this->getUserID($username);
		$resourceid = $this->_dbConnection->selectFromTableDesc("UserHistories", "UserID", $id, "HistoryTimeStamp");
		$array = $this->_dbConnection->formatQueryResults($resourceid, "HistoryTimeStamp");
		if (empty($array) || is_null($array[0]))
		{
			echo 'There are no recent check-ins to display!';
			return false;
		}
		$outputcontent = json_encode($array);
		//echo $outputcontent; // prints JSON to page
		return $outputcontent;
	}
	
	// FINANCE SYSTEM FUNCTIONS
	/* THE FOLLOWING METHODS ARE STILL UNDER CONSTRUCTION */
	/**
	 * Inputs an order, with all necessary associated fields passed as an array, into the db.
	 * $array should be in the format: array("PartName" => "Bolts", "Quantity" => 2) etc. for all necessary values. The $array should include 
	 * $
	 */
	public function inputOrder($username, $array)
	{
		$id = $this->getUserID($username);
		
	}
	
	/**
	 * Notifies relevant users of changes in the order status. This function relies on the php mail function.
	 */
	public function notifyUsersAndAdmins($orderID)
	{
		
	}
	
	/**
	 * Returns an array in JSON format of all the past orders the given user has placed, with most recent order on top.
	 */
	public function getUsersOrders($username)
	{
		
	}
}
?>