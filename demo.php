<?php 
	/*
		UserCake Version: 1.4
		http://usercake.com
		
		Developed by: Adam Davis
	*/
	require_once("models/config.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml"> 
<head> 
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
<title>MIREX :: E6K :: DEMO</title> 
<link href="mirex.css" rel="stylesheet" type="text/css" /> 
<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/> 
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js"></script> 
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script> 
<script type="text/javascript"> 
var state = {"d019312":{"b":"","f":"-1"},
			 "a006799":{"b":"","f":"-1"},
			 "e012948":{"b":"","f":"-1"},
			 "b010010":{"b":"","f":"-1"},
			 "d002244":{"b":"","f":"-1"}}
updateRelevance = function(candidate, type, score) 
{
	state[candidate][type] = score;
	colorRow(candidate);
}
 
colorRow = function(candidate) 
{
	if ((state[candidate]['b'] != "") && (state[candidate]['f'] > -1)) {
		$("#row-"+candidate+" > td").css("background-color", "#9c6");		
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
        
        <div id="main" style="background:url('layout/demo.png')"> 
        <h1>Demo Query</h1> 
        		<div class="alert"> 
			<strong>Instructions</strong> 
			<p> 
			<strong>THIS IS A DEMO. The similarity judgments you make on this page are not saved.</strong><br/>
			Rate the similarity of the following Query-Candidate pairs. Assign a categorical similarity (Not similar, Somewhat Similar, or Very Similar) and a numeric similarity score. The numeric similarity score ranges from 0 (not similar) to 100 (very similar or identical).
			</p> 
		</div> 
			<table cellspacing="0px" cellpadding="5px"> 
			<tbody> 
				<tr> 
					<th>Listen</th> 
					<th>Categorical Similarity</th> 
					<th>Similarity</th> 
				</tr> 
				<tr> 
					<td>Same query for all candidates</td> 
					<td> 
						<table width="175px"> 
							<tr align="center"> 
								<td>Not Similar</td> 
								<td>Somewhat Similar</td> 
								<td>Very Similar</td> 
							</tr> 
						</table> 
					</td> 
					<td>(0: Low) to (100: High)</td> 
				</tr> 
                			<tr id="row-d019312"> 
						<td> 
							<div style="font-weight:bold">b4a</div> 
							<a type="audio/mpeg" href="http://www.music-ir.org/mirex/e6k/audio/test/b008067.mp3?c=d019312">Query</a><br/> 
							<a type="audio/mpeg" href="http://www.music-ir.org/mirex/e6k/audio/test/d019312.mp3?i=0">Candidate</a> 
						</td> 
						<td align="center"> 
							<table width="175px"> 
								<tr align="center"> 
																			<td> 
											NS											<input 	type="radio" 

													value="NS" 

													name="broad-d019312" 

													onclick="updateRelevance('d019312', 'b', 'NS')"/> 
										</td> 
																				<td> 
											SS											<input 	type="radio" 

													value="SS" 

													name="broad-d019312" 

													onclick="updateRelevance('d019312', 'b', 'SS')"

																										/> 
										</td> 
																				<td> 
											VS											<input 	type="radio" 

													value="VS" 

													name="broad-d019312" 

													onclick="updateRelevance('d019312', 'b', 'VS')"

																										/> 
										</td> 
																			</tr> 
							</table> 
						</td> 
						<td> 
							<table> 
								<tr> 
									<td><div style="width:130px;margin-bottom:10px;margin-top:10px;" id="slider-d019312"></div></td> 
									<td><input type="text" size="2" id="fine-d019312" value="0"/></td></tr> 
							</table> 
						</td> 
					</tr> 
				</div> 
		        			<tr id="row-a006799"> 
						<td> 
							<div style="font-weight:bold">beh</div> 
							<a type="audio/mpeg" href="http://www.music-ir.org/mirex/e6k/audio/test/b008067.mp3?c=a006799">Query</a><br/> 
							<a type="audio/mpeg" href="http://www.music-ir.org/mirex/e6k/audio/test/a006799.mp3?i=1">Candidate</a> 
						</td> 
						<td align="center"> 
							<table width="175px"> 
								<tr align="center"> 
																			<td> 
											NS											<input 	type="radio" 

													value="NS" 

													name="broad-a006799" 

													onclick="updateRelevance('a006799', 'b', 'NS')"

																										/> 
										</td> 
																				<td> 
											SS											<input 	type="radio" 

													value="SS" 

													name="broad-a006799" 

													onclick="updateRelevance('a006799', 'b', 'SS')"

																										/> 
										</td> 
																				<td> 
											VS											<input 	type="radio" 

													value="VS" 

													name="broad-a006799" 

													onclick="updateRelevance('a006799', 'b', 'VS')"

																										/> 
										</td> 
																			</tr> 
							</table> 
						</td> 
						<td> 
							<table> 
								<tr> 
									<td><div style="width:130px;margin-bottom:10px;margin-top:10px;" id="slider-a006799"></div></td> 
									<td><input type="text" size="2" id="fine-a006799" value="0"/></td></tr> 
							</table> 
						</td> 
					</tr> 
				</div> 
		        			<tr id="row-e012948"> 
						<td> 
							<div style="font-weight:bold">bbg</div> 
							<a type="audio/mpeg" href="http://www.music-ir.org/mirex/e6k/audio/test/b008067.mp3?c=e012948">Query</a><br/> 
							<a type="audio/mpeg" href="http://www.music-ir.org/mirex/e6k/audio/test/e012948.mp3?i=2">Candidate</a> 
						</td> 
						<td align="center"> 
							<table width="175px"> 
								<tr align="center"> 
																			<td> 
											NS											<input 	type="radio" 

													value="NS" 

													name="broad-e012948" 

													onclick="updateRelevance('e012948', 'b', 'NS')"

																										/> 
										</td> 
																				<td> 
											SS											<input 	type="radio" 

													value="SS" 

													name="broad-e012948" 

													onclick="updateRelevance('e012948', 'b', 'SS')"

																										/> 
										</td> 
																				<td> 
											VS											<input 	type="radio" 

													value="VS" 

													name="broad-e012948" 

													onclick="updateRelevance('e012948', 'b', 'VS')"

																										/> 
										</td> 
																			</tr> 
							</table> 
						</td> 
						<td> 
							<table> 
								<tr> 
									<td><div style="width:130px;margin-bottom:10px;margin-top:10px;" id="slider-e012948"></div></td> 
									<td><input type="text" size="2" id="fine-e012948" value="0"/></td></tr> 
							</table> 
						</td> 
					</tr> 
				</div> 
		        			<tr id="row-b010010"> 
						<td> 
							<div style="font-weight:bold">anz</div> 
							<a type="audio/mpeg" href="http://www.music-ir.org/mirex/e6k/audio/test/b008067.mp3?c=b010010">Query</a><br/> 
							<a type="audio/mpeg" href="http://www.music-ir.org/mirex/e6k/audio/test/b010010.mp3?i=3">Candidate</a> 
						</td> 
						<td align="center"> 
							<table width="175px"> 
								<tr align="center"> 
																			<td> 
											NS											<input 	type="radio" 

													value="NS" 

													name="broad-b010010" 

													onclick="updateRelevance('b010010', 'b', 'NS')"

																										/> 
										</td> 
																				<td> 
											SS											<input 	type="radio" 

													value="SS" 

													name="broad-b010010" 

													onclick="updateRelevance('b010010', 'b', 'SS')"

																										/> 
										</td> 
																				<td> 
											VS											<input 	type="radio" 

													value="VS" 

													name="broad-b010010" 

													onclick="updateRelevance('b010010', 'b', 'VS')"

																										/> 
										</td> 
																			</tr> 
							</table> 
						</td> 
						<td> 
							<table> 
								<tr> 
									<td><div style="width:130px;margin-bottom:10px;margin-top:10px;" id="slider-b010010"></div></td> 
									<td><input type="text" size="2" id="fine-b010010" value="0"/></td></tr> 
							</table> 
						</td> 
					</tr> 
				</div> 
		        			<tr id="row-d002244"> 
						<td> 
							<div style="font-weight:bold">aze</div> 
							<a type="audio/mpeg" href="http://www.music-ir.org/mirex/e6k/audio/test/b008067.mp3?c=d002244">Query</a><br/> 
							<a type="audio/mpeg" href="http://www.music-ir.org/mirex/e6k/audio/test/d002244.mp3?i=4">Candidate</a> 
						</td> 
						<td align="center"> 
							<table width="175px"> 
								<tr align="center"> 
																			<td> 
											NS											<input 	type="radio" 

													value="NS" 

													name="broad-d002244" 

													onclick="updateRelevance('d002244', 'b', 'NS')"

																										/> 
										</td> 
																				<td> 
											SS											<input 	type="radio" 

													value="SS" 

													name="broad-d002244" 

													onclick="updateRelevance('d002244', 'b', 'SS')"

																										/> 
										</td> 
																				<td> 
											VS											<input 	type="radio" 

													value="VS" 

													name="broad-d002244" 

													onclick="updateRelevance('d002244', 'b', 'VS')"

																										/> 
										</td> 
																			</tr> 
							</table> 
						</td> 
						<td> 
							<table> 
								<tr> 
									<td><div style="width:130px;margin-bottom:10px;margin-top:10px;" id="slider-d002244"></div></td> 
									<td><input type="text" size="2" id="fine-d002244" value="0"/></td></tr> 
							</table> 
						</td> 
					</tr> 
				</div> 
				</table> 
		<script type="text/javascript"> 
			$(document).ready(function() {
			$("#slider-d019312").slider({ 
				min: 0, 
				max: 100,
				step: 1,
				value: -1,
				stop: function(event, ui) { 
					var x = $("#fine-d019312");
					x[0].value=ui.value;
					updateRelevance('d019312', 'f', ui.value);
				}
			});
				$("#slider-a006799").slider({ 
				min: 0, 
				max: 100,
				step: 1,
				value: -1,
				stop: function(event, ui) { 
					var x = $("#fine-a006799");
					x[0].value=ui.value;
					updateRelevance('a006799', 'f', ui.value);
				}
			});
				$("#slider-e012948").slider({ 
				min: 0, 
				max: 100,
				step: 1,
				value: -1,
				stop: function(event, ui) { 
					var x = $("#fine-e012948");
					x[0].value=ui.value;
					updateRelevance('e012948', 'f', ui.value);
				}
			});
				$("#slider-b010010").slider({ 
				min: 0, 
				max: 100,
				step: 1,
				value: -1,
				stop: function(event, ui) { 
					var x = $("#fine-b010010");
					x[0].value=ui.value;
					updateRelevance('b010010', 'f', ui.value);
				}
			});
				$("#slider-d002244").slider({ 
				min: 0, 
				max: 100,
				step: 1,
				value: -1,
				stop: function(event, ui) { 
					var x = $("#fine-d002244");
					x[0].value=ui.value;
					updateRelevance('d002244', 'f', ui.value);
				}
			});
			});
 		</script> 
		<script type="text/javascript"> 
		var YMPParams = { displaystate: 3, autoadvance: false }
		</script> 
		<script type="text/javascript" src="http://mediaplayer.yahoo.com/latest"></script> 
		<div> 
			<h2>Finish</h2> 
			<p>Quickly scan the candidates above, all rows should be green, indicating that
			you've successfully completed each evaluation. Ordinarily, your similarity judgments
			are saved automatically when you make them. However, this is a demo and results are 
			not saved. Reloading this page will reset the judgments you've already made.
			</p> 
		</div> 
		</div>  
	</div> 
</div> 
</body> 
</html> 
 