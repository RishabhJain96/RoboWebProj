<?php
function __autoload($class)
{
	require_once $class . '.php';
}

$api = new roboSISAPI();


?>