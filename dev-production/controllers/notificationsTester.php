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

$controller = new financeController();
$username = "12rohits";
$orderID = 1;

$order = $controller->getOrder($orderID);
$status = $order[0]["Status"];
$vendorname = $order[0]["PartVendorName"];
$result = $controller->emailUserStatusUpdate($username, $orderID, $status, $vendorname);
//print_r($result);
?>