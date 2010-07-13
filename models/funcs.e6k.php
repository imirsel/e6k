<?php
require_once('JSON.php');
$linkCounter = 0;

function enhash($str) {
	$checksum = 0;
	for ($i = 0; $i < strlen($str); $i++) {
		$checksum += (36^$i) * ord(substr($str, $i, 1));
	}
	return base_convert(sprintf("%u", $checksum), 10, 36);
}

function logEvent($user, $task, $query, $cand, $action, $value) 
{
	global $db,$db_table_prefix;
	if ($user != NULL) 
	{
		$sql = "INSERT INTO
					".$db_table_prefix."E6K_Event_Log
				SET
					event_Time = NOW(),
					event_Username = '".$db->sql_escape($user->clean_username)."',
					event_Task = '".$db->sql_escape($task)."',
					event_Query = '".$db->sql_escape($query)."',
					event_Candidate = '".$db->sql_escape($cand)."',
					event_Action = '".$db->sql_escape($action)."',
					event_Value = '".$db->sql_escape($value)."'";
		$db->sql_query($sql);
	}
}

function updateRelevance($user, $task, $query, $cand, $score) 
{
	global $db,$db_table_prefix;
	$clause = array();
	if (isset($score['broad'])) {
		$clause[] = "r.result_Broad 	 = '".$db->sql_escape($score['broad'])."'";
	}
	if (isset($score['fine'])) {
		$clause[] = "r.result_Fine 	 = '".$db->sql_escape($score['fine'])."'";
	}
	if ($user != NULL) {
		if (count($clause) > 0) {
			$sql = "UPDATE 
						".$db_table_prefix."E6K_Results r,
						".$db_table_prefix."E6K_Assignments a
					SET 
						".join(",", $clause).",
						r.result_Grader = '".$db->sql_escape($user->clean_username)."',
						r.result_Timestamp = now()
					WHERE
						r.result_Task = a.assign_Task
					AND
						r.result_Query = a.assign_Query
					AND
						a.assign_Grader = '".$db->sql_escape($user->clean_username)."'
					AND
						a.assign_Task = '".$db->sql_escape($task)."'
					AND
						a.assign_Query = '".$db->sql_escape($query)."'
					AND
						r.result_Candidate = '".$db->sql_escape($cand)."'
					AND
						r.result_Active	 = 1";

			$db->sql_query($sql);
		}
	}
}

function getTasks() 
{
	global $db,$db_table_prefix; 
	$tasks = array();

	$sql = "SELECT 
					t.task_ID,
					t.task_Name,
					t.task_Assignment_Size,
					t.task_MP3,
					t.task_Instructions
			FROM 
					".$db_table_prefix."E6K_Tasks t
			";

	$result = $db->sql_query($sql);
	while (($row = $db->sql_fetchrow($result)) != null) {
		$tasks[$row['task_ID']] = $row;
	}
	return $tasks;	
}

function genMP3URL($base, $id, $cand = NULL)
{
	global $linkCounter;
	
	return $base . $id . ".mp3?" . ($cand != NULL ? "c=".$cand : "i=" . ($linkCounter++));	
}

function getTask($id) 
{
	global $db,$db_table_prefix; 
	$tasks = array();

	$sql = "SELECT 
					t.task_ID,
					t.task_Name,
					t.task_MP3,
					t.task_Assignment_Size,
					t.task_Instructions
			FROM 
					".$db_table_prefix."E6K_Tasks t
			WHERE
					t.task_ID = '".$id."'";

	$result = $db->sql_query($sql);
	return $db->sql_fetchrow($result);
}

function countAvailableAssignments($task) 
{
	global $db,$db_table_prefix; 

	$sql = "SELECT 
				count(*) as num
			FROM 
				".$db_table_prefix."E6K_Assignments
			WHERE
				assign_Task = ".$db->sql_escape($task)."
			AND
				assign_Grader IS NULL";

	$result = $db->sql_query($sql);
	$row = $db->sql_fetchrow($result);
	return $row['num'];
}

function userGiveConsent($user)
{
	global $db,$db_table_prefix;
	if ($user != NULL)
	{
		$ip = $_SERVER['REMOTE_ADDR'];
		$ua = $_SERVER['HTTP_USER_AGENT'];
		
		$sql = "INSERT IGNORE INTO
					".$db_table_prefix."E6K_Consent
				SET
					consent_Username='".$db->sql_escape($user->clean_username)."',
					consent_Status='Y',
					consent_Date = NOW(),
					consent_IP = '".$db->sql_escape($ip)."',
					consent_UserAgent = '".$db->sql_escape($ua)."'";

		$db->sql_query($sql);
	}
}

function userHasGivenConsent($user)
{
	global $db,$db_table_prefix;
	if ($user != NULL)
	{		
		$sql = "SELECT 
					*
				FROM
					".$db_table_prefix."E6K_Consent
				WHERE
					consent_Username='".$db->sql_escape($user->clean_username)."'
				AND
					consent_Status='Y'";

		$result = $db->sql_query($sql);
		$row = $db->sql_fetchrow($result);
		return $row;
	}
	return false;
}

function userGetCandidates($user, $task, $query) 
{
	global $db,$db_table_prefix;
	$candidates = array();

	//check if user is mirex organizer
	if ($user != NULL)
	{
		$sql = "SELECT
					r.result_Query,
					r.result_Candidate,
					r.result_Broad,
					r.result_Fine
				FROM
					".$db_table_prefix."E6K_Results r,
					".$db_table_prefix."E6K_Assignments a				
				WHERE
					a.assign_Grader = '".$db->sql_escape($user->clean_username)."'
				AND
					a.assign_Task = '".$db->sql_escape($task)."'
				AND
					a.assign_Query = '".$db->sql_escape($query)."'
				AND	
					r.result_Task = a.assign_Task
				AND
					r.result_Query = a.assign_Query
				AND
					r.result_Active = 1
				GROUP BY
					r.result_Task,
					r.result_Query,
					r.result_Candidate
				ORDER BY
					r.result_Random ASC;
				";

		$result = $db->sql_query($sql);
		while (($row = $db->sql_fetchrow($result)) != null)
		{
			$candidates[] = $row;
		}
	}
	return $candidates;
}

function userGetAssignments($user, $task) 
{
	global $db,$db_table_prefix; 
	$assignments = array();

	if ($user != NULL) {
		$sql = "SELECT 
						assign_Query
				FROM 
						".$db_table_prefix."E6K_Assignments
				WHERE
						assign_Task		= '".$db->sql_escape($task)."'
				AND
						assign_Grader 	= '".$db->sql_escape($user->clean_username)."'				
				";

		$result = $db->sql_query($sql);
		while (($row = $db->sql_fetchrow($result)) != null) {
			$assignments[] = stripslashes($row['assign_Query']);
		}
	}
	return $assignments;
}

function userAssignQueries($user, $tid)
{
	global $db,$db_table_prefix; 
	
	if ($user != NULL) 
	{
		$task = getTask($tid);
		$lim = $task['task_Assignment_Size'];
		
		$sql = "UPDATE 
					".$db_table_prefix."E6K_Assignments
				SET
					assign_Grader = '".$db->sql_escape($user->clean_username)."',
					assign_Timestamp = NOW()
				WHERE
					assign_Task = '".$db->sql_escape($tid)."'
				AND
					assign_Grader IS NULL
				ORDER BY
					rand()
				LIMIT
					".$lim."
				";
		$db->sql_query($sql);
	}
}

function userGetAssignmentStatus($user, $task, $query)
{
	global $db,$db_table_prefix;

	//check if user is mirex organizer
	if ($user != NULL)
	{
		$sql = "SELECT
					count(distinct(result_Candidate)) as num
				FROM
					".$db_table_prefix."E6K_Results
				WHERE
					result_Task = '".$db->sql_escape($task)."'
				AND
					result_Query = '".$db->sql_escape($query)."'
				AND
					result_Active = 1
				";

		$result = $db->sql_query($sql);
		$status = array();
		$row = $db->sql_fetchrow($result);
		$status['total'] = $row['num'];

		$sql = "SELECT
					count(distinct(result_Candidate)) as num
				FROM
					".$db_table_prefix."E6K_Results
				WHERE
					result_Task = '".$db->sql_escape($task)."'
				AND
					result_Query = '".$db->sql_escape($query)."'
				AND
					result_Broad != ''
				AND
					result_Fine > -1
				AND
					result_Active = 1
				";				

		$result = $db->sql_query($sql);
		$row = $db->sql_fetchrow($result);
		$status['completed'] = $row['num'];
		return $status;
	}
}


function adminCreateTask($user, $name, $size, $url, $inst)
{
	global $db,$db_table_prefix;

	//check if user is mirex organizer
	if (($user != NULL) && ($user->isGroupMember(2)))
	{
		$sql = "INSERT INTO 
					".$db_table_prefix."E6K_Tasks
				SET
					task_Name = '".$db->sql_escape($name)."',
					task_Assignment_Size = '".$db->sql_escape($size)."',
					task_MP3 = '".$db->sql_escape($url)."',
					task_Instructions = '".$db->sql_escape($inst)."'";
		$db->sql_query($sql);
	}
}

function adminUpdateTask($user, $task, $name, $size, $url, $inst) 
{
	global $db,$db_table_prefix;

	//check if user is mirex organizer
	if (($user != NULL) && ($user->isGroupMember(2)))
	{
		$sql = "UPDATE 
					".$db_table_prefix."E6K_Tasks
				SET
					task_Name = '".$db->sql_escape($name)."',
					task_Assignment_Size = '".$db->sql_escape($size)."',
					task_MP3 = '".$db->sql_escape($url)."',
					task_Instructions = '".$db->sql_escape($inst)."'
				WHERE
					task_ID = '".$db->sql_escape($task)."'";

		$db->sql_query($sql);
	}
}

function adminIsTaskDefined($user, $task) 
{
	global $db,$db_table_prefix;

	//check if user is mirex organizer
	if (($user != NULL) && ($user->isGroupMember(2)))
	{
		$sql = "SELECT
					count(*) as num
				FROM
					".$db_table_prefix."E6K_Results
				WHERE
					result_Task = '".$db->sql_escape($task)."'";

		$result = $db->sql_query($sql);
		$row = $db->sql_fetchrow($result);
		return ($row['num']);
	}
	return false;
}

function adminLoadResults($user, $task, $data, $append) 
{
	global $db,$db_table_prefix;

	//check if user is mirex organizer
	if (($user != NULL) && ($user->isGroupMember(2)))
	{
		if (!$append) 
		{
			$sql = "DELETE FROM
						".$db_table_prefix."E6K_Results 
					WHERE
						result_Task = '".$db->sql_escape($task)."'";
			$db->sql_query($sql);

			$sql = "DELETE FROM
						".$db_table_prefix."E6K_Assignments 
					WHERE
						assign_Task = '".$db->sql_escape($task)."'";
			$db->sql_query($sql);
		}
		
		// Create Result Records
		$sql = "INSERT IGNORE INTO 
					".$db_table_prefix."E6K_Results 
					(
						result_Task, 
						result_Submission,
						result_Query,
						result_Candidate,
						result_Random
					)
				VALUES
				";
				
		$queries = array();				
		$clauses = array();

		foreach ($data as $row) 
		{
			list($s, $q, $g, $c) = preg_split("/[,\t]/", $row);
			if (($s != '') && ($q != '') && ($c != '') && ($g != '')) {
				$queries[$q] = $g;

				$clauses[] = "( '".$db->sql_escape($task)."', 	
								'".$db->sql_escape(trim($s))."',
								'".$db->sql_escape(trim($q))."',
								'".$db->sql_escape(trim($c))."',
								rand())";
			}
		}

		foreach ($queries as $q=>$g) 
		{
			// stick the query in the results list somewhere
			// towards the middle
			$clauses[] = "( '".$db->sql_escape($task)."',
							'#IDCHECK#',
							'".$db->sql_escape(trim($q))."',
							'".$db->sql_escape(trim($q))."',
							0.5)";
		}

		$db->sql_query($sql . join(",", $clauses));
		
		$sql = "INSERT IGNORE INTO
					".$db_table_prefix."E6K_Assignments 
					(
						assign_Task, 
						assign_Query,
						assign_Genre
					)
				VALUES 
				";

		// Create Assignments
		$clauses = array();
		foreach ($queries as $q=>$g) 
		{
			$clauses[] = "( '".$db->sql_escape($task)."', 	
							'".$db->sql_escape($q)."',
							'".$db->sql_escape($g)."')";
		}

		$db->sql_query($sql . join(",", $clauses));
	}
}

function adminGetAllAssignments($user, $task) 
{
	global $db,$db_table_prefix;

	//check if user is mirex organizer
	if (($user != NULL) && ($user->isGroupMember(2)))
	{
		$sql = "SELECT 
					*,
					assign_Grader IS NULL AS sort
				FROM
					".$db_table_prefix."E6K_Assignments
				WHERE
					assign_Task = '".$db->sql_escape($task)."'
				ORDER BY
					sort ASC,
					assign_Query ASC";

		$result = $db->sql_query($sql);
		while (($row = $db->sql_fetchrow($result)) != null) {
			$assignments[$row['assign_Query']] = $row['assign_Grader'];
		}
	}
	return $assignments;
}

function adminGenerateReport($user, $task)
{
	global $db,$db_table_prefix;
	$report = array();

	//check if user is mirex organizer
	if (($user != NULL) && ($user->isGroupMember(2)))
	{
		$sql = "SELECT 
					r.result_Submission as SubID,
					r.result_Query as QueryID,
					a.assign_Genre as QueryGenre,
					AVG(IF(r.result_Broad = 'VS',2,IF(r.result_Broad = 'SS',1,0))) AS AvgBroad,
					AVG(r.result_Fine) AS FineAvg
				FROM
					".$db_table_prefix."E6K_Assignments a,
					".$db_table_prefix."E6K_Results r					
				WHERE
					r.result_Task = '".$db->sql_escape($task)."'
				AND
					a.assign_Task = r.result_Task
				AND
					a.assign_Query = r.result_Query
				GROUP BY
					r.result_Submission, 
					r.result_Task, 
					r.result_Query
				ORDER BY
					r.result_Submission,
					a.assign_Genre,
					r.result_Query";

		$result = $db->sql_query($sql);
		while (($row = $db->sql_fetchrow($result)) != null) {
			$report[] = $row;
		}
	}
	return $report;
}

if (!function_exists('json_encode'))
{
	$json = new Services_JSON();
	function json_encode($a=false)
	{
		global $json;
		return $json->encode($a);
	}
	function json_decode($a) 
	{
		global $json;
		return $json->decode($a);
	}
}
  	
