<?php
	/*
		UserCake Version: 1.4
		http://usercake.com
		
		Developed by: Adam Davis
	*/
	require_once("models/config.php");
	
	//Prevent the user visiting the logged in page if he/she is not logged in
	if (!isUserLoggedIn()) { header("Location: login.php"); die(); }
	if (!userHasGivenConsent($loggedInUser)) { header("Location: consent.php"); die(); }

	if ((!empty($_POST)) && (isset($_POST['task'])))
	{
		$tid = $_POST['task'];
		$assignments = userGetAssignments($loggedInUser, $tid);
		if (count($assignments) == 0) {
			$assignments = userAssignQueries($loggedInUser, $tid);
		}
	}
	header("Location: assignment.list.php"); die();
