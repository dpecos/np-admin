<?
global $ddbb;

$ddbb_table = "menus";
$ddbb_mapping = array();
$ddbb_types = array();
$ddbb_sql = array();

$ddbb_mapping['id'] =        "id";
$ddbb_mapping['parentId'] =  "parent_id";
$ddbb_mapping['order'] =     "order";
$ddbb_mapping['text'] =      "text";
$ddbb_mapping['url'] =       "url";

$ddbb_types['id'] =        "INT";
$ddbb_types['parentId'] =    "INT";
$ddbb_types['order'] =    "INT";
$ddbb_types['text'] =       "STRING";
$ddbb_types['url'] =        "STRING";

$ddbb_sql['id'] = array("PK" => true, "NULLABLE" => false, "LENGTH" => 11, "AUTO_INCREMENT" => true);
$ddbb_sql['parentId'] = array("NULLABLE" => false, "LENGTH" => 11, "DEFAULT" => 0);
$ddbb_sql['order'] = array("NULLABLE" => false, "LENGTH" => 11, "DEFAULT" => 0);
$ddbb_sql['text'] = array("NULLABLE" => true, "LENGTH" => 60, "DEFAULT" => NULL);
$ddbb_sql['url'] = array("NULLABLE" => true, "LENGTH" => 100, "DEFAULT" => NULL);

$ddbb->addConfig("Menu", $ddbb_table, $ddbb_mapping, $ddbb_types, $ddbb_sql);
?>
