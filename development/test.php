<?php
spl_autoload_register(function ($className) { 
    $possibilities = array( 
        './controllers'.DIRECTORY_SEPARATOR.$className.'.php', 
        './back_end'.DIRECTORY_SEPARATOR.$className.'.php', 
        './views'.DIRECTORY_SEPARATOR.$className.'.php', 
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

$orderID = 13;
$username = "12rohits";
$controller = new financeController();

$orderslist = $controller->getOrdersList($orderID);
print_r($orderslist);
?>