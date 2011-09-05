<?php
session_start();
include("includes/ConnectMysql.php");
include("includes/variables.php");

if(isset($_SESSION['admin'])) {

}
else {
	header("Location: http://cytopic.net/semester2finals/login.php");
	exit;
}

	$delete = $_POST['delete_option'];
	$address = "../uploads/";
	$myfile = $address.$delete;
	if($_POST['delete_select']) {
		unlink($myfile);
		$query = "DELETE FROM tester WHERE notes='$delete'";
		mysql_query($query);
	}		
	$count_result = mysql_query("SELECT * FROM tester"); 
	$count_rows = mysql_num_rows($count_result);
mysql_close($conn);	
?>
<?php
session_start();
// if session variable not set, redirect to login page
if (!isset($_SESSION['authenticated'])) {
  header('Location: http://finals.cytopic.net/admins/login.php');
  exit;
  }
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
	"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8" >
	<link rel="stylesheet" type="text/css" href="../css/style.css" />
	<title>Admin Mode - Finals Study Guides - Admin Delete</title>
</head>
<body>

	<?php include("adminpagestuff.inc.php"); ?>
<div class="center">
	<div id="page">
		<div id="mainHeader" class="center">
			<p id="headerText">Finals Study Guides</p>
		</div>
		<div id="loginbutton"></div>

		<div id="mainNav" class="center">
				<div class="center">
					<ul class="center">
						<li><a href="index.php">Home</a></li>
						<li><a href="upload.php">Upload</a></li>
						<li><a href="display.php">Display</a></li>
						<li><a href="contact.php">Contact</a></li>
					</ul>
				</div>
		</div>
		<div id="mainContent_admin" class="center">
			<p id="mainContentIntro">
				Delete Files
			</p>
			<p id="mainContent_" class="contentText">
				<?php
				$path_to_includes = "../uploads/";

				$dir_handle = @opendir($path_to_includes) or die("Unable to open $path_to_includes");

				$files = array();
				
				if ($count_rows > 0) {
				echo "Total Number of Study Guides: $count_rows";
			} else {
				echo "";
			}
				
				if($count_rows == "0") {
					echo " No Study Guides have been uploaded.";
					} else {
						$counter = 0;
				while ($file = readdir($dir_handle))
				{
					$counter++;
				if (($file == ".") OR ($file == "..")) {

				} else {
					$files[] = "$file";
					if(is_float($counter/2)) {
						$class="odd";
					}else {$class = "even";}
					echo "<div align=\"center\">
					<table width=\"560\" border=\"0\" class=\"admindeletetable\">
   				   		<tr class=\"$class\">
								<form enctype=\"multipart/form-data\" method=\"POST\" action=\"\">
        					<td width=\"550\">
								<input type=\"hidden\" name=\"delete_option\" value=\"$file\"><a href='../uploads/$file' class=\"admintable\">$file</a> &nbsp
        					</td>
        					<td width=\"10\">
								<input type=\"submit\" name=\"delete_select\" value=\"Delete\" onclick=\"return confirm('Are you sure you want to delete $file?')\">
        					</td>
    					</tr>
								</form>
					</table>
							</div>";
					}
				}
				}
				 // end while loop
				?>
			</p>
		</div>
	</div>
	
<?php include("../includes/footer.inc.php"); ?>
		<div id="validation">
		<p>
			<a href="http://jigsaw.w3.org/css-validator/">
				<img style="border:0;width:88px;height:31px;border-style:none;"
		 		src="../images/icons/css.png"
	     		alt="Valid CSS!" 
				/>
			</a>
				<a href="http://validator.w3.org/check?uri=referer">
				<img style="border:0;width:88px;height:31px;border-style:none;"
				   src="../images/icons/html.png"
				   alt="Valid HTML 4.01 Transitional" height="31" width="88">
		 		</a>
	 	</p>
		<a href="http://www.apple.com/" class="mac"><img style="border:0;border-style:none;" src="../images/madeonmac.png" alt="Made on a Mac!" /></a>
		</div>
	</div>
</div>
<script src="http://static.getclicky.com/97827.js" type="text/javascript"></script>
</body>
</html>