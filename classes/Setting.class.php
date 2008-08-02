<?php

class Setting {
   public function __construct($data) {     
      global $ddbb_table, $ddbb_mapping, $ddbb_types;
      if (!is_array($data)) {
         $data = NP_executePKSelect("SELECT * FROM ".$ddbb_table['Setting']." WHERE ".$ddbb_mapping['Setting']['name']." = ".encodeSQLValue($data, $ddbb_types['Setting']['name']));
      }   
      NP_loadData($this, $data, $ddbb_mapping['Setting'], $ddbb_types['Setting']);
   }
   
   public function store() {
      global $ddbb_table, $ddbb_mapping, $ddbb_types;
      NP_insertObject($this, $ddbb_table, $ddbb_mapping, $ddbb_types);
      return true;
   }
   
   public function update() {
      global $ddbb_table, $ddbb_mapping, $ddbb_types;
      
      $sql = "UPDATE ".$ddbb_table['Setting']." SET ".$ddbb_mapping['Setting']['value']."=".encodeSQLValue($this->value, $ddbb_types['Setting']['value'])." WHERE ".$ddbb_mapping['Setting']['name']." = ".encodeSQLValue($this->name, $ddbb_types['Setting']['name']);

      NP_executeInsertUpdate($sql);

      return true;
   }
   
   public function delete() {
      global $ddbb_table, $ddbb_mapping, $ddbb_types;

      $sql = "DELETE FROM ".$ddbb_table['Setting']." WHERE ".$ddbb_mapping['Setting']['name']." = ".encodeSQLValue($this->name, $ddbb_types['Setting']['name']);
     
      return (NP_executeDelete($sql) > 0);
   }
   
}
?>
