<?php
/**
 * Robotics Student Information System General API
 * This api currently supports inputing a checkin, getting an array of all checkins, and getting a userID for a given username
 */

class roboSISAPI
{
	// constants
	const MAX_CHECKINS_PER_DAY = 2; // changed this to change the max number of checkins allowed per day
	
	// instance variables
	protected $_dbConnection;
	
	public function __construct()
	{
		$this->_dbConnection = dbUtils::getConnection();
		$this->_connection = $this->_dbConnection->open_db_connection();
		date_default_timezone_set('America/Los_Angeles'); // all times are in PST
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
			error_log("username does not exist");
			//echo 'The username '$username' does not exist';
			return false;
		}
		
		return $array[0];
	}
	
	/**
	 * Returns an array of all the emails in the database
	 */
	public function getAllEmails()
	{
		$resourceid = $this->_dbConnection->selectFromTable("RoboUsers");
		$arr = $this->_dbConnection->formatQueryResults($resourceid, "UserEmail");
		$output = json_encode($arr);
		//echo $output;
		return $output;
	}
	
	
	// CHECK-IN SYSTEM FUNCTIONS
	
	
	/**
	 * $timestamp: pass the timestamp of the check in as a parameter
	 * $id: the id of the user who is checking in (can be obtained using getUserID)
	 */
	public function inputCheckIn($username)
	{
		$id = $this->getUserID($username);
		if ($this->hasReachedCheckInLimit($id))
		{
			echo '<p>You have reached your check-in limit for today.';
			return false;
		}
		$timestamp = date("l, F j \a\\t g:i a"); // Friday, September 23 at 11:05 pm
		$timestamp2 = date("YmdHi"); // 201109232355
		$table = "UserHistories";
		$columnforid = "UserID";
		$arrayTime = array("HistoryTimeStamp" => $timestamp, "NumericTimeStamp" => $timestamp2);
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
		$resourceid = $this->_dbConnection->selectFromTableDesc("UserHistories", "UserID", $id, "NumericTimeStamp");
		$array = $this->_dbConnection->formatQueryResults($resourceid, "HistoryTimeStamp");
		if (empty($array) || is_null($array[0]))
		{
			echo '<p>There are no recent check-ins to display!</p>';
			return false;
		}
		$outputcontent = json_encode($array);
		//echo $outputcontent; // prints JSON to page
		return $outputcontent;
	}
	
	/**
	 * Checks if the given user has reached his/her check-in limit for the day
	 * returns true if user is at or greater than the limit of MAX_CHECKINS, false otherwise
	 * $id: the UserID of the user to check
	 */
	public function hasReachedCheckInLimit($id)
	{
		$resourceid = $this->_dbConnection->selectFromTableDesc("UserHistories", "UserID", $id, "NumericTimeStamp");
		$array = $this->_dbConnection->formatQueryResults($resourceid, "NumericTimeStamp");
		if (empty($array) || (count($array) < self::MAX_CHECKINS_PER_DAY))
		{
			return false; // user can not possibly have reached check-in limit
		}
		$timestamp = date("Ymd"); // 20110923, does not include hours/minutes
		for ($i=0; $i < count($array); $i++)
		{
			$array[$i] = substr($array[$i], 0, -4); // trims hours and minutes from timestamps to allow checking checkins for the current day
		}
		for ($i=0; $i < self::MAX_CHECKINS_PER_DAY; $i++)
		{
			if ($array[$i] != $timestamp)
			{
				return false;
			}
		}
		return true; // the array of usercheckins contains at least 2 values with the current day, meaning the user has already checked in twice
	}
	
	/**
	 * This method returns an array in JSON of the usernames of all the users who checked in on a given day
	 * $timestamp: the day to get the list of checked in users for. Must be in the format of the NumericTimeStamp column: 20110923, which cane be obtained in php by: date("Ymd");
	 */
	public function getUsersCheckedInForDate($timestamp)
	{
		$resourceid = $this->_dbConnection->selectFromTable("UserHistories");
		$array_time = $this->_dbConnection->formatQueryResults($resourceid, "NumericTimeStamp");
		$resourceid2 = $this->_dbConnection->selectFromTable("UserHistories");
		$array_id = $this->_dbConnection->formatQueryResults($resourceid2, "UserID");
		for ($i=0; $i < count($array_time); $i++)
		{
			$array_time[$i] = substr($array_time[$i], 0, -4); // trims hours and minutes from timestamps to allow getting checkins for the current day
		}
		//echo json_encode($array_time);
		//echo json_encode($array_id);
		$len = count($array_time); // keeps the number constant as array is changed
		// the following loop keeps only the timestamps that match the given one in $array_time, along with their associated ids
		for ($k=0; $k < $len; $k++)
		{
			if ($array_time[$k] != $timestamp)
			{
				unset($array_time[$k]); // removes element at k but does not reindex array
				unset($array_id[$k]); // by definition, array_id must have the same number of elements as array_time
			}
		}
		$array_time = array_values($array_time); // order elements in order
		$array_id = array_values($array_id);
		$array_usernames = array();
		for ($z=0; $z < count($array_id); $z++)
		{
			$resourceid3 = $this->_dbConnection->selectFromTable("RoboUsers", "UserID", $array_id[$z]);
			$arr = $this->_dbConnection->formatQueryResults($resourceid3, "Username");
			if (!in_array($arr[0],$array_usernames))
			{
				$array_usernames[$z] = $arr[0];
			}
		}
		$array_usernames = array_values($array_usernames);
		$output = json_encode($array_usernames);
		//echo $output;
		return $output;
	}
	
	// FINANCE SYSTEM FUNCTIONS
	
	
	/* THE FOLLOWING METHODS ARE STILL UNDER CONSTRUCTION */
	
	/**
	 * Inputs an order, with all necessary associated fields passed as an array, into the db.
	 * $array should be in the format: array("PartName" => "Bolts", "Quantity" => 2) etc. for all necessary values. The $array should include 
	 * $username: the user who is submitting the order
	 */
	public function inputOrder($username, $array)
	{
		$id = $this->getUserID($username);
	}
	
	/**
	 * Notifies relevant users of changes in the order status. This function relies on the php mail function.
	 */
	//public function notifyUsersAndAdmins($orderID)
	//{
		
	//}
	
	/**
	 * Returns an array in JSON format of all the past orders the given user has placed, with most recent order on top.
	 */
	public function getUsersOrders($username)
	{
		
	}
	
	
	// PROFILE FUNCTIONS
	
	
	/**
	 * Returns an array with each element except the first element containing an array of a user's profile data. The first element is an array of the names of the columns.
	 */
	public function getAllProfiles()
	{
		$array1 = array();
		$array1[0] = array("Username", "Full Name", "Phone Number", "Graduation Year", "Mother's Email", "Father's Email", "Student Email", "Subteam"); // this element is the top row, lists the names of the columns
		$array1[1] = array();
	}
	
}
?>