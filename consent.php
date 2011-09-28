<?php
	/*
		UserCake Version: 1.4
		http://usercake.com

		Developed by: Adam Davis
	*/
	require_once("models/config.php");

	//Prevent the user visiting the logged in page if he/she is not logged in
	if (!isUserLoggedIn()) { header("Location: login.php"); die(); }

	$consent = userHasGivenConsent($loggedInUser);

	if (empty($consent) &&
		isset($_POST['consent_Sign']) &&
		($_POST['consent_Sign'] == 'Y'))
	{
		userGiveConsent($loggedInUser);

		if (isset($_POST['assignTask']) && preg_match("/^[0-9]+$/", $_POST['assignTask'])) {
			$tid = $_POST['assignTask'];
			$assignments = userGetAssignments($loggedInUser, $tid);
			if (count($assignments) == 0) {
				userAssignQueries($loggedInUser, $tid);
			}
			header("Location: assignment.list.php"); die();
		}
		header("Location: consent.php"); die();
	}
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
			<h1>Informed Consent</h1>
        <?php
		if ((!empty($consent)) &&
			(isset($consent['consent_Date'])) &&
			(isset($consent['consent_Status'])) &&
			(isset($consent['consent_IP'])) &&
			(isset($consent['consent_UserAgent'])))
		{
			?>
			<div id="success">
				<p>You have consented to participate in this study. You gave your informed consent on
				<strong><?php echo $consent['consent_Date'];?></strong> from a computer connected to the
				internet from IP address <em><?php echo $consent['consent_IP'];?></em> using a browser
				reporting to be <em><?php echo $consent['consent_UserAgent'];?></em>.</p>

				<p>If this
				information seems incorrect or suspicious to you, please contact the IMIRSEL
				team at <a href="mailto:mirex@imirsel.org">mirex@imirsel.org</a>.</p>

				<p>Now that you have consented to participating in this study you can start rating
				queries. <strong>Go to <a href="assignment.list.php">My Assignments</a> to start grading.</a></strong>
				</p>
			</div>
			<?php
		}
			?>
			<h2>Music Similarity Grading System</2>
			<h3>Investigators:</h3>
			<dl>
				<dt>Responsible Project Investigator</dt>
				<dd>J. Stephen Downie (jdownie@illinois.edu)</dd>

				<dt>Graduate Assistant</dt>
				<dd>Andreas F. Ehmann (aehmann@illinois.edu)</dd>
			</dl>

			<p>The following music similarity grading system is designed capture human judgments concerning the similarity of various query songs and those songs deemed to be similar to them (also known as "candidates") by one or more Music Information Retrieval (MIR) systems. The similarity judgments you provide for each query/candidate pair will be aggregated with the judgments of other graders to form a ground-truth set of similarity judgments. The ground-truth set that you help us create will then be used to evaluate the performance of MIR systems. You have been asked to participate as a similarity grader because of your active involvement in the MIR and/or Music Digital Library, e-musicology and informatics research domains.</p>

			<p>Your participation as a grader is completely voluntary. If you come to any selections you do not want to grade, please feel free to skip to the next selection. You may also start and stop your grading sessions, and modify your judgments as you see fit, up to October 12, 2011  when we will be closing the collection process. You may discontinue participation at any time, including after the completion of the grading, for any reason. In the event that you chose to stop participation, you may ask us to have your answers deleted by contacting us through email prior to October 12, 2011 when we will be aggregating the collected data.</p>

			<p>All personally identifying information of the graders, however obtained, (e.g., name, company of employment, place of residence, names of collaborators, email addresses, website URLs, response times, etc) will be kept confidential, meaning accessible by only the investigators and not published nor shared with other researchers. The original raw grader scores will not be distributed nor disseminated beyond the investigators and will be kept locked in a University office and on restricted access (i.e., password- protected) areas of the investigators' computers. Data will be retained until the end of IMIRSEL's active involvement in MIR/MDL evaluations, for a minimum of three years after its collection and as long as is necessary to complete the necessary analyses of the data.</p>

			<h3>Benefits of Participation</h3>
			<p>The sharing of your knowledge of which queries are similar to which candidates will contribute to a fuller understanding of music similarity in general, and also aid in the development of algorithms and systems designed to identify and locate similar musical works.</p>

			<h3>Risks of Participation</h3>
			<p>Participation as a grader does not involve risks beyond those encountered in daily life.</p>

			<h3>Time Commitment</h3>
			<po>Evaluation of a single query against a single candidate takes approximately one minute. Completing all assigned queries will take you approximately 2 to 3 hours. You can stop at anytime and resume grading, allowing you to complete the entire process in stages.</p>

			<h3>Contact Information</h3>
			<p>If you have any questions or concerns about this study, please contact the investigators.</p>
			<p>Project contact address: c/o Dr. J. Stephen Downie, Graduate School of Library and Information Science, 501 E. Daniel St., Champaign, IL 61820; phone: 217-265-5018, fax: 217-244-3302.</p>

			<p>If you have any general questions about your rights as a participant in this study, please contact the University of Illinois Institutional Review Board at 217-333-2670 (you may call collect if you identity yourself as a research participant) or via email at irb@illinois.edu</p>

			<h2>CONSENT TO GRADING PARTICIPATION</h2>

			<h3>Music Similarity Grading System</h3>

			<p>YOU MUST BE 18 YEARS OF AGE OR OLDER TO PARTICIPATE!</p>

			<p>I certify that I am 18 years of age or older, I can print out a copy of this consent form, I have read the preceding and that I understand its contents. By selecting the checkbox below I am freely agreeing to participate in this study by filling out the survey.</p>
			<form action="consent.php" method="post">
			<?php
				if (isset($_GET['assignTask']) || isset($_POST['assignTask'])) {
					if (isset($_GET['assignTask'])) { $tid = $_GET['assignTask']; }
					else { $tid = $_POST['assignTask']; }

					if (preg_match("/^[0-9]+$/", $tid)) {
					?>
					<input type="hidden" name="assignTask" value="<?php echo $tid;?>"/>
					<?php
					}
				}
			?>
				<p>
				<input type="checkbox" value="Y" name="consent_Sign"/>
				By checking this box, I consent that I have read and understand
				the above statements regarding my voluntary participation in this survey.
				</p>
				<input type="submit" value="Consent"/>
			</form>
		</div>
	</div>
</div>
</body>
</html>


