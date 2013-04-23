<?php
	/*
		UserCake Version: 1.4
		http://usercake.com

		Developed by: Adam Davis
	*/
	require_once("models/config.php");

	//Prevent the user visiting the logged in page if he/she is not logged in
	if (!isUserLoggedIn()) { header("Location: login.php"); die(); }

	$tid = $_POST['assignTask'];
	$task = getTask($tid);
	$consentForm = $task['task_Consent_Form'];

	$consent = userHasGivenConsent($loggedInUser, $consentForm);

	if (empty($consent) &&
		isset($_POST['consent_Sign']) &&
		($_POST['consent_Sign'] == 'Y'))
	{
		userGiveConsent($loggedInUser, $consentForm);

		$assignments = userGetAssignments($loggedInUser, $tid);
		if (count($assignments) == 0) {
			userAssignQueries($loggedInUser, $tid);
		}
		header("Location: assignment.list.php"); die();
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

<h3>"Choose mood clusters that describe songs best" consent form</h3>
<p>This qualification will allow you to participate in our music mood tagging research HIT, "Choose mood clusters that describe songs best"</p>
<p>Investigators:</p>
<p>J. Stephen Downie (jdownie@illinois.edu)<br/>
	Xiao Hu (xiaoxhusmile@gmail.com) &nbsp;<br/>Jin Ha Lee (jinhalee@uw.edu)</p>
<p>Please read this information</p>
<p>You are invited to participate in a research study about music mood and genre tagging. This research project is being conducted by Dr. J. Stephen Downie and Dr. Xiao Hu at University of Illinois at Urbana-Champaign, and Dr. Jin Ha Lee at University of Washington. The objective of this research project is to improve music information retrieval systems by increasing our understanding of music users. In particular, we are interested in how people perceive and determine the mood and genre of music. You will be asked to listen to a number of short music clips and select the most appropriate genre and mood information among the given options for each of the music clips. Your assistance will help future researchers build better and more useful music retrieval tools to enhance our music interaction experiences.</p>
<p>Your participation is completely voluntary. You will be compensated for your time at the rate of [$0.75 to $1.00] per group of 20 songs that you tag.&nbsp;</p>
<p>All information will be anonymous in any publications or presentation in order to preserve your right to privacy.</p>
<p>We will not circumvent the anonymity functions of the Mechanical Turk system. Notwithstanding, all personally identifying information of the participants, however obtained, (e.g., name, place of residence, email addresses, website URLs, response times, etc.) will be kept confidential, meaning accessible by only the project investigators and not published nor shared with other researchers. The tags you create will not be associated with you in any way. The tags created will be collected into larger data sets that future researchers will use to help them train and test music retrieval systems.&nbsp;</p>
<p><b>Benefits of Participation</b></p>
<p>The sharing of your knowledge of how you perceive the mood of music and determine the music genre will contribute to a fuller understanding of music user behaviors, and also aid in the development of algorithms and systems designed to search and browse music by mood and genre.&nbsp;</p>
<p><b>Risks of Participation</b></p>
<p>Participation as a grader does not involve risks beyond those encountered in daily life.&nbsp;</p>
<p><b>Time Estimates</b></p>
<p>Evaluation of a single music file will take approximately less than 1 minute, completing a group of 20 music files will take you approximately less than 20 minutes.</p>
<p><b>Contact Information</b></p>
<p>If you have any questions or concerns about this study, please contact the investigators.</p>
<p>Project contact address: c/o Dr. J. Stephen Downie, Graduate School of Library and Information Science, 501 E. Daniel St., Champaign, IL 61820; phone: 217-649-3839, fax: 217-244-3302.</p>
<p>If you have any questions about your rights as a participant in this study or any concerns or complaints, please contact the University of Illinois Institutional Review Board at 217-333-2670 (collect calls will be accepted if you identify yourself as a research participant) or via email at irb@illinois.edu.</p>
<p><b>CONSENT TO GRADING PARTICIPATION</b></p>
<p>Music Mood Tagging</p>
<p><b>YOU MUST BE 18 YEARS OF AGE OR OLDER TO PARTICIPATE!</b></p>
<p>I certify that I am 18 years of age or older, I can print out a copy of this consent form, I have read the preceding and that I understand its contents. By selecting "I Agree" below I am freely agreeing to participate in this study by filling out the survey.</p>
			<form action="consent.keti.php" method="post">
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

				<input type="checkbox" value="Y" name="consent_Sign" <?php if ($consent == "Y") { echo "checked"} ?>/><b>I agree</b>
        
				<p>By checking this box, I consent that I have read and understand
				the above statements regarding my voluntary participation in this survey.
				</p>
				<input type="submit" value="Consent"/>
			</form>
		</div>
	</div>
</div>
</body>
</html>


