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
				<h3><?php echo $task['task_Name'];?> Assignments</h3>
				<?php
	            $assignments = adminGetAllAssignments($loggedInUser, $tid); 
	            if (count($assignments) == 0)
	            {
	            	?>
	            	<p>No Assignments</p>
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
					<div style="max-height:400px;overflow:auto;">
					<?php
					foreach ($assignments as $query=>$grader) 
					{
						$status = userGetAssignmentStatus($loggedInUser, $tid, $query);
						$sc = $status['completed'];
						$st = $status['total'];
						?>
						<div class="sub">
							<div class="sub-shortcode">
								<?php echo $query, " (", enhash($query), ")";?>
								<div>
									<div style="float:left;height:10px;width:<?php echo floor(75 * $sc/$st);?>px;background:#0c0;border-width:1px 0px 1px 1px;border-color:gray;border-style:solid;"></div><div style="float:left;height:10px;width:<?php echo ceil(75 * (($st-$sc)/$st));?>px;background:#c00;border-width:1px 1px 1px 0px;border-color:gray;border-style:solid;"></div>
								</div>
							</div>
							<div class="sub-info">
								<?php echo ($grader != '' ? "Assigned to " . $grader : "Unassigned");?> 
								(<?php echo $sc, ' of ', $st;?> candidates completed)
							</div>
							<div class="clear" style="height:0px;"></div>
						</div>
						<?php
					}
					?>
					</div>
					<?php
	            }
			}
   			?>
  		</div>  
	</div>
</div>
</body>
</html>

