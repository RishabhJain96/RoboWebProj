<?php
/*opens everything up HTML, SQL conneciton and so on, some stuff in here needs to be moved, or a script put into place to perform certain tasks based on the document the head is included in
*/

echo("<html>\n");
echo(" <head>\n");
echo("  <title>");
echo($pagetitle);

if(isset($tl)) {
	echo(" :: ");
	echo($tl);
}

echo("</title>\n");
echo("<LINK rel=\"stylesheet\" type=\"text/css\" href=\"style.css\" />");
echo(" </head>\n");
echo(" <body>\n");
echo("<center><h1>PHP Task Manager</h1></center>\n");

//Display a '|' seperated menu accross the top with a link for each tasklist that will take you to a page displaying just that tasklist.
echo("<div class=\"menu\">");
echo("Menu:: \n");
echo("<a href=\"taskmgr.php\">Home</a> \n");

//Establish the SQL connection
$link = mysql_connect($host,$username,$password) or die('Could not connect: '.mysql_error());
mysql_select_db($database,$link) or die('could not select database'.$database.mysql_error());

//Get the unique tasklist names, opted to get this one from the task table instead of thge config table as this way it will only get tasklists that have tasks associated with it, will be a longer query this way though...as there will be more values in tasktable then config table
$result = mysql_query("SELECT DISTINCT tasklist FROM $tasktablename", $link);

$i=0;
while ($row = mysql_fetch_object($result))
{
	echo("| <a href=\"taskmgr.php?tl=".rawurlencode($row->tasklist)."\">".$row->tasklist." </a>\n");
	$taskarray[$i]=$row->tasklist;
	$i++;
}
mysql_free_result($result);
echo("</div>");
?>
