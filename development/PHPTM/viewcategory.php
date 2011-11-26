<?php

echo("<br />&nbsp;&nbsp;<a href=\"taskmgr.php?tl=".$_GET['tl']."\">".rawurldecode($_GET['tl'])."</a> => ");
echo("<a href=\"taskmgr.php?tl=".$_GET['tl']."&ca=".$_GET['ca']."\">".rawurldecode($_GET['ca'])."</a>");
echo("<table width=\"100%\" cellspacing=\"2\" cellpadding=\"2\" border=\"0\">\n <tr>\n");
echo("   <th>Task</th>");
echo("   <th>Assigned To</th>");
echo("   <th>Priority</th>");
echo("   <th>Flags</th>");
echo("   <th>Status</th>");
echo("   <th>Due Date</th>");
echo("\n  </tr>\n");

//Show all items that are not in a category (category=none in SQL TABLE)

$result = mysql_query("SELECT * FROM $tasktablename WHERE tasklist = '".rawurldecode($_GET['tl'])."' AND category = '".rawurldecode($_GET['ca'])."'", $link);
while ($row = mysql_fetch_object($result)) {
	list($assigned,$complete,$due_date, $created_date,$modified_date, $assigned_date)=determineOutput($row->assigned_group,$row->assigned_user,$row->complete,$row->due_date, $row->created_date, $row->modified_date, $row->assigned_date);

	$flags=determineFlags($row->created_date,$row->due_date,$days_new,$warning_hours,$row->complete);
	echo ($i%2==0)?"<TR class=\"dark\">":"<TR class=\"light\">";
	echo("<td>&nbsp;&nbsp;&nbsp;<a href=\"viewtask.php?ta=".$row->indexID."\">".$row->task."</a>   ".$row->shortnote."</td>\n");
	echo("<td>".$assigned."</td>\n");
	echo("<td>".$row->priority."</td>\n");
	echo("<td>".$flags[0].$flags[1].$flags[2]."</td>\n");
	echo("<td>".$complete."</td>\n");
	echo("<td>".$due_date."</td>\n");
	echo("</tr>");
}
echo("</table>");
mysql_free_result($result);
?>
