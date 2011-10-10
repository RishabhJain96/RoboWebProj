<?php
//session_start();
/*if (isset($_SESSION['authenticated'])) {
  header('Location: http://finals.cytopic.net/admins/display.php');
  exit;
  }*/
?>
<?php
include("ConnectMysql.php");
session_start();
if (!(isset($_SESSION['loser']))) {
  header('Location: http://finals.cytopic.net/NewIdTesting/login.php');
  exit;
  }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
   "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
	<link rel="stylesheet" type="text/css" href="../css/style.css" />
	<title>Finals Study Guides - Home</title>
</head>
<body>

<div class="center">
	<div id="page">
		<?php include ("../includes/nav.inc.php"); ?>

		<?php
			$username = $_SESSION['username'];
			$q = mysql_query("SELECT * FROM notes WHERE username = '$username'");
			$result = mysql_fetch_assoc($q);
			$data = $result['english'];
			if(!(empty($data))) {
				if (isset($_GET['page'])) { 
					$page  = $_GET['page']; 
					} else { 
						$page = 1;
						}; 
				$start_from = ($page-1) * 5; 
				$q3 = mysql_query("SELECT * FROM tester WHERE class = 'english' LIMIT $start_from, 5");
					while($row = mysql_fetch_assoc($q3)) {
					$data2 = $row['notes'];
					echo "<a href='../uploads/$data2' class=\"output_list\">$data2</a><br >";
				}
				$count_result = mysql_query("SELECT COUNT(class) FROM tester WHERE class = 'english'");
				$row_count = mysql_fetch_row($count_result);
				$total_records = $row_count[0];
				$total_pages = ceil($total_records / 5);
				for ($i=1; $i<=$total_pages; $i++) { 
					echo "<a href=\"index.php?page=$i\">$i</a>"; 
				}; 
				
			}else {
				echo "you still need to enroll in English";
			};
			?>
			<p></p>
			<?php
			$q2 = mysql_query("SELECT * FROM notes WHERE username = '$username'");
			$result2 = mysql_fetch_assoc($q2);
			$data3 = $result2['spanish'];
			if(!(empty($data3))) {
					while($row = mysql_fetch_assoc($q)) {
					$data4 = $row['spanish'];
					echo "<a href='uploads/$data4' class=\"output_list\">$data4</a><br >";
				}
			}else {
				echo "Shoot you still need to enroll";
			};
		?>
		
			
	<?php include("../includes/footer.inc.php"); ?>
	
		<div id="validation">
		<p>
			<a href="http://jigsaw.w3.org/css-validator/">
				<img style="border:0;width:88px;height:31px;border-style:none;"
		 		src="images/icons/css.png"
	     		alt="Valid CSS!" 
				/>
			</a>
			<a href="http://validator.w3.org/check?uri=referer">
				<img style="border:0;width:88px;height:31px;border-style:none;"
		 		src="images/icons/xhtml.png"
	     		alt="Valid XHTML 1.0 Strict" 
				/>
	 		</a>
	 	</p>
		<a href="http://www.apple.com/" class="mac"><img style="border:0;border-style:none;" src="images/madeonmac.png" alt="Made on a Mac!" /></a>
		</div>
	</div>
</div>
</body>
</html>