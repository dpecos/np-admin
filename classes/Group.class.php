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
         $sql_3 = "DELETE FROM ".$ddbb->getTable('PanelGroup')." WHERE ".$ddbb->getMapping('PanelGroup','groupName')." = ''";            
         $sql_4 = "DELETE FROM ".$ddbb->getTable('MenuGroup')." WHERE ".$ddbb->getMapping('MenuGroup','groupName')." = ''";                        
      } else {
         $sql_1 = "DELETE FROM ".$ddbb->getTable('Group')." WHERE ".$ddbb->getMapping('Group','group_name')." = ".NP_DDBB::encodeSQLValue($this->group_name, $ddbb->getType('Group','group_name'));
         $sql_2 = "DELETE FROM ".$ddbb->getTable('UserGroup')." WHERE ".$ddbb->getMapping('UserGroup','group_name')." = ".NP_DDBB::encodeSQLValue($this->group_name, $ddbb->getType('UserGroup','group_name'));            
         $sql_3 = "DELETE FROM ".$ddbb->getTable('PanelGroup')." WHERE ".$ddbb->getMapping('PanelGroup','groupName')." = ".NP_DDBB::encodeSQLValue($this->group_name, $ddbb->getType('PanelGroup','groupName'));            
         $sql_4 = "DELETE FROM ".$ddbb->getTable('MenuGroup')." WHERE ".$ddbb->getMapping('MenuGroup','groupName')." = ".NP_DDBB::encodeSQLValue($this->group_name, $ddbb->getType('MenuGroup','groupName'));            
      }
      $ddbb->executeDeleteQuery($sql_2);
      $ddbb->executeDeleteQuery($sql_3);
      $ddbb->executeDeleteQuery($sql_4);
      return ($ddbb->executeDeleteQuery($sql_1) > 0);
   }
}
?>
