<?php
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

$controller = new profileController();

$username = "12rohits";

// test updateUserInfo
// key is column name, value is the value you want to input
$userInfo = array(
"UserFullName" => "Rohit Sanbhadti",
"UserPhoneNumber" => "(408) 582-4781",
"UserYear" => 2012,
"UserParentsEmail" => "vnarayan@yahoo.com",
"UserEmail" => "12rohits@students.harker.org",
"UserSubteam" => "Operational"
);

$controller->updateUserInfo($username, $userInfo);
//$result = $controller->getUserInfo($username);
//print_r($result);

?>