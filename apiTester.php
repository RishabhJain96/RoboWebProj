<?php
function __autoload($class)
{
	require_once $class . '.php';
}

$api = new roboSISAPI(new relationalDbConnections('RoboticsSIS', 'localhost:8889', 'root', 'root'));

//print_r($api->getCheckIns($api->getUserID("12rohits")));
$jk = 1399924;
$di = 1;
$api->inputCheckIn($jk,$api->getUserID("12rohits"));
//print_r($api->getUserID("12rohits"));

?>