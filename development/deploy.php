<?php
/**
 * This procedural script copies all listed files from the development folder to the production folder, in preparation for pushing to the server.
 * 
 * Instructions:
 * To run, simply go to the correct address, as with any other php script (e.g. http://localhost:8888/development/deploy.php)
 * To add new files to the deployment script, add a line of code to set the next element of the array ($array_filenames) to be the filename as a string.
 * Notes:
 * Do NOT add dbParameters.txt to this script. If dbParameters.txt needs to be changed, please copy it to the production environment manually. This is because dbParameters holds database configuration data specific to each environment that generally should not be changed, and should not move between the development and production environments.
 * No tester scripts should ever have to move to the production environment.
 */

// add strings to the following array if new files need to be deployed
$array_filenames = array();
$array_filenames[0] = 'activation.php';
$array_filenames[1] = 'dashboard.php';
$array_filenames[2] = 'databaseProperties.php';
$array_filenames[3] = 'dbConfig.php';
$array_filenames[4] = 'dbConnections.php';
$array_filenames[5] = 'dbUtils.php';
$array_filenames[6] = 'index.php';
$array_filenames[7] = 'login.php';
$array_filenames[8] = 'register.php';
$array_filenames[9] = 'registration.php';
$array_filenames[10] = 'relationalDbConnections.php';
$array_filenames[11] = 'reset.css';
$array_filenames[12] = 'roboSISAPI.php';
$array_filenames[13] = 'style.css';
$array_filenames[14] = 'style2.css';

$production_path = '../robotics/';

for ($i=0; $i < count($array_filenames); $i++)
{
	$devFile = $array_filenames[$i];
	$prodFile = $production_path . $array_filenames[$i];
	if (!copy($devFile, $prodFile))
	{
	    echo "Failed to copy $devFile.\n";
	}
	else
	{
		echo "Successfully copied $devFile.\n";
	}
}

?>