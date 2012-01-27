<?php
/**
 * rootController Class
 * 
 * This controller gives access to change accounts and data at the highest level.
 */

class rootController extends roboSISAPI
{
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
	
	/**
	 * description: 
	 * 
	 * @return array: An array of the usernames/names of admins
	 */
	public function getAdmins()
	{
		$resourceid = $this->_dbConnection->selectFromTable("RoboUsers");
		$users = $this->_dbConnection->formatQuery($resourceid);
		$admins = array();
		for ($i=0; $i < count($users); $i++) {
			if ($users[$i]['UserType'] == self::TYPE_ADMIN) {
				$admins[] = $users[$i]['Username'];
			}
		}
		return $admins;
	}
}

?>