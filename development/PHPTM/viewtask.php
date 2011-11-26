<?php
include("config.php");
include("functions.php");
include("head.php");
//this page is used to show a nice table of all the information about an individual task.
//needs to be checked for private settings if is private require login to view if not display it, also needs to validate person is logged in is on the assigned list and has permission to view it.

$result = mysql_query("SELECT * FROM $tasktablename WHERE indexID = '".$_GET['ta']."'", $link);
$row = mysql_fetch_object($result);
echo("<br />");

echo("<a href=\"taskmgr.php\">".$pagetitle."</a> \n");
if($row->tasklist != "") {
	echo("=> <a href=\"taskmgr.php?tl=".$row->tasklist."\">".$row->tasklist."</a> \n");
}
if($row->category != "none") {
	echo("=> <a href=\"taskmgr.php?tl=".$row->tasklist."&ca=".$row->category."\">".$row->category."</a> \n");
}
if($row->subcategory != "none") {
	echo("=> <a href=\"taskmgr.php?tl=".$row->tasklist."&ca=".$row->category."&su=".$row->subcategory."\">".$row->subcategory."</a> \n");
}

echo("<br />\n[ <a href=\"modify.php?ta=".$_GET['ta']."\">Modify task</a> ]\n");
echo("<table align=center border=\"0\" cellspacing=\"2\" cellpadding=\"5\">\n");
echo(" <tr>\n");
echo("  <th colspan=\"5\">Task details</th>\n");
echo(" </tr>\n");
echo(" <tr class=light>\n");

list($assigned,$complete,$due_date, $created_date,$modified_date, $assigned_date)=determineOutput($row->assigned_group,$row->assigned_user,$row->complete,$row->due_date, $row->created_date, $row->modified_date, $row->assigned_date);
$flags=determineFlags($row->created_date,$row->due_date,$days_new,$warning_hours,$row->complete);		

echo("  <td><b>Task #:</b><br />".$row->indexID."</td>\n");
echo("  <td colspan=\"3\"><b>Task Name:</b><br />".$row->task);
if($row->shortnote != "") {
	echo(" :: ".$row->shortnote);
}
echo("</td>\n");

echo("<td>".$complete."</td>\n");

echo(" </tr>\n");

echo(" <tr class=dark>\n");
echo("  <td><b>Date Created:</b><br />".$created_date."</td> \n");
echo("  <td><b>Created by:</b><br />".$row->created_by."</td>\n");
echo("  <td><b>Date Last Modified:</b><br />".$modified_date."</td>\n");
echo("  <td><b>Due Date:</b><br />".$due_date."</td> \n");
echo("  <td><b>Priority:</b><br />".$row->priority."</td> \n");
echo(" </tr> \n");

echo(" <tr class=light>\n");
echo("  <td colspan=\"3\"><b>Assigned To:</b><br />".$assigned."</td>\n");
echo("  <td><b>Assigned Date:</b><br />".$assigned_date."</td> \n");
echo("  <td><b>Assigned by:</b><br />".$assigned_by."</td> \n");

echo(" </tr>\n");
echo(" <tr class=dark>\n");
echo("  <td colspan=\"5\"><b>Description:</b><br />".$row->description."</td>\n");
echo(" </tr>\n");
echo("</table>");

mysql_free_result($result);
include("foot.php");
?>