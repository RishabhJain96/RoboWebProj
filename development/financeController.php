<?php
/**
 * This class contains all the function related to the finance system. It is currently a subclass of roboSISAPI so as to keep it logically separate, yet still have easy access to general functions such as getUserID.
 */
class financeController extends roboSISAPI
{
	
	public function __construct()
	{
		parent::__construct();
	}
	
	/**
	 * This method inserts a new order into the database.
	 * This method should be called only when an order is inputted into the database for the first time. Updating an order that has already been started should call updateOrder.
	 * Check the finance tester for an example of how the $orders and $orderslist arrays should be structured, the examples are too large to reasonably fit here.
	 * $username: the user who is submitting the order
	 */
	public function inputOrder($username, $orders, $orderslist)
	{
		$id = parent::getUserID($username);
		$orders["UserID"] = $id; // saves the front-end from making the api call to get the UserID
		$uniqueID = uniqid("UID"); // generates a unique 16-character string starting with "UID"
		$orders["UniqueID"] = $uniqueID; // adds the uniqueID string to the orders array, with key "UniqueID"
		// inserts the general orders-related info into the OrdersTable
		//print_r($orders);
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
			$this->_dbConnection->insertIntoTable("OrdersListTable", "OrdersTable", "OrderID", $orderID, "OrderID", $list);
		}
		//echo "success";
	}
	
	/**
	 * Updates an order with new information
	 */
	public function updateOrder($orderID, $orders, $orderslist)
	{
		//$id = parent::getUserID($username);
		// updates the order with given orderID
		$this->_dbConnection->updateTable("OrdersTable", "OrdersTable", "OrderID", $orderID, "OrderID", $orders, "OrderID = $orderID");
		// iterates through orderslist and inserts the list of parts and associated info into the orderslist table
		//echo "win";
		//return;
		for ($i=0; $i < count($orderslist); $i++)
		{
			$list = $orderslist[$i];
			//print_r($list);
			$condition = "UniqueEntryID = " . $list["UniqueEntryID"];
			//print_r($condition);
			$this->_dbConnection->updateTable("OrdersListTable", "OrdersListTable", "UniqueEntryID", $list["UniqueEntryID"], "OrderID", $list, $condition);
		}
		//echo "success";
	}
	
	/**
	 * Gets the order with given orderID
	 */
	public function getOrder($orderID)
	{
		$resourceid = $this->_dbConnection->selectFromTable("OrdersTable", "OrderID", $orderID);
		$arr = $this->_dbConnection->formatQueryResults($resourceid); // array is twice as big as it needs to be, should be optimized
		return $arr;
	}
	
	/**
	 * Returns an array in JSON format of all the past orders the given user has placed, with most recent order on top.
	 */
	public function getUsersOrders($username)
	{
		$id = parent::getUserID($username);
		$resourceid = $this->_dbConnection->selectFromTable("OrdersTable", "UserID", $id);
		$arr = $this->_dbConnection->formatQueryResults($resourceid, "OrderID"); // holds all
		$users_orders = array();
		$users_orders[] = $this->getOrder($arr[0]);
		$users_orders[] = $this->getOrder($arr[1]);
		$users_orders[] = $this->getOrder($arr[2]);
		return json_encode($users_orders);
	}
}

?>