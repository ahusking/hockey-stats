<?php
include("hockeyfunctions.php");
$Response = EnumerateCompetitions(GetCompetitionList());
$myfile = fopen("unitedcomp.json", "w");

fwrite($myfile, json_encode($compJSON));
?>