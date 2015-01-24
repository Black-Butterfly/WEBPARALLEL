<?php
	include ("../model/MMembers.class.php");
	
	
	$mmeeting = new MMeeting();
	$id = addslashes($_SESSION['USER_ID']);
	
	
	$meetings = $mmeeting->addAllMeeting();
	
	$meetingsUser = $mmeeting->addMeetingByIdUser($id);
	header("Location: index.php?uc=viewMeetings");
?>