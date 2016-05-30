<?php
require_once '../../Classes/PHPExcel/IOFactory.php';
require_once '../../Classes/PHPExcel.php';
include("simple_html_dom.php");
$compJSON = "";

function DownloadCompetitionStats($json) {
	$baseURL = "https://sportsdesq.onesporttechnology.com/15/portal/playerStatisticsXls/competitionid/";
	foreach ($json as $id => $name) {
		print "\tDLCompStats: Downloading Files\r\n";
		DownloadAndWriteFile($baseURL . $name["competitionId"], $name["competitionId"] . ".xlsx");
		print "\tDLCompStats: Converting files to SQL\r\n";
		ConvertCompetitionXLSToSqlV2 ("tmp/" . $name["competitionId"] . ".xlsx", $name["competitionId"]);
	}
}

function ConvertCompetitionXLSToSql ($filename, $competitionID) {
	$compArray = ExecuteSQL("Select CompetitionName From Competitions Where CompetitionID = '$competitionID';");

	if (!file_exists("$filename")) {
		print "error, file doesn't exist<br>\r\n";
	}
	$objPHPExcel = PHPExcel_IOFactory::load($filename);
	$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
	foreach ($sheetData as $row => $data) {
		if ($data["A"] != "Last Name" && $data["B"] != "Firstname") {
			print $data["B"] . " " . $data["A"] . "\r\n";
			print 'REPLACE INTO ' . $compArray['CompetitionName'] . ' (`PlayerName`, `GamesPlayed`, `Goals`, `GCard`, `YCard`, `RCard`) VALUES ("' . ucwords($data['B'] . ' ' . $data['A']) . '","' . $data['D'] . '","' . $data['E'] . '","' . $data['H'] . '","' . $data['G'] . '","' . $data['F'] . '");\r\n\r\n';
			ExecuteSQL('REPLACE INTO ' . $compArray['CompetitionName'] . ' (`PlayerName`, `GamesPlayed`, `Team`, `Goals`, `GCard`, `YCard`, `RCard`) VALUES ("' . ucwords($data['B'] . ' ' . $data['A']). '","' . $data['D'] . '","' . $data['C'] . '","' . $data['E'] . '","' . $data['H'] . '","' . $data['G'] . '","' . $data['F'] . '");'  );
		}
	}	
}

function ConvertCompetitionXLSToSqlV2 ($filename, $competitionID) {
	$compArray = ExecuteSQL("Select CompetitionName From Competitions Where CompetitionID = '$competitionID';")[0];
// 	var_dump($compArray);
// 	die();
	$compQuery = "Select replace(replace(Replace(Replace(Replace(REPLACE(CompetitionName,'Women',''),'Men',''),'Outdoor2016',''),'CanberraCupMidweek2016','CBR'),'GirlsDivision','G'),'BoysDivision','B') As 'CompetitionCode' from Competitions Where CompetitionName = '" . $compArray['CompetitionName'] . "'";
	$compCode = ExecuteSQL ($compQuery)[0];
	// $compCode["CompetitionCode"]
	//var_dump($compCode);
	//die();
	
	if (!file_exists("$filename")) {
		print "error, file doesn't exist<br>\r\n";
	}
	$objPHPExcel = PHPExcel_IOFactory::load($filename);
	$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
	foreach ($sheetData as $row => $data) {
		if ($data["A"] != "Last Name" && $data["B"] != "Firstname") {
			print "\t\t" . $data["B"] . " " . $data["A"] . " - " . $compCode["CompetitionCode"] . "\r\n";
			print $compCode["CompetitionCode"] . "\r\n";
			//print 'REPLACE INTO ' . $compArray['CompetitionName'] . ' (`PlayerName`, `GamesPlayed`, `Goals`, `GCard`, `YCard`, `RCard`) VALUES ("' . ucwords($data['B'] . ' ' . $data['A']) . '","' . $data['D'] . '","' . $data['E'] . '","' . $data['H'] . '","' . $data['G'] . '","' . $data['F'] . '");\r\n\r\n';
			ExecuteSQL ('UPDATE PlayerStats SET ' . $compCode["CompetitionCode"] . 'Goals = "' . $data['E'] . '" Where PlayerName = "' . $data["B"] . " " . $data["A"] . '"');
			ExecuteSQL('REPLACE INTO ' . $compArray['CompetitionName'] . ' (`PlayerName`, `GamesPlayed`, `Team`, `Goals`, `GCard`, `YCard`, `RCard`) VALUES ("' . ucwords($data['B'] . ' ' . $data['A']). '","' . $data['D'] . '","' . $data['C'] . '","' . $data['E'] . '","' . $data['H'] . '","' . $data['G'] . '","' . $data['F'] . '");'  );
		}
	}	
}

function GetCompetitionList ($StripSpace = false){
	$html = file_get_html("https://sportsdesq.onesporttechnology.com/index.cfm?action=ajax.getCompetitions&orgId=15&keyword=&searchColumn=&currentpage=1&rowsperpage=100&clubId=278&_=1462844773562");
	if ($StripSpace) {
		return (str_replace(" " , "", $html));
	} else {
		return $html;
	}
}

function EnumerateCompetitions($html) {
	global $ClubOnly;
	$Response = "";
	$json = json_decode($html,1);
	foreach ($json["competitions"] as $arrID => $comp) {
		$Response =  "$Response<div><p>";
		$Response = $Response . GetCompetition($comp["competitionId"], $comp["competitionName"]);
		$Response =  "$Response</p></div>";
	}
	return $Response;
}


function GetCompetition ($compID, $compName) {
	global $ClubOnly;
	global $compJSON;
	$tableResponse = "";
	$html = file_get_html("https://sportsdesq.onesporttechnology.com/15/portal/fixtures/competitionid/$compID");
	//print $html;
	$count = 0;
	$round = array();
	$teams = array();
	$scores = array();
	$dates = array();
	foreach ($html-> find('div.row') as $row) {
		$count++;
		$round[$count] = array();
		foreach ($row-> find('div.team-info .team-name') as $team) {
			array_push ($teams,trim(str_replace('</p>',"",str_replace('<p class="team-name">',"",$team))));
		}
		foreach ($row-> find('div.team-info .team-score') as $score) {
			array_push ($scores,trim(str_replace('</p>',"",str_replace('<p class="team-score">',"",$score))));
		}
		foreach ($row-> find('div.venue .datetime-block') as $date) {
			array_push ($dates,trim(str_replace('</p>',"",str_replace('<p class="heading">',"",str_replace('Date/Time:',"",trim(str_replace("</div>","",explode("<p>",$date)[1])))))));
		}
	}
// 	var_dump($dates);
	if ($ClubOnly == 0) {
		$tableResponse =  "$tableResponse<table border=1 class='table table-hover'>
		<tr><th colspan=3>$compName</th></tr>
		<tr><th> Home </th><th>Away</th><th>Date</th></tr>";
	} else {
		$tableResponse = "$tableResponse<table border=1 class='table table-hover'><tr><th>Home</th><th>Away</th><th>Date</th></tr>";
	}
	$compJSON[$compName] = "";
	for ($x = 0; $x <= ((count($teams))/2); $x++) {
		if (($teams[$x*2] == "United Hockey Club") or ($teams[$x*2+1] == "United Hockey Club")) {
			$compJSON[$compName][$teams[$x*2]] = $scores[$x*2];
			$compJSON[$compName][$teams[$x*2+1]] = $scores[$x*2+1];
			$compJSON[$compName]["Date"] = $dates[$x];
			if ($ClubOnly && $teams[$x*2] == "United Hockey Club") {
				$tableResponse =  "$tableResponse<tr bgcolor='#00ffff'><td class='col-md-2'><b> United " . $compName . "</b><br>" . $scores[$x*2] . "</td><td class='col-md-2'><b>" . $teams[$x*2+1] . " " . $compName .  "</b><br>" . $scores[$x*2+1] . "</td><td> " .  $dates[$x] . "</td></tr>";
			} elseif ($ClubOnly && $teams[$x*2+1] == "United Hockey Club") {
				$tableResponse =  "$tableResponse<tr bgcolor='#00ffff'><td class='col-md-2'><b>" . $teams[$x*2] . " " . $compName . "</b><br>" . $scores[$x*2] . "</td><td class='col-md-2'><b> United " . $compName . "</b><br>" . $scores[$x*2+1] . "</td><td> " .  $dates[$x] . "</td></tr>";
			} elseif ($ClubOnly == 0)  {
				$tableResponse =  "$tableResponse<tr bgcolor='#00ffff'><td class='col-md-2'><b>" . $teams[$x*2] . "</b><br>" . $scores[$x*2] . "</td><td class='col-md-2'><b>" . $teams[$x*2+1] . "</b><br>" . $scores[$x*2+1] . "</td><td> " .  $dates[$x] . "</td></tr>";
			}
		} else {
			if ($ClubOnly && $teams[$x*2] == "United Hockey Club") {
				$tableResponse =  "$tableResponse<tr><td class='col-md-2'><b> United" . $compName . "</b><br>" . $scores[$x*2] . "</td><td class='col-md-2'><b>" . $teams[$x*2+1] . " " . $compName .  "</b><br>" . $scores[$x*2+1] . "</td><td> " .  $dates[$x] . "</td></tr>";
			} elseif ($ClubOnly && $teams[$x*2+1] == "United Hockey Club") {
				$tableResponse =  "$tableResponse<tr><td class='col-md-2'><b>" . $teams[$x*2] . " " . $compName . "</b><br>" . $scores[$x*2] . "</td><td class='col-md-2'><b> United " . $compName . "</b><br>" . $scores[$x*2+1] . "</td><td> " .  $dates[$x] . "</td></tr>";
			} elseif ($ClubOnly == 0)  {
				$tableResponse =  "$tableResponse<tr><td class='col-md-2'><b>" . $teams[$x*2] . "</b><br>" . $scores[$x*2] . "</td><td class='col-md-2'><b>" . $teams[$x*2+1] . "</b><br>" . $scores[$x*2+1] . "</td><td> " .  $dates[$x] . "</td></tr>";
			}
		}
	}
	$tableResponse = "$tableResponse</table>";
	return $tableResponse;
}

function ExecuteSQL ($querystring) {
	$db = mysqli_connect("localhost","hockeydb",'$7a7$DB',"Hockey" . date("Y"));
	if(!$result = $db->query($querystring)){
		die('There was an error running the query [' . $db->error . ']');
	} else {
		if (strpos($querystring,"elect") or  strpos($querystring,"all")) {
			while($row = $result->fetch_array())
			{
				$return[] = $row;
			}
			
// 			$return = $result->fetch_array(MYSQLI_BOTH);
			return $return;
		}
	}
	$db->close();
}
function DownloadAndWriteFile($url, $filename) {
	// file handler
	$file = fopen("/var/www/html/hockey/tmp/$filename", 'w');
	// cURL
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL,$url);
	// set cURL options
	curl_setopt($ch, CURLOPT_FAILONERROR, true);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	// set file handler option
	curl_setopt($ch, CURLOPT_FILE, $file);
	// execute cURL
	curl_exec($ch);
	// close cURL
	curl_close($ch);
	// close file
	fclose($file);	
}

function GenerateClubResultsXLS ($json) {
	error_reporting(E_ALL);
	ini_set('display_errors', TRUE);
	ini_set('display_startup_errors', TRUE);
	require_once dirname(__FILE__) . '/../../Classes/PHPExcel.php';
	// Create new PHPExcel object
	$objPHPExcel = new PHPExcel();
	// Set document properties
	$objPHPExcel->getProperties()->setCreator("Andrew Husking")
	->setLastModifiedBy("Andrew Husking")
	->setTitle("United Hockey Club Stats")
	->setSubject("United Hockey Club Stats")
	->setDescription("Document containing the latest round stats at time of generation")
	->setKeywords("United Hockey Stats")
	->setCategory("United Hockey");
	
	$objPHPExcel->setActiveSheetIndex(0);
	$sheet = $objPHPExcel->getActiveSheet();
	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
// 	$sheet->mergeCells('A1:C1');
// 	$sheet->setCellValue('A1', 'Competition Name');
// 	$sheet->setCellValue('A2', 'Home');
// 	$sheet->setCellValue('B2', 'Away');
// 	$sheet->setCellValue('C2', 'Date');
	$counter = 0;
// 	var_dump($json);
// 	die();
	foreach ($json as $compName => $compData) {
		$counter++;
// 		var_dump($compData);
// 		die();
		if (!(is_string($compData))) {
			list($home, $away, $date) = array_keys($compData);
// 			print "$home -- $away -- $date";
	// 		die();
	// 		die();
			// Row 1
			$sheet->setCellValue('A' . $counter, $compName);
			// Row 2
			$sheet->setCellValue('A' . ($counter + 1) , 'Home');
			$sheet->setCellValue('B' . ($counter + 1), 'Away');
			$sheet->setCellValue('C' . ($counter + 1), 'Date');
			// Row 3
			$sheet->setCellValue('A' . ($counter + 2) , $home);
			$sheet->setCellValue('B' . ($counter + 2), $away);
	// 		$sheet->setCellValue('C' . ($counter + 2), $date);
			// Row 4
			$sheet->setCellValue('A' . ($counter + 3) , $compData[$home]);
			$sheet->setCellValue('B' . ($counter + 3), $compData[$away]);
			$sheet->setCellValue('C' . ($counter + 3), $compData[$date]);
			$counter = $counter + 5;
		}
	}
	
	
	
	$objPHPExcel->getActiveSheet()->setTitle('Stats');
	
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Disposition: attachment;filename="UnitedStats.xlsx"');
	header('Cache-Control: max-age=0');
	// If you're serving to IE 9, then the following may be needed
	header('Cache-Control: max-age=1');
	// If you're serving to IE over SSL, then the following may be needed
	header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
	header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
	header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
	header ('Pragma: public'); // HTTP/1.0
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	$objWriter->save('php://output');
	
	
	
	
}

?>