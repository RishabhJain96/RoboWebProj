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

//$api = new roboSISAPI(new relationalDbConnections('RoboticsSIS', 'localhost:8889', 'root', 'root'));

$reg = new register((new relationalDbConnections('RoboticsSIS', 'localhost:8889', 'root', 'root')));

$reg->register("12rohits","password1234");
//$reg->inputEmail("12rohits");

?>