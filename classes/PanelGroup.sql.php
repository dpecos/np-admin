<?
global $ddbb;

$ddbb_table = "panels_groups";
$ddbb_mapping = array();
$ddbb_types = array();
$ddbb_sql = array();

$ddbb_mapping['panelId'] =    "panel_id";
$ddbb_mapping['groupName'] =  "group_name";

$ddbb_types['panelId'] =          "STRING";
$ddbb_types['groupName'] =    "STRING";

$ddbb_sql['panelId'] = array("PK" => true, "NULLABLE" => false, "LENGTH" => 40);
$ddbb_sql['groupName'] = array("PK" => true, "NULLABLE" => false, "LENGTH" => 40);

$ddbb->addConfig("PanelGroup", $ddbb_table, $ddbb_mapping, $ddbb_types, $ddbb_sql);
?>
