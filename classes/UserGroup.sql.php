<?
global $npsql_dbconfig, $ddbb_table, $ddbb_mapping, $ddbb_types;

$ddbb_table['UserGroup'] = $npsql_dbconfig["PREFIX"]."users_groups";

$ddbb_mapping['UserGroup']['group_name'] =  "group_name";
$ddbb_mapping['UserGroup']['user'] =        "user";

$ddbb_types['UserGroup']['group_name'] =    "STRING";
$ddbb_types['UserGroup']['user'] =          "STRING";

?>
