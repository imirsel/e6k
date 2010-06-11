<?php
require_once('JSON.php');
$MIREX_absdir = '/nema-raid/www/mirex/abstracts/2010/';

$MIREX_statuses =  array(	0 => "pending",
							1 => "ready for upload",
							2 => "upload completed",
							3 => "submission running",
							4 => "submission completed",
							5 => "waiting for comments",
							6 => "error in submission",
							7 => "review"
							);
							
$MIREX_licenses = array(	0 => 	"I have my own license",
							1 =>	"Apache Software License 2.0",
							2 =>	"Artistic License/GPL",
							3 =>	"GNU GPL v 2.0",
							4 =>	"GNU GPL v 3.0",
							5 =>	"GNU LGPL",
							6 =>	"Eclipse Public License v1.0",
							7 =>	"UIUC/NCSA Open Source License",
							8 =>	"New BSD License",
							9 =>	"Mozilla Public License 1.0",
							10 =>	"MIT License"
);

$MIREX_machines = array( "compute-0-2",
"compute-0-3",
"compute-1-0",
"compute-1-1-0",
"compute-1-1-1",
"compute-1-1-2",
"compute-1-1-3",
"hosted-vm-0-1-7",
"nema-0-0",
"nema-2-0",
"nema-c-1-1-4",
"nema-c-1-1-5",
"nema-c-1-2",
"nema-c-1-3",
"ubuntu-1-1-0",
"ubuntu-1-1-1",
"vm-container-0-1",
"vm-container-1-1",
"win-0-1-0",
"win-0-1-1",
"win-1-1-0");

function isempty($str) 
{
	return (trim($str) == "");
}

function createSubmission($user, $sub, $contributors) 
{
	global $db,$db_table_prefix;
	
	if ($user != NULL) {
		$sql = "INSERT INTO ".$db_table_prefix."SubID
				SET sub_Hashprefix = '".$db->sql_escape($sub['hash'])."'";
		$db->sql_query($sql);

		$hashcode = $sub['hash'] . $db->sql_nextid();

		$sql = "INSERT INTO ".$db_table_prefix."Submissions
				SET sub_Username = '".$db->sql_escape($user->clean_username)."',
					sub_Hashcode = '".$db->sql_escape($hashcode)."',
					sub_Readme 	 = '".$db->sql_escape($sub['readme'])."',
					sub_Name	 = '".$db->sql_escape($sub['name'])."',
					sub_Task	 = '".$db->sql_escape($sub['task'])."',
					sub_Status	 = 0,
					sub_Machine  = 'not assigned',
					sub_MIREX_Handler = '',
					sub_Path	 = '/data/raid2/dropbox/".$db->sql_escape($hashcode)."',
					sub_PubNotes = '',
					sub_PrivNotes = '',
					sub_Updated	 = now(),
					sub_Created  = now()";
		$db->sql_query($sql);
		$subID = $db->sql_nextid();
		
		$sql = "INSERT INTO ".$db_table_prefix."Submission_Contributors (sub_ID, sub_ContributorID, sub_Rank) VALUES ";
		foreach ($contributors as $c) {
			$csql[] = "(".$subID.",".$db->sql_escape($c[1]).",".$db->sql_escape($c[0]).")";
		}
		$sql .= join(",", $csql);
		$db->sql_query($sql);
		
		return $hashcode;
	}		
}

function createIdentity($user, $adata)
{
	global $db,$db_table_prefix;
	
	if ($user != NULL) {
		$sql = "INSERT INTO ".$db_table_prefix."Profiles
				SET profile_Fname = 		'".$db->sql_escape($adata['fname'])."',
					profile_Lname = 		'".$db->sql_escape($adata['lname'])."',
					profile_Organization = 	'".$db->sql_escape($adata['org'])."',
					profile_Department = 	'".$db->sql_escape($adata['dept'])."',
					profile_Unit = 			'".$db->sql_escape($adata['unit'])."',
					profile_URL = 			'".$db->sql_escape($adata['url'])."',
					profile_Title = 		'".$db->sql_escape($adata['title'])."',
					profile_Email = 		'".$db->sql_escape($adata['email'])."',
					profile_Addr_Street_1 = '".$db->sql_escape($adata['str1'])."',
					profile_Addr_Street_2 = '".$db->sql_escape($adata['str2'])."',
					profile_Addr_Street_3 = '".$db->sql_escape($adata['str3'])."',
					profile_Addr_City = 	'".$db->sql_escape($adata['city'])."',
					profile_Addr_Region = 	'".$db->sql_escape($adata['reg'])."',
					profile_Addr_Post = 	'".$db->sql_escape($adata['post'])."',
					profile_Addr_Country = 	'".$db->sql_escape($adata['country'])."',
					profile_Start = 		'".$db->sql_escape($adata['start'])."',
					profile_End = 			'".$db->sql_escape($adata['end'])."',
					profile_Username = 		'".$db->sql_escape($user->clean_username)."'
				";
	}
	else {
		$sql = "INSERT INTO ".$db_table_prefix."Profiles
				SET profile_Fname = 		'".$db->sql_escape($adata['fname'])."',
					profile_Lname = 		'".$db->sql_escape($adata['lname'])."',
					profile_Organization = 	'".$db->sql_escape($adata['org'])."',
					profile_Department = 	'".$db->sql_escape($adata['dept'])."',
					profile_Unit = 			'".$db->sql_escape($adata['unit'])."',
					profile_URL = 			'".$db->sql_escape($adata['url'])."',
					profile_Title = 		'".$db->sql_escape($adata['title'])."',
					profile_Email = 		'".$db->sql_escape($adata['email'])."',
					profile_Addr_Street_1 = '".$db->sql_escape($adata['str1'])."',
					profile_Addr_Street_2 = '".$db->sql_escape($adata['str2'])."',
					profile_Addr_Street_3 = '".$db->sql_escape($adata['str3'])."',
					profile_Addr_City = 	'".$db->sql_escape($adata['city'])."',
					profile_Addr_Region = 	'".$db->sql_escape($adata['reg'])."',
					profile_Addr_Post = 	'".$db->sql_escape($adata['post'])."',
					profile_Addr_Country = 	'".$db->sql_escape($adata['country'])."',
					profile_Start = 		'".$db->sql_escape($adata['start'])."',
					profile_End = 			'".$db->sql_escape($adata['end'])."',
					profile_Username = 		NULL
				";		
	}
	
	$db->sql_query($sql);
	return $db->sql_nextid();
}

function countIdentities($user) 
{
	global $db,$db_table_prefix; 
	$identities = array();

	if ($user != NULL) {
		$sql = "SELECT 
						count(*) as num
				FROM 
						".$db_table_prefix."Profiles
				WHERE
						profile_Username = '".$db->sql_escape($user->clean_username)."'
				";

		$result = $db->sql_query($sql);
		$row = $db->sql_fetchrow($result);
		return $row['num'];
	}
	return 0;
}

function countSubmissions($user) 
{
	global $db,$db_table_prefix; 
	$identities = array();

	if ($user != NULL) {
		$sql = "SELECT 
						count(*) as num
				FROM 
						".$db_table_prefix."Submissions s,
						".$db_table_prefix."Profiles p,
						".$db_table_prefix."Submission_Contributors c
				WHERE
						s.sub_Username = '".$db->sql_escape($user->clean_username)."'
				OR		(s.sub_ID=c.sub_ID
				AND		 c.sub_ContributorID = p.profile_ID
				AND      p.profile_Username = '".$db->sql_escape($user->clean_username)."')
				GROUP BY s.sub_ID";

		$result = $db->sql_query($sql);
		$row = $db->sql_fetchrow($result);
		return $row['num'];
	}
	return 0;
}

function getIdentities($user) 
{
	global $db,$db_table_prefix; 
	$identities = array();

	if ($user != NULL) {
		$sql = "SELECT 
						*
				FROM 
						".$db_table_prefix."Profiles
				WHERE
						profile_Username = '".$db->sql_escape($user->clean_username)."'
				ORDER BY
						profile_End DESC, profile_Start DESC";

		$result = $db->sql_query($sql);

		while (($row = $db->sql_fetchrow($result)) != null) {
			$identities[] = $row;
		}
	}
	return $identities;
}

function claimIdentity($user, $profile)
{
	global $db,$db_table_prefix; 
	if ($user != null) 
	{
		$sql = "UPDATE 
						".$db_table_prefix."Profiles
				SET
						profile_Username='".$db->sql_escape($user->clean_username)."'
				WHERE
						profile_Username is NULL
				AND
						profile_ID='".$db->sql_escape($profile)."'
				";
		$db->sql_query($sql);
		return ($db->sql_affectedrows() == 1);
	}
	return false;
}

function findPossibleMatchingProfiles($user) 
{
	global $db,$db_table_prefix; 
	$identities = array();

	list($addr, $domain) = explode("@", $user->email);

	if ($user != NULL) {
		$sql = "SELECT 
						*
				FROM 
						".$db_table_prefix."Profiles
				WHERE
				(
						profile_Email = '".$db->sql_escape($user->email)."'
				OR
						profile_Email like '".$db->sql_escape($addr)."@%'
				OR
						profile_Email like '".$db->sql_escape($user->clean_username)."@%'
				) 
				AND
				(
						profile_Username = ''
				OR
						profile_Username IS NULL
				)
				GROUP BY
						profile_ID
				ORDER BY
						profile_End DESC, profile_Start DESC";

		$result = $db->sql_query($sql);

		while (($row = $db->sql_fetchrow($result)) != null) {
			$identities[] = $row;
		}
	}
	return $identities;
}


function getIdentity($id) 
{
	global $db,$db_table_prefix; 

	$sql = "SELECT 
					profile_ID as id,
					profile_Fname as fname,
					profile_Lname as lname,
					profile_Organization as org,
					profile_Title as title
			FROM 
					".$db_table_prefix."Profiles
			WHERE
					profile_ID = '".$db->sql_escape($id)."'
			LIMIT 1";

	$result = $db->sql_query($sql);

	return $db->sql_fetchrow($result);
}

function getSubmissionTasks() 
{
	global $db,$db_table_prefix; 
	$tasks = array();

	$sql = "SELECT 
					task_ID as id, 
					task_Name as name
			FROM 
					".$db_table_prefix."Tasks
			WHERE
					task_IsActive=1
			ORDER BY
					task_Name ASC";

	$result = $db->sql_query($sql);

	while (($row = $db->sql_fetchrow($result)) != null) {
		$tasks[] = $row;
	}
	return $tasks;
}

function getSubmissions($user) {

	global $db, $db_table_prefix;
	$submission = array();
	
	if ($user != NULL) {
		$sql = "SELECT 
						s.*,
						t.task_Name
				FROM 
						".$db_table_prefix."Submissions s,
						".$db_table_prefix."Tasks t,
						".$db_table_prefix."Submission_Contributors c,
						".$db_table_prefix."Profiles p
				WHERE
						s.sub_Task = t.task_ID
				AND		(s.sub_Username = '".$db->sql_escape($user->clean_username)."'
				OR		(s.sub_ID=c.sub_ID
				AND		 c.sub_ContributorID = p.profile_ID
				AND      p.profile_Username = '".$db->sql_escape($user->clean_username)."'))
				GROUP BY s.sub_ID
				ORDER BY
						s.sub_Updated DESC";

		$result = $db->sql_query($sql);

		while (($row = $db->sql_fetchrow($result)) != null) {
			$submissions[] = $row;
		}
		$outSubs = array();

		if (count($submissions) > 0) {
			foreach ($submissions as $sub) {
				$id = $sub['sub_ID'];
				$sql = "SELECT 
							CONCAT(p.profile_Fname, ' ', p.profile_Lname) as contributor
						FROM
							".$db_table_prefix."Submission_Contributors c,
							".$db_table_prefix."Profiles p
						WHERE c.sub_ID 				= ".$id."
						  AND c.sub_ContributorID 	= p.profile_ID
						ORDER BY c.sub_Rank";
				$result = $db->sql_query($sql);
	
				$contributors = array();
				while (($row = $db->sql_fetchrow($result)) != null) {
					$contributors[] = $row['contributor'];
				}
	
				$sub['contributors'] = $contributors;
				$outSubs[] = $sub;
			}
		}
	}
	return $outSubs;
}

function getLicenses() {
	global $MIREX_licenses;
	return $MIREX_licenses;
}

function getSubmissionLicense($user, $id) {

	global $db, $db_table_prefix;
	$sub = NULL;
	
	if ($user != NULL) {
		$sql = "SELECT 
						s.sub_ID as id,
						s.sub_License_Type as sublic,
						s.sub_License_Text as sublictext
				FROM 
						".$db_table_prefix."Submissions s
				WHERE	s.sub_Username = '".$db->sql_escape($user->clean_username)."'
				AND		s.sub_ID = '".$db->sql_escape($id)."'
				LIMIT 1";

		$result = $db->sql_query($sql);

		$sub = $db->sql_fetchrow($result);
	}
	return $sub;
}

function updateSubmissionLicense($user, $id, $license, $text) {
	global $db, $db_table_prefix;
	
	if ($user != NULL) {
		$sql = "UPDATE ".$db_table_prefix."Submissions
				SET
					sub_License_Type 	 = '".$db->sql_escape($license)."',
					sub_License_Text	 = '".$db->sql_escape($text)."',
					sub_Updated	 = now()
				WHERE
				    sub_ID = '".$db->sql_escape($id)."'
				AND sub_Username ='".$db->sql_escape($user->clean_username)."'
				";
		return $db->sql_query($sql);
	}
	return false;
}


function getSubmissionUser($user, $id) {

	global $db, $db_table_prefix;
	$sub = NULL;
	
	if ($user != NULL) {
		$sql = "SELECT 
						s.sub_ID as id,
						s.sub_Name as name,
						s.sub_Task as task,
						s.sub_Readme as readme,
						s.sub_Hashcode as hash,
						s.sub_License_Type as lic,
						s.sub_Status as status
				FROM 
						".$db_table_prefix."Submissions s
				WHERE	s.sub_Username = '".$db->sql_escape($user->clean_username)."'
				AND		s.sub_ID = '".$db->sql_escape($id)."'
				LIMIT 1";
//				AND		s.sub_Status IN (0,1,7)

		$result = $db->sql_query($sql);

		$sub = $db->sql_fetchrow($result);

		$id = $sub['id'];
		$sql = "SELECT 
					p.profile_Fname,
					p.profile_Lname,
					p.profile_ID,
					c.sub_Rank
				FROM
					".$db_table_prefix."Submission_Contributors c,
					".$db_table_prefix."Profiles p
				WHERE c.sub_ID 				= ".$id."
				AND   c.sub_ContributorID 	= p.profile_ID
				ORDER BY c.sub_Rank";
		$result = $db->sql_query($sql);

		$contributors = array();
		while (($row = $db->sql_fetchrow($result)) != null) {
			$contributors[] = array($row['sub_Rank'], $row['profile_ID'], $row['profile_Fname'], $row['profile_Lname']);
		}

		$sub['contributors'] = $contributors;
	}
	return $sub;
}

function updateSubmissionUser($user, $sub, $contributors) 
{
	global $loggedInUser,$db,$db_table_prefix;
	
	if ($user != NULL) {
		$sql = "UPDATE ".$db_table_prefix."Submissions
				SET
					sub_Readme 	 = '".$db->sql_escape($sub['readme'])."',
					sub_Name	 = '".$db->sql_escape($sub['name'])."',
					sub_Task	 = '".$db->sql_escape($sub['task'])."',
					sub_Updated	 = now()
				WHERE
				    sub_ID = '".$db->sql_escape($sub['id'])."'
				AND sub_Username ='".$db->sql_escape($user->clean_username)."'
				AND	sub_Status IN (0,1,7)
				";
		$db->sql_query($sql);
		
		$sql = "SELECT sub_Hashcode FROM ".$db_table_prefix."Submissions
				WHERE  sub_ID = '".$db->sql_escape($sub['id'])."'
				AND    sub_Username ='".$db->sql_escape($user->clean_username)."'";

 		$result = $db->sql_query($sql);
		$row = $db->sql_fetchrow($result);
		$hashcode = $row['sub_Hashcode'];

		$sql = "DELETE FROM ".$db_table_prefix."Submission_Contributors WHERE sub_ID=".$db->sql_escape($sub['id']);
		$db->sql_query($sql);

		$sql = "INSERT INTO ".$db_table_prefix."Submission_Contributors (sub_ID, sub_ContributorID, sub_Rank) VALUES ";
		foreach ($contributors as $c) {
			$csql[] = "(".$sub['id'].",".$db->sql_escape($c[1]).",".$db->sql_escape($c[0]).")";
		}
		$sql .= join(",", $csql);

		$db->sql_query($sql);
	}
	return $hashcode;
}



// ADMIN FUNCTIONS
function getAllSubmissions()
{
	global $loggedInUser, $db, $db_table_prefix;

	if ($loggedInUser->isGroupMember(2)) {
		$submission = array();
		$sql = "SELECT 
						s.*,
						t.task_Name
				FROM 
						".$db_table_prefix."Submissions s,
						".$db_table_prefix."Tasks t						
				WHERE
						s.sub_Task = t.task_ID
				ORDER BY
						s.sub_Updated DESC";

		$result = $db->sql_query($sql);

		while (($row = $db->sql_fetchrow($result)) != null) {
			$submissions[] = $row;
		}
		return populateContributors($submissions);
	}
	return array();
}

function getSubmissionsByTask($task)
{
	global $loggedInUser, $db, $db_table_prefix;

	if ($loggedInUser->isGroupMember(2)) {
		$submission = array();
		$sql = "SELECT 
						s.*,
						t.task_Name
				FROM 
						".$db_table_prefix."Submissions s,
						".$db_table_prefix."Tasks t						
				WHERE
						s.sub_Task = t.task_ID
				AND		s.sub_Task = '".$db->sql_escape($task)."'
				ORDER BY
						s.sub_Updated DESC";
		$result = $db->sql_query($sql);

		while (($row = $db->sql_fetchrow($result)) != null) {
			$submissions[] = $row;
		}
		return populateContributors($submissions);
	}
	return array();
}

function getSubmissionsByStatus($status)
{
	global $loggedInUser, $db, $db_table_prefix;

	if ($loggedInUser->isGroupMember(2)) {
		$submission = array();
		$sql = "SELECT 
						s.*,
						t.task_Name
				FROM 
						".$db_table_prefix."Submissions s,
						".$db_table_prefix."Tasks t						
				WHERE
						s.sub_Task = t.task_ID
				AND		s.sub_Status = '".$db->sql_escape($status)."'
				ORDER BY
						s.sub_Updated DESC";

		$result = $db->sql_query($sql);

		while (($row = $db->sql_fetchrow($result)) != null) {
			$submissions[] = $row;
		}
		return populateContributors($submissions);
	}
	return array();
}

function getSubmissionsByMachine($machine)
{
	global $loggedInUser, $db, $db_table_prefix;

	if ($loggedInUser->isGroupMember(2)) {
		$submission = array();
		$sql = "SELECT 
						s.*,
						t.task_Name
				FROM 
						".$db_table_prefix."Submissions s,
						".$db_table_prefix."Tasks t						
				WHERE
						s.sub_Task = t.task_ID
				AND		s.sub_Machine = '".$db->sql_escape($machine)."'
				ORDER BY
						s.sub_Updated DESC";

		$result = $db->sql_query($sql);

		while (($row = $db->sql_fetchrow($result)) != null) {
			$submissions[] = $row;
		}
		return populateContributors($submissions);
	}
	return array();
}

function generateEmailList($id) 
{
	global $loggedInUser, $db, $db_table_prefix;
	$emails = array();
	$sql = "SELECT 
				DISTINCT(p.profile_Email) as email
			FROM
				".$db_table_prefix."Submission_Contributors c,
				".$db_table_prefix."Profiles p
			WHERE c.sub_ID 				= ".$id."
			  AND c.sub_ContributorID 	= p.profile_ID
			ORDER BY c.sub_Rank";

	$result = $db->sql_query($sql);
	
	$emails = array();
	while (($row = $db->sql_fetchrow($result)) != null) {
		$emails[] = $row['email'];
	}
	
	return stripslashes(join(",", $emails));
}

function populateContributors($submissions) 
{
	global $loggedInUser, $db, $db_table_prefix;

	$outSubs = array();
	if (count($submissions) > 0) {
		foreach ($submissions as $sub) {
			$id = $sub['sub_ID'];
			$sql = "SELECT 
						CONCAT(p.profile_Fname, ' ', p.profile_Lname) as contributor
					FROM
						".$db_table_prefix."Submission_Contributors c,
						".$db_table_prefix."Profiles p
					WHERE c.sub_ID 				= ".$id."
					  AND c.sub_ContributorID 	= p.profile_ID
					ORDER BY c.sub_Rank";
			$result = $db->sql_query($sql);

			$contributors = array();
			while (($row = $db->sql_fetchrow($result)) != null) {
				$contributors[] = $row['contributor'];
			}

			$sub['contributors'] = $contributors;
			$outSubs[] = $sub;
		}
	}
	return $outSubs;
}

function getStatuses() {
	global $MIREX_statuses;
	return $MIREX_statuses;
}

function getMachineNames() {
	global $MIREX_machines;
	return $MIREX_machines;
}

function getSubmissionStatus($id) {
	global $MIREX_statuses;
	return $MIREX_statuses[$id];	
}

function findParticipants($str)
{
	global $db,$db_table_prefix; 
	
	$cstr = $db->sql_escape(sanitize($str));
	
	$sql = "SELECT 	p.profile_ID as id,
					p.profile_Fname as fname,
					p.profile_Lname as lname,
					p.profile_Organization as org,
					p.profile_Title as title
			FROM ".$db_table_prefix."Profiles p, ".$db_table_prefix."Users u
			WHERE p.profile_Fname 			like '%".$cstr."%' OR
				  p.profile_Lname 			like '%".$cstr."%' OR
				  p.profile_Email 			like '%".$cstr."%' OR
				  p.profile_Organization 	like '%".$cstr."%' OR
				  p.profile_Department 		like '%".$cstr."%' OR
				  p.profile_Unit 			like '%".$cstr."%' OR 
				  (u.Email like '%".$cstr."%' AND u.Username_Clean = p.profile_Username)
			GROUP BY profile_ID
			ORDER BY profile_End DESC, profile_Lname ASC, profile_Fname ASC
			";
	$result = $db->sql_query($sql);
	
	$all = array();
	while (($row = $db->sql_fetchrow($result)) != null) {
		$all[] = $row;
	}
	
	return $all;
}

function updateSubmissionAdmin($sub) 
{
	global $loggedInUser,$db,$db_table_prefix;
	
	if ($loggedInUser->isGroupMember(2)) {

		$sql = "UPDATE ".$db_table_prefix."Submissions
				SET sub_Status	 = '".$db->sql_escape($sub['sub_Status'])."',
					sub_Machine  = '".$db->sql_escape($sub['sub_Machine'])."',
					sub_MIREX_Handler = '".$db->sql_escape($sub['sub_MIREX_Handler'])."',
					sub_Path	 = '".$db->sql_escape($sub['sub_Path'])."',
					sub_PubNotes = '".$db->sql_escape($sub['sub_PubNotes'])."',
					sub_PrivNotes = '".$db->sql_escape($sub['sub_PrivNotes'])."',
					sub_Updated	 = now()
				WHERE
					sub_Hashcode = '".$db->sql_escape($sub['sub_Hashcode'])."'
				AND sub_ID = '".$db->sql_escape($sub['sub_ID'])."'";
		return ($db->sql_query($sql));
	}		
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
  	
