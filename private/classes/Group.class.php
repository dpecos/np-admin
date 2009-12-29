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

$ddbb->addTable("Group", "groups");
$ddbb->addField("Group", "groupId", "group_id", "INT", array("PK" => true, "NULLABLE" => false, "AUTO_INCREMENT" => true));
$ddbb->addField("Group", "groupName", "group_name", "STRING", array("NULLABLE" => false, "LENGTH" => 40));
$ddbb->addField("Group", "description", null, "STRING", array("NULLABLE" => true, "LENGTH" => 150, "DEFAULT" => null));

class Group {
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
	   $sql_1 = "DELETE FROM ".$ddbb->getTable('Group')." WHERE ".$ddbb->getMapping('Group','groupId')." = ".NP_DDBB::encodeSQLValue($this->groupId, $ddbb->getType('Group','groupId'));
	   $sql_2 = "DELETE FROM ".$ddbb->getTable('UserGroup')." WHERE ".$ddbb->getMapping('UserGroup','groupId')." = ".NP_DDBB::encodeSQLValue($this->groupId, $ddbb->getType('UserGroup','groupId'));            
	   $sql_3 = "DELETE FROM ".$ddbb->getTable('GroupRol')." WHERE ".$ddbb->getMapping('GroupRol','groupId')." = ".NP_DDBB::encodeSQLValue($this->groupId, $ddbb->getType('GroupRol','groupId'));            
	   $ddbb->executeDeleteQuery($sql_2);
	   $ddbb->executeDeleteQuery($sql_3);
	   return ($ddbb->executeDeleteQuery($sql_1) > 0);
   }
}
?>
