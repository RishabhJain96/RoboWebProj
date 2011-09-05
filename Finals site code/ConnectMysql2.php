<?php
$db_user = "yroot"; // Username
$db_pass = "cytopic"; // Password
$db_database = "tester"; // Database Name
$db_host = "mysql"; // Server Hostname
$db_connect = mysql_connect ($db_host, $db_user, $db_pass); // Connects to the database.
$db_select = mysql_select_db ($db_database); // Selects the database.
 
function form($data) { // Prevents SQL Injection
   global $db_connect;
   $data = ereg_replace("[\'\")(;|`,<>]", "", $data);
   $data = mysql_real_escape_string(trim($data), $db_connect);
   return stripslashes($data);
}

?>