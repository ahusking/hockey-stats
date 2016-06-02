<?php
//Include website downloader
// include("simple_html_dom.php");
// include("config.php");
include("hockeyfunctions.php");
// var_dump($argv);
//The below function will pull the current competition list for Hockey ACT, and create databases for each competition
if ($argv["1"] == "rebuildcompdb") {
	CycleCompetitions(json_decode(GetCompetitionList(true),1)["competitions"]);
}


// The below function will generate the club infomation based on the team names the in the database (clubs that change their team names will show up multiple times
if ($argv["1"] == "generateteamdbs") {
	CycleClubs();
}


// function GetCompetitionList (){
// 	$html = file_get_html("https://sportsdesq.onesporttechnology.com/index.cfm?action=ajax.getCompetitions&orgId=15&keyword=&searchColumn=&currentpage=1&rowsperpage=100&clubId=&_=1462844773562");
// 	// United Competitions
// 	// $html = file_get_html("https://sportsdesq.onesporttechnology.com/index.cfm?action=ajax.getCompetitions&orgId=15&keyword=&searchColumn=&currentpage=1&rowsperpage=100&clubId=278&_=1462844773562");
// 	return (str_replace(" " , "", $html));
// }


function CycleCompetitions ($json) {
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


function CycleClubs () {
	print "Getting Clubs\r\n";
	$clubs = ExecuteSQL("CALL GetClubs() ");
	foreach ($clubs as $clubname) {
		$tablename = preg_replace('/[0-9]+/', '', str_replace(".","",str_replace("/","",str_replace("'","",str_replace(" ", "",$clubname[0])))));
	}
}

?>