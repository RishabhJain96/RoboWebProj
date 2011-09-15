<?php
function __autoload($class)
{
	require_once $class . '.php';
}

//$api = new roboSISAPI(new relationalDbConnections('RoboticsSIS', 'localhost:8889', 'root', 'root'));

$reg = new register((new relationalDbConnections('RoboticsSIS', 'localhost:8889', 'root', 'root')));

$reg->register("12rohits","password1234");
//$reg->inputEmail("12rohits");

?>