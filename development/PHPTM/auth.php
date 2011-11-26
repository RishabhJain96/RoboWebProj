<?php
	// NOTE:  This is kind of kludgy (since I just threw something together fairly quickly), but it's a starting
	//        point and (before I made modifications custom to what I could tell of the PHPTM setup) it works well.

	// Needed for privacy settings on some browsers
	header("P3P: CP='CAO PSA OUR'");
	$errorTxt = $_GET["errorTxt"];
	// Site-wide variables
	include_once('config.php');
	// This is still around from back in the day ... haven't tested if it's still required
	global $PHP_SELF, $sent, $mysession, $login, $passwd, $bye, $realname, $backTo, $user_id, $errorTxt, $host, $database, $username, $password, $usertablename;

	if ($bye) {                           // user requested logout
		session_start();
		session_unregister("mysession");
		session_destroy();
		Header("Location: $backTo");
		exit();
	}

	function auth(){  
		global $mysession, $errorTxt, $host, $database, $username, $password, $usertablename;
		$db = mysql_connect($host,$username,$password);
		mysql_select_db($database);
		if(isset($_POST["sent"])):
//			echo "Found information from form.<br>";
			$login_ok = 0;  
			if (isset($_POST["login"]) and isset($_POST["passwd"])):  
//				echo "Found login and password.<br>";
				$sql = "select * from $usertablename where username='".$_POST["login"]."'";
				$results = mysql_query($sql);
				$currUser = mysql_fetch_assoc($results);
				if (mysql_num_rows($results) != 0):
//					echo "Found username.<br>";
					if (md5($_POST["passwd"]) == $currUser["password"]):
//						echo "Found password.<br>";  
						session_start();
						// create the session array 
						$mysession = array ("login" => $_POST["login"], "passwd" => $_POST["passwd"], "ID" => session_id(), "valid" => 1,"realname"=>$currUser["realname"], "user_id"=>$currUser["indexID"], "level"=>$currUser["level"]);
						session_register("mysession");
						$_SESSION = $mysession;
//						echo "Should return 1.<br>";
						return 1;     // authentication succeeded
						$login_ok = 1;
//						echo "Setting login_ok to 1.<br>";
						// echo "Finished logging in.";
						break;
                  			endif;
//					echo $sql;
				endif;  
			endif;
			if(!$login_ok):  
//				echo "Login NOT ok ...<br>";
//				echo $sql;
				$errorTxt = "ERROR:  Invalid username / password.  Please re-enter.";  
				return 0;             // access denied
			else:
//				echo "Login ok ...<br>";
				$errorTxt = "";
				return 1;
			endif;
//			echo "No form information.<br>";
			return 0;
		elseif ($GLOBALS["mysession"]["login"] != FALSE):
			$login_ok = 0;  
			session_start();  
			foreach($GLOBALS["mysession"] as $elem):      // retrieve session array 
				$ses_tmp[] = $elem;
			endforeach;
			$login = $ses_tmp[0];
			$passwd = $ses_tmp[1];
			$sql = "select * from $usertablename where username='$login'";
			$results = mysql_query($sql);
			$currUser = mysql_fetch_assoc($results);
			if (mysql_num_rows($results) != 0):
				if (md5($passwd) == $currUser["password"]):  
					session_start();  
					//	create the session array 
					$mysession = array ("login" => $login, "passwd" => $passwd, "ID" => session_id(), "valid" => 1,"realname"=>$currUser["realname"],"user_id"=>$currUser["indexID"],"level"=>$currUser["level"]);
					//	echo "Starting ...";
					session_register("mysession");
					return 1;    // authentication succeeded 
					//	break;
					$login_ok = 1;  
					//	echo "What's up?";
					break;  
				endif;  
			endif;  
			if(!$login_ok):  
				$errorTxt = "ERROR:  Invalid username / password.  Please re-enter.";
				return 0;                                // access denied 
			else:
				$errorTxt = "";
				return 1;
			endif;
		else:
			$errorTxt = "";
			return 0;
		endif;

	}

	function LoginForm(){  
		global $errorTxt;
?>

<? // include('topframefullauth.inc'); ?> 
<form method="post" action="<?php echo basename($PHP_SELF); ?>" name=loginform> 
	<table width="75%" cellpadding="4" cellspacing="1" border="0" class="forumline" align="center" valign=middle style="vertical-align: center">
		<tr>
			<th height="25" class="thHead" nowrap="nowrap">
				Please enter your username and password to login
			</th>
		</tr>
		<tr>
			<td class="row1">
				<font color=#dc0909 size=1><b><?=$errorTxt?></b></font><br>
				<table border="0" cellpadding="3" cellspacing="1" width="100%">
					<tr>
						<td rowspan=4 width=20% align=middle valign=middle>
							<center>
								<img src="/images/lock.gif" border=0 align=middle valign=middle>
							</center>
						</td>
						<td colspan="2" align="center">&nbsp;</td>
					</tr>
					<tr>
						<td width="25%" align="right">
							<span class="gen">Username:</span>
						</td>
						<td>
							<input type="text" name="login" size="25" maxlength="40" value="" />
						</td>
					</tr>
					<tr>
						<td align="right">
							<span class="gen">Password:</span>
						</td>
						<td>
							<input type="password" name="passwd" size="25" maxlength="25" />
						</td>
					</tr>
					<tr align="center">
						<td colspan="2">
							<input type="hidden" name="backTo" value="<? echo $_GET["backTo"]; ?>" /><input type="submit" name="sent" class="mainoption" value="Login" />
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</form>
<script type="text/javascript"> 
	<!-- 
	if (document.loginform) { 
		document.loginform.login.focus(); 
    }
    // --> 
</script> 
<?
		// include('bottomframefull.inc'); 
	}

// -------------------------------------------------------------------------------------- 
// main 
// -------------------------------------------------------------------------------------- 

      //init vars; 
	$mysession = array ("login"=>FALSE, "passwd"=>FALSE, "ID"=>FALSE, "valid"=>FALSE,"realname"=>FALSE);
	$uri = basename($PHP_SELF);  
	$stamp = md5(srand(5));

	if(!auth()):                 // authentication failed
		LoginForm($errorTxt);    // display login form
	else:                        // authentication was successful 
//		if ($backTo == "") { $backTo = "/"; }
//		echo "We made it.<br>";
		echo "<META HTTP-EQUIV=Refresh CONTENT=\"0; URL=$backTo\">"; 
		//      Header("Location: $redirect");
	endif;  
?>
