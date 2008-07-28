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
}
?>
