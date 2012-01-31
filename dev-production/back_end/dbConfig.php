<?php
include "autoloader.php";

/**
 * PLEASE MAKE SURE THAT THE DATABASE WITH THE APPROPRIATE dbName FROM dbParameters.txt
 * HAS ALREADY BEEN MADE MANUALLY IN THE MYSQL BACK-END, ELSE THIS CODE WILL NOT WORK.
 * 
 */
$dbConfig = dbUtils::getPropertiesConnection();

/**
 * RoboUsers Table
 */
$users = array();
$users[] = array("UserID", "int", "NOT NULL", "AUTO_INCREMENT");
$users[] = array("PRIMARY KEY(UserID)", "");
$users[] = array("Username", "TEXT");
$users[] = array("UserFullName", "TINYTEXT");
//$users[] = array("UserDescription", "TEXT"); // needed?
$users[] = array("UserPhoneNumber", "TINYTEXT");
$users[] = array("UserYear", "INT");
//$users[] = array("UserMomEmail", "TINYTEXT"); // split into mom/dad email?
$users[] = array("UserParentsEmail", "TINYTEXT"); // split into mom/dad email?
$users[] = array("UserEmail", "TINYTEXT");
//$users[] = array("UserTitle", "TINYTEXT"); // needed?
//$users[] = array("UserPicture", "TINYTEXT"); // needed?
$users[] = array("UserPassword", "TINYTEXT");
$users[] = array("ActivationCode", "TINYTEXT");
$users[] = array("Activated", "INT"); // nonzero val is true
$users[] = array("UserSubteam", "TINYTEXT"); // vals: Mechanical, Electronics, Programming, Operational
$users[] = array("UserType", "TINYTEXT"); // vals: Regular, VP, Admin, Mentor

if($dbConfig->createINNODBTable("RoboUsers", $users))
	echo "Success! Your RoboUsers Table is now set up! <br />";

/**
 * UserHistories Table
 */
$histories = array();
$histories[] = array("HistoryID", "int", "NOT NULL", "AUTO_INCREMENT");
$histories[] = array("PRIMARY KEY(HistoryID)");
$histories[] = array("HistoryTimeStamp", "TINYTEXT");
$histories[] = array("NumericTimeStamp", "TINYTEXT");
$histories[] = array("UserID", "INT");
	
if($dbConfig->createINNODBTable("UserHistories", $histories))
	echo "Success! Your UserHistory Table is now set up! <br />";
if($dbConfig->setRelation("UserHistories", "RoboUsers", "UserID"))
	echo "Success! Your UserHistories and RoboUsers Table are now linked via UserID! <br />";

/**
 * OrdersTable
 */
$orders = array();
$orders[] = array("OrderID", "int", "NOT NULL", "AUTO_INCREMENT");
$orders[] = array("PRIMARY KEY(OrderID)");
$orders[] = array("UserID", "INT"); // submitting user
$orders[] = array("Username", "TINYTEXT"); // submitting user
$orders[] = array("UserFullName", "TINYTEXT"); // submitting user
$orders[] = array("UserSubteam", "TINYTEXT"); // submitting user
$orders[] = array("EnglishDateSubmitted", "TINYTEXT");
$orders[] = array("NumericDateSubmitted", "TINYTEXT");
$orders[] = array("EnglishDateApproved", "TINYTEXT");
$orders[] = array("NumericDateApproved", "TINYTEXT");
$orders[] = array("EnglishDateMentorApproved", "TINYTEXT");
$orders[] = array("NumericDateMentorApproved", "TINYTEXT");
$orders[] = array("ReasonForPurchase", "TEXT");
$orders[] = array("ShippingAndHandling", "DOUBLE");
$orders[] = array("TaxPrice", "DOUBLE");
$orders[] = array("EstimatedTotalPrice", "DOUBLE");
$orders[] = array("PartVendorName", "TINYTEXT");
$orders[] = array("PartVendorEmail", "TINYTEXT");
$orders[] = array("PartVendorAddress", "TINYTEXT"); // adress stored as one line
$orders[] = array("PartVendorPhoneNumber", "TINYTEXT");
$orders[] = array("AdminComment", "TEXT");
$orders[] = array("AdminApproved", "INT"); // int acts as bool, 0 and 1
$orders[] = array("AdminUsername", "TINYTEXT"); // NOT DB LINKED
$orders[] = array("AdminUserFullName", "TINYTEXT"); // NOT DB LINKED
$orders[] = array("MentorComment", "TEXT");
$orders[] = array("MentorApproved", "INT"); // int acts as bool, 0 and 1
$orders[] = array("Status", "TINYTEXT"); // vals: Unfinished, AdminPending, AdminApproved, MentorPending, MentorApproved, AdminRejected, MentorRejected
$orders[] = array("PrintCounter", "INT");
$orders[] = array("ConfirmationOfPurchase", "INT"); // int acts as bool, 0 and 1
$orders[] = array("Locked", "INT"); // int acts as bool, 0 and 1
$orders[] = array("UniqueID", "TINYTEXT"); // acts as a way to get a specific OrderID after inserting
//$orders[19] = array("ActualTotalPrice", "DOUBLE");

if($dbConfig->createINNODBTable("OrdersTable", $orders))
	echo "Success! Your OrdersTable is now set up! <br />";
if($dbConfig->setRelation("OrdersTable", "RoboUsers", "UserID"))
	echo "Success! Your OrdersTable and RoboUsers Table are now linked via UserID! <br />";

/**
 * OrdersListTable
 */
$ordersList = array();
$ordersList[] = array("OrderListID", "int", "NOT NULL", "AUTO_INCREMENT");
$ordersList[] = array("PRIMARY KEY(OrderListID)");
$ordersList[] = array("OrderID", "INT");
$ordersList[] = array("UniqueEntryID", "TINYTEXT"); // allows updating of individual entries to work
$ordersList[] = array("PartNumber", "TINYTEXT");
$ordersList[] = array("PartName", "TINYTEXT");
$ordersList[] = array("PartSubsystem", "TINYTEXT");
$ordersList[] = array("PartIndividualPrice", "DOUBLE");
$ordersList[] = array("PartQuantity", "INT");
$ordersList[] = array("PartTotalPrice", "DOUBLE");
$ordersList[] = array("PartURL", "TINYTEXT"); // optional
$ordersList[] = array("AdminApproved", "INT"); // int acts as bool, 0 and 1
$ordersList[] = array("Status", "TINYTEXT"); // optional	

if($dbConfig->createINNODBTable("OrdersListTable", $ordersList))
	echo "Success! Your OrdersListTable is now set up! <br />";
if($dbConfig->setRelation("OrdersListTable", "OrdersTable", "OrderID"))
	echo "Success! Your OrdersTable and OrdersListTable are now linked via OrderID! <br />";

/**
 * ArchiveOrdersTable
 */
$archiveOrders = array();
$archiveOrders[] = array("OrderID", "int", "NOT NULL", "AUTO_INCREMENT");
$archiveOrders[] = array("PRIMARY KEY(OrderID)");
$archiveOrders[] = array("UserID", "INT"); // submitting user
$archiveOrders[] = array("Username", "TINYTEXT"); // submitting user
$archiveOrders[] = array("UserFullName", "TINYTEXT"); // submitting user
$archiveOrders[] = array("UserSubteam", "TINYTEXT"); // submitting user
$archiveOrders[] = array("EnglishDateSubmitted", "TINYTEXT");
$archiveOrders[] = array("NumericDateSubmitted", "TINYTEXT");
$archiveOrders[] = array("EnglishDateApproved", "TINYTEXT");
$archiveOrders[] = array("NumericDateApproved", "TINYTEXT");
$archiveOrders[] = array("EnglishDateMentorApproved", "TINYTEXT");
$archiveOrders[] = array("NumericDateMentorApproved", "TINYTEXT");
$archiveOrders[] = array("ReasonForPurchase", "TEXT");
$archiveOrders[] = array("ShippingAndHandling", "DOUBLE");
$archiveOrders[] = array("TaxPrice", "DOUBLE");
$archiveOrders[] = array("EstimatedTotalPrice", "DOUBLE");
$archiveOrders[] = array("PartVendorName", "TINYTEXT");
$archiveOrders[] = array("PartVendorEmail", "TINYTEXT");
$archiveOrders[] = array("PartVendorAddress", "TINYTEXT"); // adress stored as one line
$archiveOrders[] = array("PartVendorPhoneNumber", "TINYTEXT");
$archiveOrders[] = array("AdminComment", "TEXT");
$archiveOrders[] = array("AdminApproved", "INT"); // int acts as bool, 0 and 1
$archiveOrders[] = array("AdminUsername", "TINYTEXT"); // NOT DB LINKED
$archiveOrders[] = array("AdminUserFullName", "TINYTEXT"); // NOT DB LINKED
$archiveOrders[] = array("MentorComment", "TEXT");
$archiveOrders[] = array("MentorApproved", "INT"); // int acts as bool, 0 and 1
$archiveOrders[] = array("Status", "TINYTEXT"); // vals: Unfinished, AdminPending, AdminApproved, MentorPending, MentorApproved, AdminRejected, MentorRejected
$archiveOrders[] = array("PrintCounter", "INT");
$archiveOrders[] = array("ConfirmationOfPurchase", "INT"); // int acts as bool, 0 and 1
$archiveOrders[] = array("Locked", "INT"); // int acts as bool, 0 and 1
$archiveOrders[] = array("UniqueID", "TINYTEXT"); // acts as a way to get a specific OrderID after inserting
//$archiveOrders[19] = array("ActualTotalPrice", "DOUBLE");

if($dbConfig->createINNODBTable("ArchiveOrdersTable", $archiveOrders))
	echo "Success! Your ArchiveOrdersTable is now set up! <br />";

/**
 * ArchiveOrdersListTable
 */
$archiveOrdersList = array();
$archiveOrdersList[] = array("OrderListID", "int", "NOT NULL", "AUTO_INCREMENT");
$archiveOrdersList[] = array("PRIMARY KEY(OrderListID)");
$archiveOrdersList[] = array("OrderID", "INT");
$archiveOrdersList[] = array("UniqueEntryID", "TINYTEXT"); // allows updating of individual entries to work
$archiveOrdersList[] = array("PartNumber", "TINYTEXT");
$archiveOrdersList[] = array("PartName", "TINYTEXT");
$archiveOrdersList[] = array("PartSubsystem", "TINYTEXT");
$archiveOrdersList[] = array("PartIndividualPrice", "DOUBLE");
$archiveOrdersList[] = array("PartQuantity", "INT");
$archiveOrdersList[] = array("PartTotalPrice", "DOUBLE");
$archiveOrdersList[] = array("PartURL", "TINYTEXT"); // optional
$archiveOrdersList[] = array("AdminApproved", "INT"); // int acts as bool, 0 and 1
$archiveOrdersList[] = array("Status", "TINYTEXT"); // optional	

if($dbConfig->createINNODBTable("ArchiveOrdersListTable", $archiveOrdersList))
	echo "Success! Your ArchiveOrdersListTable is now set up! <br />";

?>