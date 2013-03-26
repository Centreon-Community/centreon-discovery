
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
 
 	/* 
	 * function updateNameBox()
	 * Fonction permettant de colorer les nom des hôtes non-renseignés ou déjà existants dans :
	 * Ce Tableau : array() -> hostsNew
	 * Centreon : array() -> hostsNameExist
	 */
	
	function updateNameBox(id){
		var selector;
			if(id=='ALL'){
				selector='form input:text[name^="hostname"]';
			}
			else {
				selector='form input:text[name="hostname['+id+']"]';
			}
			$j(selector).each(function(){
				var id=this.name.replace("hostname[", "");
				id=id.replace("]", "");
				var ipVar=$j('td [name="ip'+id+'"]').text();
				var inputVar=$j(this).val().toUpperCase();
					if(inputVar==''){ // Hostname vide
						$j(this).css("background-color", "yellow");
						$j(':checkbox[value="'+id+'"]').attr("disabled", true);
						$j('td [name="exist'+id+'"]').html('Empty Hostname').css('color', 'red');
					}
					else if(inputVar==' TIMEOUT SNMP '){ // Hostname introuvable
						$j(this).css("background-color", "yellow");
						$j(':checkbox[value="'+id+'"]').attr("disabled", true);
						$j('td [name="exist'+id+'"]').html('Invalid Hostname').css('color', 'red');
					}
					else if($j.inArray(inputVar, hostsNameExist) >= 1 || ($j.inArray(inputVar, hostsNew) >= 1 && $j.inArray(inputVar, hostsNew) != id)){ // Recherche du nom dans les Hôtes
						$j(this).css("background-color", "yellow");
						$j(':checkbox[value="'+id+'"]').attr("disabled", true);
						$j('td [name="exist'+id+'"]').html('Hostname already exist').css('color', CONFIG["host_exists_color"]);
					}
					else if(IllegalCharsRegex.test(inputVar)){ // Hostname invalide
						$j(this).css("background-color", "yellow");
						$j(':checkbox[value="'+id+'"]').attr("disabled", true);
						$j('td [name="exist'+id+'"]').html('Invalid hostname').css('color', 'red');
					}
					else if($j.inArray(ipVar, hostsIPExist) >= 1){ // Ip existe déjà
						$j(this).css("background-color", "white");
						$j(':checkbox[value="'+id+'"]').attr("disabled", false).css('background-color', 'yellow');
						$j('td [name="exist'+id+'"]').html('IP already exist').css('color', CONFIG["ip_exists_color"]);
					}
					else {
						$j(this).css("background-color", "white"); // Hote manquant
						$j(':checkbox[value="'+id+'"]').attr("disabled", false);
						$j('td [name="exist'+id+'"]').html('Host missing').css('color', CONFIG["host_missing_color"]);
					}
					hostsNew[id]=inputVar.toUpperCase(); 
			});
		}
		


 
	/* 
	* Fonction permettant de recharger l'hostname d'origine
	*/
 
	function Hostname_rollback(id){
		$j('form input:text[name="hostname['+id+']"]').val(hostsNewOrig[id]);
		updateNameBox(id);
	}
 
	/* 
	 * function checkall()
	 * Fonction permettant de checker/dechecker un groupe de checkboxes
	 */
	 
	function checkall(groupcheckbox) {	
		temp = groupcheckbox.length;	
		if (groupcheckbox[0].checked) { 
			// si la case est coch�e
			for (i=1; i < temp; i++) { 
				// on coche toutes les autres
                                if(!groupcheckbox[i].disabled){
                                    groupcheckbox[i].checked=true;
                                }
			}
		}
		else {
			for (i=1; i < temp; i++) { 
				// on d�coche tout
				groupcheckbox[i].checked=false;
			}
		}
	}
	
	/* 
	 * function checkone()
	 * Fonction permettant de checker/dechecker la checkbox primaire
	 */
	function checkone(groupcheckbox) {
		m=0; // initialisation du nombre de checkboxes coch�es	
		temp = groupcheckbox.length;

		for (i=1; i < temp; i++) { 
			if (groupcheckbox[i].checked) { 
				// si la checkbox courante est coch�e, on comptabilise
				m++;
			}
		}
		if ( m > 0 ) {
			// si au moins une des checkboxes est coch�e, on coche la checkboxe principale
			groupcheckbox[0].checked=true;
		}
		else {
			// sinon on d�coche la checkboxe principale
			groupcheckbox[0].checked=false;
		}
	}	
	
	/*
	 * function verifselection()
	 * Fonction permettant de v�rifer que l'on a bien selectionn� au moins un objet ...
	*/
	function verifselection(formulaire) {
		n=0;
		temp = formulaire.elements.length;
		for (i=1; i< temp;i++) {
			if (formulaire.elements[i].checked) {
				n++;
			}
		}
		if (n != 0) {
			formulaire.submit();
			return true;
		}
		else {
			alert('Please, select at least one host!');
			return false;
		}
	}
	
	/* 
	 * function radiovide()
	 * Fonction v�rifiant si une radiobox est s�l�ctionn�e
	 */
	function radiovide(maform) {
		for(i=0; i<maform.length; i++) {
			mesradios = maform.elements[i];
			if (mesradios.checked)
				return true;
		}
		alert("Veuillez s\351lectionner un host");
		return false;
	}
	
	
	/* 
	 * function init()
	 * Fonction permettant l'attente lors du chargement des pages
	 */
	function init()
	{
		if(ns4){ld.visibility="hidden";}
		else if (ns6||ie4) ld.display="none";
	}