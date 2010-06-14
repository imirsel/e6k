<?php
	/*
		UserCake Version: 1.4
		http://usercake.com
		
		Developed by: Adam Davis
	*/
	require_once("models/config.php");
	
	//Prevent the user visiting the logged in page if he/she is not logged in
	if (!isUserLoggedIn()) { header("Location: login.php"); die(); }
	if (!userHasGivenConsent($loggedInUser)) { header("Location: consent.php"); die(); }
	
	if (!isset($_GET['task']) || !isset($_GET['query'])) 
	{
		header("Location: assignment.list.php"); die();
	}
	
	$tid = $_GET['task'];
	$query = $_GET['query'];
	$task = getTask($tid);

	$candidates = userGetCandidates($loggedInUser, $tid, $query);	
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
updateRelevance = function(candidate, type, score) 
{
	var update = "relevance.update.php?t=<?php echo $tid;?>&q=<?php echo $query;?>&c=";
	update += escape(candidate) + "&" + type + "=" + score;
	$.ajax({ url: update, context: document.body});
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
		<table>
			<tbody>
				<tr>
					<th>Listen</th>
					<th>Categorical Similarity</th>
					<th> Similarity</th>
				</tr>
				<tr>
					<td></td>
					<td>
						<table width="175px">
							<tr align="center">
								<td>Not Similar</td>
								<td>Somewhat Similar</td>
								<td>Very Similar</td>
							</tr>
						</table>
					</td>
					<td></td>
				</tr>
        <?php
        	foreach ($candidates as $c) 
        	{
        		?>
        			<tr>
						<td>
							<a type="audio/mpeg" href="<?php echo genMP3URL($task['task_MP3'], $query);?>">Query</a><br/>
							<a type="audio/mpeg" href="<?php echo genMP3URL($task['task_MP3'], $c['result_Candidate']);?>">Candidate</a>
						</td>
						<td align="center">
							<table width="175px">
								<tr align="center">
									<?php
									$bs = array("NS", "SS", "VS");
									foreach ($bs as $b) 
									{
										?>
										<td>
											<input 	type="radio" 
													value="<?php echo $b;?>" 
													name="broad-<?php echo $c['result_Candidate'];?>" 
													onclick="updateRelevance('<?php echo $c['result_Candidate'];?>', 'b', '<?php echo $b;?>')"
													<?php echo ($c['result_Broad'] == $b) ? "checked='true'" : "";?>
													/>
										</td>
										<?php
									}
									?>
									</tr>
							</table>
						</td>
						<td>
							<table>
								<tr>
									<td><div style="width:125px;margin-bottom:10px;margin-top:10px;" id="slider-<?php echo $c['result_Candidate'];?>"></div></td>
									<td><input type="text" size="2" id="fine-<?php echo $c['result_Candidate'];?>" value="<?php echo ($c['result_Fine'] >= 0 ? $c['result_Fine'] : 0);?>"/></td></tr>
							</table>
						</td>
					</tr>
				</div>
        		<?php
        	}        
        ?>
		</table>
		<script type="text/javascript">
			$(document).ready(function() {
			<?php
        	foreach ($candidates as $c) 
			{
			?>$("#slider-<?php echo $c['result_Candidate'];?>").slider({ 
				min: 0, 
				max: 100,
				step: 1,
				value: <?php echo ($c['result_Fine'] >= 0 ? $c['result_Fine'] : 0);?>,
				stop: function(event, ui) { 
					var x = $("#fine-<?php echo $c['result_Candidate'];?>");
					x[0].value=ui.value;
					updateRelevance('<?php echo $c['result_Candidate'];?>', 'f', ui.value);
				}
			});
			<?php
			}
			?>
			});
		</script>
		<script type="text/javascript">
		var YMPParams = { displaystate: 3 }
		</script>
		<script type="text/javascript" src="http://mediaplayer.yahoo.com/latest"></script>
		</div>  
	</div>
</div>
</body>
</html>

