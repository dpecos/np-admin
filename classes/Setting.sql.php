<?
global $npsql_dbconfig, $ddbb_table, $ddbb_mapping, $ddbb_types;

$ddbb_table['Setting'] = $npsql_dbconfig["PREFIX"]."settings";

$ddbb_mapping['Setting']['name'] =        "name";
$ddbb_mapping['Setting']['type'] =        "type";
$ddbb_mapping['Setting']['value'] =       "value";
$ddbb_mapping['Setting']['defaultValue'] =  "default_value";

$ddbb_types['Setting']['name'] =          "STRING";
$ddbb_types['Setting']['type'] =          "STRING";
$ddbb_types['Setting']['value'] =         "STRING";
$ddbb_types['Setting']['defaultValue'] =  "STRING";
?>
