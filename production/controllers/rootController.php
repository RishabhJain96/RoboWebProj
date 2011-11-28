<?php
/**
 * rootController Class
 * 
 * This controller gives access to change accounts and data at the highest level.
 */

class rootController
{
	// instance variables
	protected $_dbConnection;
	
	public function __construct()
	{
		parent::__construct();
	}
	
	/**
	 * Sets a user's type as "Admin"
	 * 
	 * @param username: The username to give admin access to
	 * @return void
	 */
	public function setAdmin($username)
	{
		$id = parent::getUserID($username);
		$arrVals = array("UserType" => "Admin");
		$this->_dbConnection->updateTable("RoboUsers", "RoboUsers", "UserID", $id, "UserID", $arrVals, "UserID = $id");
	}
	
}

?>