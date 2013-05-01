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
           $values = ""; 
           foreach ($_POST['Q'.($i+1)] as $value) {
              $values .= $value . ",";
           } 
           $item = $items[$i];
           userSetSubTaskItemValue($loggedInUser, $tid, $subTask, $item['input_Name'], $values);
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
           var val1 = $("input[name='Q" + i + "[]']:checked").val();
           var val2 = $("input[name='Q" + j + "[]']:checked").val();

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
   $("input[type='checkbox']").change(function () {
      var selection=$(this).val();
      checkComplete();
      checkConsistency();
   });
});

function checkComplete() {
   var count = 0;
   for (var i=1; i<=numItems; i++) {
      if ($("input[name='Q" + i + "[]']:checked").val()) {
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
    <li class="overview-list-item">본 과제는 22곡의 노래를 듣고, 보기에 주어지는 &quot;음악 태그 그룹&quot; 중 각 음악에 적합한 &quot;음
악 태그 그룹&quot;(들)을 고르는 것입니다.</li>
    <li class="overview-list-item">30초 길이의 음악을 끝까지 충분히 들어주십시오.</li>
    <li class="overview-list-item">보기에 주어지는 18가지 &quot;음악 태그 그룹&quot; 중 음악이 표현하는 감성에 해당되는 &quot;음악 태그 그룹&quot;을 한개 이상 선택하십시오.</li>
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
            <td valign="center"><input type="checkbox" name="Q<?php echo $i?>[]" value="Choice01" /></td>
            <td><span class="answertext">그룹 1: 침착한, 고요한, 평온한, 위로가 되는, 조용한, 진정시키는, 감미로운 </span></td>
        </tr>
        <tr>
            <td valign="center"><input type="checkbox" name="Q<?php echo $i?>[]" value="Choice02" /></td>
            <td><span class="answertext">그룹 2: 슬픈, 불행한, 구슬픈</span></td>
        </tr>
        <tr>
            <td valign="center"><input type="checkbox" name="Q<?php echo $i?>[]" value="Choice03" /></td>
            <td><span class="answertext">그룹 3: 행복한, 기쁜</span></td>
        </tr>
        <tr>
            <td valign="center"><input type="checkbox" name="Q<?php echo $i?>[]" value="Choice04" /></td>
            <td><span class="answertext">그룹 4: 로맨틱한</span></td>
        </tr>
        <tr>
            <td valign="center"><input type="checkbox" name="Q<?php echo $i?>[]" value="Choice05" /></td>
            <td><span class="answertext">그룹 5: 긍정적인, 열광적인, 의기양양한, 열정적인, 신이 난</span></td>
        </tr>
        <tr>
            <td valign="center"><input type="checkbox" name="Q<?php echo $i?>[]" value="Choice06" /></td>
            <td><span class="answertext">그룹 6: 우울한, 음울한, 어두운</span></td>
        </tr>
        <tr>
            <td valign="center"><input type="checkbox" name="Q<?php echo $i?>[]" value="Choice07" /></td>
            <td><span class="answertext">그룹 7: 화가난, 격양된, 분노한, 격노한</span></td>
        </tr>
        <tr>
            <td valign="center"><input type="checkbox" name="Q<?php echo $i?>[]" value="Choice08" /></td>
            <td><span class="answertext">그룹 8: 비통한, 애절한, 큰 슬픔의, 구슬픈</span></td>
        </tr>
        <tr>
            <td valign="center"><input type="checkbox" name="Q<?php echo $i?>[]" value="Choice09" /></td>
            <td><span class="answertext">그룹 9: 꿈결같은</span></td>
        </tr>
        <tr>
            <td valign="center"><input type="checkbox" name="Q<?php echo $i?>[]" value="Choice10" /></td>
            <td><span class="answertext">그룹 10: 발랄한, 쾌활한, 즐거운, 명랑한, 축제의</span></td>
        </tr>
        <tr>
            <td valign="center"><input type="checkbox" name="Q<?php echo $i?>[]" value="Choice11" /></td>
            <td><span class="answertext">그룹 11: 음울한, 사색하는, 명상적인, 사색적인, 아쉬운</span></td>
        </tr>
        <tr>
            <td valign="center"><input type="checkbox" name="Q<?php echo $i?>[]" value="Choice12" /></td>
            <td><span class="answertext">그룹 12: 공격적인</span></td>
        </tr>
        <tr>
            <td valign="center"><input type="checkbox" name="Q<?php echo $i?>[]" value="Choice13" /></td>
            <td><span class="answertext">그룹 13: 불안한, 조마조마한, 초조한</span></td>
        </tr>
        <tr>
            <td valign="center"><input type="checkbox" name="Q<?php echo $i?>[]" value="Choice14" /></td>
            <td><span class="answertext">그룹 14: 힘을 북돋아 주는, 낙관적인, 자신감 있는</span></td>
        </tr>
        <tr>
            <td valign="center"><input type="checkbox" name="Q<?php echo $i?>[]" value="Choice15" /></td>
            <td><span class="answertext">그룹 15: 희망찬, 갈망하는</span></td>
        </tr>
        <tr>
            <td valign="center"><input type="checkbox" name="Q<?php echo $i?>[]" value="Choice16" /></td>
            <td><span class="answertext">그룹 16: 진심어린</span></td>
        </tr>
        <tr>
            <td valign="center"><input type="checkbox" name="Q<?php echo $i?>[]" value="Choice17" /></td>
            <td><span class="answertext">그룹 17: 비관적인, 냉소적인, 염세적인, 빈정대는</span></td>
        </tr>
        <tr>
            <td valign="center"><input type="checkbox" name="Q<?php echo $i?>[]" value="Choice18" /></td>
            <td><span class="answertext">그룹 18: 신나는, 아주 즐거운, 흥분되는, 황홀한, 흥을 돋우는</span></td>
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

