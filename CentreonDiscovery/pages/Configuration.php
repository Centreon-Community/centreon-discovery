<!--
/*  This file is part of CentreonDiscovery.
 *
 *	 CentreonDiscovery is developped with GPL Licence 3.0 :
 *	 Developped by : Jonathan Teste - Cedric Dupre
 *   
 *   CentreonDiscovery is free software: you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation, either version 3 of the License.
 *   
 *   CentreonDiscovery is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU General Public License for more details.
 *
 *   You should have received a copy of the GNU General Public License
 *   along with CentreonDiscovery.  If not, see <http://www.gnu.org/licenses/>.
 */ -->
 
<html lang="fr">
	<head>
		<meta content="text/html; charset=ISO-8859-1" http-equiv="content-type">
		<title>Centreon Discovery</title>
		<link href="./Themes/Centreon-2/style.css" rel="stylesheet" type="text/css"/>
		<meta content="CentreonDiscovery" name="Jonathan TESTE & Cédric DUPRE">
		<!-- Fonctions JavaScript -->
		<script type="text/javascript" src="./modules/CentreonDiscovery/pages/JS-Func.js"></script>	
		<!-- End Fonctions JavaScript -->
	</head>
	<body style="background-color: rgb(255, 255, 255); color: rgb(0, 0, 0); height: 158px;" alink="#ff6600" link="#ff6600" vlink="#ff6600" onLoad="init()">  
		<div id="loading" style="position:absolute; width:90%; text-align:center; top:300px;"><img src="./modules/CentreonDiscovery/pictures/loading.gif" border=0></div>
		<script>
			var ld=(document.all);
			var ns4=document.layers;
			var ns6=document.getElementById&&!document.all;
			var ie4=document.all;

			if (ns4)
				ld=document.loading;
			else if (ns6)
				ld=document.getElementById("loading").style;
			else if (ie4)
				ld=document.all.loading.style;
		</script>
		<span class="" style="font-family: Candara;  font-weight: bold;">
			<!-- Logo Centreon Discovery -->
			<div style="position:relative; top: 5px;	text-align:center;">
				<div style="position:relative; width: 295px; height: 65px; top: 0px; text-align: left; margin : auto;">
					<span style="font-family: Candara; font-size: 1.75em; font-weight: bold; color: black; font-style: italic;">Centr</span><span style="font-family: Candara; font-size: 1.75em; font-weight: bold; color: rgb(0, 102, 0); font-style: italic;">e</span><span style="font-family: Candara; font-size: 1.75em; font-weight: bold; color: black; font-style: italic;">on Discovery</span>
					<br>
					<span style="font-family: Candara; font-size: 1.5em; font-weight: bold; color: rgb(0, 102, 0); font-style: italic;">Developped by</span>
					<a href="http://en.doc.centreon.com/User:Teamreon" target="_blank">
						<img style="border: 0px solid ; width: 189px; height: 37px;" alt="Centreon" src="./modules/CentreonDiscovery/pictures/teamreon_logo.jpg" align="middle">
					</a>
				</div>
				<div style="position:relative; width: 400px; height: 20px; top: 7px; margin : auto;">
					<hr align="left" color="black" size="5" width="400">
				</div>			
			</div>
			<br>
<?	
			// Test de la validité de la session utilisateur Centreon
			if (!isset ($oreon)) exit ();
			 
			// Librairie d'accès aux bases de données 
			require_once("DB-Func.php");	
			
			$services = mysql_query('SELECT service_id,command_id,service_description,command_name FROM service JOIN command ON command.command_id = service.command_command_id WHERE service.service_description LIKE "%Template_TeamReon%"');

			if ( mysql_num_rows($services) > 0 ) {
				echo '<span style="font-family: Candara; font-size: 1.25em; font-weight: bold; color: black; font-style: italic;">Templates de service & Commandes : </span><br><br>';
				echo '    <table class="ListTable" align="left">'," \n ";
				echo '		<tr class="ListHeader">'," \n ";
				echo '			<td class="ListColHeaderCenter">Templates de service</td>'," \n ";
				echo '			<td class="ListColHeaderCenter">Commandes</td>'," \n ";
				echo '		</tr>'," \n ";
				
				while ( $dataservices = mysql_fetch_assoc($services) ) {
					$service_id = $dataservices['service_id'];
					$command_id = $dataservices['command_id'];
					$service_description = $dataservices['service_description'];
					$command_name = $dataservices['command_name'];
					echo '		<tr class="list_one">'," \n ";
					echo '			<td class="ListColHeaderCenter"><a href="?p=60206&o=c&service_id='.$service_id.'">'.$service_description.'</a></td>'," \n ";
					echo '			<td class="ListColHeaderCenter"><a href="?p=60801&o=c&command_id='.$command_id.'&type=2">'.$command_name.'</a></td>'," \n ";
					echo '		</tr>'," \n ";
				}
			}
			
			
?>	  
	</span>
  </body>
</html>