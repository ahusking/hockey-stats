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

    <title>United Hockey Club</title>

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
          <a class="navbar-brand" href="?">United Hockey Club</a>
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
          <h1 class="page-header">United Dashboard</h1>
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
			$querystr = "call GetCLGamesPlayed();";
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
			$querystr = "call GetSLGamesPlayed();";
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
				$querystr = "call GetJuniorGamesPlayed();";
				$results = ExecuteSQL($querystr);
				foreach ($results as $rv => $PlayerInfo) {
					print "<tr>\r\n";
					print "<td>" . ucwords(strtolower($PlayerInfo["PlayerName"])) . "</td>\r\n";
					print "<td>" . $PlayerInfo["Junior Games Played"] . "</td>\r\n";
					print "</tr>\r\n";
				}
				print "</tbody></table>";
				break;	
			case "fuckthisshit":
				print '<div class="container">

  <h1>Bootstrap 3 DataTables</h1>

  <table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%">
    <thead>
      <tr>
        <th>Name</th>
        <th>Position</th>
        <th>Office</th>
        <th>Salary</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>Tiger Nixon</td>
        <td>System Architect</td>
        <td>Edinburgh</td>
        <td>$320,800</td>
      </tr>
      <tr>
        <td>Garrett Winters</td>
        <td>Accountant</td>
        <td>Tokyo</td>
        <td>$170,750</td>
      </tr>
      <tr>
        <td>Ashton Cox</td>
        <td>Junior Technical Author</td>
        <td>San Francisco</td>
        <td>$86,000</td>
      </tr>
      <tr>
        <td>Cedric Kelly</td>
        <td>Senior Javascript Developer</td>
        <td>Edinburgh</td>
        <td>$433,060</td>
      </tr>
      <tr>
        <td>Airi Satou</td>
        <td>Accountant</td>
        <td>Tokyo</td>
        <td>$162,700</td>
      </tr>
      <tr>
        <td>Brielle Williamson</td>
        <td>Integration Specialist</td>
        <td>New York</td>
        <td>$372,000</td>
      </tr>
      <tr>
        <td>Herrod Chandler</td>
        <td>Sales Assistant</td>
        <td>San Francisco</td>
        <td>$137,500</td>
      </tr>
      <tr>
        <td>Rhona Davidson</td>
        <td>Integration Specialist</td>
        <td>Tokyo</td>
        <td>$327,900</td>
      </tr>
      <tr>
        <td>Colleen Hurst</td>
        <td>Javascript Developer</td>
        <td>San Francisco</td>
        <td>$205,500</td>
      </tr>
      <tr>
        <td>Sonya Frost</td>
        <td>Software Engineer</td>
        <td>Edinburgh</td>
        <td>$103,600</td>
      </tr>
      <tr>
        <td>Jena Gaines</td>
        <td>Office Manager</td>
        <td>London</td>
        <td>$90,560</td>
      </tr>
      <tr>
        <td>Quinn Flynn</td>
        <td>Support Lead</td>
        <td>Edinburgh</td>
        <td>$342,000</td>
      </tr>
      <tr>
        <td>Charde Marshall</td>
        <td>Regional Director</td>
        <td>San Francisco</td>
        <td>$470,600</td>
      </tr>
      <tr>
        <td>Haley Kennedy</td>
        <td>Senior Marketing Designer</td>
        <td>London</td>
        <td>$313,500</td>
      </tr>
      <tr>
        <td>Tatyana Fitzpatrick</td>
        <td>Regional Director</td>
        <td>London</td>
        <td>$385,750</td>
      </tr>
      <tr>
        <td>Michael Silva</td>
        <td>Marketing Designer</td>
        <td>London</td>
        <td>$198,500</td>
      </tr>
      <tr>
        <td>Paul Byrd</td>
        <td>Chief Financial Officer (CFO)</td>
        <td>New York</td>
        <td>$725,000</td>
      </tr>
      <tr>
        <td>Gloria Little</td>
        <td>Systems Administrator</td>
        <td>New York</td>
        <td>$237,500</td>
      </tr>
      <tr>
        <td>Bradley Greer</td>
        <td>Software Engineer</td>
        <td>London</td>
        <td>$132,000</td>
      </tr>
      <tr>
        <td>Dai Rios</td>
        <td>Personnel Lead</td>
        <td>Edinburgh</td>
        <td>$217,500</td>
      </tr>
      <tr>
        <td>Jenette Caldwell</td>
        <td>Development Lead</td>
        <td>New York</td>
        <td>$345,000</td>
      </tr>
      <tr>
        <td>Yuri Berry</td>
        <td>Chief Marketing Officer (CMO)</td>
        <td>New York</td>
        <td>$675,000</td>
      </tr>
      <tr>
        <td>Caesar Vance</td>
        <td>Pre-Sales Support</td>
        <td>New York</td>
        <td>$106,450</td>
      </tr>
      <tr>
        <td>Doris Wilder</td>
        <td>Sales Assistant</td>
        <td>Sidney</td>
        <td>$85,600</td>
      </tr>
      <tr>
        <td>Angelica Ramos</td>
        <td>Chief Executive Officer (CEO)</td>
        <td>London</td>
        <td>$1,200,000</td>
      </tr>
      <tr>
        <td>Gavin Joyce</td>
        <td>Developer</td>
        <td>Edinburgh</td>
        <td>$92,575</td>
      </tr>
      <tr>
        <td>Jennifer Chang</td>
        <td>Regional Director</td>
        <td>Singapore</td>
        <td>$357,650</td>
      </tr>
      <tr>
        <td>Brenden Wagner</td>
        <td>Software Engineer</td>
        <td>San Francisco</td>
        <td>$206,850</td>
      </tr>
      <tr>
        <td>Fiona Green</td>
        <td>Chief Operating Officer (COO)</td>
        <td>San Francisco</td>
        <td>$850,000</td>
      </tr>
      <tr>
        <td>Shou Itou</td>
        <td>Regional Marketing</td>
        <td>Tokyo</td>
        <td>$163,000</td>
      </tr>
      <tr>
        <td>Michelle House</td>
        <td>Integration Specialist</td>
        <td>Sidney</td>
        <td>$95,400</td>
      </tr>
      <tr>
        <td>Suki Burks</td>
        <td>Developer</td>
        <td>London</td>
        <td>$114,500</td>
      </tr>
      <tr>
        <td>Prescott Bartlett</td>
        <td>Technical Author</td>
        <td>London</td>
        <td>$145,000</td>
      </tr>
      <tr>
        <td>Gavin Cortez</td>
        <td>Team Leader</td>
        <td>San Francisco</td>
        <td>$235,500</td>
      </tr>
      <tr>
        <td>Martena Mccray</td>
        <td>Post-Sales support</td>
        <td>Edinburgh</td>
        <td>$324,050</td>
      </tr>
      <tr>
        <td>Unity Butler</td>
        <td>Marketing Designer</td>
        <td>San Francisco</td>
        <td>$85,675</td>
      </tr>
      <tr>
        <td>Howard Hatfield</td>
        <td>Office Manager</td>
        <td>San Francisco</td>
        <td>$164,500</td>
      </tr>
      <tr>
        <td>Hope Fuentes</td>
        <td>Secretary</td>
        <td>San Francisco</td>
        <td>$109,850</td>
      </tr>
      <tr>
        <td>Vivian Harrell</td>
        <td>Financial Controller</td>
        <td>San Francisco</td>
        <td>$452,500</td>
      </tr>
      <tr>
        <td>Timothy Mooney</td>
        <td>Office Manager</td>
        <td>London</td>
        <td>$136,200</td>
      </tr>
      <tr>
        <td>Jackson Bradshaw</td>
        <td>Director</td>
        <td>New York</td>
        <td>$645,750</td>
      </tr>
      <tr>
        <td>Olivia Liang</td>
        <td>Support Engineer</td>
        <td>Singapore</td>
        <td>$234,500</td>
      </tr>
      <tr>
        <td>Bruno Nash</td>
        <td>Software Engineer</td>
        <td>London</td>
        <td>$163,500</td>
      </tr>
      <tr>
        <td>Sakura Yamamoto</td>
        <td>Support Engineer</td>
        <td>Tokyo</td>
        <td>$139,575</td>
      </tr>
      <tr>
        <td>Thor Walton</td>
        <td>Developer</td>
        <td>New York</td>
        <td>$98,540</td>
      </tr>
      <tr>
        <td>Finn Camacho</td>
        <td>Support Engineer</td>
        <td>San Francisco</td>
        <td>$87,500</td>
      </tr>
      <tr>
        <td>Serge Baldwin</td>
        <td>Data Coordinator</td>
        <td>Singapore</td>
        <td>$138,575</td>
      </tr>
      <tr>
        <td>Zenaida Frank</td>
        <td>Software Engineer</td>
        <td>New York</td>
        <td>$125,250</td>
      </tr>
      <tr>
        <td>Zorita Serrano</td>
        <td>Software Engineer</td>
        <td>San Francisco</td>
        <td>$115,000</td>
      </tr>
      <tr>
        <td>Jennifer Acosta</td>
        <td>Junior Javascript Developer</td>
        <td>Edinburgh</td>
        <td>$75,650</td>
      </tr>
      <tr>
        <td>Cara Stevens</td>
        <td>Sales Assistant</td>
        <td>New York</td>
        <td>$145,600</td>
      </tr>
      <tr>
        <td>Hermione Butler</td>
        <td>Regional Director</td>
        <td>London</td>
        <td>$356,250</td>
      </tr>
      <tr>
        <td>Lael Greer</td>
        <td>Systems Administrator</td>
        <td>London</td>
        <td>$103,500</td>
      </tr>
      <tr>
        <td>Jonas Alexander</td>
        <td>Developer</td>
        <td>San Francisco</td>
        <td>$86,500</td>
      </tr>
      <tr>
        <td>Shad Decker</td>
        <td>Regional Director</td>
        <td>Edinburgh</td>
        <td>$183,000</td>
      </tr>
      <tr>
        <td>Michael Bruce</td>
        <td>Javascript Developer</td>
        <td>Singapore</td>
        <td>$183,000</td>
      </tr>
      <tr>
        <td>Donna Snider</td>
        <td>Customer Support</td>
        <td>New York</td>
        <td>$112,000</td>
      </tr>
    </tbody>
  </table>

</div>';
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
