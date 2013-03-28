<?php

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
 * Developped by: Nicolas Dietrich & Vincent Van Den Bossche
 *
 * WEBSITE: http://community.centreon.com/projects/centreon-discovery
 * SVN: http://svn.modules.centreon.com/centreon-discovery
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
		
		function getConfig(){
			$conf_sql=mysql_query("SELECT * FROM mod_discovery_config");
			$conf=mysql_fetch_array($conf_sql, MYSQL_ASSOC);
			echo '<script type="text/javascript">CONFIG = Array();';
			foreach($conf as $var=>$val){
				echo 'CONFIG["'.$var.'"]=\''.$val.'\';';
			}
			echo '</script>';
			return $conf;
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
			
			$host = $data["host"];
			$macro = $data["macro"];
			require_once $centreon_path."www/include/configuration/configObject/host/DB-Func.php";
			require_once $centreon_path."www/include/configuration/configObject/service/DB-Func.php";
			
			//Création de l'hôte dans la bdd
			$host_id = insertHostInDB($host, $macro);
			
			//Mise à jour du template associé à cette hôte
			$req = mysql_query("INSERT INTO host_template_relation (host_host_id,host_tpl_id,`order`) VALUES('".$host_id."','".$host["host_template_model_htm_id"]."','1');");
			
			//Mise à jour du host_group
			if (isset($host["host_hgs"])) {updateHostHostGroup($host_id, $host);}
						
			//Association des services au template
			$req2 = mysql_query("SELECT service_service_id FROM `host_service_relation` WHERE host_host_id = '".$host["host_template_model_htm_id"]."';");
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
			generateHostServiceMultiTemplate($host_id, $host["host_template_model_htm_id"]);
	
			//Mise à jour du poller associé à cette hôte
			updateNagiosServerRelation($host_id, $host);
			
			$centreon->user->access->updateACL();
			insertHostExtInfos($host_id, $host);

			return $host_id;
		}

?>
