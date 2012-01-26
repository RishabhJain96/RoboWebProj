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


$api = new roboSISAPI();

//print_r($api->getCheckIns("12rohits");
//$jk = 1399924;
//$di = 1;
//$api->inputCheckIn($jk,"12rohits");
//print_r($api->getUserID("12rohits"));
//$api->getAllEmails();
//$api->getUserType("12rohits");
$api->getMentorsEmail();

?>