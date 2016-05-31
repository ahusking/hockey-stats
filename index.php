<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../favicon.ico">

    <title>
	<?php 
	include ("inc/config.php");
	print "$clubname\r\n";
	?>
	</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">


    <!-- Custom styles for this template -->
    <link href="css/dashboard.css" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
<link href="https://cdn.datatables.net/plug-ins/1.10.7/integration/bootstrap/3/dataTables.bootstrap.css" rel="stylesheet" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="https://cdn.datatables.net/1.10.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/plug-ins/1.10.7/integration/bootstrap/3/dataTables.bootstrap.js"></script>
<script>
$(document).ready(function() {
	  $('#table').dataTable({
		  "displayLength": 100 
	  });
	});
	</script>
  </head>

  <body>

    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container-fluid">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="?">	<?php 
// 			include ("inc/config.php");
			print "$clubname\r\n";
			?>	
		</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav navbar-right">
<!--             <li><a href="#">Dashboard</a></li> -->
<!--             <li><a href="#">Settings</a></li> -->
<!--             <li><a href="#">Profile</a></li> -->
            <li><a href="mailto://support@husking.id.au">Help</a></li>
          </ul>
<!--           <form class="navbar-form navbar-right"> -->
<!--             <input type="text" class="form-control" placeholder="Search..."> -->
<!--           </form> -->
        </div>
      </div>
    </nav>

    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-3 col-md-2 sidebar">
          <ul class="nav nav-sidebar">
            <li class="active"><a href="#">Score Stats <span class="sr-only">(current)</span></a></li>
            <li><a href="?page=topscorers">Highest Overall Scorers </a></li>
            <li><a href="?page=seniorscorers">Highest Senior Scorers </a></li>
            <li><a href="?page=juniorscorers">Highest Junior Scorers</a></li>
            
          </ul>
          <ul class="nav nav-sidebar">
          	<li class="active"><a href="#">Grade Stats<span class="sr-only">(current)</span></a></li>
          	
            <li ><a href="?page=getclgames">CL Games Played</a></li>
            <li ><a href="?page=getslgames">SL Games Played</a></li>
            <li ><a href="?page=getjuniorgames">Junior Games Played</a></li>
          </ul>
          <ul class="nav nav-sidebar">
          	<li class="active"><a href="#">Team Stats (To be Added)<span class="sr-only">(current)</span></a></li>
            <li class="disabled"><a href="">Random Item 1</a></li>
            <li class="disabled"><a href="">Random Item 2</a></li>
            <li class="disabled"><a href="">Random Item 3</a></li>
            <li ><a href="inc/generateclubxls.php">This Weeks Results</a></li>
          </ul>
        </div>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
          <h1 class="page-header"><?php print explode(" ", $clubname)[0]?> Dashboard</h1>
<?php 
error_reporting(E_ALL);
include("inc/hockeyfunctions.php");
$page = strtolower($_REQUEST["page"]);
// $page = "topscorers";

	switch ($page) {
		case "topscorers":
			print '<table id="table" class="table table-striped dataTable">
			<thead>
			<tr>
			<th>Player Name</th>
			<th>CL Goals</th>
			<th>SL Goals</th>
			<th>Junior Boys Goals</th>
    		<th>Junior Girls Goals</th>
    		<th>Total Goals</th>
			</tr>
			</thead><tbody>' . "\r\n";
// 			print $page;
			$querystr = "Select PlayerName, CL1Goals + CL2Goals as 'CL Goals', SL1Goals + SL2Goals + SL3Goals as 'SL Goals', U11B1Goals + U11B2Goals + U13B1Goals + U13B2Goals + U15B1Goals + U15B2Goals + U18B1Goals + U18B2Goals as 'Junior Boys Goals', U11G1Goals + U11G2Goals + U13G1Goals + U13G2Goals + U15G1Goals + U15G2Goals + U18G1Goals + U18G2Goals as 'Junior Girls Goals', CL1Goals + CL2Goals + SL1Goals + SL2Goals + SL3Goals + U11B1Goals + U11B2Goals + U13B1Goals + U13B2Goals + U15B1Goals + U15B2Goals + U18B1Goals + U18B2Goals + U11G1Goals + U11G2Goals + U13G1Goals + U13G2Goals + U15G1Goals + U15G2Goals + U18G1Goals + U18G2Goals  as 'Total Goals' from PlayerStats order by 6 Desc, PlayerName Asc;";
			$results = ExecuteSQL($querystr);
			foreach ($results as $rv => $PlayerInfo) {
				print "<tr>\r\n";
				print "<td>" . ucwords(strtolower($PlayerInfo["PlayerName"])) . "</td>\r\n";
				print "<td>" . $PlayerInfo["CL Goals"] . "</td>\r\n";
				print "<td>" . $PlayerInfo["SL Goals"] . "</td>\r\n";
				print "<td>" . $PlayerInfo["Junior Boys Goals"] . "</td>\r\n";
				print "<td>" . $PlayerInfo["Junior Girls Goals"] . "</td>\r\n";
          		print "<td>" . $PlayerInfo["Total Goals"] . "</td>\r\n";
				print "</tr>\r\n";
			}
			print "</tbody></table>";
// 			var_dump($results);
			break;
		case "juniorscorers":
			print '<table id="table" class="table table-striped display">
			<thead>
			<tr>
			<th>Player Name</th>
			<th>Junior Boys Goals</th>
    		<th>Junior Girls Goals</th>
    		<th>Total Goals</th>
			</tr>
			</thead><tbody>' . "\r\n";
			// 			print $page;
			$querystr = "Select PlayerName, U11B1Goals + U11B2Goals + U13B1Goals + U13B2Goals + U15B1Goals + U15B2Goals + U18B1Goals + U18B2Goals as 'Junior Boys Goals', U11G1Goals + U11G2Goals + U13G1Goals + U13G2Goals + U15G1Goals + U15G2Goals + U18G1Goals + U18G2Goals as 'Junior Girls Goals', U11B1Goals + U11B2Goals + U13B1Goals + U13B2Goals + U15B1Goals + U15B2Goals + U18B1Goals + U18B2Goals + U11G1Goals + U11G2Goals + U13G1Goals + U13G2Goals + U15G1Goals + U15G2Goals + U18G1Goals + U18G2Goals  as 'Total Goals' from PlayerStats order by 4 Desc, PlayerName Asc;";
			$results = ExecuteSQL($querystr);
			foreach ($results as $rv => $PlayerInfo) {
				print "<tr>\r\n";
				print "<td>" . ucwords(strtolower($PlayerInfo["PlayerName"])) . "</td>\r\n";
				print "<td>" . $PlayerInfo["Junior Boys Goals"] . "</td>\r\n";
				print "<td>" . $PlayerInfo["Junior Girls Goals"] . "</td>\r\n";
				print "<td>" . $PlayerInfo["Total Goals"] . "</td>\r\n";
				print "</tr>\r\n";
			}
			print "</tbody></table>";
			break;
		case "seniorscorers":
			print '<table id="table" class="table table-striped display">
			<thead>
			<tr>
			<th>Player Name</th>
			<th>CL Goals</th>
			<th>SL Goals</th>
    		<th>Total Goals</th>
			</tr>
			</thead><tbody>' . "\r\n";
			$querystr = "Select PlayerName, CL1Goals + CL2Goals as 'CL Goals', SL1Goals + SL2Goals + SL3Goals as 'SL Goals', CL1Goals + CL2Goals + SL1Goals + SL2Goals + SL3Goals  as 'Total Goals' from PlayerStats order by 4 Desc, PlayerName Asc;";
			$results = ExecuteSQL($querystr);
			foreach ($results as $rv => $PlayerInfo) {
				print "<tr>\r\n";
				print "<td>" . ucwords(strtolower($PlayerInfo["PlayerName"])) . "</td>\r\n";
				print "<td>" . $PlayerInfo["CL Goals"] . "</td>\r\n";
				print "<td>" . $PlayerInfo["SL Goals"] . "</td>\r\n";
				print "<td>" . $PlayerInfo["Total Goals"] . "</td>\r\n";
				print "</tr>\r\n";
			}
			print "</tbody></table>";
			break;
		case "getclgames":
			print '<table id="table" class="table table-striped display">
			<thead>
			<tr>
			<th>Player Name</th>
			<th>CL Games</th>
			</tr>
			</thead><tbody>' . "\r\n";
			$querystr = "call GetCLGamesPlayed('$clubname');";
			$results = ExecuteSQL($querystr);
			foreach ($results as $rv => $PlayerInfo) {
				print "<tr>\r\n";
				print "<td>" . ucwords(strtolower($PlayerInfo["PlayerName"])) . "</td>\r\n";
				print "<td>" . $PlayerInfo["CL Games Played"] . "</td>\r\n";
				print "</tr>\r\n";
			}
			print "</tbody></table>";
			break;
		case "getslgames":
			print '<table id="table" class="table table-striped display">
			<thead>
			<tr>
			<th>Player Name</th>
			<th>SL Games</th>
			</tr>
			</thead><tbody>' . "\r\n";
			$querystr = "call GetSLGamesPlayed('$clubname');";
			$results = ExecuteSQL($querystr);
			foreach ($results as $rv => $PlayerInfo) {
				print "<tr>\r\n";
				print "<td>" . ucwords(strtolower($PlayerInfo["PlayerName"])) . "</td>\r\n";
				print "<td>" . $PlayerInfo["SL Games Played"] . "</td>\r\n";
				print "</tr>\r\n";
			}
			print "</tbody></table>";
			break;
			case "getjuniorgames":
				print '<table id="table" class="table table-striped display">
			<thead>
			<tr>
			<th>Player Name</th>
			<th>Junior Games</th>
			</tr>
			</thead><tbody>' . "\r\n";
				$querystr = "call GetJuniorGamesPlayed('$clubname');";
				$results = ExecuteSQL($querystr);
				foreach ($results as $rv => $PlayerInfo) {
					print "<tr>\r\n";
					print "<td>" . ucwords(strtolower($PlayerInfo["PlayerName"])) . "</td>\r\n";
					print "<td>" . $PlayerInfo["Junior Games Played"] . "</td>\r\n";
					print "</tr>\r\n";
				}
				print "</tbody></table>";
				break;	
		default:
			
	
	}
?>

    </div>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery.min.js"><\/script>')</script>
  </body>
</html>
