<?php
	include("hockeyfunctions.php");
	$Response = EnumerateCompetitions(GetCompetitionList());
	$filename = $argv[1];
	print "$filename";
	$myfile = fopen("../data/$filename.json", "w");
	fwrite($myfile, json_encode($compJSON));
?>