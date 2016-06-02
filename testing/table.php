<!DOCTYPE html>
<html>
<head>
<meta charset="ISO-8859-1">
<title>Insert title here</title>
<link href="https://netdna.bootstrapcdn.com/bootstrap/3.0.3/css/bootstrap.min.css" rel="stylesheet" />
<link href="https://cdn.datatables.net/plug-ins/1.10.7/integration/bootstrap/3/dataTables.bootstrap.css" rel="stylesheet" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="https://cdn.datatables.net/1.10.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/plug-ins/1.10.7/integration/bootstrap/3/dataTables.bootstrap.js"></script>

<script>
$(document).ready(function() {
	  $('#example2').dataTable();
	});
	</script>
</head>
<body>
<div class="container">

  <table id="example" class="table table-striped table-bordered">
    <col width = "130">
    <col width = "20">
    <col width = "130">
    <col width = "130">
    <thead>
      <tr>
        <th>Home</th>
        <th >&nbsp;</th>
        <th>Away</th>
        <th>Game Info</th>
      </tr>
    </thead>
    <tbody>
    <?php 
    	$json  = json_decode(file_get_contents("../data/unitedcomp.json"), 1);
    	foreach ($json as $CompetitionName => $GameInfo) {
				// Fix this code for PHP
				$CompCode = str_replace("Boys Division","Boys D",str_replace("Girls Division","Girls D",str_replace("Canberra Cup Midweek 2016","Midweek",str_replace("Outdoor2016","",$CompetitionName))));
     			//$CompCode = str_replace(str_replace(str_replace(str_replace(str_replace(str_replace($CompetitionName,'Women',''),'Men',''),'Outdoor2016',''),'CanberraCupMidweek2016','CBR'),'GirlsDivision','G'),'BoysDivision','B');
    		$keys = array_keys($GameInfo);
    		if ($keys[0] != "" && $keys[1] != "") {
				print "<tr>";
	    		print "<td> $CompCode - " . $keys[0] . "<br>" . $GameInfo[$keys[0]] . "</td>";
	    		print '<td><img src="../inc/vs.png" width="40" height="40"></img></td>';
	    		print "<td> $CompCode - " . $keys[1] . "<br>" . $GameInfo[$keys[1]] . "</td>";
	    		print "<td>" . $GameInfo["Date"] . "<br>" . $GameInfo["Venue"] . "</td></tr>";
    		}
    	}
      // <tr>
      //  <td>Old Canberrans Hockey Club <br> Goals: 4</td>
      //  <td><img src="../inc/vs.png" width="40" height="40"></img></td>
      //  <td>United Hockey Club <br> Goals: 2</td>
      //  <td> 28 May 2016 5:00 PM <br> Powell </td>
      // </tr>
	?>
    </tbody>
  </table>

</div>

</body>
</html>