<?php
/**
 * tasksController Class
 * 
 * This class holds all the server-side functions related to the tasks system.
 */

class tasksController
{
	// instance variables
	protected $_dbConnection;
	
	public function __construct()
	{
		parent::__construct();
	}
	
	/**
	 * Assigns the given task to the given user
	 * $parameter: 
	 */
	public function assignTaskToUser($assigninguser, $receivinguser)
	{
		
	}
	
}

?>