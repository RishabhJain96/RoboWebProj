<?php 
include("config.php");
include("functions.php");
include("head.php");
if(isset($_GET['ta'])) {//if a task is selected for modifying
	if(isset($_POST['hmodify'])) {//modifying task
	
		$_SESSION['loginusername']="Drummerman";/////login name....
		
		//checking for correct values
		$error="";
		if($_POST['due_year']=="") {
			$due_date="00000000000000";
		} else {
			if(!isCorrectDate($_POST['due_year'], $_POST['due_month'], $_POST['due_day'], $_POST['due_hour'], $_POST['due_minute'])) {
				$error="You must enter a correct due date!<BR>";
			} else {
				$due_date=mktime($_POST['due_hour'],$_POST['due_minute'],$_POST['due_second'],$_POST['due_month'],$_POST['due_day'],$_POST['due_year']) ;
			}
		}
		if(!is_numeric($_POST['priority']) || $_POST['priority']<0 || $_POST['priority']>10) {
			$error="You must enter priority from 0 to 10!<BR>";
		}
		if(!is_numeric($_POST['complete']) || $_POST['complete']<0 || $_POST['complete']>100) {
			$error="You must enter complete from 0 to 100!<BR>";
		}
		//I have to check whether assigned_group and assigned_user are entered
		//and if so to update assigned_by and assigned_date
		
		if($error!="") {
			?>
			<DIV align="center" class="error"><?php echo $error;?></DIV>
			<?php
		} else {
			//building sql query
			$query="UPDATE $tasktablename SET ";
			$query.="tasklist='".$_POST['tasklist']."',"; 
			$query.=$_POST['category']==""?"category='none',":"category='".$_POST['category']."',"; 
			$query.=$_POST['subcategory']==""?"subcategory='none',":"subcategory='".$_POST['subcategory']."',";
			$query.="task='".$_POST['task']."',"; 
			$query.="shortnote='".$_POST['shortnote']."',"; 
			$query.="description='".$_POST['description']."',"; 
			$query.=trim($_POST['assigned_group'])==""?"assigned_group='none',":"assigned_group='".$_POST['assigned_group']."',"; 
			$query.=trim($_POST['assigned_user'])==""?"assigned_user='none',":"assigned_user='".$_POST['assigned_user']."',"; 
			$query.=(trim($_POST['assigned_group'])=="" && trim($_POST['assigned_user'])=="")?"assigned_by='none',":"assigned_by='".$_SESSION['loginusername']."',"; //user's name
		// when modifying task and it has been assigned before have to check whether the user and the group are the same and if so to leave the old date...else to assign new date...pending...
//			if(trim($_POST['assigned_group'])!="") {
//				if(trim($_POST['assigned_user'])!="") {//entered assigned_group and assigned_user
//					$result=mysql_query("SELECT * FROM $tasktablename WHERE tasklist='$tasklist' AND category='$category' AND subcategory='$subcategory' AND task='$task'");
//					if($error=="" && mysql_num_rows($result)!=0){
//						$error="Duplicated data";
//					}
//				} else {//entered assigned_group
//				}
//			} else {
//				if(trim($_POST['assigned_user'])!="") {//entered assigned_user
//				} 
//			}
		//	$query.=(trim($_POST['assigned_group'])=="" && trim($_POST['assigned_user'])=="")?"assigned_date='0000-00-00 00:00:00',":"assigned_date='".$_POST['assigned_date']."',"; 
			$query.="due_date='$due_date',"; 
			$query.=isset($_POST['private'])?"private='".$_POST['private']."',":"private='n',"; 
			$query.=isset($_POST['hidden'])?"hidden='".$_POST['hidden']."',":"hidden='n',";  
			$query.="priority='".$_POST['priority']."',";
			$query.="complete='".$_POST['complete']."',"; 
			$query.="modified_date='".time()."'";
			$query.="WHERE indexID=".$_POST['indexID'];
			
			mysql_query($query);
			echo "Entry Modified Successfully.<BR>";
			echo "<A href=\"viewtask.php?ta=".$_POST['indexID']."\">View task</A><BR>";
			include("foot.php");
			exit();
		}
	}
	$result = mysql_query("SELECT * FROM $tasktablename WHERE indexID = '".$_GET['ta']."'", $link);
	$row_db = mysql_fetch_object($result);
	?>
	<FORM action="<?php echo $_SERVER['PHP_SELF']."?ta=".$_GET['ta'];?>" method="post">
	<TABLE cellspacing="5" align="center">
	<TR>
		<TH colspan="2">Modify task</TH></TR>
	<TR>
		<TD>ID:</TD>
		<TD><?php echo $row_db->indexID;?><INPUT type="hidden" name="indexID" value="<?php echo $row_db->indexID;?>"></TD></TR>
	<!--This section will read all the available tasklists from the configuration table and display them in a dropdown box-->
	<TR>
		<TD>TaskList:</TD>
		<TD>
		<SELECT name="tasklist">
		<?php
		$result= mysql_query("SELECT DISTINCT tasklist FROM $configtablename", $link);
		while ($row = mysql_fetch_object($result)) {
			?>
			<OPTION value="<?php echo $row->tasklist;?>" <?php echo $row_db->tasklist==$row->tasklist?"selected":"";?>>
			<?php echo $row->tasklist;
		}
		mysql_free_result($result);
		?>
		</SELECT></TD></TR>
	<!--This section will read all the available Categories from the configuration table and display them in a dropdown box, needs to be altered so that it only shows the values available based on the tasklist selected in box 1.  At this time I am thinking about doing it java based so all the values would have to be pre-loaded into a java script, might have to find another way as that could lead to really bad loadtimes on large tables...would like to start it developed this way though, if it turns out bad on large tables maybe make it an option in the configuration-->
	<TR>
		<TD>Category:</TD>
		<TD>
		<SELECT name="category">
		<OPTION value="">--- none ---
		<?php
		$result= mysql_query("SELECT DISTINCT category FROM $configtablename", $link);
		while ($row = mysql_fetch_object($result)) {
			if($row->category != "-") {
				?>
				<OPTION value="<?php echo $row->category;?>" <?php echo $row_db->category==$row->category?"selected":"";?>>
				<?php echo $row->category;
			}
		}
		mysql_free_result($result);
		?>
		</SELECT></TD></TR>
	<!--This section will read all the available Sub-Categories from the configuration table and display them in a dropdown box, needs to be altered so that it only shows the values available based on the tasklist selected in box 1 and the Category Selected in Box 2...see last comment for ideas on how-->
	<TR>
		<TD>Sub-Category:</TD>
		<TD>
		<SELECT name="subcategory">
		<OPTION value="">--- none ---
		<?php
		$result= mysql_query("SELECT DISTINCT subcategory FROM $configtablename", $link);
		while ($row = mysql_fetch_object($result)) {
			if($row->subcategory != "-") {
				?>
				<OPTION value="<?php echo $row->subcategory;?>" <?php echo $row_db->subcategory==$row->subcategory?"selected":"";?>>
				<?php echo $row->subcategory;
			}
		}
		mysql_free_result($result);
		?>
		</SELECT></TD></TR>
	<TR>
		<TD>Taskname:</TD>
		<TD><INPUT type="text" size="50" maxlength="50" name="task" value="<?php echo $row_db->task;?>"></TD></TR>
	<TR>
		<TD>Short Description:</TD>
		<TD><INPUT type="text" size="50" maxlength="75" name="shortnote" value="<?php echo $row_db->shortnote;?>"></TD></TR>
	<TR>
		<TD colspan="2">Long Description:<BR><TEXTAREA name="description" rows="4" cols="55"><?php echo $row_db->description;?></textarea></TD></TR>
	<TR>
		<TD>Assign to Group:</TD>
		<TD><INPUT type="text" size="50" maxlength="255" name="assigned_group" value="<?php echo $row_db->assigned_group=="none"?"":$row_db->assigned_group;?>"></TD></TR>
	<TR>
		<TD>Assign to User:</TD>
		<TD><INPUT type="text" size="50" maxlength="255" name="assigned_user" value="<?php echo $row_db->assigned_user=="none"?"":$row_db->assigned_user;?>"></TD></TR>
	<TR>
		<TD>Assigned by:</TD>
		<TD><?php echo $row_db->assigned_by;?></TD></TR>
	<TR>
		<TD>Assigned on:</TD>
		<TD><?php echo $row_db->assigned_date=="00000000000000"?"never":date("Y-m-j H:i:s",$row_db->assigned_date);?></TD></TR>
	<TR>
		<TD>Created by:</TD>
		<TD><?php echo $row_db->created_by;?></TD></TR>
	<TR>
		<TD>Created on:</TD>
		<TD><?php echo date("Y-m-j H:i:s",$row_db->created_date);?></TD></TR>
	<TR>
		<TD>Due Date:<BR>Y-M-D hh:mm:ss</TD>
		<TD><SELECT name="due_year">
		<OPTION value="">----
		<?php
		list($due_year, $due_month, $due_day, $due_hour, $due_minute, $due_second)=split("[-[:space:]:]", date("Y-m-d H:j:s" ,$row_db->due_date));
		$t_year=date("Y");
		if($due_year<$t_year && $due_year!="0000") {
			echo "<OPTION value=\"$due_year\" selected>$due_year";
		}
		for($i=$t_year; $i<=$t_year+10; $i++) {
			echo "<OPTION value=\"$i\" ";
			echo $due_year==$i?"selected>":">";
			echo $i;
		}
		?>
		</SELECT> -
		<SELECT name="due_month">
		<?php
		for($i=1; $i<=9; $i++) {
			echo "<OPTION value=\"0$i\" ";
			echo $due_month=="0$i"?"selected>":">";
			echo "0$i";
		}
		for($i=10; $i<=12; $i++) {
			echo "<OPTION value=\"$i\" ";
			echo $due_month==$i?"selected>":">";
			echo $i;
		}
		?>
		</SELECT> -
		<SELECT name="due_day">
		<?php
		for($i=1; $i<=9; $i++) {
			echo "<OPTION value=\"0$i\" ";
			echo $due_day=="0$i"?"selected>":">";
			echo "0$i";
		}
		for($i=10; $i<=31; $i++) {
			echo "<OPTION value=\"$i\" ";
			echo $due_day==$i?"selected>":">";
			echo $i;
		}
		?>
		</SELECT>&nbsp;
		<INPUT type="text" size="2" maxlength="2" name="due_hour" value="<?php echo $due_hour;?>"> :
		<INPUT type="text" size="2" maxlength="2" name="due_minute" value="<?php echo $due_minute;?>"> :
		<INPUT type="text" size="2" value="00" disabled>
		<INPUT type="hidden" size="2" value="00" name="due_second"></TD></TR>
	<TR>
		<TD>Privacy Settings:</TD>
		<TD>Hidden: <INPUT type="checkbox" name="hidden" value="y" <?php echo $row_db->hidden=="y"?"checked":"";?>>
		<BR>Private: <INPUT type="checkbox" name="private" value="y" <?php echo $row_db->private=="y"?"checked":"";?>></TD></TR>
	<TR>
		<TD>Priority:</TD>
		<TD><INPUT type="text" size="2" maxlength="2" name="priority" value="<?php echo $row_db->priority;?>"></TD></TR>
	<TR>
		<TD>Complete:</TD>
		<TD><INPUT type="text" size="3" maxlength="3" name="complete" value="<?php echo $row_db->complete;?>"></TD></TR>
	<TR>
		<TD>Last modified:</TD>
		<TD><?php echo $row_db->modified_date=="00000000000000"?"never":date("Y-m-d H:m:s",$row_db->modified_date);?></TD></TR>
	<TR>
		<TD colspan="2" align="center"><INPUT type="reset" name="reset" value="Reset"> <INPUT type="submit" name="submit" value="Modify"></TD></TR>
	</TABLE>
	<INPUT type="hidden" name="hmodify" value="modify">
	</FORM>
	<?php
}
include("foot.php");
?>