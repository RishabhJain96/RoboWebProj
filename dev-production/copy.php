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

function copy_directory($source, $destination)
{
	mkdir($destination);
	$directory = dir($source);
	while ( FALSE !== ( $readdirectory = $directory->read() ) )
	{
		if ( $readdirectory == '.' || $readdirectory == '..' )
		{
			continue;
		}
		$pathDir = $source . '/' . $readdirectory; 
		if (is_dir($pathDir)) // recursively copies subdirectories
		{
			copy_directory($pathDir, $destination . '/' . $readdirectory );
			continue;
		}
		if (copy( $pathDir, $destination . '/' . $readdirectory ))
		{
			echo "Successfully copied $pathDir.\n";
		}
	}
	$directory->close();
}

// Add any files/folders in the development directory that should NOT be copied to the production directory to this array, including file ending (like .php or .txt).
$blacklist = array(".", "..", ".DS_Store", "controllers/apiTester.php", "back_end/dbParameters.txt", "copy.php", "controllers/financeTester.php", "back_end/registerTester.php", "back_end/relationalDBTester.php", "back_end/dbUtils.php");


$production_path = '../'; // set to parent directory

$dev = "";
$prod = "";

if ($handle = opendir('.')) // opens a php resource handle to current directory
{
	while (false !== ($source = readdir($handle))) // MUST use direct comparison to boolean false, otherwise filenames that evaluate to false will break the loop i.e. "0"
	{
		if (!in_array($source, $blacklist)) // ensures that blacklisted files do not get copied to production
		{
			$dev = "$source";
			$prod = "$production_path" . "$source"; // path to copy devFile to
			if (is_dir($source)) // handles copying of folders and all files within
			{
				copy_directory($dev, $prod);
			}
			else if (copy($dev, $prod)) // copying of just files,
			{
				echo "Successfully copied $dev.\n";
			}
			else
			{
				echo "Failed to copy $dev.\n";
			}
		}
	}
	closedir($handle);
}

?>