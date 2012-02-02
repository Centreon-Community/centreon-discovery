<!--
/*  This file is part of Centreon-Discovery.
 *
 *	 Centreon-Discovery is developped with GPL Licence 3.0 :
 *	 Developped by : Jonathan Teste - Cedric Dupre
 *   
 *   Centreon-Discovery is free software: you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation, either version 3 of the License.
 *   
 *   Centreon-Discovery is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU General Public License for more details.
 *
 *   You should have received a copy of the GNU General Public License
 *   along with Centreon-Discovery.  If not, see <http://www.gnu.org/licenses/>.
 */

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
			return true;
		}
		else {
			alert("Veuillez s\351lectionner au moins un \351l\351ment");
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
//-->