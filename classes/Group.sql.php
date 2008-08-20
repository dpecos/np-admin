<?
global $ddbb;

$ddbb_table = "groups";
$ddbb_mapping = array();
$ddbb_types = array();

$ddbb_mapping['group_name'] =        "group_name";
$ddbb_mapping['description'] =    "description";

$ddbb_types['group_name'] =        "STRING";
$ddbb_types['description'] =    "STRING";

$ddbb->addConfig("Group", $ddbb_table, $ddbb_mapping, $ddbb_types);
?>
