<?
global $ddbb;

$ddbb_table = "panels";
$ddbb_mapping = array();
$ddbb_types = array();
$ddbb_sql = array();

$ddbb_mapping['id'] =       "id";
$ddbb_mapping['title'] =    "title";

$ddbb_types['id'] =        "STRING";
$ddbb_types['title'] =     "STRING";

$ddbb_sql['id'] = array("PK" => true, "NULLABLE" => false, "LENGTH" => 40);
$ddbb_sql['title'] = array("NULLABLE" => false, "LENGTH" => 60);

$ddbb->addConfig("Panel", $ddbb_table, $ddbb_mapping, $ddbb_types, $ddbb_sql);
?>
