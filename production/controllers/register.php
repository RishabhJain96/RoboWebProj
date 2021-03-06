<?php
/**
 * Robotics SIS Register class
 * @author: Rohit Sanbhadti
 * Inputs the username and password in the database, along with a randomly generated activation number. Emails the user with a link containing the activation number, which when clicked, sets the Activated field in the db to a nonzero value, indicating the user has registered.
 * This class also handles changing a user's password.
 */

class register extends roboSISAPI
{
	// constants
	const DEFAULT_PASS = "qwerty";
	
	// instance variables
	protected $_dbConnection;
	protected $_serverurl;
	
	public function __construct()
	{
		$this->_serverurl = "http://robo.harker.org/";
		$this->_dbConnection = dbUtils::getConnection();
		$this->_connection = $this->_dbConnection->open_db_connection();
	}
	
	/**
	 * combines all other methods under one hood. returns true on success and the username is taken message on failure.
	 */
	public function register($username, $password, $phonenumber)
	{
		$code = md5(mt_rand());
		$username = parent::sanitize($username);
		$password = parent::sanitize($password);
		$phonenumber = parent::sanitize($phonenumber);
		$result = $this->inputNewUser($username, $password, $phonenumber, $code); // result stores false or text string 'true' depending on the outcome of the input method
		if ($result) // checks literal string value
		{
			//print 'result is TRUE';
			$this->inputEmail($username);
			$this->emailNewUser($username, $code);
			return true; // success in inputting user
		}
		else
		{
			return false; // username already taken
		}
	}
	
	/**
	 * This function checks if the given username already exists in the database. It returns a string protesting the existence of the given username if it finds it in the db, otherwise it creates the username/password combo in the db
	 */
	public function inputNewUser($username, $password, $phonenumber, $code)
	{
		// checks if username already exists in db
		$resourceid = $this->_dbConnection->selectFromTable("RoboUsers", "Username", $username);
		$arr = $this->_dbConnection->formatQueryResults($resourceid, "Username");
		if (count($arr) > 0)
		{
			error_log("This username is already taken!");
			//print 'The username ' . $username . ' is already taken! Please choose a different one.'; // for debugging purposes
			echo "<p>The username $username is already taken! Please choose a different one.</p>";
			return false;
		}
		// username is a valid, new username at this point
		$password = md5($password); // encodes password in md5 for security/privacy
		//print_r($passwordCoded);
		$type = "Regular"; // user is regular unless changed specifically
		$array = array("ActivationCode" => $code, "Username" => $username, "UserPassword" => $password, "UserPhoneNumber" => $phonenumber, "UserType" => $type);
		$this->_dbConnection->insertIntoTable("RoboUsers", "RoboUsers", "Username", $username, "UserID", $array);
		return true;
	}
	
	/**
	 * This is a separate function because it's existence is not necessarily required, but it seems smart to have it.
	 * This function inputs the user's harker email based on their username.
	 * Assumes that username is harker username.
	 */
	public function inputEmail($username)
	{
		$email = "" . $username . "@students.harker.org";
		$array = $array = array("UserEmail" => $email);
		$this->_dbConnection->updateTable("RoboUsers", "RoboUsers", "Username", $username, "UserID", $array, "Username = '$username'");
	}
	
	/**
	 * emails the user with activation link to activate account
	 */
	public function emailNewUser($username, $code)
	{
		/*$to = $username . "@students.harker.org";
		$subject = "Robotics SIS Account Creation";
		$message = "Hello $username, \n\n Please go to: $this->_serverurl/activation.php?acode=$code to activate your account. \n\n Thanks,\n The Robotics 1072 Web Team"; // _serverurl is global var set at construction
		$header = "From: harker1072@gmail.com";
		echo "<a href=\"$this->_serverurl/activation.php?acode=$code\">$this->_serverurl/activation.php?acode=$code</a>";
		*/
		
		// in-place activation code
		$bool = 1; // any nonzero value to indicated activated status
		$stuffing = "Activated"; // clears the activation code field to md5 for clarity
		$array = array("Activated" => $bool, "ActivationCode" => $stuffing);
		$this->_dbConnection->updateTable("RoboUsers", "RoboUsers", "ActivationCode", $code, "UserID", $array, "ActivationCode = '$code'");

		//echo 'Congratulations! Your account has been activated.';
		/*print_r($to);
		print_r($subject);
		print_r($message);
		print_r($header);
		*/
		//mail($to, $subject, $message, $header);
	}
	
	/**
	 * description: Returns the given user's current password.
	 * 
	 * @param username: The user who's password to get
	 * @return string: The password, straight from the database (still in md5 form)
	 */
	public function getPassword($username)
	{
		$resourceid = $this->_dbConnection->selectFromTable("RoboUsers", "Username", $username);
		$array = $this->_dbConnection->formatQueryResults($resourceid, "UserPassword");
		return $array[0];
	}
	
	/**
	 * description: Reset the password to a hardcoded constant
	 * 
	 * @param username: 
	 * @return int: 
	 */
	public function resetPassword($username)
	{
		$password = self::DEFAULT_PASS;
		$this->setPassword($username, $password);
	}
	
	/**
	 * description: 
	 * 
	 * @param password: 
	 * @param username: 
	 * @return int: 
	 */
	public function setPassword($username, $password)
	{
		$password = md5($password);
		$condition = "Username = '$username'";
		$array = array("UserPassword" => $password);
		$this->_dbConnection->updateTable("RoboUsers", "RoboUsers", "Username", $username, "UserID", $array, $condition);
	}
}

?>