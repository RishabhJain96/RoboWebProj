<?php
/**
 * profileController Class
 * 
 * This class holds all the server-side methods related to processing profile data.
 */

class profileController extends roboSISAPI
{
	// instance variables
	protected $_dbConnection;
	
	public function __construct()
	{
		parent::__construct();
	}
	
	
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
		$id = parent::getUserID($username);
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
		$id = parent::getUserID($username);
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
		if (is_null($fullname))
		{
			return $username;
		}
		return $fullName;
	}
}

?>