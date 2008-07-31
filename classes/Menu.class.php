<?php

class Menu {
   public function __construct($data = null) {     
      global $ddbb_table, $ddbb_mapping, $ddbb_types;
      NP_loadData($this, $data, $ddbb_mapping['Menu'], $ddbb_types['Menu']);
   }
   
   public function store() {
      global $ddbb_table, $ddbb_mapping, $ddbb_types;
      NP_insertObject($this, $ddbb_table, $ddbb_mapping, $ddbb_types);
      return true;
   }
   
   public function delete() {
      global $ddbb_table, $ddbb_mapping, $ddbb_types;
      if (strlen($this->user) == 0) {
         $sql_1 = "DELETE FROM ".$ddbb_table['Menu']." WHERE ".$ddbb_mapping['Menu']['id']." = ''";
         $sql_2 = "DELETE FROM ".$ddbb_table['MenuGroup']." WHERE ".$ddbb_mapping['MenuGroup']['menu_id']." = ''";            
      } else {
         $sql_1 = "DELETE FROM ".$ddbb_table['Menu']." WHERE ".$ddbb_mapping['Menu']['id']." = ".encodeSQLValue($this->user, $ddbb_types['Menu']['id']);
         $sql_2 = "DELETE FROM ".$ddbb_table['MenuGroup']." WHERE ".$ddbb_mapping['MenuGroup']['menu_id']." = ".encodeSQLValue($this->user, $ddbb_types['MenuGroup']['menu_id']);            
      }
      NP_executeDelete($sql_2);
      return (NP_executeDelete($sql_1) > 0);
   }
   
}
?>
