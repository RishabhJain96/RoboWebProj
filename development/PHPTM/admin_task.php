<?php
include("config.php");
include("functions.php");
include("head.php");

?>
<BR>
[ <A href="admin_cat.php">Add category</A> ]
[ <A href="admin_task.php">Add task</A> ]
<?php
if(isset($_POST["h_add_task"])) {// if this was directed from a form (if the submit value exists) then continue on

	$_SESSION['loginusername']="Drummerman"; //This is just a place holder once the admin login is requires this will be removed, or made equal to admin login name

	$timenow = time();
	$error="";
	/*PRE DEFINE DEFAULT VALUES FOR ALL VARIABLES*/
//	$indexID="NULL";  //This value is no longer added in the SQL add, it is a default auto-increment calue
	$tasklist=$_REQUEST["tasklist"];
	$category=$_REQUEST["category"];
	$subcategory=$_REQUEST["subcategory"];
	$task=$_REQUEST["task"];
	$shortnote=$_REQUEST["shortnote"];
	$description=$_REQUEST["description"];
	$assigned_group=$_REQUEST["assigned_group"];
	$assigned_user=$_REQUEST["assigned_user"];
	$assigned_by="none";
	$assigned_date="none";
	$created_by=$_SESSION['loginusername'];
	$created_date=$timenow;

	$due_year=$_REQUEST["due_year"];
	$due_month=$_REQUEST["due_month"];
	$due_day=$_REQUEST["due_day"];
	$due_hour=$_REQUEST["due_hour"];
	$due_minute=$_REQUEST["due_minute"];
	if($due_year=="") {
		$due_date="00000000000000";
	} else {
		if(!isCorrectDate($due_year, $due_month, $due_day, $due_hour, $due_minute)) {
			$error="Enter correct due date";
		} else {
			$due_date=mktime($due_hour,$due_minute,"00",$due_month,$due_day,$due_year);
		}
	}
	
	if(isset($_REQUEST["private"])) {
		$privacy="y";
	} else {
		$privacy="n";
	}
	if(isset($_REQUEST["hidden"])) {
		$hidden="y";
	} else {
		$hidden="n";
	}
	$priority=$_REQUEST["priority"];
	$complete="0";
	$modified_date="00000000000000";
	
	/*VALIDATE & MANIPULATE USER PROVIDED VALUES
	--Due Date needs to be checked to ensure it is not due yesterday type thing, maybe add a warning if it is due in less then XX hours
	--Tasklist/Category/subcategory are choosen from drop down boxes on entry form, the shouldn't need to be validated, maybe in the future, see how much it effects load times on large tables...
	--Prioirty needs to be validated to make sure it is within the determined range for priority (this isn't done yet, not sure if it will be a set number, or variable number...
	--shortnote and description should probably be stripped of HTML tags before being committed...possibly allow certain tags to remain (i.e. bold, links, etc.  not important yet though)

	*/
	if($tasklist=="") {
		$error="You must enter a task list for all items";
	}
	if($category=="") {
		$category="none";
	}
	if($subcategory=="") {
		$subcategory="none";
	}
	if(($subcategory != "none") && ($category =="none")) {
		$error="You can not have a Subcategory without a Category";
	}
	if(trim($task)=="") {
		$error="You must enter a task name";
	}
	if($assigned_group=="") {
		$assigned_group="none";
	}
	if($assigned_user=="") {
		$assigned_user="none";
	}
	if(($assigned_group != "none") ||($assigned_user != "none")) {
		$assigned_by=$_SESSION['loginusername'];
		$assigned_date=$timenow;
	}
	//checking not to enter duplicate data...
	$result=mysql_query("SELECT * FROM $tasktablename WHERE tasklist='$tasklist' AND category='$category' AND subcategory='$subcategory' AND task='$task'");
	if($error=="" && mysql_num_rows($result)!=0){
		$error="Duplicated data";
	}
	
	if($error != "") { //if there was an error display it
		echo("<DIV align=\"center\" class=\"error\">$error</DIV>");
	}else { // if there is no error commit the data to the table
		mysql_query("INSERT INTO $tasktablename (tasklist, category, subcategory, task, shortnote, description, assigned_group, assigned_user, assigned_by, assigned_date, created_by, created_date, due_date, private, hidden, priority, complete, modified_date) VALUES('$tasklist', '$category','$subcategory', '$task', '$shortnote', '$description', '$assigned_group', '$assigned_user', '$assigned_by', '$assigned_date', '$created_by', '$created_date', '$due_date', '$privacy', '$hidden', '$priority', '$complete', '$modified_date')", $link);
		echo("<DIV align=\"center\" class=\"ok\">Entry Added Successfully.</DIV>");
		include("foot.php");
		exit();
	}
}
?>
<!--This is the Table to Add a Task-->
<FORM action="admin_task.php" method="post">
<TABLE align="center" cellspacing="5">
<TR>
	<TH colspan="2">Add task</TH></TR>
<!--This section will read all the available tasklists from the configuration table and display them in a dropdown box-->
<TR>
	<TD>TaskList: <FONT color="red">*</FONT></TD>
	<TD><SELECT name="tasklist">
		<?php
		$result= mysql_query("SELECT DISTINCT tasklist FROM $configtablename", $link);
		while ($row = mysql_fetch_object($result)) {
			echo("<OPTION value=\"$row->tasklist\">$row->tasklist");
		}
		mysql_free_result($result);
		?>
		</SELECT></TD></TR>
<!--This section will read all the available Categories from the configuration table and display them in a dropdown box, needs to be altered so that it only shows the values available based on the tasklist selected in box 1.  At this time I am thinking about doing it java based so all the values would have to be pre-loaded into a java script, might have to find another way as that could lead to really bad loadtimes on large tables...would like to start it developed this way though, if it turns out bad on large tables maybe make it an option in the configuration-->
<TR>
	<TD>Category:</TD>
	<TD><SELECT name="category">
		<OPTION selected value="">--- none ---
		<?php
		$result= mysql_query("SELECT DISTINCT category FROM $configtablename", $link);
		while ($row = mysql_fetch_object($result)) {
			if($row->category != "-") {
				echo("<OPTION value=\"$row->category\">$row->category");
			}
		}
		mysql_free_result($result);
		?>
		</SELECT></TD></TR>
<!--This section will read all the available Sub-Categories from the configuration table and display them in a dropdown box, needs to be altered so that it only shows the values available based on the tasklist selected in box 1 and the Category Selected in Box 2...see last comment for ideas on how-->
<TR>
	<TD>Sub-Category:</TD>
	<TD><SELECT name="subcategory">
		<OPTION selected value="">--- none ---
		<?php
		$result= mysql_query("SELECT DISTINCT subcategory FROM $configtablename", $link);
		while ($row = mysql_fetch_object($result)) {
			if($row->subcategory != "-") {
				echo("<OPTION value=\"$row->subcategory\">$row->subcategory");
			}
		}
		mysql_free_result($result);
		?>
		</SELECT></TD></TR>

<!--Rest of the table is pretty straight forward-->
<TR>
	<TD>Taskname: <FONT color="red">*</FONT></TD>
	<TD><INPUT type="text" size="50" maxlength="50" name="task"></TD></TR>
<TR>
	<TD>Short Description:</TD>
	<TD><INPUT type="text" size="50" maxlength="75" name="shortnote"></TD></TR>
<TR>
	<TD colspan="2">Long Description:<BR><TEXTAREA name="description" rows="4" cols="55"></TEXTAREA></TD></TR>
<TR>
	<TD>Assign to Group:</TD>
	<TD><INPUT type="text" size="50" maxlength="255" name="assigned_group"></TD></TR>
<TR>
	<TD>Assign to User:</TD>
	<TD><INPUT type="text" size="50" maxlength="255" name="assigned_user"></TD></TR>
<TR>
	<TD>Due Date:<BR>Y-M-D hh:mm:ss</TD>
	<TD><SELECT name="due_year">
		<OPTION value="">----
		<?php
		$t_year=date("Y");
		for($i=$t_year; $i<=$t_year+10; $i++) {
			echo("<OPTION value=\"$i\">$i");
		}
		?>
		</SELECT> -
		<SELECT name="due_month">
		<?php
		for($i=1; $i<=9; $i++) {
			echo "<OPTION value=\"0$i\">0$i";
		}
		for($i=10; $i<=12; $i++) {
			echo "<OPTION value=\"$i\">$i";
		}
		?>
		</SELECT> -
		<SELECT name="due_day">
		<?php
		for($i=1; $i<=9; $i++) {
			echo "<OPTION value=\"0$i\">0$i";
		}
		for($i=10; $i<=31; $i++) {
			echo "<OPTION value=\"$i\">$i";
		}
		?>
		</SELECT>&nbsp;
		<INPUT type="text" size="2" maxlength="2" name="due_hour" value="00"> :
		<INPUT type="text" size="2" maxlength="2" name="due_minute" value="00"> :
		<INPUT type="text" size="2" value="00" disabled></TD></TR>
<TR>
	<TD>Privacy Settings:</TD>
	<TD>Hidden: <INPUT type="checkbox" name="hidden" value="y"><BR>Private: <INPUT type="checkbox" name="private" value="y"></TD></TR>
<TR>
	<TD>Priority: </TD>
	<TD><INPUT type="text" size="2" maxlength="2" name="priority"></TD></TR>
<TR>
	<TD align="center" colspan="2"><INPUT type="reset" name="reset" value="Clear"> <INPUT type="submit" name="submit" value="Send"></TD></TR>
</TABLE>
<INPUT type="hidden" name="h_add_task">
</FORM>
<?php
include("foot.php");
?>
