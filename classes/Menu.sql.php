<?
global $npsql_dbconfig, $ddbb_table, $ddbb_mapping, $ddbb_types;

$ddbb_table['Menu'] = $npsql_dbconfig["PREFIX"]."menus";

$ddbb_mapping['Menu']['id'] =        "id";
$ddbb_mapping['Menu']['parentId'] =  "parent_id";
$ddbb_mapping['Menu']['order'] =     "order";
$ddbb_mapping['Menu']['text'] =      "text";
$ddbb_mapping['Menu']['url'] =       "url";

$ddbb_types['Menu']['id'] =        "INT";
$ddbb_types['Menu']['parentId'] =    "INT";
$ddbb_types['Menu']['order'] =    "INT";
$ddbb_types['Menu']['text'] =       "STRING";
$ddbb_types['Menu']['url'] =        "STRING";
?>
