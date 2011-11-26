<?php
include("config.php");
include("functions.php");
include("head.php");
?>
<BR>
[ <A href="admin_cat.php">Add category</A> ]
[ <A href="admin_task.php">Add task</A> ]
<?php
if(isset($_POST["h_add_cat"])) {// if this was directed from a form (if the submit value exists) then continue on

	$error="";
	/*Get Values from the Form Currently found in Admin.php*/
	$addtype=$_POST["addtype"];
	$tasklist=$_POST["tasklist"];
	$category=$_POST["category"];
	$subcategory=$_POST["subcategory"];
	
	//Validate the information based on what is being added, this needs to be improved some but can wait until after the first release is done
	switch($addtype) {
		case 'tasklist': //If we are adding a tasklist make sure there is a value for tasklist, and put both Category and Sub category to "-"
			if ($tasklist=="") {
				$error.="You must enter a value for the tasklist";
			}
			$category="-";
			$subcategory="-";
			break;
		case 'category'://If we are adding a Category, make sure there are values for tasklist and Category, also put subcategory to "-"
			if($tasklist=="") {
				$error.="You must enter a value for the tasklist<BR>";
			}
			if($category=="") {
				$error.="You must enter a value for the category";
			}
			$subcategory="-";
			break;
		
		case 'subcategory': //If we are adding a Sub-Category make sure there is a value for fields
			if($tasklist=="") {
				$error.="You must enter a value for the tasklist<BR>";
			}
			if($category=="") {
				$error.="You must enter a value for the category<BR>";
			}
			if($subcategory=="") {
				$error.="You must enter a value for the subcategory";
			}
			break;
		default:
			$error.="This Script has encountered an unexpected problem"; // if for somereason a valid option wasn't selected, error out
			break;
	}
	//checking not to enter duplicate data...
	$result=mysql_query("SELECT * FROM $configtablename WHERE tasklist='$tasklist' AND category='$category' AND subcategory='$subcategory'");
	if($error=="" && mysql_num_rows($result)!=0) {
		$error="Duplicated data";
	}
	if($error!="") {// if there is an error display the error, if not commit the entry to the database
		echo("<DIV align=\"center\" class=\"error\">$error</DIV>");
	} else {
		mysql_query("INSERT INTO $configtablename ( tasklist, category, subcategory) VALUES('".$tasklist."', '".$category."','".$subcategory."')", $link);
		echo("<DIV align=\"center\" class=\"ok\">Entry Added Successfully.</DIV>");
		include("foot.php");
		exit();
	}
}
?>
<!--This is the form to add the Category, this needs to be re-worked, not sure how yet though for now this is good enough, will be something to fix in near future though-->
<FORM action="admin_cat.php" method="post">
<TABLE align="center" cellspacing="5">
<TR>
	<TH colspan="2">Add category</TH></TR>
<TR>
	<TD>Type:</TD>
	<TD><SELECT name="addtype">
	<OPTION selected value="tasklist">TaskList
	<OPTION value="category">Category
	<OPTION value="subcategory">Sub-Category
	</SELECT></TD></TR>
<TR>
	<TD>TaskList</TD><TD><input type="text" size="50" maxlength="50" name="tasklist"></TD></TR>
<TR>
	<TD>Category</TD><TD><input type="text" size="50" maxlength="50" name="category"></TD></TR>
<TR>
	<TD>Sub-Category</TD><TD><input type="text" size="50" maxlength="50" name="subcategory"></TD></TR>
<TR>
	<TD align="center" colspan="2"><INPUT type="reset" name="reset" value="Clear"> <INPUT type="submit" name="submit" value="Send"></TD></TR>
</TABLE>
<INPUT type="hidden" name="h_add_cat">
</FORM>
<?php
include("foot.php");
?>
