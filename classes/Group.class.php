<?php
global $ddbb;

$ddbb->addTable("Group", "groups");
$ddbb->addField("Group", "groupName", "group_name", "STRING", array("PK" => true, "NULLABLE" => false, "LENGTH" => 40));
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
      if (strlen($this->group_name) == 0) {
         $sql_1 = "DELETE FROM ".$ddbb->getTable('Group')." WHERE ".$ddbb->getMapping('Group','groupName')." = ''";
         $sql_2 = "DELETE FROM ".$ddbb->getTable('UserGroup')." WHERE ".$ddbb->getMapping('UserGroup','groupName')." = ''";
         $sql_3 = "DELETE FROM ".$ddbb->getTable('PanelGroup')." WHERE ".$ddbb->getMapping('PanelGroup','groupName')." = ''";            
         $sql_4 = "DELETE FROM ".$ddbb->getTable('MenuGroup')." WHERE ".$ddbb->getMapping('MenuGroup','groupName')." = ''";                        
      } else {
         $sql_1 = "DELETE FROM ".$ddbb->getTable('Group')." WHERE ".$ddbb->getMapping('Group','groupName')." = ".NP_DDBB::encodeSQLValue($this->group_name, $ddbb->getType('Group','groupName'));
         $sql_2 = "DELETE FROM ".$ddbb->getTable('UserGroup')." WHERE ".$ddbb->getMapping('UserGroup','groupName')." = ".NP_DDBB::encodeSQLValue($this->group_name, $ddbb->getType('UserGroup','groupName'));            
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
