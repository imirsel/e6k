<?php
	/*
		UserCake Version: 1.4
		http://usercake.com
		
		Developed by: Adam Davis
	*/
	require_once("models/config.php");
	
	//Prevent the user visiting the logged in page if he/she is not logged in
	if(!isUserLoggedIn()) { header("Location: login.php"); die(); }
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>MIREX :: Submissions :: <?php echo $loggedInUser->display_username; ?></title>
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
			$tasks = getTasks();
			foreach ($tasks as $tid=>$task) {
				
				?>
				<h1><?php echo $task;?> Assignments for <?php echo $loggedInUser->display_username;?></h2>
				<?php
	            $assignments = userGetAssignments($loggedInUser, $tid); 
	            if (count($assignments) == 0) 
	            {
	            	?>
	            	<p>You have no assignments for this task.</p>
	            	<div><input type="button" value="Get Assignments"></div>
	            	<?php
	            }
	            else 
	            {
					?>
					<div class="sub">
						<div class="sub-shortcode"><strong>Assignment</strong></div>
						<div class="sub-info"><strong>Status</strong></div>
						<div class="clear" style="height:0px;"></div>
					</div>
					<?php
					foreach ($assignments as $query) 
					{
						$status = userGetAssignmentStatus($user, $tid, $query);
						?>
						<div class="sub">
							<div class="sub-shortcode"><?php echo $query;?></div>
							<div class="sub-info"><?php echo $status['completed'], ' of ', $status['size'], ' completed';?></div>
							<div class="clear" style="height:0px;"></div>
						</div>
						<?php

					}
	            }
			}
   			?>
  		</div>  
	</div>
</div>
</body>
</html>

