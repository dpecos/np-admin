<?php

class User {
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
         $sql_1 = "DELETE FROM ".$ddbb->getTable('User')." WHERE ".$ddbb->getMapping('User','user')." = ''";
         $sql_2 = "DELETE FROM ".$ddbb->getTable('UserGroup')." WHERE ".$ddbb->getMapping('UserGroup','user')." = ''";            
      } else {
         $sql_1 = "DELETE FROM ".$ddbb->getTable('User')." WHERE ".$ddbb->getMapping('User','user')." = ".NP_DDBB::encodeSQLValue($this->user, $ddbb->getType('User','user'));
         $sql_2 = "DELETE FROM ".$ddbb->getTable('UserGroup')." WHERE ".$ddbb->getMapping('UserGroup','user')." = ".NP_DDBB::encodeSQLValue($this->user, $ddbb->getType('UserGroup','user'));            
      }
      $ddbb->executeDeleteQuery($sql_2);
      return ($ddbb->executeDeleteQuery($sql_1) > 0);
   }
   
   public function toString() {
      return $this->user;
   }
}
?>
