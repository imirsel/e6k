<?php 
	require_once("models/config.php");
	if (!isUserLoggedIn()) { die(); }

	if (isset($_GET['t']) &&
		isset($_GET['q']) && 
		isset($_GET['c']) &&
		isset($_GET['callback']) &&
		(isset($_GET['b']) || isset($_GET['f'])))
	{
		$t = $_GET['t'];
		$q = $_GET['q'];
		$c = $_GET['c'];

		$scores = array();
		if (isset($_GET['b']) && preg_match("/^[NSV]S$/", $_GET['b'])) 
		{
			$b = $_GET['b'];
			$scores["broad"] = $b;
		}
		
		if (isset($_GET['f']) && ($_GET['f'] >= 0) && ($_GET['f'] <= 100))
		{
			$f = $_GET['f'];
			$scores["fine"] = $f;
		}
		
		if (count($scores) > 0) {		
			updateRelevance($loggedInUser, $t, $q, $c, $scores);
			echo $_GET['callback'], "('", $c, "')";
			die();
		}
	}
	echo "error";
	die();