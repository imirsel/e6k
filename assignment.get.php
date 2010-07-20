<?php
	/*
		UserCake Version: 1.4
		http://usercake.com
		
		Developed by: Adam Davis
	*/
	require_once("models/config.php");
	
	//Prevent the user visiting the logged in page if he/she is not logged in
	if (!isUserLoggedIn()) { header("Location: login.php"); die(); }


	if ((!empty($_POST)) && (isset($_POST['task'])))
	{
		$tid = $_POST['task'];

		if ((isset($_POST['confirm'])) && (strtoupper($_POST['confirm']) == "YES")) {
			if (!userHasGivenConsent($loggedInUser)) { header("Location: consent.php?assignTask=".$_POST['task']); die(); }
			$assignments = userGetAssignments($loggedInUser, $tid);
			if (count($assignments) == 0) {
				$assignments = userAssignQueries($loggedInUser, $tid);
			}
			header("Location: assignment.list.php"); die();
		}
		else {
			$task = getTask($tid);
		?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>MIREX :: E6K :: <?php echo $loggedInUser->display_username; ?></title>
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
        	<h2>Confirm signup</h2>
        	<p>Are you certain you wish to sign up for <strong><?php echo stripslashes($task['task_Name']);?></strong>?</p>
        	<p>This action CANNOT be undone</p>
			<form action="assignment.get.php" method="post">
				<input type="hidden" name="task" value="<?php echo $tid;?>"/>
				<input type="submit" name="confirm" value="Yes" />
				<input type="button" value="No" onclick="window.location.href='assignment.list.php'"/>
			</form>
		
		</div>
	</div>
</div>
</body>
</html>
<?php
		}	
	}
	else {
		header("Location: assignment.list.php"); die();
	}
	
	
