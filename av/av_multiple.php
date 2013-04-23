<?php 
   $assignmentId = $_REQUEST['assignmentId'];
   $workerId = $_REQUEST['workerId'];
   $mp3 = array();
   for ($i=1; $i<=11; $i++) {
      $mp3[$i] = $_REQUEST['a'.$i];
   }
?>

<html> <head>
 <link rel="stylesheet" href="/av/ui/1.10.2/themes/smoothness/jquery-ui.css" />
<script src="/av/jquery-1.9.1.js"></script>
<script src="/av/ui/1.10.2/jquery-ui.js"></script>
<style>
  body { 
     font-family: arial;
     font-size: 12px;
  }
</style>
<script>
var offsetLeft = 104;
var offsetTop = 102;
var pointSize = '6px';
var pointColor = '#FF0000';

function checkConsistency()
{
   for (var i=1; i<=11; i++) {
      for (var j=i+1; j<=11; j++) {
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
                  $('#check').text("Consistency check failed (" + dist + ") (" + ax + "," + bx + "," + ay + "," + by + ")" );
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
   $('#status-' + id).text('Song score entered');
   $('#status-' + id).css('color', 'green');

   var count = 0;
   for (var i=1; i<=11; i++) {
      if ($('#status-' + i).css('display') == 'none') {
         count += 1;
      }
   }
   $('#count').text(count);
   if (checkConsistency() && count == 11) {
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
<body>


<form id="mturk_form" method="POST" action="http://www.mturk.com/mturk/externalSubmit">
<input type="hidden" name="assignmentId" value="<?php echo $assignmentId ?>"/>

<div style="border: 1px #cccccc solid; background-color: #FFFFE0; padding: 10px; margin: 5px">
  <ol>
    <li>Your task for this HIT is to listen to the following eleven (11) music clips and select what you think are the most appropriate valence (pleasure) and arousal (energy) scores for each song.
    <li>Please listen to the entire thirty-second (30-second) clip for each of the songs.
    <li>To answer a question, you can click a point in the 2 dimensional plane or move sliders. 
    <li>The pleasure  score indicates negative vs. positive mood expressed by the song: -10 represents very negative; 10 represents very positive. The values in between represent different levels of negative or positive moods. (Note: the pleasure score here is what the piece tries to express, NOT how you like it)
    <li>The energy score indicates  low-energy vs. high-energy expressed by the song: -10 represents very low energy, 10 represents very high energy. The values in between represent different levels of low-energy or high-energy,
    <li>To check for consistency, there are identical clips in each HIT (set of eleven clips). Your scores given must be consistent within each identical set in order to be accepted.
  </ol>
</div>
<table cellspacing="0" cellpadding="0" border="0" width="100%">
  <tbody>
<?php for ($i=1; $i<=11; $i++) { ?>
    <tr height="40px"><td colspan="4"><hr/></td></tr>
    <tr class="track-<?php echo $i?>">
       <td colspan="3" valign="top">Song: <?php echo $i?>:
    </tr>
    <tr class="track-<?php echo $i?>">
       <td colspan="1" valign="top">
         <embed src="http://www.google.com/reader/ui/3523697345-audio-player.swf" flashvars="audioUrl=<?php echo 'http://www.music-ir.org/mirex/e6k_genre/audio/genre_30s/'. $mp3[$i] ?>" width="230" height="27" quality="best" type="application/x-shockwave-flash" />
         <input type="hidden" id="track-id-<?php echo $i ?>" value="<?php echo $mp3[$i] ?>">
        </td>
       <td><div id="status-<?php echo $i?>" style="margin-left: 40px; color: red">Not done yet</div></td>
      </tr>
    <tr class="track-<?php echo $i?>">
      <td width="230">
        <div>
          <div class="track"> 
              <input class="id" type="hidden" value="<?php echo $i?>"/>
              <img  src="arousal_valence_blank.jpg" width="210" style="float: left; margin-bottom: 5px;">
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
        <img src="av_guide.jpg" width="250" style="margin-left: 40px"> 
      </td>
    </tr>
<?php } ?>
  </tbody>
</table>

<br/>

<div style="margin: 20px; text-align:center">
   <span id="check"></span><br>
   <span id="count">0</span> out of 11 items complete<br><br>
   <input id="submitButton" type="submit" name="Submit" value="Complete HIT" disabled>
   </div>
<p><style type="text/css">
<!--
.highlight-box { border:solid 0px #98BE10; background:#FCF9CE; color:#222222; padding:4px; text-align:left; font-size: smaller;}
-->
</style></p>


</form>
</body>
</html>
