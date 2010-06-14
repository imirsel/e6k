<?php
	/*
		UserCake Version: 1.4
		http://usercake.com
		
		Developed by: Adam Davis
	*/
	require_once("models/config.php");
	
	//Prevent the user visiting the logged in page if he/she is not logged in
	if (!isUserLoggedIn()) { header("Location: login.php"); die(); }
	if (!$loggedInUser->isGroupMember(2)) { die(); }

	if (!empty($_GET) && isset($_GET['task']))
	{
		header("Content-type: text/plain");
		print "SubID,QueryID,QueryGenre,AvgBroad,AvgFine\n";

		$report = adminGenerateReport($loggedInUser, $_GET['task']);

		foreach ($report as $row) 
		{
			echo join(",", $row), "\n";
		}
	}
	