<?php

class UserGroup {
   public function __construct($data = null) {     
      global $ddbb_table, $ddbb_mapping, $ddbb_types;
      NP_loadData($this, $data, $ddbb_mapping['UserGroup'], $ddbb_types['UserGroup']);
   }
   
   public function store() {
      global $ddbb_table, $ddbb_mapping, $ddbb_types;
      NP_insertObject($this, $ddbb_table, $ddbb_mapping, $ddbb_types);
      return true;
   }
   
   public function delete() {
      /*global $ddbb_table, $ddbb_mapping, $ddbb_types;
      if (strlen($this->UserGroup_name) == 0)
         $sql = "DELETE FROM ".$ddbb_table['UserGroup']." WHERE ".$ddbb_mapping['UserGroup']['UserGroup_name']." = ''";      
      else
         $sql = "DELETE FROM ".$ddbb_table['UserGroup']." WHERE ".$ddbb_mapping['UserGroup']['UserGroup_name']." = ".encodeSQLValue($this->UserGroup_name, $ddbb_types['UserGroup']['UserGroup_name']);      
      return (NP_executeDelete($sql) > 0);*/
   }
}
?>
