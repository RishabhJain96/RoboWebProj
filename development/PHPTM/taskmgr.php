<?php
include("config.php");
include("functions.php");
include("head.php");
//pretty basic bage, for the most part this page should just choose what to display and let other scripts take care of displaying it
//needs to be something here to check if users need to be logged in or not, if so direct to login page, if not display what ever should be displayed

if(isset($_GET['tl'], $_GET['ca'], $_GET['su'])) {
	include("viewsubcat.php");
} elseif(isset($_GET['tl'], $_GET['ca'])) {
	include("viewcategory.php");
} elseif(isset($_GET['tl'])) {
	include("viewtasklist.php");
} else {
	include("viewnewtasks.php");
}

include("foot.php");
?>