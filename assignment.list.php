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
			$tasks = getTasks();
			foreach ($tasks as $tid=>$task) {
				
				?>
				<h3><?php echo $task['task_Name'];?> Assignments for <?php echo $loggedInUser->display_username;?></h3>
				<?php
	            $assignments = userGetAssignments($loggedInUser, $tid); 
	            if (count($assignments) == 0) 
	            {
	            	?>
	            	<p>You have no assignments for this task.</p>
	            	
	            	<?php 
	            		$avail = countAvailableAssignments($tid);
	            		
	            		if ($avail > 0)
	            		{	
	            	?>
	            	<div>
						<form action="assignment.get.php" method="post">
							<input type="hidden" name="task" value="<?php echo $tid;?>"/>
							<input type="submit" value="Get Assignment">
						</form>
		            </div>
	            	<?php
		            	}
		            	else
		            	{
					?>
					<div>
						There are no queries available to assign.
					</div>
					<?php
		            	}	            		
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
						$status = userGetAssignmentStatus($loggedInUser, $tid, $query);
						$sc = $status['completed'];
						$st = $status['total'];
						?>
						<div class="sub">
							<div class="sub-shortcode">
								<?php echo $query;?>
								<div>
									<div style="float:left;height:10px;width:<?php echo floor(75 * $sc/$st);?>px;background:#0c0;border-width:1px 0px 1px 1px;border-color:gray;border-style:solid;"></div><div style="float:left;height:10px;width:<?php echo ceil(75 * (($st-$sc)/$st));?>px;background:#c00;border-width:1px 1px 1px 0px;border-color:gray;border-style:solid;"></div>
								</div>
							</div>
							<div class="sub-info">
								<div><?php echo $sc, ' of ', $st;?> candidates evaluated.</div>
								<input type="button" onclick="window.location.href='assignment.evaluate.php?task=<?php echo $tid;?>&query=<?php echo $query;?>'" value="Evaluate Query" />
							</div>
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

