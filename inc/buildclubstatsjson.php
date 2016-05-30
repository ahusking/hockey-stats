<?php
include("hockeyfunctions.php");
$Response = EnumerateCompetitions(GetCompetitionList());
$myfile = fopen("../data/unitedcomp.json", "w");

fwrite($myfile, json_encode($compJSON));
?>