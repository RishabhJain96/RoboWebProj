<?php
/**
 * This class contains all the function related to the finance system. It is currently a subclass of roboSISAPI so as to keep it logically separate, yet still have easy access to general functions such as getUserID.
 * @author Rohit Sanbhadti
 */
class financeController extends notificationsController
{
	
	public function __construct()
	{
		parent::__construct();
	}
	
	// BOOLEAN HELPER FUNCTIONS
	
	/**
	 * Returns true if the order has been locked against further editing
	 */
	public function isLocked($orderID)
	{
		$resourceid = $this->_dbConnection->selectFromTable("OrdersTable", "OrderID", $orderID);
		$order = $this->_dbConnection->formatQueryResults($resourceid, "Locked");
		if ($order[0] == 1) // loose checking
			return true;
		else
			return false;
	}
	
	// INPUT/DATABASE FUNCTIONS
	
	/**
	 * This method inserts a new order into the database.
	 * This method should be called only when an order is inputted into the database for the first time. Updating an order that has already been started should call updateOrder.
	 * Check the finance tester for an example of how the $orders and $orderslist arrays should be structured, the examples are too large to reasonably fit here.
	 * $username: the user who is submitting the order
	 * returns the orderID of the order just inputted
	 */
	public function inputOrder($username, $orders, $orderslist)
	{
		$id = parent::getUserID($username);
		$orders["UserID"] = $id; // saves the front-end from making the api call to get the UserID
		$uniqueID = uniqid("UID"); // generates a unique 16-character string starting with "UID"
		$orders["UniqueID"] = $uniqueID; // adds the uniqueID string to the orders array, with key "UniqueID"
		// sets default values for locked, confirmationofpurchase, english and numeric date submitted, status
		$orders["Status"] = "Unfinished";
		$orders["Locked"] = 0; // locked is false
		$orders["ConfirmationOfPurchase"] = 0;
		$orders["EnglishDateSubmitted"] = date("l, F j \a\\t g:i a"); // format: Sunday, January 31 at 3:66 pm
		$orders["NumericDateSubmitted"] = date("YmdHi"); // format: YYYYMMDDhhmm i.e. 201109232355
		// inserts the general orders-related info into the OrdersTable
		//print_r($orders);
		$orders = parent::sanitizeArray($orders);
		$this->_dbConnection->insertIntoTable("OrdersTable", "RoboUsers", "UserID", $id, "UserID", $orders);
		//echo 'yes';
		//return;
		// code to get the orderID that was just created from the insert call
		$resourceid = $this->_dbConnection->selectFromTable("OrdersTable", "UniqueID", $uniqueID);
		$arr = $this->_dbConnection->formatQueryResults($resourceid, "OrderID");
		$orderID = $arr[0];
		// iterates through orderslist and inserts the list of parts and associated info into the orderslist table
		for ($i=0; $i < count($orderslist); $i++)
		{
			$list = $orderslist[$i];
			$list["UniqueEntryID"] = uniqid("UEID");
			$list = parent::sanitizeArray($list);
			$this->_dbConnection->insertIntoTable("OrdersListTable", "OrdersTable", "OrderID", $orderID, "OrderID", $list);
		}
		//echo "success";
		return $orderID;
	}
	
	/**
	 * Updates an order with new information
	 */
	public function updateOrder($orderID, $orders, $orderslist)
	{
		//$id = parent::getUserID($username);
		if($this->isLocked($orderID)) return false; // prevents updating a locked order
		$orders["EnglishDateSubmitted"] = date("l, F j \a\\t g:i a"); // format: Sunday, January 31 at 3:66 pm
		$orders["NumericDateSubmitted"] = date("YmdHi"); // format: YYYYMMDDhhmm i.e. 201109232355
		// updates the order with given orderID
		$orders = parent::sanitizeArray($orders);
		$this->_dbConnection->updateTable("OrdersTable", "OrdersTable", "OrderID", $orderID, "OrderID", $orders, "OrderID = $orderID");
		// iterates through orderslist and inserts the list of parts and associated info into the orderslist table
		//echo "win";
		//return;
		for ($i=0; $i < count($orderslist); $i++)
		{
			$list = $orderslist[$i];
			//print_r($list);
			
			// allows new entries to be added, in addition to updates
			if (is_null($list["UniqueEntryID"]) || empty($list["UniqueEntryID"]))
			{
				$list["UniqueEntryID"] = uniqid("UEID");
				$list = parent::sanitizeArray($list);
				$this->_dbConnection->insertIntoTable("OrdersListTable", "OrdersTable", "OrderID", $orderID, "OrderID", $list);
				//print $list["PartName"];
				//print '1';
			}
			else
			{
				//print $list["PartName"];
				//print '2';
				$condition = "UniqueEntryID = '" . $list["UniqueEntryID"] . "'";
				//print_r($condition);
				$list = parent::sanitizeArray($list);
				$this->_dbConnection->updateTable("OrdersListTable", "OrdersListTable", "UniqueEntryID", $list["UniqueEntryID"], "OrderID", $list, $condition);
			}
		}
		//echo "success";
		return true;
	}
	
	/**
	 * description: Archives the given order into the ArchiveOrders/ArchiveOrdersList table, and deletes it from the main orders and orderslist tables.
	 * 
	 * @param orderID: 
	 * @return boolean: 
	 */
	public function archiveOrder($orderID)
	{
		// copies the order from the orders and orderslist tables to archiveorders and archiveorderslist tables
		
		// deletes the orders from the activer orders and orderslist tables
		$result = $this->_dbConnection->deleteFromTable("OrdersListTable", "OrderID", $orderID);
		if ($result)
			$result = $this->_dbConnection->deleteFromTable("OrdersTable", "OrderID", $orderID);
		return $result;
	}
	
	/**
	 * description: Changes the submitting user of a PO. Allows the PO to be an accurate record of who actually went out to buy the order.
	 * 
	 * @param submittingUsername: The new user to set as the submitting user.
	 * @param orderID: The order to update the submitting user of.
	 * @return int: 
	 */
	public function setSubmittingUser($orderID, $submittingUsername)
	{
		$newID = parent::getUserID($submittingUsername);
		$updateArray = array("UserID" => $newID, "Username" => $submittingUsername);
		$updateArray = parent::sanitizeArray($updateArray);
		$this->_dbConnection->updateTable("OrdersTable", "OrdersTable", "OrderID", $orderID, "OrderID", $updateArray, "OrderID = $orderID");
	}
	
	// OUTPUT FUNCTIONS
	
	/**
	 * $orderID: the id of the order to get
	 */
	public function getOrder($orderID)
	{
		$resourceid = $this->_dbConnection->selectFromTable("OrdersTable", "OrderID", $orderID);
		$order = $this->_dbConnection->formatQuery($resourceid); // gets order with keys being column names
		return $order;
	}
	
	/**
	 * Gets the list of parts associated with the given order
	 */
	public function getOrdersList($orderID)
	{
		$resourceid = $this->_dbConnection->selectFromTable("OrdersListTable", "OrderID", $orderID);
		$arr = $this->_dbConnection->formatQuery($resourceid); // custom method built for this purpose
		return $arr;
	}
	
	/**
	 * description: Return a 2D array of the entire OrdersList table.
	 * 
	 * @return array: A 2D array of all the parts in the OrdersList table, with each entry being an array containing information about a single part.
	 */
	public function getAllOrdersListParts()
	{
		$resourceid = $this->_dbConnection->selectFromTable("OrdersListTable");
		$ordersList = $this->_dbConnection->formatQuery($resourceid);
		return $ordersList;
	}
	
	/**
	 * description: Get's the info for the desired part.
	 * 
	 * @param ordersListID: The orderListID of the desired part.
	 * @return array: The array containing info pertaining to the desired orderList.
	 */
	public function getOrdersListPart($ordersListID)
	{
		$resourceid = $this->_dbConnection->selectFromTable("OrdersListTable", "OrderListID", $ordersListID);
		$arr = $this->_dbConnection->formatQuery($resourceid); // custom method built for this purpose
		return $arr;
	}
	
	/**
	 * Returns multidimensional array of order and orderslist
	 * calls internal methods getOrder and getOrdersList
	 */
	public function getFullOrder($orderID)
	{
		$orders = $this->getOrder($orderID);
		$orderslist = $this->getOrdersList($orderID);
		$fullorder = array($orders, $orderslist);
		//return json_encode($fullorder);
		return $fullorder;
	}
	
	/**
	 * Returns a 2D array in JSON format of all the past orders the given user has placed, with most recent order on top. First array is orders, second array is orderslists, with sub-arrays being individual orders or lists(arrays) of parts per order.
	 * UPDATED: Returns a PHP array of the users orders only, no lists. Keys are DB column names. Note: Encoding to JSON causes problems, therefore array is returned as a simple PHP array.
	 */
	public function getUsersOrders($username)
	{
		$id = parent::getUserID($username);
		$resourceid = $this->_dbConnection->selectFromTableDesc("OrdersTable", "UserID", $id, "NumericDateSubmitted"); // orders in most recently edited/submitted
		$arr = $this->_dbConnection->formatQueryResults($resourceid, "OrderID"); // holds list of all the OrderIDs of the orders that the given user has placed
		$orders = array(); // will be an array of arrays, each contained array being an order
		for ($i=0; $i < count($arr); $i++)
		{
			$orders[$i] = $this->getOrder($arr[$i]); // gets a single order and adds it to orders array
			$orders[$i] = $orders[$i][0];
		}
		//$lists = array();
		//for ($i=0; $i < count($orders); $i++)
		//{
		//	$lists[$i] = $this->getOrdersList($orders[$i][0]["OrderID"]); // gets the list of orderlist entries with given orderID as an array and stores it into one element of the lists array
		//}
		//$users_orders = array($orders, $lists); // puts into a 2D array
		//$users_orders[] = $this->getOrder($arr[2]); // gets a single order
		return $orders;
		//return json_encode($users_orders);
		//return json_encode($orders);
	}
	
	/**
	 * gets ALL orders in the database, with keys as db column names
	 */
	public function getAllOrders()
	{
		$resourceid = $this->_dbConnection->selectFromTableDesc("OrdersTable", null, null, "NumericDateSubmitted");
		$orders = $this->_dbConnection->formatQuery($resourceid);
		//return json_encode($orders);
		return $orders;
	}
	
	/**
	 * description: Searches all orders for the keyword in all fields, including OrdersList parts.
	 * 
	 * algorithm:
	 	- Pulls the orders and orders list tables.
		- Puts them into one one-dimensional array, with an idArray that matches each element with the orderID from whence it came.
		- Uses stripos() (case-insensitive) to see if the keyword is in each element (uses === to check false, false means not in string).
			- if it is, the respective orderID is copied into the final array.
		- Returns the array of orderIDs containing keyword.
	 * 
	 * @param keyword: The query to search for
	 * @return array: The list of orders containing the desired keyword.
	 */
	public function searchAllOrders($keyword)
	{
		$ordersList = $this->getAllOrdersListParts();
		$orders = $this->getAllOrders();
		$fullArray = array(); // the array to hold all elements
		$idArray = array(); // the array to hold the orderIDs that correspond to the elements in $fullArray
		for ($i=0; $i < count($ordersList); $i++)
		{ 
			foreach ($ordersList[$i] as $value)
			{
				$fullArray[] = $value;
				$idArray[] = $ordersList[$i]["OrderID"];
			}
		}
		print 'ordersList';
		print_r($ordersList);
		print 'orders';
		print_r($orders);
	}
	
	// ADMIN FUNCTIONS
	
	/**
	 * Locks the order and sets status to pending
	 */
	public function submitForAdminApproval($orderID)
	{
		$locked = 1; // Locks the order while under approval process
		$status = "AdminPending";
		// email the submitter with new status
		$order = $this->getOrder($orderID);
		$vendorname = $order[0]["PartVendorName"];
		$username = $order[0]["Username"];
		$this->emailUserStatusUpdate($username, $orderID, $status, $vendorname);
		// set the new status
		$eds = date("l, F j \a\\t g:i a");
		$nds = date("YmdHi");
		$arr_vals = array("Locked" => $locked, "Status" => $status, "EnglishDateSubmitted" => $eds, "NumericDateSubmitted" => $nds);
		$arr_vals = parent::sanitizeArray($arr_vals);
		$this->_dbConnection->updateTable("OrdersTable", "OrdersTable", "OrderID", $orderID, "OrderID", $arr_vals, "OrderID = $orderID");
	}
	
	/**
	 * Gets the list of pending orders in JSON
	 */
	public function getAdminPendingOrders()
	{
		$resourceid = $this->_dbConnection->selectFromTableAsc("OrdersTable", "Status", "AdminPending", "NumericDateSubmitted");
		$orders = $this->_dbConnection->formatQuery($resourceid);
		//return json_encode($orders);
		return $orders;
	}
	
	/**
	 * Sets the admin approval for an order, when admin makes decision
	 * orderID: the orderID of the order to update
	 * approved: Either boolean true for approved or false for rejected
	 * comment: an optional text comment
	 * $adminusername: the username of the admin who make the decision
	 */
	public function setAdminApproval($orderID, $approved, $adminusername, $comment = null)
	{
		// set status, AdminApproved, AdminComment, AdminUsername, Locked, English/NumericDateApproved
		// email the user
		$status = "";
		if ($approved)
		{
			$approved = 1; // writeable to DB, since AdminApproved is an int, 1 = true 0 = false
			$status = "AdminApproved";
		}
		else
		{
			$approved = 0;
			$status = "AdminRejected";
			// email the submitter with new status ONLY IF rejected. Otherwise, the email is pointless because it will be immediately sent for mentor approval, which sends another email, resulting in an unnecessary extra email.
			$order = $this->getOrder($orderID);
			$vendorname = $order[0]["PartVendorName"];
			$username = $order[0]["Username"];
			$this->emailUserStatusUpdate($username, $orderID, $status, $vendorname);
		}
		// set the new status
		$locked = $approved; // if order is approved, order stays locked, if not order is unlocked to allow user to edit it again
		$englishdateapproved = date("l, F j \a\\t g:i a"); // of format Sunday, June 31 at 3:33 pm
		$numericdateapproved = date("YmdHi"); // of format 201109232355
		$arr_vals = array("Status" => $status, "AdminApproved" => $approved, "AdminComment" => $comment, "AdminUsername" => $adminusername, "Locked" => $locked, "EnglishDateApproved" => $englishdateapproved, "NumericDateApproved" => $numericdateapproved);
		$arr_vals = parent::sanitizeArray($arr_vals);
		$this->_dbConnection->updateTable("OrdersTable", "OrdersTable", "OrderID", $orderID, "OrderID", $arr_vals, "OrderID = $orderID");
	}
	
	/**
	 * description: 
	 * 
	 * @param order: 
	 * @return int: 
	 */
	public function setPartsAdminApproval($uniqueID, $approved)
	{
		// set status, AdminApproved
		$status = "";
		if ($approved)
		{
			$approved = 1; // writeable to DB, since AdminApproved is an int, 1 = true 0 = false
			$status = "AdminApproved";
		}
		else
		{
			$approved = 0;
			$status = "AdminRejected";
		}
		$arr_vals = array("Status" => $status, "AdminApproved" => $approved);
		$condition = "UniqueEntryID = '" . $uniqueID . "'";
		$arr_vals = parent::sanitizeArray($arr_vals);
		$this->_dbConnection->updateTable("OrdersListTable", "OrdersListTable", "UniqueEntryID", $uniqueID, "OrderID", $arr_vals, $condition);
	}
	
	/**
	 * description: Returns true if given order has been approved
	 * 
	 * @param orderID: 
	 * @return bool: 
	 */
	public function isAdminApproved($orderID)
	{
		$order = $this->getOrder($orderID);
		//print_r($order);
		if ($order[0]["Status"] == "AdminApproved")
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	
	// ROOT FUNCTIONS
	
	
	/**
	 * description: Returns true if given order has been approved by root
	 * 
	 * @param orderID: 
	 * @return bool: 
	 */
	public function isMentorApproved($orderID)
	{
		$order = $this->getOrder($orderID);
		//print_r($order);
		if ($order[0]["Status"] == "MentorApproved")
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	/**
	 * Locks the order and sets status to MentorPending
	 */
	public function submitForMentorApproval($orderID)
	{
		$locked = 1; // Locks the order while under approval process
		$status = "MentorPending";
		// email the submitter with new status
		$order = $this->getOrder($orderID);
		$vendorname = $order[0]["PartVendorName"];
		$username = $order[0]["Username"];
		$this->emailUserStatusUpdate($username, $orderID, $status, $vendorname);
		// email the mentor with a notification about the now-pending order
		$this->notifyMentorOfPending($orderID, $vendorname, $username);
		// set the new status
		$eds = date("l, F j \a\\t g:i a");
		$nds = date("YmdHi");
		$arr_vals = array("Locked" => $locked, "Status" => $status, "EnglishDateSubmitted" => $eds, "NumericDateSubmitted" => $nds);
		$arr_vals = parent::sanitizeArray($arr_vals);
		$this->_dbConnection->updateTable("OrdersTable", "OrdersTable", "OrderID", $orderID, "OrderID", $arr_vals, "OrderID = $orderID");
	}
	
	/**
	 * Sets the root approval for an order, when root makes decision
	 * orderID: the orderID of the order to update
	 * approved: Either boolean true for approved or false for rejected
	 * comment: an optional text comment
	 */
	public function setMentorApproval($orderID, $approved, $comment = null)
	{
		// set status, AdminApproved, AdminComment, AdminUsername, Locked, English/NumericDateApproved
		$status = "";
		if ($approved)
		{
			$approved = 1; // writeable to DB, since AdminApproved is an int, 1 = true 0 = false
			$status = "MentorApproved";
		}
		else
		{
			$approved = 0;
			$status = "MentorRejected";
		}
		// email the submitter with new status
		$order = $this->getOrder($orderID);
		$vendorname = $order[0]["PartVendorName"];
		$username = $order[0]["Username"];
		$this->emailUserStatusUpdate($username, $orderID, $status, $vendorname);
		// set the new status
		$locked = $approved; // if order is approved, order stays locked, if not order is unlocked to allow user to edit it again
		$englishdateapproved = date("l, F j \a\\t g:i a"); // of format Sunday, June 31 at 3:33 pm
		$numericdateapproved = date("YmdHi"); // of format 201109232355
		$arr_vals = array("Status" => $status, "MentorApproved" => $approved, "MentorComment" => $comment, "Locked" => $locked, "EnglishDateMentorApproved" => $englishdateapproved, "NumericDateMentorApproved" => $numericdateapproved);
		$arr_vals = parent::sanitizeArray($arr_vals);
		$this->_dbConnection->updateTable("OrdersTable", "OrdersTable", "OrderID", $orderID, "OrderID", $arr_vals, "OrderID = $orderID");
	}
	
	/**
	 * Gets the list of pending orders in JSON
	 */
	public function getMentorPendingOrders()
	{
		$resourceid = $this->_dbConnection->selectFromTableDesc("OrdersTable", "Status", "MentorPending", "NumericDateSubmitted");
		$orders = $this->_dbConnection->formatQuery($resourceid);
		//return json_encode($orders);
		return $orders;
	}
	
	// PRINT COUNTER FUNCTIONS
	
	/**
	 * description: 
	 * 
	 * @param orderID: 
	 * @return void: 
	 */
	public function incrementPrintCounter($orderID)
	{
		$order = $this->getOrder($orderID);
		$count = intval($order[0]["PrintCounter"]);
		$count = $count + 1;
		$arr_vals = array("PrintCounter" => $count);
		$arr_vals = parent::sanitizeArray($arr_vals);
		$this->_dbConnection->updateTable("OrdersTable", "OrdersTable", "OrderID", $orderID, "OrderID", $arr_vals, "OrderID = $orderID");
	}
	
	/**
	 * description: 
	 * 
	 * @param orderID: 
	 * @return int: The number of times this order has been printed
	 */
	public function getPrintCount($orderID)
	{
		$order = $this->getOrder($orderID);
		$count = intval($order[0]["PrintCounter"]);
		if (is_null($count) || empty($count))
			$count = 0;
		return $count;
	}
}

?>