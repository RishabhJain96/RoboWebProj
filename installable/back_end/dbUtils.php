<?php
/**
 * A static utilities class to hold dbConnection-related functions.
 */
class dbUtils
{
	public static function getConnection()
	{
		$dbArr = array();
		//$dbParameters = "dbParameters.txt";
		//$fileHandle = fopen($dbParameters, 'r');
		//$data = fread($fileHandle, filesize($dbParameters));
		$dbArr = file("dbParameters.txt", FILE_IGNORE_NEW_LINES);
		//$dbArr = file_get_contents("dbParameters.txt");
		//$dbArr = explode("", $data);
		//var_dump($dbArr);
		//$dbArr[0] = str_replace(array("\r", "\r\n", "\n"), '', $dbArr[0]);
		//$dbArr[1] = str_replace(array("\r", "\r\n", "\n"), '', $dbArr[1]);
		//$dbArr[2] = str_replace(array("\r", "\r\n", "\n"), '', $dbArr[2]);
		//if (count($dbArr) > 3)
		//{
		//	$dbArr[3] = str_replace(array("\r", "\r\n", "\n"), '', $dbArr[3]);
		//}
		//else
		//{
		//	$dbArr[3] = ""; // null string if array index 3 does not exist, means password is empty field
		//}
		//$dbArr[3] = str_replace(array("\r", "\r\n", "\n"), '', $dbArr[3]);
		
//		// hardcoded values to get around dbParameters problem
//		$dbArr[0] = "RoboticsDevProd";
//		$dbArr[1] = "localhost";
//		$dbArr[2] = "root";
//		$dbArr[3] = "team1072";
//		var_dump($dbArr[0]);
//		var_dump($dbArr[1]);
//		var_dump($dbArr[2]);
//		var_dump($dbArr[3]);
//		var_dump($dbArr);
		$dbConnection = new relationalDbConnections($dbArr[0], $dbArr[1], $dbArr[2], $dbArr[3]);
		return $dbConnection;
	}
	
	public static function getPropertiesConnection()
	{
		/*$dbArr = file('dbParameters.txt');
		$dbArr[0] = str_replace(array("\r", "\r\n", "\n"), '', $dbArr[0]);
		$dbArr[1] = str_replace(array("\r", "\r\n", "\n"), '', $dbArr[1]);
		$dbArr[2] = str_replace(array("\r", "\r\n", "\n"), '', $dbArr[2]);
		if (count($dbArr) > 3)
		{
			$dbArr[3] = str_replace(array("\r", "\r\n", "\n"), '', $dbArr[3]);
		}
		else
		{
			$dbArr[3] = ""; // null string if array index 3 does not exist, means password is empty field
		}
		$dbArr[3] = str_replace(array("\r", "\r\n", "\n"), '', $dbArr[3]);
		*/
		$dbArr = array();
		// hardcoded values to get around dbParameters problem
		$dbArr[0] = "RoboticsDevProd";
		$dbArr[1] = "localhost";
		$dbArr[2] = "root";
		$dbArr[3] = "team1072";
		$dbConnection = new databaseProperties($dbArr[0], $dbArr[1], $dbArr[2], $dbArr[3]);
		return $dbConnection;
	}
}

?>