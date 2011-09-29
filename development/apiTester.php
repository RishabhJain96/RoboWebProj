<?php
function __autoload($class)
{
	require_once $class . '.php';
}

$api = new roboSISAPI();

//print_r($api->getCheckIns("12rohits");
//$jk = 1399924;
//$di = 1;
//$api->inputCheckIn($jk,"12rohits");
//print_r($api->getUserID("12rohits"));
//$api->getUsersCheckedInForDate("20110924");
$api->getAllEmails();

?>