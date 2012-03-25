<?php
/* $Id: DB-Func.php 7 2010-03-08 14:41:33Z malinges $
 *
 * Centreon is developped with GPL Licence 2.0 :
 * http://www.gnu.org/licenses/old-licenses/gpl-2.0.txt
 * Developped by : Julien Mathis - Romain Le Merlus - Christophe Coraboeuf
 * 
 * The Software is provided to you AS IS and WITH ALL FAULTS.
 * Centreon makes no representation and gives no warranty whatsoever,
 * whether express or implied, and without limitation, with regard to the quality,
 * any particular or intended purpose of the Software found on the Centreon web site.
 * In no event will Centreon be liable for any direct, indirect, punitive, special,
 * incidental or consequential damages however they may arise and even if Centreon has
 * been previously advised of the possibility of such damages.
 * 
 * For information : contact@centreon.com
 */

        /*
         * {DataBase connexion function}
         *
         * @param	string	$db_host database hostname
         * @param	string	$db_user database user login
         * @param	string	$db_passwd database password
         * @param	string	$db_name database name
         * @param	boulean	$arg create new link
         * @throws Exception Description
         * @return	int
         */

		function dbConnect($db_host,$db_user,$db_passwd,$db_name,$arg){
            $mysql = mysql_connect($db_host,$db_user,$db_passwd,$arg);
            if (!$mysql) {
                echo '<br><br><br>';
                die('Could not connect to Mysql: ' . mysql_error());
                return 0;
            }
            $db = mysql_select_db($db_name,$mysql);
            if (!$db) {
                echo '<br><br><br>';
                die('Could not connect to DataBase: ' . mysql_error());
                return 0;
            }
            return $mysql;
        }

        /*
         * {DataBase logout function}
         *
         * @param	int	$db database conexion identifier
         * @throws Exception Description
         * @return	int
         */

        function dbClose($db){
            mysql_close($db);
            return 0;
        }
		
		function callInsertHostInDb($data){
		
			global $centreon_path;
			global $oreon;  
			global $centreon;  
			
			require_once $centreon_path."www/include/configuration/configObject/host/DB-Func.php";
			require_once $centreon_path."www/include/configuration/configObject/service/DB-Func.php";
			
			//Création de l'hôte dans la bdd
			$host_id = insertHostInDB($data);
			
			//Mise à jour du template associé à cette hôte
			$req = mysql_query("INSERT INTO host_template_relation (host_host_id,host_tpl_id,`order`) VALUES('".$host_id."','".$data["host_template_model_htm_id"]."','1');");
			
			//Mise à jour du host_group
			if (isset($data["host_hgs"])) {updateHostHostGroup($host_id, $data);}
						
			//Association des services au template
			$req2 = mysql_query("SELECT service_service_id FROM `host_service_relation` WHERE host_host_id = '".$data["host_template_model_htm_id"]."';");
			while ($values=mysql_fetch_array($req2))
			{
				$alias = getMyServiceAlias($values["service_service_id"]);
				if (testServiceExistence($alias, array(0 => $host_id))) {
					$service = array(
						"service_template_model_stm_id" => $values["service_service_id"],
						"service_description" => $alias,
						"service_register" => array("service_register" => 1),
						"service_activate" => array("service_activate" => 1),
						"service_hPars" => array("0" => $host_id));
					$service_id = insertServiceInDB($service);
				}
			}			
			generateHostServiceMultiTemplate($host_id, $data["host_template_model_htm_id"]);
	
			//Mise à jour du poller associé à cette hôte
			updateNagiosServerRelation($host_id, $data);
			
			$centreon->user->access->updateACL();
			insertHostExtInfos($host_id, $data);

			return $host_id;
		}

?>
