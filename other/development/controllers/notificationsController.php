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
		parent::__construct();
	}
	
	/**
	 * description: Emails the given user about the update to his/her PO.
	 * 
	 * @param username: The user to email.
	 * @param orderID: The ID of the order which the notification is about.
	 * @return int: 
	 */
	public function emailUserStatusUpdate($username, $orderID)
	{
		$to = $username . "@students.harker.org";
		$subject = "Robotics PO Status Update: OrderID #$id";
		$message = "Hello $username, \n\n Please go to: website to activate your account. \n\n - The Robotics 1072 Web Team"; 
		$header = "From: harker1072@gmail.com";
		print_r($to);
		print_r($subject);
		print_r($message);
		print_r($header);
		$result = mail($to, $subject, $message, $header); // returns true on delivery
		return $result;
	}
}
?>