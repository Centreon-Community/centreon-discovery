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
			
			// Récupération d'informations du bouton 1.
	  		$bouton1 = $_POST['send1'];
			$bouton2 = $_POST['send2'];
			$bouton3 = $_POST['send3'];

			// Si le bouton send3 a été validé
			if (!empty($bouton3)) {
			// **************************************************************************
			// * Page 4 : Host sélectionné - Résumé des éléments supervisés *
			// **************************************************************************
				require_once("page4.php");
			}
			// Si le bouton send2 a été validé
			else if (!empty($bouton2)) {
			// **************************************************************************
			// * Page 3 : Host sélectionné - Résumé des éléments supervisés *
			// **************************************************************************
				require_once("page3.php");
			}
			// Si le bouton send1 a été validé		
			else if(!empty($bouton1)) {
			// ***************************************************************************************************
			// * Page 2 : Rappel de l'host sélectionné - Enumération des éléments supervisables de l'host *
			// ***************************************************************************************************
				require_once("page2.php");
			}
			else {
			// ***********************************************************************
			// * Page 1 : liste des "hosts" - Choix de l'élément à superviser *
			// ***********************************************************************
				require_once("page1.php");
			}
?>	  
	</span>
  </body>
</html>