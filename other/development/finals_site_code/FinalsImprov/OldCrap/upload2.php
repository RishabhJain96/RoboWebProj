
<?php
session_start();
include("includes/ConnectMysql.php");
$name = $_SESSION['logged_in'];
if(!(isset($_SESSION['logged_in']))) {
	header("Location: http://cytopic.net/semester2finals/login.php");
}


 //list of variables to use later
$dbhost = "mysql";
$dbuser = "yroot";
$dbpass = "cytopic";
$teacher = $_POST['Teacher'];
$class = $_POST['Class'];
$subject = $_POST['subject'];
$file = pathinfo($_FILES['uploadedfile']['name']);
$pathinfo = $file['extension'];

//back to the real code!
$upload = $teacher.$class.'_'.$name.'_'.$subject.'.'.$pathinfo;
$uploadsuccess = false;
$upload_fail = false;
$teacher_vd = false;
$subject_vd = false;
$class_vd = false;
$name_vd = false;

//connection to mysql database
$conn = mysql_connect($dbhost, $dbuser, $dbpass) or die ('Error connecting to mysql');

$dbname = 'test';
mysql_select_db($dbname);


//the upload stuff
$target_path = "uploads/";
$new_file_name = $teacher.$class.'_'.$name.'_'.$subject.'.'.$pathinfo;
$target_path = $target_path . $new_file_name;

//changing the permission of the file
function changePermissions($path,$modlevel){
 
	chmod($path,$modlevel); 
 
	if(chmod){
 
	echo "";
 
	} else {
 
		echo "";
	}
}

if ($_POST['everything']) {
	$post_teacher = $_POST['Teacher'];
	$post_class = $_POST['Class'];
	$post_subject = $_POST['subject'];
	if (!($_POST['Teacher'])) {
		$teacher_vd = true; 
	}
	if (!($_POST['subject'])) {
		$subject_vd = true;
	}
	if (!($_POST['Class'])) {
		$class_vd = true;
	}
	if (!(($pathinfo == 'doc') OR ($pathinfo == 'docx') OR ($pathinfo == 'ppt') OR ($pathinfo == 'pps') OR ($pathinfo == 'pptx') OR ($pathinfo == 'pages') OR ($pathinfo == 'numbers') OR ($pathinfo == 'key') OR ($pathinfo == 'txt') OR ($pathinfo == 'rtf') OR ($pathinfo == 'pdf') OR ($pathinfo == 'abw') OR ($pathinfo == 'xls') OR ($pathinfo == 'swx') OR ($pathinfo == 'obt'))) {
		$upload_fail = true;
		}elseif ($_POST['Teacher'] & $_POST['Class'] & $_POST['subject']) {
			if((($pathinfo == 'doc') OR ($pathinfo == 'docx') OR ($pathinfo == 'ppt') OR ($pathinfo == 'pps') OR ($pathinfo == 'pptx') OR ($pathinfo == 'pages') OR ($pathinfo == 'numbers') OR ($pathinfo == 'key') OR ($pathinfo == 'txt') OR ($pathinfo == 'rtf') OR ($pathinfo == 'pdf') OR ($pathinfo == 'abw') OR ($pathinfo == 'xls') OR ($pathinfo == 'swx') OR ($pathinfo == 'obt'))){
				if(move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $target_path)) {
					changePermissions($target_path,999);
					$uploadsuccess = true;
					
					$array1=mysql_query("SELECT * FROM finals WHERE username = '$name'");
					$array2 = mysql_fetch_array($array1);
					$result = $array2["files"];

					$query = "UPDATE finals SET files = '$result $new_file_name,' WHERE username = '$name'";
					mysql_query($query) or die('Error, insert query failed');

					$query = "FLUSH PRIVILEGES";
					mysql_query($query) or die('Error, insert query failed');

					mysql_close($conn); 
				}
			}
		}
	}
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
	<title>Finals Study Guides - Upload</title>
	<script type="text/javascript" src="js/list.js"></script>
</head>
<body onload="fillClass();">
<script type="text/javascript" src="list.js"></script>
<div class="center">
	<div id="page">
		<?php include ("includes/nav.inc.php"); ?>

		<div id="mainContent" class="center">
			<p id="mainContentIntro">
				Upload Study Guides!
			</p>
				<p id="mainContent_" class="contentText">
					<p class="uploadsuccess">
					<?php	if($uploadsuccess == 1) {
							echo "Your file: ". $new_file_name. " has been uploaded."; 
						}	
					?>
					</p>
					<FORM name="drop_list" method="POST" enctype="multipart/form-data" action="" id="upload_form">
						<p class="attr_name" id="class_name_attr">Class Name:</p>
							<SELECT  NAME="Class" onChange="SelectTeacher();">
								<Option value="<?PHP if(($_POST['everything']) & ($_POST['Teacher']) & ($_POST['Class'])) {echo $post_class;} ?>"><?PHP if(($_POST['everything']) & ($_POST['Teacher']) & ($_POST['Class'])) {echo $post_class;}else {echo 'Class';} ?></option>
							</SELECT>
							<?PHP
								if ($class_vd == 1) {
									echo "<span class=\"uploadfail\">Please select your class.</span>";
								}
								?>
					<div id="tna">	<p class="attr_name" id="teacher_name_attr">Teacher Name:</p>
							<SELECT id="Teacher" NAME="Teacher">
								<Option value="<?PHP if(($_POST['everything']) & $_POST['Teacher']) {echo $post_teacher;}else {echo '';} ?>"><?PHP if(($_POST['everything']) & $_POST['Teacher']) {echo $post_teacher;}else {echo 'Teacher';} ?></option>
							</SELECT>	
										<?PHP
										if($teacher_vd == 1) {
											echo "<span class=\"uploadfail\">Please select your teacher.</span>";
										}
										?>
					</div>
					<div id="fna">
					<p class="attr_name" id="file_name_attr"><br >Please choose a file:</p>	
							<INPUT type="file" name="uploadedfile" id="file_input_attr" value=\"<?PHP if ($_POST['everything'] == true) {echo $_FILE['uploadedfile'];}?>\" ">
								<?PHP
									if($upload_fail == 1) {
										echo "<span class=\"uploadfail_file\">Please select a valid file.</span>";
									}
									?>
					</div>
						<p class="attr_name" id="your_name">Your Name <span class="eg">(FirstLast)</span>:</p>
							<?PHP
							 echo "$name";
							?>
							<br >
						<p class="attr_name" id="your_suj">Subject/Description:</p>
							<input name="subject" type="text" value="General" id="suj_attr">
							<br ><span class="eg">(General, Vocab, Grammar, Chapter #s, etc...)</span>
						
						<br ><br >
						<div id="uploadsubmitbutton">
						<input type="submit" name="everything" class="uploadsubmit" value="Submit">
						</div>
					</form>
				<p class="javascript">Note: Javascript must be enabled to use this page!</p>
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