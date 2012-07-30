/* This file is part of Centreon-Discovery module.
 *
 * Centreon-Discovery is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by the
 *  Free Software Foundation, either version 2 of the License.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, see <http://www.gnu.org/licenses>.
 *
 * Linking this program statically or dynamically with other modules is making a
 * combined work based on this program. Thus, the terms and conditions of the GNU
 * General Public License cover the whole combination.
 *
 * Module name: Centreon-Discovery
 *
 * Adapted by: Nicolas Dietrich & Vincent Van Den Bossche
 *
 * WEBSITE: http://community.centreon.com/projects/centreon-discovery
 * SVN: http://svn.modules.centreon.com/centreon-discovery
 */
 
function check(div_id,plage_id)
{
$j.post('./modules/Discovery/include/verif_discovery_status.php', { id: plage_id }, function(data) 
	{
			//On renvoie les données HTML
			var result = $j('#status'+div_id).html(data);
			
			//On teste la valeur afin de savoir si on grise la checkbox ou non
			var value = result[0].innerHTML;
			if (value.indexOf("DOWN",0) != -1){
				document.getElementById('RangeToScan'+div_id).disabled = 1;
				document.getElementById('RangeToScan'+div_id).checked = false;
			}
			if (value.indexOf("UP",0) != -1){
				document.getElementById('RangeToScan'+div_id).disabled = 0;
			}
	}
);
}

$j(document).ready(function()
{	
	var nb_elements = document.getElementsByClassName('status').length;
	for (i=0;i<nb_elements;i++){
		var plage = document.getElementById('status'+i).getElementsByTagName("p")[0].getAttribute("id");
		setInterval('check('+i+','+plage+')', 3000);
	}
});

//Fonction permettant d'afficher ou de cacher les options dans le tableau
function afficher_cacher(id, classe){
	var objet = document.getElementById(id);
	if (objet.style.display == "none"){
		$j('.'+classe).slideUp('fast');
		$j('#'+id).slideDown('fast');
	}
	else{
		$j('#'+id).slideUp('fast');
	}
}		

//Fonction permettant de cocher ou de décocher toutes les plages à scanner
function select_all(classe){
	var object = document.getElementsByClassName(classe).length;
	for (i=0;i<object;i++){
		if ((document.getElementById('SelectAll').checked == true) && (document.getElementById(classe+i).disabled == 0)){
			document.getElementById(classe+i).checked=true;
		}else{
			document.getElementById(classe+i).checked=false;
		}
	}
}

//Cette fonction permet de valider le formulaire, et de vérifier si des plages ont bien été sélectionnées
function valider_form(formulaire){
	var object = document.getElementsByClassName('RangeToScan').length;
		for (i=0;i<object;i++){
			var tmp = 'RangeToScan'+i;
			if (document.getElementById('RangeToScan'+i).checked == true){	
				return true;
			}
		}
		alert('Please, select at least one address range!');
		return false;
}

//Fonction ajax, permettant de mettre à jour la base de données lors du changement de poller dans la liste déroulante
function request_update(url,cadre,source,id) {
        var XHR = null;

        if(window.XMLHttpRequest) // Firefox
                XHR = new XMLHttpRequest();
        else if(window.ActiveXObject) // Internet Explorer
                XHR = new ActiveXObject("Microsoft.XMLHTTP");
        else { // XMLHttpRequest non supporté par le navigateur
                alert("Votre navigateur ne supporte pas les objets XMLHTTPRequest...");
                return;
        }
       
	// on guette les changements d'état de l'objet
    XHR.onreadystatechange = function attente() {
                // l'état est à 4, requête reçu !
        if(XHR.readyState == 4)     {
                // ecriture de la réponse
			document.getElementById(cadre).innerHTML = XHR.responseText;
		}
    }
	//On envoie avec la méthode POST
	XHR.open("POST","./modules/Discovery/include/update.php",true);
	XHR.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	
	//On précise les arguments que l'on veut poster
	sel = document.getElementById(source);
	poller_id = sel.options[sel.selectedIndex].value;
	XHR.send("poller_id="+poller_id+"&id="+id);

    return;
}

function refresh_div() {
	var XHR = null;

	if(window.XMLHttpRequest) // Firefox
			XHR = new XMLHttpRequest();
	else if(window.ActiveXObject) // Internet Explorer
			XHR = new ActiveXObject("Microsoft.XMLHTTP");
	else { // XMLHttpRequest non supporté par le navigateur
			alert("Votre navigateur ne supporte pas les objets XMLHTTPRequest...");
			return;
	}
	
	XHR.onreadystatechange = function()
	{
		if(XHR.readyState == 4 && XHR.status == 200)
		{
			var tmp = XHR.responseText;
			$j('#mon_div').html(tmp);
			if (tmp.indexOf("end",0) != -1){
				window.setTimeout("window.location='./main.php?p=61202';",100);
				XHR.abort();
			}else{
				window.setTimeout("refresh_div()",2000);
			}
		}
	}
			
	var method = 'GET';
	var filename = './modules/Discovery/include/refresh_result.php';
	XHR.open(method, filename, false);
	XHR.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	XHR.send(null);
}