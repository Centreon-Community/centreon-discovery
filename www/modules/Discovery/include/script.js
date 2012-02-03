$(document).ready(function()
{	
	var nb_elements = document.getElementsByClassName('status').length;
	for (i=0;i<nb_elements;i++){
		var plage = document.getElementById('status'+i).getElementsByTagName("p")[0].getAttribute("id");
		setInterval('check('+i+','+plage+')', 3000);
	}
});

function check(div_id,plage_id)
{
$.post('./modules/Discovery/include/verif_discovery_status.php', { id: plage_id }, function(data) 
	{
			//On renvoie les données HTML
			var result = $('#status'+div_id).html(data);
			
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

//Fonction permettant d'afficher ou de cacher les options dans le tableau
function afficher_cacher(id, classe){
	var objet = document.getElementById(id);
	if (objet.style.display == "none"){
		$('.'+classe).slideUp('fast');
		$('#'+id).slideDown('fast');
	}
	else{
		$('#'+id).slideUp('fast');
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
			document.getElementById('mon_div').innerHTML = tmp;
			if (tmp.indexOf("end",0) != -1){
				window.setTimeout("window.location='./main.php?p=61202&stop=1';",100);
				XHR.abort();
			}else{
				window.setTimeout("refresh_div()",1000);
			}
		}
	}
			
	var method = 'GET';
	var filename = './modules/Discovery/include/refresh_result.php';
	XHR.open(method, filename, false);
	XHR.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	XHR.send(null);
}

	
