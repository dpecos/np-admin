<?
global $ddbb;

$ddbb->addTable("MenuGroup", "menus_groups");
$ddbb->addField("MenuGroup", "menuId", "menu_id", "INT", array("PK" => true, "NULLABLE" => false, "LENGTH" => 11));
#$ddbb->addField("MenuGroup", "groupName", "group_name", "STRING", array("PK" => true, "NULLABLE" => false, "LENGTH" => 40));
$ddbb->addField("MenuGroup", "groupId", "group_id", "INT", array("PK" => true, "NULLABLE" => false));

class MenuGroup {
}
?>
