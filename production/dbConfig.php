<?PHP
// The function __autoload is the method for loading all the classes being used in the script. Use it at the beginning of every php main page.
function __autoload($class)
{
	require_once $class . '.php';
}

/**
 * PLEASE MAKE SURE THAT THE DATABASE WITH THE APPROPRIATE dbName FROM dbParameters.txt
 * HAS ALREADY BEEN MADE MANUALLY IN THE MYSQL BACK-END, ELSE THIS CODE WILL NOT WORK.
 * 
 */
$version = 1; // no reason to change this

// do not use a dbUtil call to replace this, as this is being used for something other than a regular dbConnection
$dbArr = file("dbParameters.txt");
$dbName = str_replace(array("\r", "\r\n", "\n"), '', $dbArr[0]);
$dbHost = str_replace(array("\r", "\r\n", "\n"), '', $dbArr[1]);
$dbUser = str_replace(array("\r", "\r\n", "\n"), '', $dbArr[2]);
$dbPass = ""; // declares variable to prevent error
if (count($dbArr) > 3)
{
	$dbPass = str_replace(array("\r", "\r\n", "\n"), '', $dbArr[3]);
}

if (is_null($dbPass))
{
	$dbPass = ""; // ensures password field gets sent, even if empty
}

//Do NOT EDIT THIS PORTION OF THE CODE.
$dbConfig = new databaseProperties($dbName, $dbHost, $dbUser, $dbPass);
$totalVersions = 5;

for($i = $version; $i <= $totalVersions; $i++)
{
/**
 * CollegeID Table + ProfessorID Table
 */
if($i == 1) {
	$array1 = array();

	$array1[0] = array("UserID", "int", "NOT NULL", "AUTO_INCREMENT");

	$array1[1] = array("PRIMARY KEY(UserID)", "");
	$array1[2] = array("Username", "TEXT");
	$array1[3] = array("UserFullName", "TEXT");
	$array1[4] = array("UserDescription", "TEXT"); // needed?
	$array1[5] = array("UserPhoneNumber", "TEXT");
	$array1[6] = array("UserYear", "INT");
	$array1[7] = array("UserMomEmail", "TINYTEXT"); // split into mom/dad email?
	$array1[8] = array("UserDadEmail", "TINYTEXT"); // split into mom/dad email?
	$array1[9] = array("UserEmail", "TINYTEXT");
	$array1[10] = array("UserTitle", "TINYTEXT"); // needed?
	$array1[11] = array("UserPicture", "TINYTEXT"); // needed?
	$array1[12] = array("UserPassword", "TINYTEXT");
	$array1[13] = array("ActivationCode", "TINYTEXT");
	$array1[14] = array("Activated", "INT"); // nonzero val is true
	$array1[15] = array("UserSubteam", "TINYTEXT");
	$array1[16] = array("UserType", "TINYTEXT");

	if($dbConfig->createINNODBTable("RoboUsers", $array1)) echo "Success! Your RoboUsers Table is now set up! <br />";
//print_r($dbConfig->createINNODBTable("CollegeSummary", $array1));
//The Next Table's set up file.
// The CollegeProfessors table.

	$array = array();
	$array[0] = array("BadgeID", "int", "NOT NULL", "AUTO_INCREMENT");
	$array[1] = array("PRIMARY KEY(BadgeID)");
	$array[2] = array("BadgeName", "TEXT");
	$array[3] = array("UserID", "INT");

	if($dbConfig->createINNODBTable("UserBadges", $array)) echo "Success! Your UserBadges Table is now set up! <br />";
//print_r($dbConfig->createINNODBTable("CollegeProfessors", $array));


	if($dbConfig->setRelation("UserBadges", "RoboUsers", "UserID")) echo "Success! Your UserBadges and RoboUsers tables have been linked via UserID.<br />";
}

/**
 * UserHistories Table
 */
if($i == 2)
{
	$array = array();
	$array[0] = array("HistoryID", "int", "NOT NULL", "AUTO_INCREMENT");
	$array[1] = array("PRIMARY KEY(HistoryID)");
	$array[2] = array("HistoryTimeStamp", "TINYTEXT");
	$array[3] = array("NumericTimeStamp", "TINYTEXT");
	$array[4] = array("UserID", "INT");
	
	if($dbConfig->createINNODBTable("UserHistories", $array)) echo "Success! Your UserHistory Table is now set up! <br />";
	
	if($dbConfig->setRelation("UserHistories", "RoboUsers", "UserID")) echo "Success! Your UserHistories and RoboUsers Table are now linked via UserID! <br />";

}

/**
 * LinkIDTable + ResearchID Table
 */
if($i == 3)
{	
	$array1 = array();
	$array1[0] = array("TaskID", "int", "NOT NULL", "AUTO_INCREMENT");
	$array1[1] = array("PRIMARY KEY(TaskID)");
	$array1[2] = array("TaskName", "TEXT");
	$array1[3] = array("UserID", "INT");
	$array1[4] = array("Deadline", "TINYTEXT");
	$array1[5] = array("AssignedByUserID", "INT");
	
	if($dbConfig->createINNODBTable("UserTasks", $array1)) echo "Success! Your UserTasks Table is now set up! <br />";
	
	if($dbConfig->setRelation("UserTasks", "RoboUsers", "UserID")) echo "Success! Your UserTasks and RoboUsers Table are now linked via UserID! <br />";
	
}

/**
 * Orders table
 */
if($i == 4)
{
	$arr = array();
	$arr[0] = array("OrderID", "int", "NOT NULL", "AUTO_INCREMENT");
	$arr[1] = array("PRIMARY KEY(OrderID)");
	$arr[2] = array("UserID", "INT"); // submitting user
	$arr[3] = array("Username", "TINYTEXT"); // submitting user
	$arr[4] = array("UserSubteam", "TINYTEXT"); // submitting user
	$arr[5] = array("DateSubmitted", "TINYTEXT");
	//$arr[] = array("DateApproved", "TINYTEXT");
	$arr[6] = array("ReasonForPurchase", "TEXT");
	$arr[7 ] = array("ShippingAndHandling", "DOUBLE");
	$arr[8 ] = array("TaxPrice", "DOUBLE");
	$arr[9 ] = array("EstimatedTotalPrice", "DOUBLE");
	$arr[10] = array("PartVendorName", "TINYTEXT");
	$arr[11] = array("PartVendorEmail", "TINYTEXT");
	$arr[12] = array("PartVendorAddress", "TINYTEXT"); // adress stored as one line
	$arr[13] = array("PartVendorPhoneNumber", "TINYTEXT");
	$arr[14] = array("AdminComment", "TEXT");
	$arr[15] = array("AdminApproved", "INT"); // int acts as bool, 0 and 1
	$arr[16] = array("AdminUsername", "INT"); // NOT DB LINKED
	$arr[16] = array("ConfirmationOfPurchase", "INT"); // int acts as bool, 0 and 1
	$arr[17] = array("Locked", "INT"); // int acts as bool, 0 and 1
	//$arr[18] = array("ActualTotalPrice", "DOUBLE");
	
	if($dbConfig->createINNODBTable("OrdersTable", $arr)) echo "Success! Your OrdersTable is now set up! <br />";
	
	if($dbConfig->setRelation("OrdersTable", "RoboUsers", "UserID")) echo "Success! Your OrdersTable and RoboUsers Table are now linked via UserID! <br />";
}

if ($i == 5)
{
	$arr1 = array();
	$arr[0] = array("OrderListID", "int", "NOT NULL", "AUTO_INCREMENT");
	$arr[1] = array("PRIMARY KEY(OrderListID)");
	$arr[2] = array("OrderID", "INT");
	$arr[3] = array("PartNumber", "INT");
	$arr[4] = array("PartName", "TINYTEXT");
	$arr[5] = array("PartSubsystem", "TINYTEXT");
	$arr[6] = array("PartIndividualPrice", "DOUBLE");
	$arr[7] = array("PartQuantity", "INT");
	
	
	if($dbConfig->createINNODBTable("OrdersListTable", $arr)) echo "Success! Your OrdersListTable is now set up! <br />";
	
	if($dbConfig->setRelation("OrdersListTable", "OrdersTable", "OrderID")) echo "Success! Your OrdersTable and OrdersListTable are now linked via OrderID! <br />";
}

}
?>