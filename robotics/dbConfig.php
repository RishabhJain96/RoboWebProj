<?PHP
// The function __autoload is the method for loading all the classes being used in the script. Use it at the beginning of every php main
// page.
function __autoload($class)
{
	require_once $class . '.php';
}

/**
 * Versioning
 * Currently versioning will be done by a variable that will be manually set. Eventually it will just update a database.properties table
 * on each person's local.
 * 
 * The Variables $dbhost, $databaseName, $dbuser, $dbpass need to be filled with your local settings.
 * 
 * If you are more than one version number behind, set it to the version right after the one you got before. If you already have 2, set it 
 * to 3 (for example!). This will trigger the for() loop which should add all the updates for you till the most recent version number.
 * 
 * Before running this script, make sure to up the version number by 1 (or to the latest version (located at the bottom));
 */
$version = 1;
$databaseName = "RoboticsSIS";
$dbhost = "mysql";
$dbuser = "yroot";
$dbpass = "cytopic";


//Do NOT EDIT THIS PORTION OF THE CODE.
$dbConfig = new databaseProperties($databaseName, $dbhost, $dbuser, $dbpass);
$totalVersions = 3;

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
	$array1[4] = array("UserDescription", "TEXT");
	$array1[5] = array("UserPhoneNumber", "TEXT");
	$array1[6] = array("UserYear", "INT");
	$array1[7] = array("UserMomEmail", "TINYTEXT");
	$array1[8] = array("UserDadEmail", "TINYTEXT");
	$array1[9] = array("UserEmail", "TINYTEXT");
	$array1[10] = array("UserTitle", "TINYTEXT");
	$array1[11] = array("UserPicture", "TINYTEXT");
	$array1[12] = array("UserPassword", "TINYTEXT");
	$array1[13] = array("ActivationCode", "TINYTEXT");
	$array1[14] = array("Activated", "INT"); // nonzero val is true

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
 * PictureID Table
 */
if($i == 2)
{
	$array = array();
	$array[0] = array("HistoryID", "int", "NOT NULL", "AUTO_INCREMENT");
	$array[1] = array("PRIMARY KEY(HistoryID)");
	$array[2] = array("HistoryTimeStamp", "TINYTEXT");
	$array[3] = array("UserID", "INT");
	
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
	
	if($dbConfig->setRelation("UserTasks", "RoboUsers", "UserID")) echo "Success! Your UserTasks and RoboUsers Table are now linked via UsersID! <br />";
	
}

}
?>