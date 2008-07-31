<?php

class Setting {
   public function __construct($name) {     
      global $ddbb_table, $ddbb_mapping, $ddbb_types;
      $data = NP_executePKSelect("SELECT * FROM ".$ddbb_table['Setting']." WHERE ".$ddbb_mapping['Setting']['name']." = ".encodeSQLValue($name, $ddbb_types['Setting']['name']));
      NP_loadData($this, $data, $ddbb_mapping['Setting'], $ddbb_types['Setting']);
   }
   
   public function store() {
      global $ddbb_table, $ddbb_mapping, $ddbb_types;
      NP_insertObject($this, $ddbb_table, $ddbb_mapping, $ddbb_types);
      return true;
   }
   
   public function delete() {
      global $ddbb_table, $ddbb_mapping, $ddbb_types;

      $sql = "DELETE FROM ".$ddbb_table['Setting']." WHERE ".$ddbb_mapping['Setting']['name']." = ''";
     
      return (NP_executeDelete($sql) > 0);
   }
   
}
?>
