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
                 $('#check').text("일관된 답을 주셨습니다.");
                 return true;
           } else {
              $('#check').text("답이 일관되지 않습니다." );
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

<div class="highlight-box">
<p><b>과제 설명: </b></p>
<ul class="overview-list">
    <li class="overview-list-item">본 과제는 22곡의 노래를 듣고, 보기에 주어지는 &quot;음악 클러스터&quot; 중 각 음악에 가장 적합한 &quot;음악 클러스터&quot;를 고르는 것입니다.</li>
    <li class="overview-list-item">30초 길이의 음악을 끝까지 충분히 들어주십시오.</li>
    <li class="overview-list-item">보기에 주어지는 5가지 &quot;음악 클러스터&quot; 중 음악이 표현하는 감성에 해당되는 &quot;음악 클러스>터&quot;를 선택하십시오.</li>
    <li class="overview-list-item">답의 일관성을 검사하기 위하여, 동일한 곡이 여러번 등장할 것입니다. 동일한 곡에 대해 동일한 답을 한 경우
 submit하실 수 있습니다. </li>
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
            <td><?php echo $i ?>번 </td>
            <td>
            <p><embed src="http://www.google.com/reader/ui/3523697345-audio-player.swf" flashvars="audioUrl=<?php echo $itemUrl?>" width="400" height="27" quality="best" type="application/x-shockwave-flash" /></p>
            <input type="hidden" id="track-id-<?php echo $i ?>" value="<?php echo $item['input_Value'] ?>">

            </td>
        </tr>
        <tr>
            <td valign="center"><input type="radio" name="Q<?php echo $i ?>" value="Choice1" /></td>
            <td><span class="answertext">클러스터 1: 열정적인, 흥분시키는, 자신감 있는, 활기가 넘치는, 소란스러운</span></td>
        </tr>
        <tr>
            <td valign="center"><input type="radio" name="Q<?php echo $i ?>" value="Choice2" /></td>
            <td><span class="answertext">클러스터 2: 흥겨운, 쾌활한, 즐거운, 달콤한, 상냥한/온화한</span></td>
        </tr>
        <tr>
            <td valign="center"><input type="radio" name="Q<?php echo $i ?>" value="Choice3" /></td>
            <td><span class="answertext">클러스터 3: 세련된, 비통한, 아쉬운, 달콤쌉쌀한, 가을느낌의, 음울한</span></td>
        </tr>
        <tr>
            <td valign="center"><input type="radio" name="Q<?php echo $i ?>" value="Choice4" /></td>
            <td><span class="answertext">클러스터 4: 익살스러운, 우스꽝스러운, 과장된, 별난, 기발한, 재치있는, 비꼬는</span></td>
        </tr>
        <tr>
            <td valign="center"><input type="radio" name="Q<?php echo $i ?>" value="Choice5" /></td>
            <td><span class="answertext">클러스터 5: 공격적인, 불같은, 긴장된/불안한, 강렬한, 변덕스러운, 본능적인</span></td>
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
   <?php echo $numItems?>곡 중 <span id="count">0</span>곡이 완료되었습니다.<br><br>
   <input id="submitButton" type="submit" name="submit" value="Submit" disabled>
   </div>


       </div>
   </div>
</div>
</form>
</body>
</html>

