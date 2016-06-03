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
	include ("inc/hockeyfunctions.php");
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
          <a class="navbar-brand" href="?clubname=	<?php 
// 			include ("inc/config.php");
			print "$clubname\"> $clubname\r\n";
			?>	
		</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav navbar-right">
<!--             <li><a href="#">Dashboard</a></li> -->
<!--             <li><a href="#">Settings</a></li> -->
             <li><form action="index.php" method="post">
             <select onchange="this.form.submit()" class="form-control" name="clubname" id="clubname" >
             <?php 
             $results = ExecuteSQL("CALL GetClubs()");
//              var_dump($results);
             foreach ($results as $result => $ateam) {
             	$team = str_replace("'", "", $ateam["Team"]);
             	if ($team == $clubname) {
             		print '<option value="' . $team . '" selected> ' . $team . ' </option>' . "\r\n";
             	} else {
             		print '<option value="' . $team . '"> ' . $team . ' </option>' . "\r\n";   
             	}
             }
             ?> 
             </select></li>
             </form>
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
            <?php 
            if ($clubname == 'United Hockey Club') {
	            print '<li><a href="?page=topscorers">Highest Overall Scorers </a></li>
	            <li><a href="?page=seniorscorers">Highest Senior Scorers </a></li>
	            <li><a href="?page=juniorscorers">Highest Junior Scorers</a></li>';
            } else {
            	print '<li class="disabled" title="Not currently available"><a href="?page=topscorers">Highest Overall Scorers </a></li>
	            <li class="disabled" title="Not currently available"><a href="?page=seniorscorers">Highest Senior Scorers </a></li>
	            <li class="disabled" title="Not currently available"><a href="?page=juniorscorers">Highest Junior Scorers</a></li>';
            }
            ?>
            
          </ul>
          <ul class="nav nav-sidebar">
          	<li class="active"><a href="#">Grade Stats<span class="sr-only">(current)</span></a></li>
          	
            <li ><a href="?page=getclgames&clubname=<?php print $clubname; ?>">CL Games Played</a></li>
            <li ><a href="?page=getslgames&clubname=<?php print $clubname; ?>">SL Games Played</a></li>
            <li ><a href="?page=getjuniorgames&clubname=<?php print $clubname; ?>">Junior Games Played</a></li>
          </ul>
          <ul class="nav nav-sidebar">
          	<li class="active"><a href="#">Team Stats (To be Added)<span class="sr-only">(current)</span></a></li>
            <li class="disabled" title="Not currently available"><a href="">Random Item 1</a></li>
            <li class="disabled" title="Not currently available"><a href="">Random Item 2</a></li>
            <li class="disabled" title="Not currently available"><a href="">Random Item 3</a></li>
            <li class="active"><a href="#">Other<span class="sr-only">(current)</span></a></li>
            <?php 
	            if ($clubname == 'United Hockey Club') {
	            	print '<li ><a href="?page=lastweeksresults">Last Rounds Results</a></li>';
	            	print '<li ><a href="inc/generateclubxls.php">Last Rounds Results (Excel)</a></li>';
	            	
	            }
            ?>
            
          </ul>
        </div>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
        <?php include_once("inc/analyticstracking.php") ?>
          <h1 class="page-header"><?php print $clubname; ?> Dashboard</h1>
<?php 

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
			case "lastweeksresults":
            	print '<h2> Last Weeks Results</h1><table id="example" class="table table-striped table-bordered">
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
				    <tbody>';
		    	$json  = json_decode(file_get_contents("data/unitedcomp.json"), 1);
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
			    print '</tbody></table>';
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
