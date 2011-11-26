<?php
for ($taskloop=0; $taskloop<sizeof($taskarray); $taskloop++) {
	//get all the Categories for the selected tasklist and put them into an array
	$result = mysql_query("SELECT DISTINCT category FROM $tasktablename WHERE tasklist = '".$taskarray[$taskloop]."'", $link) or die(mysql_error());
	unset($catsarray);
	$catsarray[0]="none";
	$i=1;
	while ($row = mysql_fetch_object($result)) {
		if (($row->category != "none") && ($row->category !="")) {
			$catsarray[$i]=$row->category;
			$i++;
		}
	}
	mysql_free_result($result);
	
	echo("<br /><a href=\"taskmgr.php?tl=".rawurlencode($taskarray[$taskloop])."\">$taskarray[$taskloop]</a> \n");
	//Display the table for the new tasks
	echo("<table width=\"100%\" cellpadding=\"2\" cellspacing=\"2\" border=\"0\">\n <tr>\n");
	echo("   <th>Task</th>");
	echo("   <th>Assigned To</th>");
	echo("   <th>Priority</th>");
	echo("   <th>Flags</th>");
	echo("   <th>Due Date</th>");
	echo("   <th>Date Created</th>");
	echo("   <th>Created by</th>");
	echo("\n  </tr>\n");
	
	for ($catsloop=0; $catsloop<sizeof($catsarray); $catsloop++) {
		if ($catsarray[$catsloop] != "none") {
			echo("<tr class=\"cat\"><td colspan=\"7\"><a href=\"taskmgr.php?tl=".rawurlencode($taskarray[$taskloop])."&ca=".rawurlencode($catsarray[$catsloop])."\">$catsarray[$catsloop]</a></td></tr>");
		}
		
		$result = mysql_query("SELECT * FROM $tasktablename WHERE tasklist = '".$taskarray[$taskloop]."' AND category = '".$catsarray[$catsloop]."'", $link);
		while ($row = mysql_fetch_object($result)) {
			list($assigned,$complete,$due_date, $created_date,$modified_date, $assigned_date)=determineOutput($row->assigned_group,$row->assigned_user,$row->complete,$row->due_date, $row->created_date, $row->modified_date, $row->assigned_date);

			$flags=determineFlags($row->created_date,$row->due_date,$days_new,$warning_hours,$row->complete);		
			if($flags[0]!="") {
				echo ($i%2==0)?"<TR class=\"dark\">":"<TR class=\"light\">";
				echo("<td><a href=\"viewtask.php?ta=".$row->indexID."\">".$row->task."</a>   ");
				if($row->shortnote != "") {
					echo(" :: ".$row->shortnote);
				}
				echo("</td>\n");
				
				echo("<td>".$assigned."</td>\n");
				echo("<td>".$row->priority."</td>\n");
				
				echo("<td>".$flags[0].$flags[1].$flags[2]."</td>\n");
				
				echo("<td>".$due_date."</td>\n");
				echo("<td>".$created_date."</td>\n");
				echo("<td>".$row->created_by."</td>\n");
				echo("</tr>");
			}
		}
		mysql_free_result($result);
	}
	echo("</table>");
}
?>
