<?
global $ddbb;

$ddbb_table = "settings";
$ddbb_mapping = array();
$ddbb_types = array();
$ddbb_sql = array();

$ddbb_mapping['name'] =        "name";
$ddbb_mapping['type'] =        "type";
$ddbb_mapping['value'] =       "value";
$ddbb_mapping['defaultValue'] =  "default_value";

$ddbb_types['name'] =          "STRING";
$ddbb_types['type'] =          "STRING";
$ddbb_types['value'] =         "STRING";
$ddbb_types['defaultValue'] =  "STRING";

$ddbb_sql['name'] = array("PK" => true, "NULLABLE" => false, "LENGTH" => 60);
$ddbb_sql['type'] = array("PK" => true, "NULLABLE" => false, "LENGTH" => 40);
$ddbb_sql['value'] = array("LENGTH" => 100, "DEFAULT" => NULL);
$ddbb_sql['defaultValue'] = array("LENGTH" => 100, "DEFAULT" => NULL);

$ddbb->addConfig("Setting", $ddbb_table, $ddbb_mapping, $ddbb_types, $ddbb_sql);
?>
