<?php
require_once("User.sql.php");

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
      if (strlen($this->user) == 0)
         $sql = "DELETE FROM ".$ddbb_table['User']." WHERE ".$ddbb_mapping['User']['user']." = ''";      
      else
         $sql = "DELETE FROM ".$ddbb_table['User']." WHERE ".$ddbb_mapping['User']['user']." = ".encodeSQLValue($this->user, $ddbb_types['User']['user']);      
      return (NP_executeDelete($sql) > 0);
   }
}
?>
