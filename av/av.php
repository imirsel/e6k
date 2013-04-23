<?php $assignmentId = $_REQUEST['assignmentId']?>
<?php $workerId = $_REQUEST['workerId']?>
<?php $url = $_REQUEST['url']?>

<html> <head>
 <link rel="stylesheet" href="/av/ui/1.10.2/themes/smoothness/jquery-ui.css" />
<script src="/av/jquery-1.9.1.js"></script>
<script src="/av/ui/1.10.2/jquery-ui.js"></script>
<script>
var offsetLeft = 208;
var offsetTop = 205;
var pointSize = '6px';
var pointColor = '#FF0000';

function showPoint(target, pageX, pageY, x, y, left, top)
{
    $(target).find('.x').val(x);
    $(target).find('.y').val(y);
    $(target).find('.point').remove();
    $(target).find('.pos').remove();
    $(target).append(
         $('<div class="point"></div>')
             .css('position', 'absolute')
             .css('top', (pageY - 3) + 'px')
             .css('left',(pageX - 3) + 'px')
             .css('width', pointSize)
             .css('height', pointSize)
             .css('background-color', pointColor)
     );      
   $('#submitButton').removeAttr('disabled');
}

jQuery(document).ready(function() {
   $( ".slider" ).slider({
      range: "min",
      min: 0,
      max: 100,
      height: 400,
      value: 50,
      slide: function( event, ui ) {
         var x = (ui.value - 50)/5;
         var y = $(this).parent().find('.y').val();

         var left = (20*x) + offsetLeft;
         var top = offsetTop - (20*y);
         var pageX = left + $(this).parent().offset().left;
         var pageY = top + $(this).parent().offset().top;

         showPoint($(this).parent(), pageX, pageY, x, y, left, top);
         $("#tooltipx").text(x);
         $("#tooltipy").text(y);
      }, 
   }).find(".ui-slider-handle").append( 
         $('<div id="tooltipx" style="font-size: 10px"/>').css({ 
            position: 'absolute', 
            top: 25
   }).hide()).hover(function() {
      $("#tooltipx").show()
      $("#tooltipy").show()
   }, function() {
      //$(this).find(".tooltip").hide()
   });

   $( ".slider-vertical" ).slider({
      orientation: "vertical",
      range: "min",
      min: 0,
      max: 100,
      height: 400,
      value: 50,
      slide: function( event, ui ) {
         var y = (ui.value - 50)/5;
         var x = $(this).parent().find('.x').val();

         var left = (20*x) + offsetLeft;
         var top = offsetTop - (20*y);
         var pageX = left + $(this).parent().offset().left;
         var pageY = top + $(this).parent().offset().top;
        
//         $(this).parent().find('.label').text(y + "; top=" + Math.round(top) + "; pageY="  + Math.round(pageY) + "; offsetTop=" + offsetTop + "; parent.offsetTop=" + Math.round($(this).parent().offset().top));

         $(this).find('.y').val(y);

         showPoint($(this).parent(), pageX, pageY, x, y, left, top);
         $("#tooltipx").text(x);
         $("#tooltipy").text(y);
       }
   }).find(".ui-slider-handle").append( 
         $('<div id="tooltipy" style="font-size: 10px; text-decoration: none"/>').css({ 
            position: 'absolute', 
            left: 25
   }).hide()).hover(function() {
      $("#tooltipy").show()
      $("#tooltipx").show()
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

        var x=Math.round((left - offsetLeft)/2)/10;
        var y=Math.round((offsetTop - top)/2)/10;
        
        if (left < 410 && top < 406) {

           var xslider = $(this).find('.slider');
           xslider.slider("value", ((x*10) + 100)/2);
           var yslider = $(this).find('.slider-vertical');
           yslider.slider("value", ((y*10) + 100)/2);

           showPoint($(this), pageX, pageY, x, y, left, top);
           $('#tooltipx').show();
           $('#tooltipx').text(x);
           $('#tooltipy').show();
           $('#tooltipy').text(y);
       }
   }); 
})
</script>
<body>


<form id="mturk_form" method="POST" action="http://www.mturk.com/mturk/externalSubmit">
<input type="hidden" name="assignmentId" value="<?php echo $assignmentId ?>"/>

<table cellspacing="0" cellpadding="0" border="0">
  <tbody>
    <tr>
       <td colspan="3" valign="top">Song:
         <embed src="http://www.google.com/reader/ui/3523697345-audio-player.swf" flashvars="audioUrl=<?php echo $url?>" width="400" height="27" quality="best" type="application/x-shockwave-flash" />
        </td>
      </tr>
    <tr>
      <td>
        <div>
          <div class="track"> 
            <img  src="http://www.music-ir.org/mirex/e6k_genre/av/av_new.jpg" width="415" style="float: left">
              <div class="slider-vertical" id="yslider" style="margin-top: 5px; float: right; height: 400px">
                <input class="y" type="hidden" name="y" value="0"/>
              </div>
              <div class="slider" id="xslider" style="clear: both; margin-left: 5px;width: 400px">
                 <input class="x" type="hidden" name="x" value="0"/>
              </div>
          </div>
        </div>
      </td>
      <td width="90"></td>
      <td valign="top" width="300" style="bgcolor: #cccccc; font-size: small; font-family: arial">
         <h2>Instructions:</h2>
         <ul>
           <li>Listen to the complete song
           <li>Click on the image or move the sliders to choose a point that best represents how the song makes you feel.
         </ul>
      </td>
    </tr>
  </tbody>
</table>



<br/>
<input id="submitButton" type="submit" name="Submit" value="Complete HIT" disabled>
<p><style type="text/css">
<!--
.highlight-box { border:solid 0px #98BE10; background:#FCF9CE; color:#222222; padding:4px; text-align:left; font-size: smaller;}
-->
</style></p>


</form>
</body>
</html>
