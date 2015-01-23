<?php
session_start();
	include ("../model/MMembers.class.php");
	include 'view/myMeetings.php';
	
	$member = new MMembers();
	$id = addslashes($_SESSION['USER_ID']);
	
	if (isset($_POST['valid'])) {
		
	}
?>