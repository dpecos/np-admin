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

$ddbb->addTable("MenuRol", "menus_rols");
$ddbb->addField("MenuRol", "menuId", "menu_id", "INT", array("PK" => true, "NULLABLE" => false, "LENGTH" => 11));
#$ddbb->addField("MenuRol", "rolName", "rol_name", "STRING", array("PK" => true, "NULLABLE" => false, "LENGTH" => 40));
$ddbb->addField("MenuRol", "rolId", "rol_id", "INT", array("PK" => true, "NULLABLE" => false));

class MenuRol {
}
?>
