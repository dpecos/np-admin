<?php

class Menu {
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
      if (strlen($this->user) == 0) {
         $sql_1 = "DELETE FROM ".$ddbb->getTable('Menu')." WHERE ".$ddbb->getMapping('Menu','id')." = ''";
         $sql_2 = "DELETE FROM ".$ddbb->getTable('MenuGroup')." WHERE ".$ddbb->getMapping('MenuGroup','menu_id')." = ''";            
      } else {
         $sql_1 = "DELETE FROM ".$ddbb->getTable('Menu')." WHERE ".$ddbb->getMapping('Menu','id')." = ".NP_DDBB::encodeSQLValue($this->user, $ddbb->getType('Menu','id'));
         $sql_2 = "DELETE FROM ".$ddbb->getTable('MenuGroup')." WHERE ".$ddbb->getMapping('MenuGroup','menu_id')." = ".NP_DDBB::encodeSQLValue($this->user, $ddbb->getType('MenuGroup','menu_id'));            
      }
      $ddbb->executeDeleteQuery($sql_2);
      return ($ddbb->executeDeleteQuery($sql_1) > 0);
   }
   
}
?>
