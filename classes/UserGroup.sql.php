<?
global $ddbb;

$ddbb_table = "users_groups";
$ddbb_mapping = array();
$ddbb_types = array();

$ddbb_mapping['group_name'] =  "group_name";
$ddbb_mapping['user'] =        "user";

$ddbb_types['group_name'] =    "STRING";
$ddbb_types['user'] =          "STRING";

$ddbb->addConfig("UserGroup", $ddbb_table, $ddbb_mapping, $ddbb_types);
?>
