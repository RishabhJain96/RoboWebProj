<?php
$catsarray[0]="none";
$i=1;
//get all the Categories for the selected tasklist and put them into an array
$result = mysql_query("SELECT DISTINCT category FROM $tasktablename WHERE tasklist = '".rawurldecode($_GET['tl'])."'", $link) or die(mysql_error());
//$result = mysql_query("SELECT DISTINCT category FROM $tasktablename WHERE tasklist = '".$tl."'", $link) or die(mysql_error());
while ($row = mysql_fetch_object($result)) {
	if(($row->category != "none") && ($row->category !="")) {
		$catsarray[$i]=$row->category;
		$i++;
	}
}
mysql_free_result($result);

//make table to show everything
echo("<br /><b>&nbsp;&nbsp;".rawurldecode($_GET['tl'])."</b>");
//echo("<b>&nbsp;&nbsp;$tl</b><br />");
echo("<table width=\"100%\" cellspacing=\"2\" cellpadding=\"2\" border=\"0\">\n <tr>\n");
echo("   <th>Task</th>");
echo("   <th>Assigned To</th>");
echo("   <th>Priority</th>");
echo("   <th>Flags</th>");
echo("   <th>Status</th>");
echo("   <th>Due Date</th>");
echo("\n  </tr>\n");

//Show all items

for ($catsloop=0; $catsloop<sizeof($catsarray); $catsloop++) {
	if($catsarray[$catsloop]!="none") {
		echo("<tr class=\"cat\"><td colspan=\"6\"<a href=\"taskmgr.php?tl=".$_GET['tl']."&ca=".rawurlencode($catsarray[$catsloop])."\">$catsarray[$catsloop]</a></td></tr>");
	}
	$result = mysql_query("SELECT * FROM $tasktablename WHERE tasklist = '".rawurldecode($_GET['tl'])."' AND category = '".$catsarray[$catsloop]."'", $link);
	while ($row = mysql_fetch_object($result)) {
		
		list($assigned,$complete,$due_date, $created_date,$modified_date, $assigned_date)=determineOutput($row->assigned_group,$row->assigned_user,$row->complete,$row->due_date, $row->created_date, $row->modified_date, $row->assigned_date);
		$flags=determineFlags($row->created_date,$row->due_date,$days_new,$warning_hours,$row->complete);
		echo ($i%2==0)?"<TR class=\"dark\">":"<TR class=\"light\">";
		echo("<td>&nbsp;&nbsp;&nbsp;<a href=\"viewtask.php?ta=".$row->indexID."\">".$row->task."</a>   ".$row->shortnote."</td>\n");
		//$assigned = determineAssigned($row->assigned_group,$row->assigned_user);
		echo("<td>".$assigned."</td>\n");
		echo("<td>".$row->priority."</td>\n");
		
		echo("<td>".$flags[0].$flags[1].$flags[2]."</td>\n");
	
		echo("<td>".$complete."</td>\n");
		echo("<td>".$due_date."</td>\n");
		echo("</tr>");
	}
	mysql_free_result($result);
}

echo("</table>");

?>
