<?
	$host_id = $_POST["host_id"];
	$group_description = $_POST["group_description"];
	$elt_name = $_POST["elt_name"];
	$services = $_POST["services"];

	for($i=0;$i<sizeof($group_description);$i++) {
		for($j=0;$j<sizeof($elt_name[$i]);$j++) {
			for($k=0;$k<sizeof($services[$i][$j]);$k++) {
				/* TEST */ //echo '<br><p>group_description = '.$group_description[$i].'</p>';
				/* TEST */ //echo '<p>elt_name = '.$elt_name[$i][$j].'</p>';
				/* TEST */ //echo '<p>services = '.$services[$i][$j][$k].'</p><br>';
				InsertionRelationServiceBDD($host_id,$services[$i][$j][$k],$elt_name[$i][$j],$group_description[$i]);
			}			
		}
	}
	
	echo '<p>'," \n ";
	echo '	<span style="font-family: Candara; font-weight: bold; color: red; font-style: italic;">'," \n ";
	echo '  	<center><big>Votre configuration est d&eacute;sormais appliqu&eacute;e...</center></big>'," \n ";
	echo '		<br>'," \n ";
	echo '  	<center>Afin de l\'activer, pensez &agrave; l\'exporter au sein de nagios</center>'," \n ";
	echo '		<br>'," \n ";
	echo '	</span>'," \n ";
	echo '	<center><img style="border: 0px solid ; width: 600px;" alt="Centreon" src="./modules/CentreonDiscovery/pictures/screenshot_application_config_nagios.png" align="middle"></center>'," \n ";
	echo '</p>'," \n ";
	echo '<br><br>'," \n ";
?>