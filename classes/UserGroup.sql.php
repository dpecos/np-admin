<?
global $ddbb;

$ddbb_table = "users_groups";
$ddbb_mapping = array();
$ddbb_types = array();
$ddbb_sql = array();

$ddbb_mapping['groupName'] =  "group_name";
$ddbb_mapping['user'] =        "user";

$ddbb_types['groupName'] =    "STRING";
$ddbb_types['user'] =          "STRING";

$ddbb_sql['groupName'] = array("PK" => true, "NULLABLE" => false, "LENGTH" => 40);
$ddbb_sql['user'] = array("PK" => true, "NULLABLE" => false, "LENGTH" => 20);

$ddbb->addConfig("UserGroup", $ddbb_table, $ddbb_mapping, $ddbb_types, $ddbb_sql);
?>
