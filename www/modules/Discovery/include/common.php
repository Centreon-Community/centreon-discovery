<?php

function clean_sql(){
    mysql_query("DELETE FROM mod_discovery_rangeip WHERE id!=0;");
    mysql_query("UPDATE mod_discovery_rangeip SET done=0, cidr=0 WHERE id=0;");
    mysql_query("DELETE FROM mod_discovery_results;");
}

?>