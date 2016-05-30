<?php
//Include website downloader
include("simple_html_dom.php");
include("config.php");


//The below function will pull the current competition list for Hockey ACT, and create databases for each competition
CycleCompetitions(json_decode(GetCompetitionList(),1)["competitions"]);


function GetCompetitionList (){
// 	$html = file_get_html("https://sportsdesq.onesporttechnology.com/index.cfm?action=ajax.getCompetitions&orgId=15&keyword=&searchColumn=&currentpage=1&rowsperpage=100&_=1462844773562");
// United Competitions
	$html = file_get_html("https://sportsdesq.onesporttechnology.com/index.cfm?action=ajax.getCompetitions&orgId=15&keyword=&searchColumn=&currentpage=1&rowsperpage=100&clubId=278&_=1462844773562");
	return (str_replace(" " , "", $html));
}


function CycleCompetitions ($json) {
	global $db;
	foreach ($json as $id => $name) {
			//each $name contains competitionId and competitionName
			print "Updating competitions table with competition:" . $name["competitionName"] . "<br>\r\n";
			//Build the table for this competitions statistics
			GenerateSQLTableCompetition($name["competitionName"]);
			print "Creating table " . $name["competitionName"] . "<br>\r\n";
			//Add the competition to the list of competitions
			$querystring = "REPLACE INTO Competitions (CompetitionID, CompetitionName) Values ('" . $name["competitionId"] . "','" . $name["competitionName"] . "');";
			ExecuteSQL($querystring);
		}
}

function GenerateSQLTableCompetition($compName) {
	// Only in emergencies!!!!
	//ExecuteSQL("DROP TABLE $compName");
	// 	 
	
	$querystring = "CREATE TABLE IF NOT EXISTS `$compName` (
	`PlayerName` VARCHAR(128) NULL DEFAULT NULL,
	`GamesPlayed` INT(11) NULL DEFAULT '0',
	`Team` VARCHAR(50) NULL,
	`Goals` INT(11) NULL DEFAULT '0',
	`GCard` INT(11) NULL DEFAULT '0',
	`YCard` INT(11) NULL DEFAULT '0',
	`RCard` INT(11) NULL DEFAULT '0',
	PRIMARY KEY (`PlayerName`)
	)
	COLLATE='latin1_swedish_ci'
	ENGINE=InnoDB
	;";
	ExecuteSQL($querystring);
}

function ExecuteSQL ($querystring) {
	include("config.php");
	$db = mysqli_connect($sqlserver,$sqlusername,$sqlpassword,$sqldatabse);
	if(!$result = $db->query($querystring)){
		die('There was an error running the query [' . $db->error . ']');
	}
}

?>