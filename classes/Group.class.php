<?php

class Group {
   public function __construct($data = null) {     
      global $ddbb_table, $ddbb_mapping, $ddbb_types;
      NP_loadData($this, $data, $ddbb_mapping['Group'], $ddbb_types['Group']);
   }
   
   public function store() {
      global $ddbb_table, $ddbb_mapping, $ddbb_types;
      NP_insertObject($this, $ddbb_table, $ddbb_mapping, $ddbb_types);
      return true;
   }
   
   public function delete() {
      global $ddbb_table, $ddbb_mapping, $ddbb_types;
      if (strlen($this->group_name) == 0) {
         $sql_1 = "DELETE FROM ".$ddbb_table['Group']." WHERE ".$ddbb_mapping['Group']['group_name']." = ''";
         $sql_2 = "DELETE FROM ".$ddbb_table['UserGroup']." WHERE ".$ddbb_mapping['UserGroup']['group_name']." = ''";            
      } else {
         $sql_1 = "DELETE FROM ".$ddbb_table['Group']." WHERE ".$ddbb_mapping['Group']['group_name']." = ".encodeSQLValue($this->group_name, $ddbb_types['Group']['group_name']);
         $sql_2 = "DELETE FROM ".$ddbb_table['UserGroup']." WHERE ".$ddbb_mapping['UserGroup']['group_name']." = ".encodeSQLValue($this->group_name, $ddbb_types['UserGroup']['group_name']);            
      }
      NP_executeDelete($sql_2);
      return (NP_executeDelete($sql_1) > 0);
   }
}
?>
