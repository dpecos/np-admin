<?
global $ddbb;

$ddbb_table = "menus_groups";
$ddbb_mapping = array();
$ddbb_types = array();

$ddbb_mapping['menu_id'] =    "menu_id";
$ddbb_mapping['group_name'] =  "group_name";

$ddbb_types['menu_id'] =          "INT";
$ddbb_types['group_name'] =    "STRING";

$ddbb_sql['menu_id'] = array("PK" => true, "NULLABLE" => false, "LENGTH" => 11);
$ddbb_sql['group_name'] = array("PK" => true, "NULLABLE" => false, "LENGTH" => 40);

$ddbb->addConfig("MenuGroup", $ddbb_table, $ddbb_mapping, $ddbb_types, $ddbb_sql);
?>
