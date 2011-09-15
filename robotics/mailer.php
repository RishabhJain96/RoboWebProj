<?php
$username = "12rohits";
$code = md5("111111");
$serverurl = "http://cytopic.net/robotics";
$to = $username . "@students.harker.org";
$subject = "Robotics SIS Account Creation";
$message = "Hello $username, \n\n Please go to: $serverurl/activation.php?acode=$code to activate your account. \n\n Thanks,\n The Robotics 1072 Web Team"; // server url 
$header = "From: harker1072@gmail.com";
print_r($to);
print_r($subject);
print_r($message);
print_r($header);
$bool = mail($to, $subject, $message, $header);
if($bool)
{
	print 'true';
}
else
{
	print 'false';
}
?>