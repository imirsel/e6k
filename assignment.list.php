<?php
    /*
        UserCake Version: 1.4
        http://usercake.com
        
        Developed by: Adam Davis
    */
    require_once("models/config.php");
    
    //Prevent the user visiting the logged in page if he/she is not logged in
    if(!isUserLoggedIn()) { header("Location: login.php"); die(); }
    
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
            <h2>Assignments for <?php echo $loggedInUser->display_username;?></h2>
            <div class="alert">Please be careful when signing up for evaluation assignments and make
                sure you are signing up for the correct task.</div>
            <?php
            $tasks = getTasks();
            foreach ($tasks as $tid=>$task) {
                ?>
                <h3>Task: <?php echo stripslashes($task['task_Name']);?></h3>
                <div style="margin-left: 15px; margin-bottom:10px;padding-bottom:15px">
                <?php
                  if ($task['task_Type'] == 'Subtask') {
                      $assignments = userGetSubtasks($loggedInUser, $tid); 
                  } else {
                      $assignments = userGetAssignments($loggedInUser, $tid); 
                  }
                  if (count($assignments) == 0) 
                  {
                    ?>
                    
                    <?php 
                        if ($task['task_Type'] == 'Subtask') {
                           $avail = countAvailableSubtasks($tid, $loggedInUser);
                        } else {   
                           $avail = countAvailableAssignments($tid);
                        }
                        
                        if ($avail > 0)
                        {    
                        ?>
                        <div>
                            <form action="assignment.get.php" method="post">
                                <input type="hidden" name="task" value="<?php echo $tid;?>"/>
                                <input type="submit" value="Get Assignment"> (<?php echo $avail ?> assignments available to you.)
                            </form>
                        </div>
                        <?php
                        }
                        else
                        {
                           ?>
                              <div>
                                 There are no assignements available for this task.
                              </div>
                           <?php
                        }                        
                }
                else 
                {
                    ?>
                    <?php
                    $incomplete = 0;
                    $complete = 0;
                    foreach ($assignments as $assignment) 
                    {
                        if ($task['task_Type'] == 'Subtask') {
                            $status = userGetSubtaskStatus($loggedInUser, $tid, $assignment);
                            $sc = $status['completed'];
                            $st = $status['total'];
                            if ($sc == $st) { $complete += 1; }
                            else if ($sc < $st) { 
                                $incomplete += 1; 
                            ?>
                            <div class="sub">
                               <table>
                               <tr> 
                                 <td>
                                   <div style="float:left; height:10px;width:<?php echo floor(200 * $sc/$st);?>px;background:#0c0;border-width:1px 0px 1px 1px;border-color:gray;border-style:solid;"></div>
                                   <div style="float:left;height:10px;width:<?php echo ceil(200 * (($st-$sc)/$st));?>px;background:#c00;border-width:1px 1px 1px 0px;border-color:gray;border-style:solid;"></div>
                                 </td>
                                 <td rowspan="2" valign="top">
                                    <?php if ($incomplete == 1 ) {  ?>
                                     <input type="button" onclick="window.location.href='<?php echo $task['task_Evaluation_Form']?>?task=<?php echo $tid;?>&subTask=<?php echo $assignment;?>'" value="Start assignment" />
                                    <?php } ?>
                                 </td>
                               </tr> 
                               <tr> 
                                  <td><div><?php echo $sc, ' of ', $st;?> tracks evaluated.</div></td>
                               </tr> 
                               </table> 
                            </div>
                            <?php
                                } 
                            } else {
                                $status = userGetAssignmentStatus($loggedInUser, $tid, $assignment);
                                $sc = $status['completed'];
                                $st = $status['total'];
                                ?>
                                <div class="sub">
                                    <div class="sub-shortcode">
                                        <?php echo enhash($assignment);?>
                                        <div>
                                           <div style="float:left;height:10px;width:<?php echo floor(75 * $sc/$st);?>px;background:#0c0;border-width:1px 0px 1px 1px;border-color:gray;border-style:solid;"></div><div style="float:left;height:10px;width:<?php echo ceil(75 * (($st-$sc)/$st));?>px;background:#c00;border-width:1px 1px 1px 0px;border-color:gray;border-style:solid;"></div>
                                        </div>
                                    </div>
                                    <div class="sub-info">
                                        <div><?php echo $sc, ' of ', $st;?> candidates evaluated.</div>
                                        <input type="button" onclick="window.location.href='assignment.evaluate.php?task=<?php echo $tid;?>&query=<?php echo $assignment;?>'" value="Evaluate Query" />
                                    </div>
                                    <div class="clear" style="height:0px;"></div>
                                </div>
                            <?php
                            }
                      }
                      if ($task['task_Type'] == 'Subtask') {
                          $avail = countAvailableSubtasks($tid, $loggedInUser);
                          ?>
                          <?php echo $complete ?> assignment(s) completed.
                          <?php if ($avail == 0 && $incomplete == 0) { ?> 
                                 No more assignments for this task. 
                             <?php 
                          }
                          if ($incomplete == 0) {
                               if ($avail > 0)
                               {    
                               ?>
                                  <div style="margin-top: 20px">
                                  <form action="assignment.get.php" method="post">
                                     <input type="hidden" name="task" value="<?php echo $tid;?>"/>
                                     <input type="submit" value="Get Another Assignment">  (<?php echo $avail ?> assignments available to you.)
                                  </form>
                                  </div>
                               <?php
                               }
                          }
                      }
                }
                ?>
                </div>
                <hr style="height: 1px; color: #cccccc; background-color: #cccccc">
                <?php
            }
               ?>
               
          </div>  
    </div>
</div>
</body>
</html>

