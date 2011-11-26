<?php
/*Determine if any Flags need to be displayed and the return the flags to be displayed,
Possible Flags are::
	New
	OverDue (and not marked as completed)
	Due soon (and not marked as completed)
	One or none of the following:
		Recently Modified
		Recently Assigned/unassigned (maybe this is part of modified?)
		Recently Completed (need to add a completion date to the SQL table for this, don't think it currently exists, although this could be done by checking if recently modified, and marked as complete as it shouldn't be modified after being completed...)
*/
//flags array - flags[0] is new, flags[1] is overdue, flags[2] is due soon
//created_date, due_date are date strings in YYYY-MM-DD HH:MM:SS
//days_new is the number of days after creation that the task is considered new for
//warning_hours is the number of hours before due_date that should raise the due soon flag
//completed is a boolean for if the task is completed or not
function determineFlags($created_date,$due_date,$days_new,$warning_hours,$completed) {
    $flags = array("","","");
    //change created_date and due_date into timestamps
    //$createdarray = preg_split('!(\S+)/(\S+)/(\S+) (\S+):(\S+):(\S+)!',$created_date, -1,PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
    //$created_date = mktime($createdarray[3],$createdarray[4],$createdarray[5],$createdarray[1],$createdarray[2],$createdarray[0],-1);
    
    //$createdarray = preg_split('!(\S+)/(\S+)/(\S+) (\S+):(\S+):(\S+)!',$due_date, -1,PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
    //$due_date = mktime($createdarray[3],$createdarray[4],$createdarray[5],$createdarray[1],$createdarray[2],$createdarray[0],-1);
    
    $todays_date = time();
    //check if new
    if(($todays_date-$created_date)<($days_new*24*60*60)){
        $flags[0] = "NEW ";
    }
    //check if overdue
    if($todays_date>$due_date && $completed!="100" && $due_date !="0"){
        $flags[1] = "DUE SOON ";
    }
    //check if due soon
    if(($due_date - ($warnings_hours*60*60))<$todays_date && $completed != "100" && $due_date!="0"){
        $flags[2] = "OVERDUE ";
    }
    return $flags;
}


function determineOutput($group, $user, $complete,$due_date, $created_date, $modified_date, $assigned_date)
{
	$assigned="";
        if(($group=="none") && ($user=="none")) {
                $assigned.="unassigned";
        }else {
                if($group !="none") {
                        $assigned.=$group;
                }
                if(($group!="none") && ($user!="none")) {
                        $assigned.=", ";
                }
                if($user!="none"){
                        $assigned.=$user;
                }
        }
	if ($complete=="100")
	{
		$complete="100%";
	} else {
		$complete.="%";
	}

	if($due_date == "00000000000000") {
        	$formatted_due_date="never";
	}else{
		$formatted_due_date=date("Y-m-d H:i:s",$due_date);
	}
	if($created_date == "00000000000000") {
        	$formatted_created_date="never";
	}else{
		$formatted_created_date=date("Y-m-d H:i:s",$created_date);
	}
	if($modified_date == "00000000000000") {
        	$formatted_modified_date="never";
	}else{
		$formatted_modified_date=date("Y-m-d H:i:s",$modified_date);
	}
	if($assigned_date == "00000000000000") {
        	$formatted_assigned_date="never";
	}else{
		$formatted_assigned_date=date("Y-m-d H:i:s",$assigned_date);
	}
	return array($assigned, $complete, $formatted_due_date, $formatted_created_date, $formatted_modified_date, $formatted_assigned_date);
}

function isCorrectDate($year, $month, $day, $hour, $minute) {
	if(!checkdate($month, $day, $year)) {
		return false;
	}
	if(!is_numeric($hour) || $hour>23 || $hour<0 || !is_numeric($minute) || $minute>59 || $minute<0) {
		return false;
	}
	return true;
}

?>
