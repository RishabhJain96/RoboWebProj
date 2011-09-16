<?PHP
// The function __autoload is the method for loading all the classes being used in the script. Use it at the beginning of every php main
// page.
function __autoload($class)
{
	require_once $class . '.php';
}

$relation = new relationalDbConnections('lala', 'localhost:3306', 'root', 'root');

$array = array("CollegeUrl" => "http://google.com", "CollegePresident" => "Abhinav Khanna");

/**
 * Example usage of InsertIntoTable. Property1: is the Table You are updating, Property2, the place you are checking for your PrimaryID
 * 			Property3 is the Column You are checking exists, Property4 is the CollegeName, Property5 is the primaryKey,
 * 			$array is the array of IDs for the insertion.
 */
//$relation->insertIntoTable("CollegeSummary","CollegeSummary", "CollegeName", "Harvard_University", "CollegeID", $array);
$relation->updateTable("CollegeSummary","CollegeSummary", "CollegeName", "Princeton_University", "CollegeID", $array, "CollegeName = 'Princeton_University'");

$relation->close_db_connection();
?>