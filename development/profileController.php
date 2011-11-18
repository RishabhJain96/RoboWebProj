<?php
/**
 * profileController Class
 * 
 * This class holds all the server-side methods related to processing profile data.
 */

class profileController
{
	// instance variables
	protected $_dbConnection;
	
	public function __construct()
	{
		$this->_dbConnection = dbUtils::getConnection();
	}
	
	
	// SETTER METHODS
	
	
	/**
	 * description: 
	 * 
	 * @param arg: 
	 * @return int: 
	 */
	public function functionName($arg)
	{
		
	}
}

?>