<?
global $ddbb;

$ddbb_table = "settings";
$ddbb_mapping = array();
$ddbb_types = array();

$ddbb_mapping['name'] =        "name";
$ddbb_mapping['type'] =        "type";
$ddbb_mapping['value'] =       "value";
$ddbb_mapping['defaultValue'] =  "default_value";

$ddbb_types['name'] =          "STRING";
$ddbb_types['type'] =          "STRING";
$ddbb_types['value'] =         "STRING";
$ddbb_types['defaultValue'] =  "STRING";

$ddbb->addConfig("Setting", $ddbb_table, $ddbb_mapping, $ddbb_types);
?>
