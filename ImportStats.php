<?php
include "inc/hockeyfunctions.php";
// ConvertCompetitionXLSToSql("tmp/20721","3237");
DownloadCompetitionStats(json_decode(GetCompetitionList(),1)["competitions"]);

?>