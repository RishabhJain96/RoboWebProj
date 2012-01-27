<?php
/**
 * Robotics Student Information System General API
 * This api currently supports inputing a checkin, getting an array of all checkins, and getting a userID for a given username
 */

class roboSISAPI
{
	// constants
	const MAX_CHECKINS_PER_DAY = 1; // changed this to change the max number of checkins allowed per day
	const TYPE_MENTOR = "Mentor";
	const TYPE_ADMIN = "Admin";
	
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
	 * description: Sanitizes the input data by escaping for MySQL entry, stripping HTML tags, and trimming whitespace.
	 * 
	 * @param input: The string to be sanitized for entry
	 * @return string: Returns the sanitized $input data
	 */
	public function sanitize($input)
	{
		$input = trim($input);
		$input = strip_tags($input);
		$input = mysql_real_escape_string($input);
		//$input = rtrim($input); // superfluous after trim
//		if (strpos($input,">"))
//			$input = preg_replace(">","",$input);
//		if (!get_magic_quotes_gpc()) { 
//			$input=addslashes($input); 
//		}
		return $input;
	}
	
	/**
	 * $username must already exist in the database
	 * returns: the id, as an int, of the user with the given username
	 */
	public function getUserID($username)
	{
		$resourceid = $this->_dbConnection->selectFromTable("RoboUsers", "Username", $username);
		$array = $this->_dbConnection->formatQueryResults($resourceid, "UserID");
		if (empty($array) || is_null($array[0])) // NOTE: can't destinguish between null value in table and invalid attribute parameter (both return array with single, null element)
		{
			error_log("username does not exist");
			echo "<p>The username $username does not exist!</p>";
			return false;
		}
		
		return $array[0];
	}
	
	/**
	 * Returns the type of the user as a string, or null if user does not have a defined type
	 */
	public function getUserType($username)
	{
		$id = $this->getUserID($username);
		$resourceid = $this->_dbConnection->selectFromTable("RoboUsers", "UserID", $id);
		$array = $this->_dbConnection->formatQueryResults($resourceid, "UserType");
		$type = $array[0];
		//echo $type;
		return $type;
	}
	
	/**
	 * description: Returns true if given user is an admin
	 * 
	 * @param username: 
	 * @return bool: 
	 */
	public function isAdmin($username)
	{
		$type = $this->getUserType($username);
		return ($type == "VP" || $type == "Admin" || $type == "Mentor");
	}
	
	/**
	 * description: Returns true if given user has root access
	 * 
	 * @param username: 
	 * @return bool: 
	 */
	public function isMentor($username)
	{
		$type = $this->getUserType($username);
		return ($type == "Mentor");
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
	
	/**
	 * description: Returns the mentor's email.
	 * 
	 * @return string: The mentor's email or null if there is no mentor in the database.
	 */
	public function getMentorsEmail()
	{
		$resourceid = $this->_dbConnection->selectFromTable("RoboUsers");
		$users = $this->_dbConnection->formatQuery($resourceid);
		$email = "";
		for ($i=0; $i < count($users); $i++) {
			if ($users[$i]['UserType'] == self::TYPE_MENTOR) {
				$email = $users[$i]['UserEmail'];
				print_r($email);
				return $email;
			}
		}
		return null;
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
			echo '<p>You have reached your check-in limit for today.</p>';
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
	 * This method is unintentionally convoluted but it works so please don't mess around with it unless you're sure you know what you're doing.
	 * This method returns a 2D array in JSON of the usernames and associated HistoryTimeStamps of all the users who checked in on a given day
	 * $timestamp: the day to get the list of checked in users for. Must be in the format of the NumericTimeStamp column: 20110903 or YYYYMMDD
	 */
	public function getUsersCheckedInForDate($timestamp)
	{
		$resourceid = $this->_dbConnection->selectFromTable("UserHistories");
		$array_time = $this->_dbConnection->formatQueryResults($resourceid, "NumericTimeStamp");
		// allows to get HistoryTimeStamp for each user check-in on given day
		$resourceid5 = $this->_dbConnection->selectFromTable("UserHistories");
		$array_numerictime = $this->_dbConnection->formatQueryResults($resourceid5, "NumericTimeStamp");
		$resourceid2 = $this->_dbConnection->selectFromTable("UserHistories");
		$array_id = $this->_dbConnection->formatQueryResults($resourceid2, "UserID");
		for ($i=0; $i < count($array_time); $i++)
		{
			$array_time[$i] = substr($array_time[$i], 0, -4); // trims hours and minutes from numeric timestamps to allow getting checkins for the given day
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
				unset($array_numerictime[$k]); // keeps in sync with $array_time
			}
		}
		$array_time = array_values($array_time);// order elements in order
		$array_id = array_values($array_id); // order elements in order
		$array_numerictime = array_values($array_numerictime);
		$array_usernames = array();
		$array_fulltimes = array(); // array to hold the textual timestamp per user
		for ($z=0; $z < count($array_id); $z++)
		{
			// fills array with usernames
			$resourceid3 = $this->_dbConnection->selectFromTable("RoboUsers", "UserID", $array_id[$z]);
			$arr_name = $this->_dbConnection->formatQueryResults($resourceid3, "Username");
			// get full name
			$array_usernames[$z] = $arr_name[0];
			// fills array with HistoryTimeStamps
			$resourceid4 = $this->_dbConnection->selectFromTable("UserHistories", "NumericTimeStamp", $array_numerictime[$z]);
			$arr_texttime = $this->_dbConnection->formatQueryResults($resourceid4, "HistoryTimeStamp");
			$array_fulltimes[$z] = $arr_texttime[0];
			// the following if block will ensure usernames are not duplicated in the list, currently unwanted
			// if (!in_array($arr[0],$array_usernames))
			// {
			//	$array_usernames[$z] = $arr[0];
			// }
		}
		$array_usernames = array_values($array_usernames); // ordered list of all users who checked in
		$array_fulltimes = array_values($array_fulltimes);
		$array_output = array($array_usernames,$array_fulltimes);
		$output = json_encode($array_output);
		// iterate array usernames, get full names if applicable
		for ($k=0; $k < count($array_usernames); $k++)
		{
			$array_usernames[$k] = $this->getUserFullName($array_usernames[$k]);
		}
		//$test = json_decode($output);
		//print_r($test);
		return $output;
	}
	
	// PROFILE FUNCTIONS
	
	
	// SETTER METHODS
	
	
	/**
	 * description: Updates the given user's profile info
	 * 
	 * @param username: the account to update
	 * @param arrUserInfo: the array of the user's info in the format specified by the tester class (profileTester)
	 * @return void
	 */
	public function updateUserInfo($username, $arrUserInfo)
	{
		$id = $this->getUserID($username);
		$this->_dbConnection->updateTable("RoboUsers", "RoboUsers", "UserID", $id, "UserID", $arrUserInfo, "UserID = $id");
	}
	
	
	// GETTER METHODS
	
	
	/**
	 * description: Returns an array with the given user's info, except for the password
	 * 
	 * @param username: The user to retrieve the info of
	 * @return array: The user's info in an array
	 */
	public function getUserInfo($username)
	{
		$id = $this->getUserID($username);
		$resourceid = $this->_dbConnection->selectFromTable("RoboUsers", "UserID", $id);
		$arrInfo = $this->_dbConnection->formatQuery($resourceid);
		unset($arrInfo[0]["UserPassword"]); // removes the user's password from the array of info for security, because it will not be needed when calling this method
		return $arrInfo[0];
	}
	
	/**
	 * description: Returns the given user's full name
	 * 
	 * @param username: The given user
	 * @return string: The user's full name
	 */
	public function getUserFullName($username)
	{
		$info = $this->getUserInfo($username);
		$fullName = $info["UserFullName"];
		if (is_null($fullName) || empty($fullName))
		{
			return $username; // returns username if fullname is null
		}
		return $fullName;
	}
	
}
?>