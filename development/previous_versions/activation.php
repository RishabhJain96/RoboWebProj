<?php
/**
 * This is a small procedural page to set the activated field for the user's account
 */
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


$dbConnection = dbUtils::getConnection();

$resourceid = $dbConnection->selectFromTable("RoboUsers", "ActivationCode", $acode);
$arr = $dbConnection->formatQueryResults($resourceid, "ActivationCode");

// this runs if the activation code is not in the database, which is what happens after a user activates his/her account for the first time.
if (empty($arr) || is_null($arr[0])) // NOTE: can't destinguish between null value in table and invalid attribute parameter (both return array with single, null element)
{
	error_log("This account has already been activated.");
	echo 'This account has already been activated.';
	return false; // cuts execution early
}


$bool = 1; // any nonzero value to indicated activated status
$stuffing = "Activated"; // clears the activation code field for clarity
$array = array("Activated" => $bool, "ActivationCode" => $stuffing);
$dbConnection->updateTable("RoboUsers", "RoboUsers", "ActivationCode", $code, "UserID", $array, "ActivationCode = '$code'");

echo 'Congratulations! Your account has been activated.';

?>