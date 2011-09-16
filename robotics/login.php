<?php
/**
 * Login class
 */

class login
{
	// instance variables
	protected $_dbConnection;
	
	public function __construct($dbConnection)
	{
		$this->_dbConnection = $dbConnection;
		$this->_connection = $this->_dbConnection->open_db_connection();
	}
	
	public function checkLogin($username, $password)
	{
		$resourceid = $this->_dbConnection->selectFromTable("RoboUsers", "Username", $username);
		$arr = $this->_dbConnection->formatQueryResults($resourceid, "Username");
		//print_r($arr);
		if (is_null($arr[0]))
		{
			error_log("This username does not exist!");
			echo "<p>This username ($username) does not exist!</p>";
			return false;
		}
		
		$md5password = md5($password);
		$resourceid2 = $this->_dbConnection->selectFromTable("RoboUsers", "Username", $username);
		$arr2 = $this->_dbConnection->formatQueryResults($resourceid2, "UserPassword");
		if (strcmp($md5password,$arr2[0]) == 0)
		{
			// username and password are valid
			return true;
		}
		else
		{
			error_log("Your password is incorrect.");
			echo "<p>Your password is incorrect.</p>";
			return false;
		}
	}
	
}

?>