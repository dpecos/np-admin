<?
global $ddbb;

$ddbb_table = "menus_groups";
$ddbb_mapping = array();
$ddbb_types = array();
$ddbb_sql = array();

$ddbb_mapping['menuId'] =    "menu_id";
$ddbb_mapping['groupName'] =  "group_name";

$ddbb_types['menuId'] =          "INT";
$ddbb_types['groupName'] =    "STRING";

$ddbb_sql['menuId'] = array("PK" => true, "NULLABLE" => false, "LENGTH" => 11);
$ddbb_sql['groupName'] = array("PK" => true, "NULLABLE" => false, "LENGTH" => 40);

$ddbb->addConfig("MenuGroup", $ddbb_table, $ddbb_mapping, $ddbb_types, $ddbb_sql);
?>
