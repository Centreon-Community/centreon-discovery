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
 */

	/* 
	 * function checkall()
	 * Fonction permettant de checker/dechecker un groupe de checkboxes
	 */
	function checkall(groupcheckbox) {	
		temp = groupcheckbox.length;	
		if (groupcheckbox[0].checked) { 
			// si la case est cochée
			for (i=1; i < temp; i++) { 
				// on coche toutes les autres
				groupcheckbox[i].checked=true;
			}
		}
		else {
			for (i=1; i < temp; i++) { 
				// on décoche tout
				groupcheckbox[i].checked=false;
			}
		}
	}
	
	/* 
	 * function checkone()
	 * Fonction permettant de checker/dechecker la checkbox primaire
	 */
	function checkone(groupcheckbox) {
		m=0; // initialisation du nombre de checkboxes cochées	
		temp = groupcheckbox.length;

		for (i=1; i < temp; i++) { 
			if (groupcheckbox[i].checked) { 
				// si la checkbox courante est cochée, on comptabilise
				m++;
			}
		}
		if ( m > 0 ) {
			// si au moins une des checkboxes est cochée, on coche la checkboxe principale
			groupcheckbox[0].checked=true;
		}
		else {
			// sinon on décoche la checkboxe principale
			groupcheckbox[0].checked=false;
		}
	}	
	
	/*
	 * function verifselection()
	 * Fonction permettant de vérifer que l'on a bien selectionné au moins un objet ...
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
	 * Fonction vérifiant si une radiobox est séléctionnée
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