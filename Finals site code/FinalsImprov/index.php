<?PHP
session_start();
include("includes/ConnectMysql.php");
include("includes/variables.php");

if(isset($_SESSION['logged_in'])) {

}
else {
	header("Location: http://finals.cytopic.net/login.php");
	exit;
}

if(isset($_SESSION['admin'])) {
	header("Location: http://finals.cytopic.net/admin/index.php");
}

$class_array = array("ATCS_ComputerArchitecture", "AT_ProgrammingLanguages", "AP_CSDS", "AP_CSA", "DigitalWorld", "IntroOOP", "Programming", "English1", "English2", "AP_ArtHistory", "AP_EuropeanHistory", "AP_Microeconomics", "AP_Psychology", "WorldHistory1", "WorldHistory2", "AlgebraI", "AlgebraII", "AP_Calculus", "Geometry", "PreCalculus", "AP_French", "AP_Japanese", "AP_Latin", "AP_Spanish", "French1", "French2", "French3", "French4", "Japanese1", "Japanese2", "Japanese3", "Japanese4", "Latin1", "Latin2", "Latin3", "Latin4", "Physics", "AP_Chem", "Chem", "AP_Physics", "AP_WorldHistory", "Mandarin1", "Mandarin2", "Mandarin3", "Mandarin4");

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Finals Study Guides - Display</title>
	<meta name="author" content="Abhinav Khanna and Devin Nguyen" />
	<link rel="stylesheet" href="css/style.css" type="text/css" media="screen" />	
</head>
<body onload="document.loginForm.username.focus();">
	<div id="floater"></div>
	<div id="mainContainer">
		<div id="login">
			<div id="upload-display">
				<ul>
					<li><a href="upload.php">Upload</a></li>
					<li>|</li>
					<li><strong>Display</strong></li>
				</ul>
			</div>
			<div id="display-list">
<?php
for($i = 0; $i < 50; $i++) {
	$end_result = $explode_user[$i];
	foreach($class_array as $v) {
		if($end_result == $v) {
			$query0 = mysql_query("SELECT * FROM classes WHERE class = '$v'");
			while($rows = mysql_fetch_array($query0)) {
				$data = $rows['fileName'];
				$data2 = $rows['username'];
				echo "				";
				echo "<p><a href=\"uploads/$data\">$data</a>";
				echo '<span class="upload_name">';
				echo "- uploaded by $data2</span></p>\r\n";
			}
		}
	}
}
?>
				<form id="logoutForm" name="logoutForm" method="post" action="">
					<input name="logout" type="submit" id="logout" value="Log out" />
				</form>
				<p id="login_footer">
					Created by Abhinav Khanna &amp; Devin Nguyen<br /><br />
					If you have any questions or comments, please e-mail <span style="color:#CCCCCC;">12devinn@students.harker.org</span>
				</p>
			</div><!--#display-list-->
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
