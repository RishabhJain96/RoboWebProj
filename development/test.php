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
/*
$orderID = 13;
$username = "12rohits";
$controller = new financeController();

$orderslist = $controller->getOrdersList($orderID);
print_r($orderslist);
*/
$controller = new register();
echo "i got here";
$username = "twist";
// old password is md5'd to allow checking with db
$oldpassword = md5("twist");
echo "\noldpassword=";
print_r($oldpassword);
// new password will be md5'd in back-end code
$newpassword = "joke";
					
//if($username == "")
//{
//	echo "<p>Please complete all fields and try again.</p>";
//	//exit("Please complete all fields and try again.");
//}
//	
if ($controller->getPassword($username) != $oldpassword)
{
	echo "<p>Your old password is incorrect.</p>";
	//exit("Your old password is incorrect.");
}
else
{
	$controller->setPassword($username, $newpassword);
	echo "<p>Successfully changed password</p>";
}


?>