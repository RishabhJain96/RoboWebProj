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
	protected $_serverURL;
	
	public function __construct()
	{
		parent::__construct();
		$this->_serverURL = "http://robo.harker.org/devprod";
	}
	
	/**
	 * Takes in a status in the format that it is stored in the database and refines it to a format that is human-readable.
	 */
	public function refineStatus($status)
	{
		// Unfinished, AdminPending, AdminApproved, MentorPending, MentorApproved, AdminRejected, MentorRejected
		if ($status == "AdminPending")
			return "Pending Admin Approval";
		else if ($status == "MentorPending")
			return "Pending Mentor Approval";
		else if ($status == "AdminApproved")
			return "Admin Approved";
		else if ($status == "MentorApproved")
			return "Mentor Approved";
		else if ($status == "AdminRejected")
			return "Admin Rejected";
		else if ($status == "MentorRejected")
			return "Mentor Rejected";
		else
			return $status;
	}
	
	/**
	 * description: Emails the given user about the update to his/her PO.
	 * 
	 * @param username: The user to email.
	 * @param orderID: The ID of the order which the notification is about.
	 * @param status: The status of the order.
	 * @param vendorname: The name of the vendor of the given order.
	 * @return boolean: true if mail was sent successfuly, false otherwise.
	 */
	public function emailUserStatusUpdate($username, $orderID, $status, $vendorname)
	{
		$info = "";
		if ($status == "MentorApproved") {
			$info = "You can now purchase the items that have been approved.";
		}
		$status = $this->refineStatus($status);
		$to = $username . "@students.harker.org"; // faster than getting the user's email, but assumes user is using harker account name
		$subject = "Robotics PO Status Update: OrderID #$orderID";
		$message = "Hello $username, \n\nThe purchase order with OrderID #$orderID and vendor \"$vendorname\" has been updated. The order is now $status. $info \n\n - The Robotics 1072 Web Team"; 
		$header = "From: harker1072@gmail.com";
		//print_r($to);
		//print_r($subject);
		//print_r($message);
		//print_r($header);
		$result = mail($to, $subject, $message, $header); // returns true on delivery
		return $result;
	}
	
	/**
	 * description: Emails the mentor when an order is Pending Mentor Approval.
	 * 
	 * @param orderID: The ID of the order which the notification is about.
	 * @param vendorname: The name of the vendor of the given order.
	 * @param submittinguser: The user who submitted the order.
	 * @return boolean: true if mail was sent successfuly, false otherwise.
	 */
	public function notifyMentorOfPending($orderID, $vendorname, $submittinguser)
	{
		$to = parent::getMentorsEmail();
		$subject = "Robotics PO System: OrderID #$orderID is awaiting your approval";
		$message = "Hello, \n\nThe purchase order with OrderID #$orderID and vendor \"$vendorname\" has been submitted by $submittinguser for your approval. You can approve or reject the order at $this->_serverURL/views/mentorvieworder.php?id=$orderID. \n\n - The Robotics 1072 Web Team"; 
		$header = "From: harker1072@gmail.com";
		//print_r($to);
		//print_r($subject);
		//print_r($message);
		//print_r($header);
		$result = mail($to, $subject, $message, $header); // returns true on delivery
		return $result;
	}
}
?>