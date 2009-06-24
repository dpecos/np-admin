<?
/** 
 * @package np-admin
 * @version 20090624
 * 
 * @author Daniel Pecos Martínez
 * @copyright Copyright (c) Daniel Pecos Martínez 
 * @license http://www.gnu.org/licenses/lgpl.html  LGPL License
 */
global $ddbb;

$ddbb->addTable("MenuGroup", "menus_groups");
$ddbb->addField("MenuGroup", "menuId", "menu_id", "INT", array("PK" => true, "NULLABLE" => false, "LENGTH" => 11));
#$ddbb->addField("MenuGroup", "groupName", "group_name", "STRING", array("PK" => true, "NULLABLE" => false, "LENGTH" => 40));
$ddbb->addField("MenuGroup", "groupId", "group_id", "INT", array("PK" => true, "NULLABLE" => false));

class MenuGroup {
}
?>
