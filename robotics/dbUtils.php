<?php
/**
 * A static utilities class to hold dbConnection-related functions
 */
class dbUtils
{
	public static function getConnection()
	{
		$dbArr = file("dbParameters.txt");
		$dbArr[0] = str_replace(array("\r", "\r\n", "\n"), '', $dbArr[0]);
		$dbArr[1] = str_replace(array("\r", "\r\n", "\n"), '', $dbArr[1]);
		$dbArr[2] = str_replace(array("\r", "\r\n", "\n"), '', $dbArr[2]);
		$dbArr[3] = str_replace(array("\r", "\r\n", "\n"), '', $dbArr[3]);
		$dbConnection = new relationalDbConnections($dbArr[0], $dbArr[1], $dbArr[2], $dbArr[3]);
		return $dbConnection;
	}
}

?>