<?php
session_start();
if (isset($_SESSION['authenticated'])) {
  header('Location: http://finals.cytopic.net/admins/display.php');
  exit;
  }
?>
<?php
session_start();
if (!(isset($_SESSION['loser']))) {
  header('Location: http://finals.cytopic.net/login.php');
  exit;
  }
?>
?>
<?php
	$dbhost = "mysql";
	$dbuser = "yroot";
	$dbpass = "cytopic";
	$conn = mysql_connect($dbhost, $dbuser, $dbpass) or die ('Error connecting to mysql');
	$dbname = 'test';
	mysql_select_db($dbname);
	$address = "../uploads/";
	$count_result = mysql_query("SELECT * FROM tester ORDER BY notes"); 
	$count_rows = mysql_num_rows($count_result);
mysql_close($conn);	
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
	"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8" >
	<link media="screen and (min-device-width: 481px)" rel="stylesheet" type="text/css" href="css/style.css" >
	<!--[if !IE]>-->
	<link media="only screen and (max=device-width: 480px)" rel="stylesheet" type="text/css" href="css/apple-mobile.css" >
	<!--<![endif]-->
	<title>Finals Study Guides - Display</title>
	<script type="text/javascript" src="js/list.js"></script>
	<style type="text/css">
	#mainContent {
		height: 2100px;
	}
	</style>
</head>
<body onload="fillClass();">
<script type="text/javascript" src="list.js"></script>
<div class="center">
	<div id="page">
		<?php include ("includes/nav.inc.php"); ?>

		<div id="mainContent" class="center">
			<p id="mainContentIntro">
				Display Study Guides
			</p>
			<p id="mainContent_" class="contentText">
				<?php
				$path_to_includes = "uploads/";

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
        					<td width=\"560\">
								<input type=\"hidden\" name=\"delete_option\" value=\"$file\"><a href='../uploads/$file' class=\"admintable\">$file</a> &nbsp
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
		</div>
	</div>
	
	<?php include("includes/footer.inc.php"); ?>
	
			<div id="validation">
			<p>
				<a href="http://jigsaw.w3.org/css-validator/">
					<img style="border:0;width:88px;height:31px;border-style:none;"
			 		src="images/icons/css.png"
		     		alt="Valid CSS!" 
					>
				</a>
				<a href="http://validator.w3.org/check?uri=referer">
				<img style="border:0;width:88px;height:31px;border-style:none;"
				   src="images/icons/html.png"
				   alt="Valid HTML 4.01 Transitional" height="31" width="88">
		 		</a>
		 	</p>
			<a href="http://www.apple.com/" class="mac"><img style="border:0;border-style:none;" src="images/madeonmac.png" alt="Made on a Mac!" ></a>
			</div>
		</div>
	</div>
	<script src="http://static.getclicky.com/97827.js" type="text/javascript"></script>
	</body>
</html>