<?
global $npsql_dbconfig, $ddbb_table, $ddbb_mapping, $ddbb_types;

$ddbb_table['Group'] = $npsql_dbconfig["PREFIX"]."groups";

$ddbb_mapping['Group']['group_name'] =        "group_name";
$ddbb_mapping['Group']['description'] =    "description";

$ddbb_types['Group']['group_name'] =        "STRING";
$ddbb_types['Group']['description'] =    "STRING";

?>
