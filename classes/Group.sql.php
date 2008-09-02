<?
global $ddbb;

$ddbb_table = "groups";
$ddbb_mapping = array();
$ddbb_types = array();
$ddbb_sql = array();

$ddbb_mapping['groupName'] =        "group_name";
$ddbb_mapping['description'] =    "description";

$ddbb_types['groupName'] =        "STRING";
$ddbb_types['description'] =    "STRING";

$ddbb_sql['groupName'] = array("PK" => true, "NULLABLE" => false, "LENGTH" => 40);
$ddbb_sql['description'] = array("NULLABLE" => true, "LENGTH" => 150, "DEFAULT" => null);

$ddbb->addConfig("Group", $ddbb_table, $ddbb_mapping, $ddbb_types, $ddbb_sql);
?>
