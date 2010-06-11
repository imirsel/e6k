<?php 
	/*
		UserCake Version: 1.4
		http://usercake.com
		
		Developed by: Adam Davis
	*/
	require_once("models/config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>MIREX</title>
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
            <h1>Welcome to MIREX Registration and Submission</h1>
            
			<p>Welcome to the MIREX 2010 submission system. In order to participate in MIREX submissions need to be entered through this interface and your code uploaded to an FTP dropbox account whose details will be provided  to you once your submission has been created.</p>
			
			<p>Once you have registered with a login and password, you'll need to create an identity which includes your name and institutional affiliation. You can create multiple identities if you have multiple affiliations, or if your affiliation changes. However, in order to preserve the integrity of our historical data, your identities cannot be edited, changed, or deleted by you.</p>
			
			<p>After you have completed your identity profiles, you can start creating submissions to individual tasks. When creating a submission you will be asked to identify each person that has contributed to the submission so that they maybe credited in the results (in much the same way that you would identify authors of a conference/journal paper). If a person has already signed up to the submission system or been added to another submission they may be found and selected using the search box, alternatively they may be added to the system).</p>
			
			<p>Contrary to previous year's submission processes, this year we ask that you create a submission record for each entry you wish to make in each task (where in previous years some participants would send us multiple submissions to one or more tasks in a single upload). This has always been problematic to track and hence this year we ask that a submission record is created for each system that you expect to receive a result for.</p>
			  
			<p>Finally, you will be asked to enter the contents of a README file for your submission onto the submission form. This should provide all the details required to run your submission, including commands to execute the submission, software/architecture dependencies, expected runtime and resource requirements (RAM, disk storage and CPUs) and any other configuration details.</p>

            <div class="clear"></div>
        </div>

   </div>
</div>
</body>
</html>


