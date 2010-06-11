CREATE TABLE  `mirexsubs`.`mirex_Groups` (
  `Group_ID` int(11) NOT NULL AUTO_INCREMENT,
  `Group_Name` varchar(225) NOT NULL,
  PRIMARY KEY (`Group_ID`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1

CREATE TABLE  `mirexsubs`.`mirex_Users` (
  `User_ID` int(11) NOT NULL AUTO_INCREMENT,
  `Username` varchar(150) NOT NULL,
  `Username_Clean` varchar(150) NOT NULL,
  `Password` varchar(225) NOT NULL,
  `Email` varchar(150) NOT NULL,
  `ActivationToken` varchar(225) NOT NULL,
  `LastActivationRequest` int(11) NOT NULL,
  `LostPasswordRequest` int(1) NOT NULL DEFAULT '0',
  `Active` int(1) NOT NULL,
  `Group_ID` int(11) NOT NULL,
  `SignUpDate` int(11) NOT NULL,
  `LastSignIn` int(11) NOT NULL,
  PRIMARY KEY (`User_ID`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1

CREATE TABLE  `mirexsubs`.`mirex_Profiles` (
  `profile_ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `profile_Username` varchar(150) DEFAULT NULL,
  `profile_Fname` varchar(255) DEFAULT NULL,
  `profile_Lname` varchar(255) DEFAULT NULL,
  `profile_Organization` varchar(255) DEFAULT NULL,
  `profile_Department` varchar(255) DEFAULT NULL,
  `profile_Unit` varchar(255) DEFAULT NULL,
  `profile_URL` varchar(255) DEFAULT NULL,
  `profile_Title` varchar(25) DEFAULT NULL,
  `profile_Email` varchar(200) DEFAULT NULL,
  `profile_Addr_Street_1` varchar(255) DEFAULT NULL,
  `profile_Addr_Street_2` varchar(255) DEFAULT NULL,
  `profile_Addr_Street_3` varchar(255) DEFAULT NULL,
  `profile_Addr_City` varchar(100) DEFAULT NULL,
  `profile_Addr_Region` varchar(100) DEFAULT NULL,
  `profile_Addr_Post` varchar(20) DEFAULT NULL,
  `profile_Addr_Country` varchar(100) DEFAULT NULL,
  `profile_Start` varchar(4) DEFAULT NULL,
  `profile_End` varchar(4) DEFAULT NULL,
  PRIMARY KEY (`profile_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8

CREATE TABLE  `mirexsubs`.`mirex_SubID` (
  `sub_Hashprefix` varchar(8) NOT NULL,
  `sub_Iterator` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`sub_Hashprefix`,`sub_Iterator`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8

CREATE TABLE  `mirexsubs`.`mirex_Submission_Contributors` (
  `sub_ID` int(11) NOT NULL,
  `sub_ContributorID` int(11) NOT NULL,
  `sub_Rank` int(11) DEFAULT NULL,
  PRIMARY KEY (`sub_ID`,`sub_ContributorID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8

CREATE TABLE  `mirexsubs`.`mirex_Submissions` (
  `sub_ID` int(11) NOT NULL AUTO_INCREMENT,
  `sub_Username` varchar(150) DEFAULT NULL,
  `sub_Hashcode` varchar(10) DEFAULT NULL,
  `sub_Name` varchar(255) DEFAULT NULL,
  `sub_Task` int(11) DEFAULT NULL,
  `sub_Readme` longtext,
  `sub_Created` datetime DEFAULT NULL,
  `sub_Status` int(11) DEFAULT NULL,
  `sub_PubNotes` longtext,
  `sub_PrivNotes` longtext,
  `sub_Machine` varchar(15) DEFAULT NULL,
  `sub_Path` varchar(255) DEFAULT NULL,
  `sub_MIREX_Handler` varchar(150) DEFAULT NULL,
  `sub_Updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`sub_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8

CREATE TABLE  `mirexsubs`.`mirex_Tasks` (
  `task_ID` int(11) NOT NULL AUTO_INCREMENT,
  `task_Name` varchar(100) DEFAULT NULL,
  `task_IsActive` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`task_ID`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8

