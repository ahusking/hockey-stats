<html>
<head>
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    
</head>
<body bgcolor=black>
<?php
error_reporting(E_ALL);
include("simple_html_dom.php");
$clubResponse = "";
$Response = "";
if ($_REQUEST["clubonly"] == 1) {
    $ClubOnly = 1;
} else {
    $ClubOnly = 0;
}
//$Response = GetCompetition("3500","U13's");
$Response = EnumerateCompetitions(GetCompetitionList());

print $Response;

// Functions below
function GetCompetitionList (){
    $html = file_get_html("https://sportsdesq.onesporttechnology.com/index.cfm?action=ajax.getCompetitions&orgId=15&keyword=&searchColumn=&currentpage=1&rowsperpage=100&clubId=278&_=1462844773562");
    $competitions = json_decode($html);
    return ($html);
}

function EnumerateCompetitions($html) {
    global $ClubOnly;
    $Response = "";
    $json = json_decode($html,1);
    //var_dump($json["competitions"]);
    foreach ($json["competitions"] as $arrID => $comp) {
        //var_dump($comp);
        $Response =  "$Response<div><p>";
        $Response = $Response . GetCompetition($comp["competitionId"], $comp["competitionName"]);
        $Response =  "$Response</p></div>";
    }
    return $Response;
}


function GetCompetition ($compID, $compName) {
    global $ClubOnly;
    $tableResponse = "";
    $html = file_get_html("https://sportsdesq.onesporttechnology.com/15/portal/fixtures/competitionid/$compID");
    //print $html;
    $count = 0;
    $round = array();
    $teams = array();
    $scores = array();
    
    foreach ($html-> find('div.row') as $row) {
        //print $row;
        $count++;
        $round[$count] = array();
        //print "<tr>";
        
        //$tcount = 
        foreach ($row-> find('div.team-info .team-name') as $team) {
            //foreach($team-> find(''))
            //print "<td><b>";
            array_push ($teams,trim(str_replace('</p>',"",str_replace('<p class="team-name">',"",$team))));
            
        }
        foreach ($row-> find('div.team-info .team-score') as $score) {
            //foreach($team-> find(''))
            
            array_push ($scores,trim(str_replace('</p>',"",str_replace('<p class="team-score">',"",$score))));
            
        }
        //print "</tr>";
    }
    //var_dump($teams);
    //var_dump($scores);
    //print count($teams);
    //print "\r\n--" . ((count($teams)/2)+1) . "--\r\n";
    if ($ClubOnly == 0) {
         $tableResponse =  "$tableResponse<table border=1 class='table table-hover'>
        <tr><th colspan=2>$compName</th></tr>
        <tr><th> Home </th><th>Away</th></tr>";
    } else {
        $tableResponse = "$tableResponse<table border=1 class='table table-hover'><tr><th>Home</th><th>Away</th></tr>";
    }
    for ($x = 0; $x <= ((count($teams))/2); $x++) {
        //print $x . "\r\n";
        if (($teams[$x*2] == "United Hockey Club") or ($teams[$x*2+1] == "United Hockey Club")) {
            if ($ClubOnly && $teams[$x*2] == "United Hockey Club") {
                $tableResponse =  "$tableResponse<tr bgcolor='#00ffff'><td class='col-md-2'><b> United " . $compName . "</b><br>" . $scores[$x*2] . "</td><td class='col-md-2'><b>" . $teams[$x*2+1] . " " . $compName .  "</b><br>" . $scores[$x*2+1] . "</td></tr>";
            } elseif ($ClubOnly && $teams[$x*2+1] == "United Hockey Club") {
                $tableResponse =  "$tableResponse<tr bgcolor='#00ffff'><td class='col-md-2'><b>" . $teams[$x*2] . " " . $compName . "</b><br>" . $scores[$x*2] . "</td><td class='col-md-2'><b> United " . $compName . "</b><br>" . $scores[$x*2+1] . "</td></tr>";
            } elseif ($ClubOnly == 0)  {
                $tableResponse =  "$tableResponse<tr bgcolor='#00ffff'><td class='col-md-2'><b>" . $teams[$x*2] . "</b><br>" . $scores[$x*2] . "</td><td class='col-md-2'><b>" . $teams[$x*2+1] . "</b><br>" . $scores[$x*2+1] . "</td></tr>";
            }
        } else {
            if ($ClubOnly && $teams[$x*2] == "United Hockey Club") {
                $tableResponse =  "$tableResponse<tr><td class='col-md-2'><b> United" . $compName . "</b><br>" . $scores[$x*2] . "</td><td class='col-md-2'><b>" . $teams[$x*2+1] . " " . $compName .  "</b><br>" . $scores[$x*2+1] . "</td></tr>";
            } elseif ($ClubOnly && $teams[$x*2+1] == "United Hockey Club") {
                $tableResponse =  "$tableResponse<tr><td class='col-md-2'><b>" . $teams[$x*2] . " " . $compName . "</b><br>" . $scores[$x*2] . "</td><td class='col-md-2'><b> United " . $compName . "</b><br>" . $scores[$x*2+1] . "</td></tr>";
            } elseif ($ClubOnly == 0)  {
                $tableResponse =  "$tableResponse<tr><td class='col-md-2'><b>" . $teams[$x*2] . "</b><br>" . $scores[$x*2] . "</td><td class='col-md-2'><b>" . $teams[$x*2+1] . "</b><br>" . $scores[$x*2+1] . "</td></tr>";
            }
        }
    }
    $tableResponse = "$tableResponse</table>";
    //var_dump($tableResponse);
    return $tableResponse;
}

//
//function GetCompetitionV2 ($compID) {
//    $curl = curl_init("https://sportsdesq.onesporttechnology.com/15/portal/fixtures/competitionid/$compID");
//    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
//    
//    $page = curl_exec($curl);
//    
//    if(curl_errno($curl)) // check for execution errors
//    {
//        echo 'Scraper error: ' . curl_error($curl);
//        exit;
//    }
//    
//    curl_close($curl);
//    
//    $regex = '/<div class="team-info">(.*?)<\/div>/s';
//    if ( preg_match($regex, $page, $list) )
//        echo $list[0];
//    else 
//        print "Not found"; 
//    
//}
?>