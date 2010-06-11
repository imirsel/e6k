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
        
        	<h1>Submissions for <?php echo $loggedInUser->display_username;?></h1>
        		<div style="margin:15px"><form action="submissions.edit.php" method="get"><input type="Submit" name="start" value="Start New Submission"/></form></div>
            <?php

            $submissions = getSubmissions($loggedInUser); 
            
            if (count($submissions) == 0) {
            ?>
            	<p>You have no submissions in progress.</p>
            <?php
            }
            else {
            	$licenses = getLicenses();
            	?>
				<div class="sub">
					<div class="sub-shortcode"><strong>Shortcode</strong></div>
					<div class="sub-info"><strong>Submission</strong></div>
					<div class="clear" style="height:0px;"></div>
				</div>
            	<?php
            	foreach ($submissions as $sub) {
            		?>
            		<div class="sub">
						<div class="sub-shortcode">
							<div style="font-size:1.8em"><?php echo $sub['sub_Hashcode'];?></div>
							<?php 
								if (($sub['sub_Status'] == 0) || ($sub['sub_Status'] == 1) || ($sub['sub_Status'] == 7)) {
							?>
							<input type="button" onclick="window.location.href='submissions.edit.php?edit=<?php echo $sub['sub_ID'];?>';" value="Modify"/>
							<?php
								}
							?>
						</div>
						<div class="sub-info">
							<strong><?php echo stripslashes($sub['sub_Name']);?></strong>
							<div>Status: <?php echo getSubmissionStatus($sub['sub_Status']);?> | <a href="submissions.edit.php?added=<?php echo $sub['sub_Hashcode']?>">Upload Instructions</a></div>
							<div style="font-size:0.8em;">
							Date Created: <?php echo $sub['sub_Created'];?> |
							Last Updated: <?php echo $sub['sub_Updated'];?>
							</div>
							Task: <em><?php echo stripslashes($sub['task_Name']);?></em><br/>
							Contributors: <em><?php echo stripslashes(join(", ",$sub['contributors']));?></em><br/>
							<div>Abstract: <?php echo ((file_exists($MIREX_absdir . $sub['sub_Hashcode'] . ".pdf")) ? "<a href='/mirex/abstracts/2010/".$sub['sub_Hashcode'].".pdf'>View</a>"  : "Not uploaded")?>
							<?php if ($sub['sub_Username'] == $loggedInUser->clean_username) { ?>
								<input type="button" onclick="window.location.href='submissions.abstract.php?sub=<?php echo $sub['sub_ID'];?>';" value="Upload Abstract"/>
							<?php } ?>
							</div>
							<div>License: <?php echo ($licenses[$sub['sub_License_Type']]);?> 
								<?php if ($sub['sub_Username'] == $loggedInUser->clean_username) { ?>
									<input type="button" onclick="window.location.href='submissions.license.php?sub=<?php echo $sub['sub_ID'];?>';" value="Update License"/>
								<?php } ?>
							</div>
							<div>README:</div>
							<textarea disabled='disabled' style="width:375px;height:100px;"><?php echo stripslashes($sub['sub_Readme']);?></textarea>
							<div>Notes from IMIRSEL team:</div>
							<textarea disabled='disabled' style="width:375px;height:100px;"><?php echo stripslashes($sub['sub_PubNotes']);?></textarea>
							<div>Your submission was last updated by: <?php echo (isempty($sub['sub_MIREX_Handler']) ? "no one" : $sub['sub_MIREX_Handler']);?></div>
						</div>
			            <div class="clear"></div>
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

