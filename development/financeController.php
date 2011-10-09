<?php
/**
 * This class contains all the function related to the finance system. It is currently a subclass of roboSISAPI so as to keep it logically seperate, yet still have easy access to general functions such as getUserID.
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
	 * $array: The array of all the necessary database values. Should be in the format: array("PartName" => "Bolts", "Quantity" => 2, …) for all given values. $array must be contiguous; if user has not inputted all possible fields yet, array should be ordered so that there are no empty elements.
	 * $username: the user who is submitting the order
	 */
	public function inputOrder($username, $array)
	{
		$id = parent::getUserID($username);
		for ($i=0; $i < count($array); $i++)
		{
			$arr = array();
			$this->_dbConnection->insertIntoTable($table, "OrdersTable", $columnforid, $id, "UserID", $arrayTime);
		}
	}

	/**
	 * Returns an array in JSON format of all the past orders the given user has placed, with most recent order on top.
	 */
	public function getUsersOrders($username)
	{
		$id = parent::getUserID($username);
		return $id;
	}

	/**
	 * Updates an order with new information
	 */
	public function updateOrder($username, $array)
	{
		$id = parent::getUserID($username);
	}
}

?>