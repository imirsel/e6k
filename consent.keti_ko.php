<?php
	/*
		UserCake Version: 1.4
		http://usercake.com

		Developed by: Adam Davis
	*/
	require_once("models/config.php");

	//Prevent the user visiting the logged in page if he/she is not logged in
	if (!isUserLoggedIn()) { header("Location: login.php"); die(); }

        if (isset($_POST['assignTask'])) {
	    $tid = $_POST['assignTask'];
        }
        else if (isset($_GET['assignTask'])) {
           $tid = $_GET['assignTask'];
	}
	$task = getTask($tid);
	$consentForm = $task['task_Consent_Form'];

	$consent = userHasGivenConsent($loggedInUser, $consentForm);
	$status = $consent['consent_Status'];

	if (empty($consent) &&
		isset($_POST['consent_Sign']) &&
		($_POST['consent_Sign'] == 'Y'))
	{
		userGiveConsent($loggedInUser, $consentForm);

		$assignments = userGetAssignments($loggedInUser, $tid);
		if (count($assignments) == 0) {
                    if ($task['task_Type'] == 'Subtask') {
                        $assignments = userAssignSubtask($loggedInUser, $tid);
                    } else {
                        $assignments = userAssignQueries($loggedInUser, $tid);
                    }
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
<p>"음악 감성 설문" HIT에 참여하실 수 있는 자격을 얻기 위해서는 꼭 지금 보시고 계신 자격 시험을 반드시 통과하셔야 합니다. </p>
<p>연구자: J. Stephen Downie (jdownie@illinois.edu), Xiao Hu (xiaoxhusmile@gmail.com), Jin Ha Lee (jinhalee@uw.edu)
<br/>프로젝트 관련 연락처: c/o Dr. J. Stephen Downie, Graduate School of Library and Information Science, 501 E. Daniel St., Champaign, IL 61820; 전화: 217-649-3839, 팩스: 217-244-3302.</p>
<p>아래 내용을 정독하시기 바랍니다.</p>
<p>당신은 음악이 표현하는 감성을 태깅하는 연구 참여에 초대되셨습니다. 본 연구는 일리노이 주립대의 J. Stephen Downie 박사님과 Xiao Hu 박사님 그리고 워싱턴 주립대의 이진하 교수님의 주관으로 진행됩니다. 본 연구의 목적은 음악 사용자들에 대한 이해를 높여 음악 정보 검색 시스템을 발전시키고자 하는 것입니다. 특별히, 저희는 음악의 감성을 사람들이 어떻게 인지하고 판단하는지에 관심이 있습니다. 당신은 각 음악을 듣고 각 음악이 표현하고 있는 감성이 무엇인지 고르게 될 것입니다. 당신이 제공한 데이터는 향후 연구자들이 좀 더 나은 사용자 인터페이스를 제공할 수 있는 음악 정보 검색 서비스를 개발하는데 사용될 것입니다.</p>
<p>당신의 참여는 순수하게 자발적입니다. 22개의 노래에 대해 답을 하시고 2.0의 보상을 받으실 것입니다. 제공하는 모든 정보는 참여자의 사생활 보호를 위하여 향후 출판이나 발표에 사용될 때 모두 익명으로 처리될 것입니다. 따라서 우리는 아마존 미케니컬 터크가 제공하는 익명성 보장 기능을 그대로 사용할 것입니다. 사용자에 대한 모든 개인 정보들 (예를 들어 거주 지역, 이메일 주소, 웹사이트 주소, 반응 시간 등등)은 모두 기밀로 처리할 것입니다. 참여자의 대답이 개인정보와 함께 저장되지 않습니다. 참여자의 데이터는 향후 연구자들이 음악 정보 검색 시스템을 학습시키고 테스트하는 데이터 셋을 만드는데 도움이 될 것입니다.</p>
<p>참여자가 제공하는 정보는 음악 이용자들의 행동을 이해하는데 도움이 될 것입니다. 또한 감성을 사용하여 음악을 검색하는 알고리즘 개발을 돕는데도 사용될 것입니다. 이 설문에 참여하는 것이 우리가 일상 생활에서 겪는 위험 그 이상을 요구하지 않습니다. 한 곡을 평가하는데는 20~30초가 걸릴 것이고, 22곡을 모두 평가하는데 약 10분 정도가 소요될 것입니다.</p>
<p>본 연구에 대해 질문 사항이 있으시면 연구 진행자에게 연락을 주십시오.</p>
<p>본 연구에 참여자로서의 권리에 대해 질문이 있거나 불만사항이 있다면, 일리노이 주립대의 기관 감사 위원회에 전화나 메일로 연락을 주시기 바랍니다. 메일 주소는 irb@illinois.edu이고 전화번호는  217-333-2670입니다. 만약 당신이 연구 참여자임을 입증할 수 있다면 수신자 부담 전화를 하실 수 있습니다. </p>
<p><b>당신은 반드시 18세 이상의 한국인이어야 합니다.</b>
<br/>본인은 18세 이상의 한국인이고 본 동의서를 출력할 수 있고, 전문을 다 읽고 이해하였습니다. 아래의 "동의합니다"를 선택함으로써, 이 설문에 답하여 본 연구에 참여하는 것을 동의합니다.</p>

			<form action="consent.keti_ko.php" method="post">
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

              <input type="checkbox" value="Y" name="consent_Sign" <?php if ($status == "Y") { echo "checked"; } ?>/><b>I agree</b>

        
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


