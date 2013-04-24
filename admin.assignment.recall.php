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
		
	if ((isset($_POST['confirm'])) && 
		(strtoupper($_POST['confirm']) == "YES") &&
		(isset($_POST['task'])) &&
		(preg_match("/^[0-9]+$/", $_POST['task'])) &&
		(isset($_POST['query']))) {
		$tid = $_POST['task'];
		$query = $_POST['query'];
		$grader = $_POST['grader'];
		$task = getTask($tid); 

		if ($task['task_Type'] == "Subtask" ) {
		   adminRecallSubtask($loggedInUser, $tid, $query, $grader);
		} else { 
		   adminRecallQuery($loggedInUser, $tid, $query);
		}
		header("Location: admin.assignments.php");
	}
	
	if ((!isset($_GET['t'])) || (!isset($_GET['q']))) {
		header("Location: admin.assignments.php");
	}
	$tid = $_GET['t'];
	$query = $_GET['q'];
	$grader = $_GET['grader'];
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>MIREX :: E6K ADMIN :: Recall Query</title>
<link href="mirex.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div id="wrapper">

	<div id="content">
    
        <div id="left-nav">
        <?php include("layout_inc/left-nav.php"); ?>
            <div class="clear"></div>
        </div>
        
        <div id="main">
        	<h2>Confirm recall of query <?php echo $query;?></h2>
        	<p>This will delete all ratings made on this query and return the query to the pool
        	for assignment to another grader. Are you sure you wish to do this?</p>
        	<p>This action CANNOT be undone</p>
			<form action="admin.assignment.recall.php" method="post">
				<input type="hidden" name="task" value="<?php echo $tid;?>"/>
				<input type="hidden" name="query" value="<?php echo $query;?>"/>
				<input type="hidden" name="grader" value="<?php echo $grader;?>"/>
				<input type="submit" name="confirm" value="Yes" />
				<input type="button" value="No" onclick="window.location.href='admin.assignments.php'"/>
			</form>
		
		</div>
	</div>
</div>
</body>
</html>
<?php
	
	
