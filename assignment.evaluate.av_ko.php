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
     
           $x = $_POST['x'.($i+1)];
           $y = $_POST['y'.($i+1)];
           $item = $items[$i];
           userSetSubTaskItemValue($loggedInUser, $tid, $subTask, $item['input_Name'], $x.",".$y);
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
           var ax = $('#x' + i).val();
           var ay = $('#y' + i).val();
           var bx = $('#x' + j).val();
           var by = $('#y' + j).val();

           if (ax != "" && ay != "" && bx != "" && by != "") {
               var dist = Math.sqrt(Math.pow((ax - bx),2) + Math.pow((ay-by),2));
               if (dist <= 2.0) {
                  $('#check').text("Consistency check ok.");
                  return true;
               } else {
                  $('#check').text("Consistency check failed" );
               }
           }
         }
      }
   }
   return false;
}

function showPoint(target, pageX, pageY, x, y, left, top)
{
    $(target).find('.x').val(x);
    $(target).find('.y').val(y);
    $(target).find('.point').remove();
    $(target).find('.pos').remove();
    $(target).append(
         $('<div class="point"></div>')
             .css('position', 'absolute')
             .css('top', (pageY - 1) + 'px')
             .css('left',(pageX - 2) + 'px')
             .css('width', pointSize)
             .css('height', pointSize)
             .css('background-color', pointColor)
     );
   var id = $(target).find('.id').val();
   $('#status-' + id).text('입력완료');
   $('#status-' + id).css('color', 'green');

   var count = 0;
   for (var i=1; i<=numItems; i++) {
      if ($('#status-' + i).text() == '입력완료') {
         count += 1;
      }
   }
   $('#count').text(count);
   if (checkConsistency() && count == numItems) {
      $('#submitButton').removeAttr('disabled');
   }
}

jQuery(document).ready(function() {
   $( ".slider" ).slider({
      range: "min",
      min: 0,
      max: 20,
      height: 200,
      value: 10,
      step: .1,
      slide: function( event, ui ) {
         var x = Math.round((ui.value - 10)*10)/10;
         var y = $(this).parent().find('.y').val();

         var left = (10*x) + offsetLeft;
         var top = offsetTop - (10*y);
         var pageX = left + $(this).parent().offset().left;
         var pageY = top + $(this).parent().offset().top;

         showPoint($(this).parent(), pageX, pageY, x, y, left, top);
         $(this).parent().find(".tooltipx").show();
         $(this).parent().find(".tooltipy").show();
         $(this).parent().find(".tooltipx").text(x);
         $(this).parent().find(".tooltipy").text(y);
      },
   }).find(".ui-slider-handle").append(
         $('<div class="tooltipx" style="font-size: 10px"/>').css({
            position: 'absolute',
            top: 25
   }).hide()).hover(function() {
      $(this).parent().find(".tooltipx").show();
      $(this).parent().find(".tooltipy").show();
   }, function() {
      //$(this).find(".tooltip").hide()
   });

   $( ".slider-vertical" ).slider({
      orientation: "vertical",
      range: "min",
      min: 0,
      max: 20,
      height: 200,
      value: 10,
      step: .1,
      slide: function( event, ui ) {
         var y = Math.round((ui.value - 10)*10)/10;
         var x = $(this).parent().find('.x').val();

         var left = (10*x) + offsetLeft;
         var top = offsetTop - (10*y);
         var pageX = left + $(this).parent().offset().left;
         var pageY = top + $(this).parent().offset().top;

         $(this).find('.y').val(y);

         showPoint($(this).parent(), pageX, pageY, x, y, left, top);
         $(this).parent().find(".tooltipx").show();
         $(this).parent().find(".tooltipy").show();
         $(this).parent().find(".tooltipx").text(x);
         $(this).parent().find(".tooltipy").text(y);
       }
   }).find(".ui-slider-handle").append(
         $('<div class="tooltipy" style="font-size: 10px; text-decoration: none"/>').css({
            position: 'absolute',
            left: 25
   }).hide()).hover(function() {
      $(this).parent().find()(".tooltipx").show()
      $(this).parent().find()(".tooltipy").show()
   }, function() {
      //$(this).find(".tooltip").hide()
   });

   $(".track").click(function(e, ui){

        var pageX = Math.round(e.pageX/2)
        var pageY = Math.round(e.pageY/2)
        pageX = pageX * 2;
        pageY = pageY * 2;
        var left = Math.round((pageX - $(this).offset().left));
        var top = Math.round((pageY - $(this).offset().top));

        var x=Math.round((left - offsetLeft)/2)/5;
        if (x > 10) { x = 10; }
        if (x < -10) { x = -10; }
        var y=Math.round((offsetTop - top)/2)/5;
        if (y > 10) { y = 10; };
        if (y < -10) { y = -10; };

        if (left < 210 && top < 210) {

           var xslider = $(this).find('.slider');
           xslider.slider("value", (x+10));
        //   xslider.slider("value", ((x*10) + 100)/2);
           var yslider = $(this).find('.slider-vertical');
           //yslider.slider("value", ((y*10) + 100)/2);
           yslider.slider("value", (y+10));

           showPoint($(this), pageX, pageY, x, y, left, top);
           $(this).parent().find('.tooltipx').show();
           $(this).parent().find('.tooltipy').show();
           $(this).parent().find('.tooltipx').text(x);
           $(this).parent().find('.tooltipy').text(y);
       }
   });
})

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
<table cellspacing="0" cellpadding="0" border="0" width="100%">
  <tbody>
<?php 
  $i = 0;
  foreach($items as $item) {
    $i++;
?>
    <tr height="40px"><td colspan="4"><hr/></td></tr>
    <tr class="track-<?php echo $i ?>">
       <td colspan="3" valign="top"><?php echo $i ?>번
    </tr>
    <tr class="track-<?php echo $i?>">
       <td colspan="1" valign="top">
         <embed src="http://www.google.com/reader/ui/3523697345-audio-player.swf" flashvars="audioUrl=<?php echo 'http://www.music-ir.org/mirex/e6k_genre/audio/genre_30s/'. $item['input_Value'] ?>" width="230" height="27" quality="best" type="application/x-shockwave-flash" />
         <input type="hidden" id="track-id-<?php echo $i ?>" value="<?php echo $item['input_Value'] ?>">
        </td>
       <td><div id="status-<?php echo $i?>" style="margin-left: 40px; color: red">미완료</div></td>
      </tr>
    <tr class="track-<?php echo $i?>">
      <td width="230">
        <div>
          <div class="track">
              <input class="id" type="hidden" value="<?php echo $i?>"/>
              <img  src="av/arousal_valence_blank.jpg" width="210" style="float: left; margin-bottom: 5px;">
              <div class="slider-vertical" id="yslider" style="margin-top: 5px; float: right; height: 200px">
                <input id="y<?php echo $i ?>" class="y" type="hidden" name="y<?php echo $i?>" value=""/>
              </div>
              <div class="slider" id="xslider" style="clear: both; margin-left: 2px; width: 205px">
                 <input id="x<?php echo $i?>" class="x" type="hidden" name="x<?php echo $i?>" value=""/>
              </div>
          </div>
        </div>
      </td>
      <td >
        <img src="av/av_guide.jpg" width="250" style="margin-left: 40px">
      </td>
    </tr>
<?php } ?>
  </tbody>
</table>

<div style="margin: 20px; text-align:center">
   <span id="check"></span><br>
   <?php echo $numItems?>곡 중 <span id="count">0</span>곡이 완료되었습니다.<br><br>
   <input id="submitButton" type="submit" name="submit" value="Submit" disabled>
   </div>
<p><style type="text/css">


       </div>
   </div>
</div>
</form>
</body>
</html>

