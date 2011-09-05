<?php
/**
 * Robotics SIS Register class
 */

class register
{
	// instance variables
	protected $_dbConnection;
	
	public function __construct($dbConnection)
	{
		$this->_dbConnection = $dbConnection;
		$this->_connection = $this->_dbConnection->open_db_connection();
	}
	
	public function registerNewUser($username, $password)
	{
		
	}
	
}

?>