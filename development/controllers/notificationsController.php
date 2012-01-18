<?php
/**
 * notificationsController Class
 * 
 * This controller handles all email-based notifications.
 */

class notificationsController extends roboSISAPI
{
	// instance variables
	protected $_dbConnection;
	
	public function __construct()
	{
		parent::construct();
	}
	
	/**
	 * description: Emails the given user about the update to his/her PO.
	 * 
	 * @param username: 
	 * @return int: 
	 */
	public function emailUserAboutOrderStatusUpdate($username)
	{
		
	}
}
?>