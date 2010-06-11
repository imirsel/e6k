<?php

class Profile {

	public $profile_id = NULL;
	public $first_name = "MIREX";
	public $last_name = "Participant";
	public $about_me = NULL;
	public $affiliations = NULL;
	private $user = NULL;
	
	public function Profile($user=NULL,$id=NULL)
	{
		global $db,$db_table_prefix; 
		
		if ($user != NULL) {
			$this->user = $user;
			
			$sql = "SELECT * FROM ".$db_table_prefix."Profiles
					WHERE
					profile_Username = '".$db->sql_escape($user->clean_username)."'
					LIMIT
					1";
		}
		elseif ($id != NULL) {
			$sql = "SELECT * FROM ".$db_table_prefix."Profiles
					WHERE
					profile_ID = '".$db->sql_escape($id)."'
					LIMIT
					1";
		}

		$result = $db->sql_query($sql);

		if ($result) {
			$row = $db->sql_fetchrow($result);		
	
			$this->profile_id 	= $row['profile_ID'];
			$this->first_name 	= $row['profile_Fname'];
			$this->last_name	= $row['profile_Lname'];
			$this->about_me 	= $row['profile_About'];
	
			$this->loadAffiliations();
		}
	}

	function loadAffiliations() 
	{
		global $db,$db_table_prefix; 
		
		$sql = "SELECT * FROM ".$db_table_prefix."Affiliations
				WHERE
				affil_Profile = '".$db->sql_escape($this->profileid)."'
				ORDER BY
				affil_end DESC";

		$result = $db->sql_query($sql);
		$this->affiliations = array();
		while (($row = $db->sql_fetchrow($result)) != null) {
			$this->affiliations[] = $row;
		}
	}
	
	function getAffiliations()
	{
		return $this->affiliations;
	}
	
	function addAffiliation($adata)
	{
		global $db,$db_table_prefix;
		
		$sql = "INSERT INTO ".$db_table_prefix."Affiliations
				SET affil_Organization = '".$db->sql_escape($adata['org'])."',
					affil_Department = '".$db->sql_escape($adata['dept'])."',
					affil_Unit = '".$db->sql_escape($adata['unit'])."',
					affil_URL = '".$db->sql_escape($adata['url'])."',
					affil_Title = '".$db->sql_escape($adata['title'])."',
					affil_Email = '".$db->sql_escape($adata['email'])."',
					affil_Addr_Street_1 = '".$db->sql_escape($adata['str1'])."',
					affil_Addr_Street_2 = '".$db->sql_escape($adata['str2'])."',
					affil_Addr_Street_3 = '".$db->sql_escape($adata['str3'])."',
					affil_Addr_City = '".$db->sql_escape($adata['city'])."',
					affil_Addr_Region = '".$db->sql_escape($adata['reg'])."',
					affil_Addr_Post = '".$db->sql_escape($adata['post'])."',
					affil_Addr_Country = '".$db->sql_escape($adata['country'])."',
					affil_Start = '".$db->sql_escape($adata['start'])."',
					affil_End = '".$db->sql_escape($adata['end'])."',
					affil_Username = '".$db->sql_escape($this->clean_username)."'
				";
		return ($db->sql_query($sql));
	}
	
	function updateProfile($userfname,$userlname,$userabout)
	{
		global $db,$db_table_prefix;
		
		$sql = "UPDATE ".$db_table_prefix."Profiles
				SET profile_Fname = '".$db->sql_escape($userfname)."',
				    profile_Lname = '".$db->sql_escape($userlname)."',
				    profile_About = '".$db->sql_escape($userabout)."',
					profile_Username = '".$db->sql_escape($this->user->clean_username)."'
				WHERE
					profile_ID = ".$db->sql_escape($this->profile_id)."
				";
		echo $sql;
		return ($db->sql_query($sql));	
	}	

	function createProfile($userfname,$userlname,$userabout)
	{
		global $db,$db_table_prefix;
		
		$sql = "INSERT INTO ".$db_table_prefix."Profiles
				SET profile_Fname = '".$db->sql_escape($userfname)."',
				    profile_Lname = '".$db->sql_escape($userlname)."',
				    profile_About = '".$db->sql_escape($userabout)."',
				    profile_Username=NULL
				";
		
		return ($db->sql_query($sql));	
	}	

}
?>