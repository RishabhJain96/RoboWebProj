<?php
include("includes/ConnectMysql.php");
include("includes/variables.php");
session_start();
if(!(isset($_SESSION['logged_in']))) {
	header("Location: http://cytopic.net/semester2finals/login.php");
	exit;
}
// variables
// this.php variables below
$username_logged_in = $_SESSION['logged_in'];
$user_query = mysql_query("SELECT * FROM finals WHERE username='$username_logged_in'") OR die("i die");
$user_query2 = mysql_fetch_array($user_query);
$user_query3 = $user_query2["classes"];
$explode_user = explode(",", $user_query3);

	// variables for the text fields
	$class = $_POST['subject'];
	$description = $_POST['description'];
	$target_path = "uploads/";
	$ext = pathinfo($_FILES['uploadedfile']['name']);
	$pathinfo = $ext['extension'];
	$new_file_name = $username_logged_in."_".$class."_".$description.".".$pathinfo;	
	$target_path = $target_path.$new_file_name;
	
	// array variables....that should be in variables.php
	$myArray = array("doc", "docx", "ppt", "pptx", "pages", "key", "pdf");
	foreach($myArray as $v){
		if($pathinfo == $v) {
			$other_true = true;
		}
	}

	
	

//check if the form has been set
if(isset($_POST['upload'])) {
	//check if the description and option fields have been set
	if(isset($_POST['description'])) {
		if($other_true == true) {
			if(move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $target_path)) {
				changePermissions($target_path,999);
					$query = "INSERT INTO classes (username, class, fileName, description) VALUES ('$username_logged_in', '$class', '$new_file_name', '$description')";
					mysql_query($query);
					$true_false = true;
			}
		} 
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Finals Study Guides - Login</title>
	<meta name="author" content="Abhinav Khanna and Devin Nguyen" />
	<link rel="stylesheet" href="css/style.css" type="text/css" media="screen" />
</head>
<body>
	<div id="floater"></div>
	<div id="mainContainer">
		<div id="login">
			<div id="upload-display">
				<ul>
					<li><strong>Upload</strong></li>
					<li>|</li>
					<li><a href="display-new.php">Display</a></li>
				</ul>
			</div>
			<div id="logform">
				
				<form id="loginForm" name="loginForm" method="post" action="" enctype="multipart/form-data">
					<fieldset>
						<label>Username: <?php echo "$username_logged_in"; ?></label>
					</fieldset>
					<fieldset>
						<label>File Name: </label>
						<input type="text" name="description" id="description" class="bigform" />
					</fieldset>
					<fieldset>
						<label for="textfield">Subject: <label>
						<select name="subject" id="subject" />
								<?PHP for($i = 0; $i < 8; $i++) { $end_result = $explode_user[$i]; if($end_result == ""){} else{echo "<option value=\"$end_result\">$end_result</option>";} }  ?>
							</select>
					</fieldset>
					<fieldset>
						<label>Select File:</label>
						<input type="file" name="uploadedfile" id="file" />
					</fieldset>
					<fieldset>
					<input name="upload" type="submit" id="login" value="upload" />
					</fieldset>
				</form>
				<?php
					if($true_false == true) {
						echo '<span class="login-error">';
						echo "Your file $new_file_name has been uploaded";
						echo '</span>';
						$true_false = false;
					}
					if(isset($_POST['upload'])) {
						if(!($other_true == true)) {
							echo '<span class="login-error">';
							echo "That file type is not supported.";
							echo '</span>';
							$other_true = false;
						}
					}
				?>
				<p class="logout"><form id="logoutForm" name="logoutForm" method="post" action="">
				           <input name="logout" type="submit" id="logout" value="Log out" />
				       </form></p>
				<p id="login_footer">Created by Abhinav Khanna &amp; Devin Nguyen</p>
			</div><!--#logform-->
		</div><!--#login-->
	</div> <!--#mainContainer-->
</body>
</html>
