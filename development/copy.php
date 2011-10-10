<?php
/**
 * This procedural script copies all non-blacklisted files from the development folder to the production folder, in preparation for FTPing to the server.
 * 
 * Instructions:
 * To run, simply go to the correct address in your web browser while your local web server is running (e.g. http://localhost:8888/development/deploy.php)
 * To add new files to the blacklist, simply add a new string element to the blacklist array with the full name of the desired folder or file.
 * 
 * Notes:
 * Any new files that are added to development will be copied to production by default. The blacklist array only needs to be modified if tester files or other development-specific files are added to the development folder.
 */


// Add any files/folders in the development directory that should NOT be copied to the production directory to this array, including file ending (like .php or .txt).
$blacklist = array(".", "..", ".DS_Store", "apiTester.php", "dbParameters.txt", "copy.php", "finals_site_code", "previous_versions", "financeTester.php", "registerTester.php", "relationalDBTester.php");


$production_path = '../production/'; // assumes that the production folder is within the parent directory

$devFile = "";
$prodFile = "";

if ($handle = opendir('.')) // opens a php resource handle to current directory
{
	while (false !== ($file = readdir($handle))) // MUST use direct comparison to boolean false, otherwise filenames that evaluate to false will break the loop i.e. "0"
	{
		if (!in_array($file, $blacklist)) // ensures that blacklisted files do not get copied to production
		{
			$devFile = "$file";
			$prodFile = "$production_path" . "$file"; // path to copy devFile to
			if (copy($devFile, $prodFile))
			{
				echo "Successfully copied $devFile.\n";
			}
			else
			{
				echo "Failed to copy $devFile.\n";
			}
		}
	}
	closedir($handle);
}

?>