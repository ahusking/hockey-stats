<?php
include("hockeyfunctions.php");
$Response = EnumerateCompetitions(GetCompetitionList());
GenerateClubResultsXLS($compJSON);
?>