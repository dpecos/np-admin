<?php

class Setting {
   public function __construct($data, $type = null) {     
      global $ddbb_table, $ddbb_mapping, $ddbb_types;
      if (!is_array($data)) {
         $data = NP_executePKSelect("SELECT * FROM ".$ddbb_table['Setting']." WHERE ".$ddbb_mapping['Setting']['name']." = ".encodeSQLValue($data, $ddbb_types['Setting']['name'])." AND ".$ddbb_mapping['Setting']['type']." = ".encodeSQLValue($type, $ddbb_types['Setting']['type']));
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
      
      $sql = "UPDATE ".$ddbb_table['Setting']." SET ".$ddbb_mapping['Setting']['value']."=".encodeSQLValue($this->value, $ddbb_types['Setting']['value']).", ".$ddbb_mapping['Setting']['defaultValue']."=".encodeSQLValue($this->defaultValue, $ddbb_types['Setting']['defaultValue'])." WHERE ".$ddbb_mapping['Setting']['name']." = ".encodeSQLValue($this->name, $ddbb_types['Setting']['name'])." AND ".$ddbb_mapping['Setting']['type']." = ".encodeSQLValue($this->type, $ddbb_types['Setting']['type']);

      NP_executeInsertUpdate($sql);

      return true;
   }
   
   public function delete() {
      global $ddbb_table, $ddbb_mapping, $ddbb_types;

      $sql = "DELETE FROM ".$ddbb_table['Setting']." WHERE ".$ddbb_mapping['Setting']['name']." = ".encodeSQLValue($this->name, $ddbb_types['Setting']['name'])." AND ".$ddbb_mapping['Setting']['type']." = ".encodeSQLValue($this->type, $ddbb_types['Setting']['type']);

      return (NP_executeDelete($sql) > 0);
   }
   
}
?>
