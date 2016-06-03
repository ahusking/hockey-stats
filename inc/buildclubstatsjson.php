<?php
	include("hockeyfunctions.php");
	$Response = EnumerateCompetitions(GetCompetitionList());
	$filename = $argv[1];
// 	print "$filename";
	$oldjson = json_decode(file_get_contents("../data/$filename.json"));
	var_dump($oldjson);
	var_dump($compJSON);
// 	die();
	if ($oldjson !== $compJSON) {
		print "writing new file $filename\r\n";
		$myfile = fopen("../data/$filename.json", "w");
		fwrite($myfile, json_encode($compJSON));
	}
?>