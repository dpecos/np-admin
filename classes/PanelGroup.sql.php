<?
global $ddbb;

$ddbb_table = "panels_groups";
$ddbb_mapping = array();
$ddbb_types = array();
$ddbb_sql = array();

$ddbb_mapping['panel_id'] =    "panel_id";
$ddbb_mapping['group_name'] =  "group_name";

$ddbb_types['panel_id'] =          "STRING";
$ddbb_types['group_name'] =    "STRING";

$ddbb_sql['panel_id'] = array("PK" => true, "NULLABLE" => false, "LENGTH" => 40);
$ddbb_sql['group_name'] = array("PK" => true, "NULLABLE" => false, "LENGTH" => 40);

$ddbb->addConfig("PanelGroup", $ddbb_table, $ddbb_mapping, $ddbb_types, $ddbb_sql);
?>
