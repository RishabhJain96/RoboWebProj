<?PHP
//global variables
	//true or false variable should only be set to true or false;
	$true_false = false;
	$other_true = false;
	$error1 = false;
	
	//constants
	define("AMOUNT_OF_CLASSES", "2");
	define("ENGLISH", "English");
	define("SPANISH", "Spanish");
	
// Random activation code
$activation_code = mt_rand().mt_rand();

// Register.php mail constants
$user_name = $_POST['email'];
$to = $_POST['email']."@students.harker.org";
$subject = "Finals Study Guides Account Creation";
$message = "Hello $user_name, \n\n Please go to: http://cytopic.net/semester2finals/activation.php?$activation_code to activate your account. \n\n Thanks,\n Abhi & Devin"; 
// overwritten in Register.php:
 $string_length = strlen($_POST['email']);

//Activation.php variables

	//the below takes the string via which the page was accessed.
	$query_url_string = $_SERVER['QUERY_STRING'];

	//username variable below
	if(!($query_url_string == "")) {
	$result = mysql_query("SELECT * FROM finals WHERE activationCode = '$query_url_string'");
	$result0 = mysql_fetch_array($result);
	$result2 = $result0["email"];
	$result3 = explode("@", $result2);
	$username = $result3[0];


	// this is the variable for the mysql_query's that happen on activation.php
	$query = mysql_query("SELECT * FROM finals WHERE activationCode = '$query_url_string'") OR die(mysql_error());
	$rows = mysql_num_rows($query);
}

	//password checks and md5()
	$password1 = $_POST['pwd'];
	$password2 = $_POST['password_check'];
	$password =  md5($password1);
	
	//below it checks for the number of classes selected and makes sure it is less than 8
//	if(isset($_POST['activate'])) {
//		$explode_classes_activate = explode(",", $classes);
//		$counter1 = 0;
//		for($i = 0; $i < 50; $i++) {
//			if(!($explode_classes_activate[$i] == "")) {
//				$counter1++;
//			}
//		}
//	}

	//IMPORTANT: This is the class list that will be uploaded!!!
	$classes = $_POST['English'].",". $_POST['Spanish'].",". $_POST['ATCS_ComputerArchitecture'].",".$_POST['AT_ProgrammingLanguages'].",".$_POST['AP_CSDS'].",".$_POST['AP_CSA'].",".$_POST['DigitalWorld'].",".$_POST['IntroOOP'].",".$_POST['Programming'].",".$_POST['English2'].",".$_POST['AP_ArtHistory'].",".$_POST['AP_EuropeanHistory'].",".$_POST['AP_Microeconomics'].",".$_POST['AP_Psychology'].",".$_POST['English1'].",".$_POST['Physics'].",".$_POST['AP_WorldHistory'].",".$_POST['WorldHistory2'].",".$_POST['WorldHistory1'].",".$_POST['Algebra1'].",".$_POST['Algebra2'].",".$_POST['AP_Calculus'].",".$_POST['Geometry'].",".$_POST['PreCalculus'].",".$_POST['AP_French'].",".$_POST['AP_Japanese'].",".$_POST['AP_Latin'].",".$_POST['AP_Spanish'].",".$_POST['French1'].",".$_POST['French2'].",".$_POST['French3'].",".$_POST['French4'].",".$_POST['Japanese1'].",".$_POST['Japanese2'].",".$_POST['Japanese3'].",".$_POST['Japanese4'].",".$_POST['Latin1'].",".$_POST['Latin2'].",".$_POST['Latin3'].",".$_POST['Spanish1'].",".$_POST['Spanish2'].",".$_POST['Spanish3'].",".$_POST['Spanish4'].",".$_POST['AP_Chem'].",".$_POST['Chem'].",".$_POST['Mandarin1'].",".$_POST['Mandarin2'].",".$_POST['Mandarin3'].",".$_POST['Mandarin4'].",".$_POST['AP_Physics'].",".$_POST['Latin4'].",".$_POST['AP_CalculusBC'].",".$_POST['AP_CalculusAB'].",".$_POST['English3'].",".$_POST['APUSH'];

//end of Activation.php variables

// login.php variables
$username_login = $_POST['username'];


// home.php variables below
$username_logged_in = $_SESSION['logged_in'];
$user_query = mysql_query("SELECT * FROM finals WHERE username='$username_logged_in'");
$user_query2 = mysql_fetch_array($user_query);
$user_query3 = $user_query2["classes"];
$explode_user = explode(",", $user_query3);


// Changing the permissions of a file

function changePermissions($path,$modlevel){
 
	chmod($path,$modlevel); 
 
	if(chmod){
 
	echo "";
 
	} else {
 
		echo "";
	}
}

// variables for index.php
	//an array of the class choices;
	$class_array = array("English", "Spanish", "Japanese");	

// logout code
	// run this script only if the logout button has been clicked
	if (array_key_exists('logout', $_POST)) {
		// empty the $_SESSION array
		$_SESSION = array();
		// invalidate the session cookie
		if (isset($_COOKIE[session_name()])) {
			setcookie(session_name(), '', time()-86400, '/');
		}
		// end session and redirect
		session_destroy();
		header('Location: http://finals.cytopic.net/');
		exit;
	}



// login.php for the admin mysql_query
$admin_query1 = mysql_query("SELECT * FROM admins");
$admin_query2 = mysql_fetch_array($admin_query1);
$admin_pwd = $admin_query2["pwd"];

?>