<?PHP
//start the session
session_start();
//include the include files
include("../includes/ConnectMysql.php");
include("../includes/variables.php");
//check if there is a cookie;
if(isset($_SESSION['admin'])) {

}
else {
	header("Location: http://finals.cytopic.net/login.php");
	exit;
}
//variables that should go in variables.php
$delete = $_POST['delete_option'];
$address = "../uploads/";
$myfile = $address.$delete;
//if the form is set complete the delete.
if($_POST['delete_select']) {
	$query = "DELETE FROM classes WHERE fileName = '$delete'";
	if(mysql_query($query)) {
		unlink($myfile);
	} else { echo "$delete";}

}

//this is the path to the directory
$path_to_includes = "../uploads/";

// this opens the directory so it can be read
$dir_handle = @opendir($path_to_includes) or die("Unable to open $path_to_includes");

// this stores the variable $files as an array;
$files = array();

?>

<html>
<head>
	<title></title>
</head>
<body>
	<?PHP
while ($file = readdir($dir_handle))
{
	if (($file == ".") OR ($file == "..")) {

	} else {
		$files[] = "$file";
		echo "<div align=\"center\">
			<table width=\"560\" border=\"0\">
			<tr>
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

	?>
</body>
</html>