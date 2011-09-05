<?PHP
include("../includes/ConnectMysql.php");
include("../includes/variables.php");
session_start();
if(!(isset($_SESSION['admin']))) {
	header("Location: http://cytopic.net/semester2finals/login.php");
	exit;
}
// variables
// this.php variables below

	// variables for the text fields
	$class = $_POST['subject'];
	$description = $_POST['description'];
	$name = $_POST['name'];
	$target_path = "../uploads/";
	$ext = pathinfo($_FILES['uploadedfile']['name']);
	$pathinfo = $ext['extension'];
	$new_file_name = $name."_".$class."_".$description.".".$pathinfo;	
	$target_path = $target_path.$new_file_name;
	
	// array variables....that should be in variables.php
	$myArray = array("doc", "docx", "ppt", "pptx", "pages", "key", "pdf");
	foreach($myArray as $v){
		if($pathinfo == $v) {
			$other_true = true;
		}
	}
	$class_array = array("ATCS_ComputerArchitecture", "AT_ProgrammingLanguages", "AP_CSDS", "AP_CSA", "DigitalWorld", "IntroOOP", "Programming", "English1", "English2", "AP_ArtHistory", "AP_EuropeanHistory", "AP_Microeconomics", "AP_Psychology", "WorldHistory1", "WorldHistory2", "AlgebraI", "AlgebraII", "AP_Calculus", "Geometry", "PreCalculus", "AP_French", "AP_Japanese", "AP_Latin", "AP_Spanish", "French1", "French2", "French3", "French4", "Japanese1", "Japanese2", "Japanese3", "Japanese4", "Latin1", "Latin2", "Latin3", "Latin4", "Physics", "AP_Chem", "Chem", "AP_Physics", "AP_WorldHistory");

	
	

//check if the form has been set
if(isset($_POST['upload'])) {
	//check if the description and option fields have been set
	if(isset($_POST['description'])) {
		if(isset($_POST['name'])) {
			if(isset($_POST['subject'])) {
				if($other_true == true) {
					if(move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $target_path)) {
						changePermissions($target_path,999);
						$query = "INSERT INTO classes (username, class, fileName, description) VALUES ('$name', '$class', '$new_file_name', '$description')";
						mysql_query($query);
					}
				}
			}
		}
	}
}



?>
<html>
<head>
	<title> </title>
</head>
<body>
	<form id="uploadForm" name="uploadForm" method="post" action="" enctype="multipart/form-data">
	    <p>
	        <label for="username" class="adminloginlabel">Teacher Name:</label>
	        <input type="text" name="name">
	    </p>
	    <p>
	        <label for="textfield" class="adminloginlabel">Subject:</label>
			  <select name="subject" id="subject" />
					<?PHP for($i = 0; $i < 50; $i++) { $end_result = $class_array[$i]; if($end_result == ""){} else{echo "<option value=\"$end_result\">$end_result</option>";} }  ?>
				</select>		<p></p>
			Description:<input type="text" name="description">
	    </p>
		<p>
			Upload File:<input type="file" name="uploadedfile">
	
	    <p>
	        <input name="upload" type="submit" id="login" value="upload" />
	    </p>
	</form>
	<?PHP echo "<a href=\"index.php\">Wanna view it</a>";?>
</body>
</html>