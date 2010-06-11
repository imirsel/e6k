<?php 
	require_once("models/config.php");
	if(!isUserLoggedIn()) { die(); }

	if (isset($_GET['t']) &&
		isset($_GET['b']) && 
		isset($_GET['f']) &&
		isset($_GET['a']) && 
		isset($_GET['v'])
	   )
	{
		$t = $_GET['t'];

		$q = $_GET['q'];
		$c = $_GET['c'];

		$a = $_GET['a'];
		$v = $_GET['v'];

		logEvent($loggedInUser, $t, $q, $c, $a, $v);
		echo "logged(\"", $q, "\",\"", $c, "\",", $b, "\",", $f, "\")";
	}
	die();