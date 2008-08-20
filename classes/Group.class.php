<?php

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
      if (strlen($this->group_name) == 0) {
         $sql_1 = "DELETE FROM ".$ddbb->getTable('Group')." WHERE ".$ddbb->getMapping('Group','group_name')." = ''";
         $sql_2 = "DELETE FROM ".$ddbb->getTable('UserGroup')." WHERE ".$ddbb->getMapping('UserGroup','group_name')." = ''";            
      } else {
         $sql_1 = "DELETE FROM ".$ddbb->getTable('Group')." WHERE ".$ddbb->getMapping('Group','group_name')." = ".NP_DDBB::encodeSQLValue($this->group_name, $ddbb->getType('Group','group_name'));
         $sql_2 = "DELETE FROM ".$ddbb->getTable('UserGroup')." WHERE ".$ddbb->getMapping('UserGroup','group_name')." = ".NP_DDBB::encodeSQLValue($this->group_name, $ddbb->getType('UserGroup','group_name'));            
      }
      $ddbb->executeDeleteQuery($sql_2);
      return ($ddbb->executeDeleteQuery($sql_1) > 0);
   }
}
?>
