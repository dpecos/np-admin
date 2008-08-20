<?
global $ddbb;

$ddbb_table = "menus";
$ddbb_mapping = array();
$ddbb_types = array();

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

$ddbb->addConfig("Menu", $ddbb_table, $ddbb_mapping, $ddbb_types);
?>
