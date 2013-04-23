<?php
    /*
        UserCake Version: 1.4
        http://usercake.com
        
        Developed by: Adam Davis
    */
    require_once("models/config.php");
    
    //Prevent the user visiting the logged in page if he/she is not logged in
    if (!isUserLoggedIn()) { header("Location: login.php"); die(); }

    $tid = $_GET['task'];
    $task = getTask($tid);
    $consentForm = $task['task_Consent_Form'];

    if (!userHasGivenConsent($loggedInUser, $consentForm)) { header("Location: ". $consentForm ."?assignTask=$tid"); die(); }
    
    if (!isset($_GET['task']) || !isset($_GET['subTask'])) 
    {
        header("Location: assignment.list.php"); die();
    }

    $subTask = $_GET['subTask'];
    $items = userGetSubtaskItems($loggedInUser, $tid, $subTask);    
    $numItems = count($items);

    if (isset($_POST['submit']) )
    {
        // Record scores
        for ($i=0; $i<$numItems; $i++) {
           $value = $_POST['Q'.($i+1)];
           $item = $items[$i];
           userSetSubTaskItemValue($loggedInUser, $tid, $subTask, $item['input_Name'], $value);
        }
        // Redirect to assignment list
        header("Location: assignment.list.php"); die();
    } 
    

    
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>MIREX :: E6K :: <?php echo $loggedInUser->display_username; ?></title>
<link href="mirex.css" rel="stylesheet" type="text/css" />
<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
<script type="text/javascript">

var offsetLeft = 104;
var offsetTop = 102;
var pointSize = '6px';
var pointColor = '#FF0000';
var numItems = <?php echo $numItems ?>;

function checkConsistency()
{
   for (var i=1; i<=numItems; i++) {
      for (var j=i+1; j<=numItems; j++) {
         var id1 = $('#track-id-' + i).val();
         var id2 = $('#track-id-' + j).val();
         if (id1 == id2) {
           var val1 = $("input[name='Q" + i + "']:checked").val();
           var val2 = $("input[name='Q" + j + "']:checked").val();

           if (val1 && val2 && (val1 == val2)) {
                 $('#check').text("Consistency check ok.");
                 return true;
           } else {
              $('#check').text("Consistency check failed" );
           }
         }
      }
   }
   return false;
}

jQuery(document).ready(function() {
   $("input[type='radio']").change(function () {
      var selection=$(this).val();
      checkComplete();
      checkConsistency();
   });
});

function checkComplete() {
   var count = 0;
   for (var i=1; i<=numItems; i++) {
      if ($("input[name='Q" + i + "']:checked").val()) {
         count += 1;
      }
   }
   $('#count').text(count);
   if (checkConsistency() && count == numItems) {
      $('#submitButton').removeAttr('disabled');
   }
}


</script>
</head> 
 
<body> 
<div id="wrapper">

    <div id="content">
    
        <div id="left-nav">
        <?php include("layout_inc/left-nav.php"); ?>
            <div class="clear"></div>
        </div>
        
        <div id="main">
          <h1><?php echo stripslashes($task['task_Name']);?></h1>
          <h2>Sub Task <?php echo enhash($subTask);?></h2>
          <?php
          if ($task['task_Instructions'] != '') {
          ?>
            <div class="alert">
              <strong>Instructions</strong>
              <p>
              <?php echo stripslashes($task['task_Instructions']);?>
              </p>
          </div>
          <?php
          }
          ?>

<form method="post">
  <input type="hidden" name="task" value="<?php echo $tid ?>">
  <input type="hidden" name="subTask" value="<?php echo $subTask ?>">

<h3>Identify the music mood.</h3>
<div class="highlight-box">
<p><b>Instructions:&nbsp;</b></p>
<ul class="overview-list">
	<li class="overview-list-item">Your task is to listen to the following <?php echo $numItems ?>  music clips and select what you think is the most appropriate &ldquo;mood cluster&rdquo; for each song from the given options.</li> 
	<li class="overview-list-item">Please listen to each song for 20~30 seconds. You can listen to different parts of the song to pick the &ldquo;mood cluster&rdquo;.</li> 
	<li class="overview-list-item">Please select the &ldquo;mood cluster&rdquo; that best describes the mood that the song expresses.</li> 
	<li class="overview-list-item">Please answer the questions carefully. Inconsistent or incomplete answers cannot be accepted.</li> 
</ul>
</div>
<table cellspacing="4" cellpadding="0" border="0">
    <tbody>
<?php
  $i = 0;
  foreach($items as $item) {
    $i++;
    $itemUrl = 'http://www.music-ir.org/mirex/e6k_genre/audio/genre_30s/'. $item['input_Value'];
?>
        <tr>
            <td>Question <?php echo $i ?></td>
            <td>
            <p><embed src="http://www.google.com/reader/ui/3523697345-audio-player.swf" flashvars="audioUrl=<?php echo $itemUrl?>" width="400" height="27" quality="best" type="application/x-shockwave-flash" /></p>
            <input type="hidden" id="track-id-<?php echo $i ?>" value="<?php echo $item['input_Value'] ?>">

            </td>
        </tr>
        <tr>
            <td valign="center"><input type="radio" name="Q<?php echo $i ?>" value="Choice1" /></td>
            <td><span class="answertext">Cluster 1: passionate, rousing, confident,boisterous, rowdy</span></td>
        </tr>
        <tr>
            <td valign="center"><input type="radio" name="Q<?php echo $i ?>" value="Choice2" /></td>
            <td><span class="answertext">Cluster 2: rollicking, cheerful, fun, sweet, amiable/good natured</span></td>
        </tr>
        <tr>
            <td valign="center"><input type="radio" name="Q<?php echo $i ?>" value="Choice3" /></td>
            <td><span class="answertext">Cluster 3: literate, poignant, wistful, bittersweet, autumnal, brooding</span></td>
        </tr>
        <tr>
            <td valign="center"><input type="radio" name="Q<?php echo $i ?>" value="Choice4" /></td>
            <td><span class="answertext">Cluster 4: humorous, silly, campy, quirky, whimsical, witty, wry</span></td>
        </tr>
        <tr>
            <td valign="center"><input type="radio" name="Q<?php echo $i ?>" value="Choice5" /></td>
            <td><span class="answertext">Cluster 5: aggressive, fiery,tense/anxious, intense, volatile,visceral</span></td>
        </tr>
<?php } ?>
    </tbody>
</table>


<p><style type="text/css">
<!--
.highlight-box { border:solid 0px #98BE10; background:#FCF9CE; color:#222222; padding:4px; text-align:left; font-size: smaller;}
-->
</style></p>

<div style="margin: 20px; text-align:center">
   <span id="check"></span><br>
   <span id="count">0</span> out of <?php echo $numItems?> items complete<br><br>
   <input id="submitButton" type="submit" name="submit" value="Submit" disabled>
   </div>


       </div>
   </div>
</div>
</form>
</body>
</html>

