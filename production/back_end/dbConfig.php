<?PHP
// autoloader code
// loads classes as needed, eliminates the need for a long list of includes at the top
spl_autoload_register(function ($className) { 
    $possibilities = array( 
        '../controllers'.DIRECTORY_SEPARATOR.$className.'.php', 
        '../back_end'.DIRECTORY_SEPARATOR.$className.'.php', 
        '../views'.DIRECTORY_SEPARATOR.$className.'.php', 
        $className.'.php' 
    ); 
    foreach ($possibilities as $file) { 
        if (file_exists($file)) { 
            require_once($file); 
            return true; 
        } 
    } 
    return false; 
});

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

$dbConfig = new databaseProperties($dbName, $dbHost, $dbUser, $dbPass);
$totalVersions = 5;

for($i = $version; $i <= $totalVersions; $i++)
{
/**
 * RoboUsers Table
 */
if($i == 1) {
	$array1 = array();

	$array1[] = array("UserID", "int", "NOT NULL", "AUTO_INCREMENT");
	$array1[] = array("PRIMARY KEY(UserID)", "");
	$array1[] = array("Username", "TEXT");
	$array1[] = array("UserFullName", "TINYTEXT");
//	$array1[] = array("UserDescription", "TEXT"); // needed?
	$array1[] = array("UserPhoneNumber", "TINYTEXT");
	$array1[] = array("UserYear", "INT");
//	$array1[] = array("UserMomEmail", "TINYTEXT"); // split into mom/dad email?
	$array1[] = array("UserParentsEmail", "TINYTEXT"); // split into mom/dad email?
	$array1[] = array("UserEmail", "TINYTEXT");
//	$array1[] = array("UserTitle", "TINYTEXT"); // needed?
//	$array1[] = array("UserPicture", "TINYTEXT"); // needed?
	$array1[] = array("UserPassword", "TINYTEXT");
	$array1[] = array("ActivationCode", "TINYTEXT");
	$array1[] = array("Activated", "INT"); // nonzero val is true
	$array1[] = array("UserSubteam", "TINYTEXT"); // vals: Mechanical, Electronics, Programming, Operational
	$array1[] = array("UserType", "TINYTEXT"); // vals: Regular, VP, Admin, Root

	if($dbConfig->createINNODBTable("RoboUsers", $array1)) echo "Success! Your RoboUsers Table is now set up! <br />";
//print_r($dbConfig->createINNODBTable("CollegeSummary", $array1));
//The Next Table's set up file.
// The CollegeProfessors table.

	$array = array();
	$array[] = array("BadgeID", "int", "NOT NULL", "AUTO_INCREMENT");
	$array[] = array("PRIMARY KEY(BadgeID)");
	$array[] = array("BadgeName", "TEXT");
	$array[] = array("UserID", "INT");

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
	$array[] = array("HistoryID", "int", "NOT NULL", "AUTO_INCREMENT");
	$array[] = array("PRIMARY KEY(HistoryID)");
	$array[] = array("HistoryTimeStamp", "TINYTEXT");
	$array[] = array("NumericTimeStamp", "TINYTEXT");
	$array[] = array("UserID", "INT");
	
	if($dbConfig->createINNODBTable("UserHistories", $array)) echo "Success! Your UserHistory Table is now set up! <br />";
	
	if($dbConfig->setRelation("UserHistories", "RoboUsers", "UserID")) echo "Success! Your UserHistories and RoboUsers Table are now linked via UserID! <br />";

}

/**
 * UserTasks Table
 */
if($i == 3)
{	
	$array1 = array();
	$array1[] = array("TaskID", "int", "NOT NULL", "AUTO_INCREMENT");
	$array1[] = array("PRIMARY KEY(TaskID)");
	$array1[] = array("TaskName", "TEXT");
	$array1[] = array("UserID", "INT");
	$array1[] = array("Deadline", "TINYTEXT");
	$array1[] = array("AssignedByUserID", "INT");
	
	if($dbConfig->createINNODBTable("UserTasks", $array1)) echo "Success! Your UserTasks Table is now set up! <br />";
	
	if($dbConfig->setRelation("UserTasks", "RoboUsers", "UserID")) echo "Success! Your UserTasks and RoboUsers Table are now linked via UserID! <br />";
	
}

/**
 * OrdersTable
 */
if($i == 4)
{
	$arr = array();
	$arr[] = array("OrderID", "int", "NOT NULL", "AUTO_INCREMENT");
	$arr[] = array("PRIMARY KEY(OrderID)");
	$arr[] = array("UserID", "INT"); // submitting user
	$arr[] = array("Username", "TINYTEXT"); // submitting user
	$arr[] = array("UserSubteam", "TINYTEXT"); // submitting user
	$arr[] = array("EnglishDateSubmitted", "TINYTEXT");
	$arr[] = array("NumericDateSubmitted", "TINYTEXT");
	$arr[] = array("EnglishDateApproved", "TINYTEXT");
	$arr[] = array("NumericDateApproved", "TINYTEXT");
	$arr[] = array("EnglishDateRootApproved", "TINYTEXT");
	$arr[] = array("NumericDateRootApproved", "TINYTEXT");
	$arr[] = array("ReasonForPurchase", "TEXT");
	$arr[] = array("ShippingAndHandling", "DOUBLE");
	$arr[] = array("TaxPrice", "DOUBLE");
	$arr[] = array("EstimatedTotalPrice", "DOUBLE");
	$arr[] = array("PartVendorName", "TINYTEXT");
	$arr[] = array("PartVendorEmail", "TINYTEXT");
	$arr[] = array("PartVendorAddress", "TINYTEXT"); // adress stored as one line
	$arr[] = array("PartVendorPhoneNumber", "TINYTEXT");
	$arr[] = array("AdminComment", "TEXT");
	$arr[] = array("AdminApproved", "INT"); // int acts as bool, 0 and 1
	$arr[] = array("AdminUsername", "TINYTEXT"); // NOT DB LINKED
	$arr[] = array("RootComment", "TEXT");
	$arr[] = array("RootApproved", "INT"); // int acts as bool, 0 and 1
	$arr[] = array("Status", "TINYTEXT"); // vals: Unfinished, Pending, Approved, Completed, Rejected
	$arr[] = array("PrintCounter", "INT");
	$arr[] = array("ConfirmationOfPurchase", "INT"); // int acts as bool, 0 and 1
	$arr[] = array("Locked", "INT"); // int acts as bool, 0 and 1
	$arr[] = array("UniqueID", "TINYTEXT"); // acts as a way to get a specific OrderID after inserting
	//$arr[19] = array("ActualTotalPrice", "DOUBLE");
	
	if($dbConfig->createINNODBTable("OrdersTable", $arr)) echo "Success! Your OrdersTable is now set up! <br />";
	
	if($dbConfig->setRelation("OrdersTable", "RoboUsers", "UserID")) echo "Success! Your OrdersTable and RoboUsers Table are now linked via UserID! <br />";
}

/**
 * OrdersListTable
 */
if ($i == 5)
{
	$arr = array();
	$arr[] = array("OrderListID", "int", "NOT NULL", "AUTO_INCREMENT");
	$arr[] = array("PRIMARY KEY(OrderListID)");
	$arr[] = array("OrderID", "INT");
	$arr[] = array("UniqueEntryID", "TINYTEXT"); // allows updating of individual entries to work
	$arr[] = array("PartNumber", "TINYTEXT");
	$arr[] = array("PartName", "TINYTEXT");
	$arr[] = array("PartSubsystem", "TINYTEXT");
	$arr[] = array("PartIndividualPrice", "DOUBLE");
	$arr[] = array("PartQuantity", "INT");
	$arr[] = array("PartTotalPrice", "DOUBLE");
		
	
	if($dbConfig->createINNODBTable("OrdersListTable", $arr)) echo "Success! Your OrdersListTable is now set up! <br />";
	
	if($dbConfig->setRelation("OrdersListTable", "OrdersTable", "OrderID")) echo "Success! Your OrdersTable and OrdersListTable are now linked via OrderID! <br />";
}

}
?>