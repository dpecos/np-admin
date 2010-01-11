<?php
/** 
 * @package np-admin
 * @version 20090624
 * 
 * @author Daniel Pecos Martínez
 * @copyright Copyright (c) Daniel Pecos Martínez 
 * @license http://www.gnu.org/licenses/lgpl.html  LGPL License
 */
global $ddbb;

$ddbb->addTable("Menu", "menus");
$ddbb->addField("Menu", "menuId", "menu_id", "INT", array("PK" => true, "NULLABLE" => false, "LENGTH" => 11, "AUTO_INCREMENT" => true));
$ddbb->addField("Menu", "parentId", "parent_id", "INT", array("NULLABLE" => false, "LENGTH" => 11, "DEFAULT" => 0));
$ddbb->addField("Menu", "order", null, "INT", array("NULLABLE" => false, "LENGTH" => 11, "DEFAULT" => 0));
$ddbb->addField("Menu", "text", null, "STRING", array("NULLABLE" => true, "LENGTH" => 60, "DEFAULT" => NULL));
$ddbb->addField("Menu", "url", null, "STRING", array("NULLABLE" => true, "LENGTH" => 100, "DEFAULT" => NULL));
$ddbb->addField("Menu", "panelId", "panel_id", "STRING", array("NULLABLE" => true, "LENGTH" => 40, "DEFAULT" => NULL));

class Menu {
   public function __construct($data = null) {     
      global $ddbb;
      $ddbb->loadData($this, $data);
      
      if (isset($this->panelId) && $this->panelId != null) {
         $this->panel = new Panel($this->panelId);
      }
   }
   
   public function store() {
      global $ddbb;
      $ddbb->insertObject($this);
      return true;
   }
   
   public function delete() {
      global $ddbb;
      $sql_1 = "DELETE FROM ".$ddbb->getTable('Menu')." WHERE ".$ddbb->getMapping('Menu','menuId')." = ".NP_DDBB::encodeSQLValue($this->menuId, $ddbb->getType('Menu','menuId'));
      $sql_2 = "DELETE FROM ".$ddbb->getTable('MenuRol')." WHERE ".$ddbb->getMapping('MenuRol','menuId')." = ".NP_DDBB::encodeSQLValue($this->menuId, $ddbb->getType('MenuRol','menuId'));            
      $ddbb->executeDeleteQuery($sql_2);
      return ($ddbb->executeDeleteQuery($sql_1) > 0);
   }
   
}
?>
