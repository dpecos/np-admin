<?php

class User {
   public function __construct($data = null) {     
      global $ddbb_table, $ddbb_mapping, $ddbb_types;
      NP_loadData($this, $data, $ddbb_mapping['User'], $ddbb_types['User']);
   }
   
   public function store() {
      global $ddbb_table, $ddbb_mapping, $ddbb_types;
      NP_insertObject($this, $ddbb_table, $ddbb_mapping, $ddbb_types);
      return true;
   }
   
   public function delete() {
      global $ddbb_table, $ddbb_mapping, $ddbb_types;
      if (strlen($this->user) == 0) {
         $sql_1 = "DELETE FROM ".$ddbb_table['User']." WHERE ".$ddbb_mapping['User']['user']." = ''";
         $sql_2 = "DELETE FROM ".$ddbb_table['UserGroup']." WHERE ".$ddbb_mapping['UserGroup']['user']." = ''";            
      } else {
         $sql_1 = "DELETE FROM ".$ddbb_table['User']." WHERE ".$ddbb_mapping['User']['user']." = ".encodeSQLValue($this->user, $ddbb_types['User']['user']);
         $sql_2 = "DELETE FROM ".$ddbb_table['UserGroup']." WHERE ".$ddbb_mapping['UserGroup']['user']." = ".encodeSQLValue($this->user, $ddbb_types['UserGroup']['user']);            
      }
      NP_executeDelete($sql_2);
      return (NP_executeDelete($sql_1) > 0);
   }
   
   public function toString() {
      return $this->user;
   }
}
?>
