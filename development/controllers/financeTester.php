<?php
// autoloader code
// loads classes as needed, eliminates the need for a long list of includes at the top
spl_autoload_register(function ($className) { 
    $possibilities = array( 
        'beans'.DIRECTORY_SEPARATOR.$className.'.php', 
        'controllers'.DIRECTORY_SEPARATOR.$className.'.php', 
        'libraries'.DIRECTORY_SEPARATOR.$className.'.php', 
        'models'.DIRECTORY_SEPARATOR.$className.'.php', 
        'views'.DIRECTORY_SEPARATOR.$className.'.php', 
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

$finance = new financeController();

$username = "12rohits";
/*
// test inputOrder
// key is column name, value is the value you want to input
$orders = array(
"Username" => $username,
"UserSubteam" => "Operational",
"EnglishDateSubmitted" => date("l, F j \a\\t g:i a"),
"NumericDateSubmitted" => date("YmdHi"),
"EnglishDateApproved" => date("l, F j \a\\t g:i a"),
"NumericDateApproved" => date("YmdHi"),
"ReasonForPurchase" => "For to prevent the effusion of blood",
"ShippingAndHandling" => 12.95,
"TaxPrice" => 19.95,
"EstimatedTotalPrice" => 99.95,
"PartVendorName" => "Bob Joe",
"PartVendorEmail" => "bob.joe@bob.com",
"PartVendorAddress" => "1673 miramar ave, san jose, ca",
"PartVendorPhoneNumber" => "(890) 756-8899"
//"AdminComment" => "We have enough soldering irons.",
//"AdminApproved" => 1,
//"AdminUsername" => "12jayr",
//"Status" => ""
//"ConfirmationOfPurchase" => 0,
//"Locked" => 0
);

// an array of arrays, with each array being a separate "part".
$orderslist = array(array(
"PartNumber" => "92452",
"PartName" => "Bolts",
"PartSubsystem" => "Geartrain",
"PartIndividualPrice" => 3.75,
"PartQuantity" => 15),
array(
"PartNumber" => "08907",
"PartName" => "Screws",
"PartSubsystem" => "Driveshaft",
"PartIndividualPrice" => 2.00,
"PartQuantity" => 7)
);

$finance->inputOrder($username, $orders, $orderslist);
*/
/*
// test updateOrder
$orders = array(
"Username" => $username,
"UserSubteam" => "Mech",
"EnglishDateSubmitted" => date("l, F j \a\\t g:i a"),
"NumericDateSubmitted" => date("YmdHi"),
"EnglishDateApproved" => date("l, F j \a\\t g:i a"),
"NumericDateApproved" => date("YmdHi"),
"ReasonForPurchase" => "Ensure the common welfare",
"ShippingAndHandling" => 10.05,
"TaxPrice" => 9.99,
"EstimatedTotalPrice" => 134.59,
"PartVendorName" => "Bob Joe",
"PartVendorEmail" => "jin.jin@bob.com",
"PartVendorAddress" => "7755 sizzle lane, cupertino, ca",
"PartVendorPhoneNumber" => "(999) 999-7777"
//"AdminComment" => "We have enough soldering irons.",
//"AdminApproved" => 1,
//"AdminUsername" => "12jayr",
//"ConfirmationOfPurchase" => 0,
//"Locked" => 0
);

// an array of arrays, with each array being a separate "part".
$orderslist = array(array(
"UniqueEntryID" => "222",
"PartNumber" => "09908",
"PartName" => "Nuts",
"PartSubsystem" => "E-box",
"PartIndividualPrice" => 40.05,
"PartQuantity" => 50),
array(
"UniqueEntryID" => "333",
"PartNumber" => "29472",
"PartName" => "Screws",
"PartSubsystem" => "Driveshaft",
"PartIndividualPrice" => 2.01,
"PartQuantity" => 8)
);

$finance->updateOrder(14, $orders, $orderslist);
*/

//$result = $finance->getUsersOrders($username);
//$result = json_encode($finance->getOrdersList(14));
//$result = $finance->getAllOrders();
//$result = $finance->getPendingOrders();
//$result = $finance->isLocked(13);
//$result = $finance->getFullOrder(13);
//$result = $finance->setApproval(13, false, "12paulw", "Try upping the quantity.");
//print_r($result);
?>