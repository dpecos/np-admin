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
      if (strlen($this->group_name) == 0)
         $sql = "DELETE FROM ".$ddbb_table['Group']." WHERE ".$ddbb_mapping['Group']['group_name']." = ''";      
      else
         $sql = "DELETE FROM ".$ddbb_table['Group']." WHERE ".$ddbb_mapping['Group']['group_name']." = ".encodeSQLValue($this->group_name, $ddbb_types['Group']['group_name']);      
      return (NP_executeDelete($sql) > 0);
   }
}
?>
