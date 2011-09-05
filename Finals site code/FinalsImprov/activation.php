<?php
include("includes/ConnectMysql.php");
include("includes/variables.php");

if($rows > 0) {

}
else {
	header("Location: http://cytopic.net/semester2finals/login.php");
	break;
}

if(isset($_POST['activate'])) {
	$explode_classes_activate = explode(",", $classes);
	$counter1 = 0;
	for($i = 0; $i < 50; $i++) {
		if(!($explode_classes_activate[$i] == "")) {
			$counter1++;
		}
	}
}

if(isset($_POST['activate'])) {
	if(isset($_POST['pwd'])) {
		if(($counter1 < 9) && ($counter1 > 0)) {
			mysql_query("UPDATE finals SET username='$username', classes='$classes', password='$password', activationCode='' WHERE activationCode = '$query_url_string'") or die (mysql_error());
			$true_false = true;
			
		} else {
			if($counter1 == 0) {
				$other_true = true;
			} else {
			$class_error = true;
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
	
	<style>
	#loginForm p {
		margin-top: 1em;
	}
	#loginForm input .class-list {
		height: 20px;
		margin-right: 1em;
	}
	</style>
</head>
<body OnLoad="document.loginForm.pwd.focus();">
	<div id="floater"></div>
	<div id="mainContainer">
		<div class="login">
			<p class="login_header">Activation</p>
			<div id="logform">
				<?php
					if($true_false == true) {
						echo '<span class="login-error">';
						echo "Thank you for activating your account. Please login <a href=\"login.php\">here</a>";
						echo '</span>';
						echo'<br /><br />';
						$true_false = false;
					}
					if($other_true == true) {
						echo '<span class="login-error">';
						echo "You forgot to choose any classes. Please try again.";
						echo '</span>';
						echo'<br /><br />';
						$other_true = false;
					}
					if($class_error == true) {
						echo '<span class="login-error">';
						echo "You have selected too many classes. Please try again.";
						echo '</span>';
						echo'<br /><br />';
						$class_error = false;
					}
				?>
				<form id="loginForm" name="loginForm" method="post" action="">
					<fieldset>
						<label>Username: <?php echo "$username"; ?></label>
					</fieldset>
					<fieldset>
						<label for="textfield" id="password" >Password: <label>
						<input type="password" name="pwd" class="bigform" />
					</fieldset>
					
					<p>Select your Classes:</p>

					<p id="activation-classlist">
							<p>Art</p>
							<label><input type="checkbox" name="AP_ArtHistory" value="AP_ArtHistory"/>AP Art History</label><br />
						<p>Computer Science</p>
						<label><input type="checkbox" name="ATCS_ComputerArchitecture" value="ATCS_ComputerArchitecture"/>Adv Topics Comp Sci Comptuer Architecture</label><br />
						<label><input type="checkbox" name="AT_ProgrammingLanguages" value="AT_ProgrammingLanguages"/>Adv Topics Programming Langauges</label><br />
						<label><input type="checkbox" name="AP_CSDS" value="AP_CSDS"/>AP Computer Science (DS)</label><br />
						<label><input type="checkbox" name="AP_CSA" value="AP_CSA"/>AP Computer Science A</label><br />
						<label><input type="checkbox" name="DigitalWorld" value="DigitalWorld"/>Digital World</label><br />
						<label><input type="checkbox" name="IntroOOP" value="IntroOOP"/>Intro to Object-Oriented Programming</label><br />
						<label><input type="checkbox" name="Programming" value="Programming"/>Programming</label><br />
						<p>English</p>
						<label><input type="checkbox" name="English1" value="English1"/>English I</label><br />
						<label><input type="checkbox" name="English2" value="English2"/>English II</label><br />
						<label><input type="checkbox" name="English3" value="English3"/>English III</label><br />
						<p>History &amp; Social Studies</p>
						<label><input type="checkbox" name="AP_EuropeanHistory" value="AP_EuropeanHistory"/>AP European History</label><br />
						
						<label><input type="checkbox" name="AP_WorldHistory" value="AP_WorldHistory"/>AP World History</label><br />
							<label><input type="checkbox" name="WorldHistory1" value="WorldHistory1"/>World History I</label><br />
							<label><input type="checkbox" name="WorldHistory2" value="WorldHistory2"/>World History II</label><br />
						<label><input type="checkbox" name="AP_Microeconomics" value="AP_Microeconomics"/>AP Microeconomics</label><br />
						<label><input type="checkbox" name="AP_Psychology" value="AP_Psychology"/>AP Psychology</label><br />
						<p>Mathematics</p>
						<label><input type="checkbox" name="AlgebraI" value="AlgebraI"/>Algebra I</label><br />
						<label><input type="checkbox" name="AlgebraII" value="AlgebraII"/>Algebra II</label><br />
						<label><input type="checkbox" name="Geometry" value="Geometry"/>Geometry</label><br />
						<label><input type="checkbox" name="AP_Calculus" value="AP_Calculus"/>AP Calculus</label><br />
						<label><input type="checkbox" name="PreCalculus" value="PreCalculus"/>Pre-Calculus</label><br />
						<p>Modern &amp; Classical Languages</p>
						<label><input type="checkbox" name="AP_French" value="AP_French"/>AP French</label><br />
						<label><input type="checkbox" name="AP_Japanese" value="AP_Japanese"/>AP Japanese</label><br />
						<label><input type="checkbox" name="AP_Latin" value="AP_Latin"/>AP Latin</label><br />
						<label><input type="checkbox" name="AP_Spanish" value="AP_Spanish"/>AP Spanish</label><br />
						<label><input type="checkbox" name="French1" value="French1"/>French I</label><br />
						<label><input type="checkbox" name="French2" value="French2"/>French II</label><br />
						<label><input type="checkbox" name="French3" value="French3"/>French III</label><br />
						<label><input type="checkbox" name="French4" value="French4"/>French IV</label><br />
						<label><input type="checkbox" name="Japanese1" value="Japanese1"/>Japanese I</label><br />
						<label><input type="checkbox" name="Japanese2" value="Japanese2"/>Japanese II</label><br />
						<label><input type="checkbox" name="Japanese3" value="Japanese3"/>Japanese III</label><br />
						<label><input type="checkbox" name="Japanese4" value="Japanese4"/>Japanese IV</label><br />
						<label><input type="checkbox" name="Latin1" value="Latin1"/>Latin I</label><br />
						<label><input type="checkbox" name="Latin2" value="Latin2"/>Latin II</label><br />
						<label><input type="checkbox" name="Latin3" value="Latin3"/>Latin III</label><br />
						<label><input type="checkbox" name="Latin4" value="Latin4"/>Latin IV</label><br />
						<label><input type="checkbox" name="Spanish1" value="Spanish1"/>Spanish I</label><br />
						<label><input type="checkbox" name="Spanish2" value="Spanish2"/>Spanish II</label><br />
						<label><input type="checkbox" name="Spanish3" value="Spanish3"/>Spanish III</label><br />
						<label><input type="checkbox" name="Spanish4" value="Spanish4"/>Spanish IV</label><br />
						<label><input type="checkbox" name="Mandarin1" value="Mandarin1"/>Mandarin I</label><br />
						<label><input type="checkbox" name="Mandarin2" value="Mandarin2"/>Mandarin II</label><br />
						<label><input type="checkbox" name="Mandarin3" value="Mandarin3"/>Mandarin III</label><br />
						<label><input type="checkbox" name="Mandarin4" value="Mandarin4"/>Mandarin IV</label><br />
						<p>Science</p>
												<label><input type="checkbox" name="AP_Physics" value="AP_Physics"/>AP Physics</label><br />
						<label><input type="checkbox" name="Physics" value="Physics"/>Physics</label><br />
						<label><input type="checkbox" name="AP_Chem" value="AP_Chem"/>AP Chemistry</label><br />
						<label><input type="checkbox" name="Chem" value="Chem"/>Chemistry</label><br />
			    </p>
			
					<fieldset>
					<input name="activate" type="submit" class="login" value="activate" />
					</fieldset>
				</form>
				
				<p id="login_footer">Created by Abhinav Khanna &amp; Devin Nguyen<br /><br />If you have any questions or comments, please e-mail <span style="color:#CCCCCC;">12devinn@students.harker.org</span> </p>
			</div><!--#logform-->
		</div><!--#login-->
	</div> <!--#mainContainer-->
	<script type="text/javascript">
	var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
	document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
	</script>
	<script type="text/javascript">
	try {
	var pageTracker = _gat._getTracker("UA-11942470-1");
	pageTracker._trackPageview();
	} catch(err) {}</script>
</body>
</html>
