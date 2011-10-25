<?php
/**
 * Robotics SIS Class
 *  Class
 * 
 * @author Rohit Sanbhadti
 */

class 
{
	// instance variables
	protected $_dbConnection;
	
	public function __construct()
	{
		$this->_dbConnection = dbUtils::getConnection();
		$this->_connection = $this->_dbConnection->open_db_connection();
	}
	
	/**
	 * Class description
	 * $assigninguser: 
	 * $receivinguser: 
	 */
	public function assignTaskToUser($assigninguser, $receivinguser)
	{
		
	}
	
}

?>