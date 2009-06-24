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

$ddbb->addTable("Rol", "rols");
$ddbb->addField("Rol", "rolId", "rol_id", "INT", array("PK" => true, "NULLABLE" => false, "AUTO_INCREMENT" => true));
$ddbb->addField("Rol", "rolName", "rol_name", "STRING", array("NULLABLE" => false, "LENGTH" => 40));
$ddbb->addField("Rol", "description", null, "STRING", array("NULLABLE" => true, "LENGTH" => 150, "DEFAULT" => null));

class Rol {
   public function __construct($data = null) {     
      global $ddbb;
      $ddbb->loadData($this, $data);
   }
   
   public function store() {
      global $ddbb;
      $ddbb->insertObject($this);
      return true;
   }
   
   public function delete() {
	   global $ddbb;
	   $sql_1 = "DELETE FROM ".$ddbb->getTable('Rol')." WHERE ".$ddbb->getMapping('Rol','rolId')." = ".NP_DDBB::encodeSQLValue($this->rolId, $ddbb->getType('Rol','rolId'));
	   //$sql_2 = "DELETE FROM ".$ddbb->getTable('UserRol')." WHERE ".$ddbb->getMapping('UserRol','rolId')." = ".NP_DDBB::encodeSQLValue($this->rolId, $ddbb->getType('UserRol','rolId'));            
	   //$sql_3 = "DELETE FROM ".$ddbb->getTable('PanelRol')." WHERE ".$ddbb->getMapping('PanelRol','rolId')." = ".NP_DDBB::encodeSQLValue($this->rolId, $ddbb->getType('PanelRol','rolId'));            
	   //$sql_4 = "DELETE FROM ".$ddbb->getTable('MenuRol')." WHERE ".$ddbb->getMapping('MenuRol','rolId')." = ".NP_DDBB::encodeSQLValue($this->rolId, $ddbb->getType('MenuRol','rolId'));            
	   //$ddbb->executeDeleteQuery($sql_2);
	   //$ddbb->executeDeleteQuery($sql_3);
	   //$ddbb->executeDeleteQuery($sql_4);
	   return ($ddbb->executeDeleteQuery($sql_1) > 0);
   }
}
?>
