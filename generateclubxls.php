<?php
include("hockeyfunctions.php");
$compJSON = json_decode(file_get_contents ("unitedcomp.json"),1);
GenerateClubResultsXLS($compJSON);
?>