<?php //This will just basically close everything off in the page, closes the the open SQL connection, adds the powered by link, closes the html
mysql_close($link);
echo("<center>Powered by <a href=\"http://phptm.sf.net\">PHP Task Manager</a></center><br /><a href=\"admin.php\">admin</a>");
echo(" </body>");
echo("</html>");
?>
