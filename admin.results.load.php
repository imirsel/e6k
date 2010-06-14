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
	if (!empty($_POST)) 
	{
		if (isset($_POST['task_ID']))
		{
			$task = $_POST['task_ID'];
			if (isset($_POST['data_Append']) && ($_POST['data_Append'] == "yes")) {
				$append = true;
			}
			else {
				$append = false;
			}
			
			$data = explode("\n", $_POST['data']);
			
			adminLoadResults($loggedInUser, $task, $data, $append);
			header("Location: admin.results.load.php?loaded&task=" . $task); die();
		}
	}
	
	$tid = $_GET['task'];
	$task = getTask($tid);
	$dcount = adminIsTaskDefined($loggedInUser, $tid);
	
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
			<?php
			if (isset($_GET['loaded'])) {

				?>
				<div id="success">Results data loaded.</div>
				<?php
			}


			?>
			<h1>Load Data for Task <?php echo $task['task_Name'];?></h1>
			<div>
				<p class="alert">Task has <?php echo $dcount;?> results to be evaluated.</p>
				<form action="admin.results.load.php" method="post">
					<input type="hidden" name="task_ID" value="<?php echo $tid;?>"/>
					<div style="margin-bottom:10px;">
						<p>Paste CSV 4-Tuples: Submission ID,Query ID,Query Genre,Candidate ID</p>
						<textarea name="data" style="width:375px;height:400px;"></textarea>
					</div>
					<div>
						<label style="width:150px">Append to previous?</label>
						<input type="checkbox" name="data_Append" value="yes"/>
						<div class="clear"></div>
					</div>
					<div>
						<input type="submit" name="submit" value="Load Data"/>
					</div>
				</form>
			</div>
  		</div>  
	</div>
</div>
</body>
</html>

