<?php
global $ddbb;

$ddbb->addTable("Setting", "settings");
$ddbb->addField("Setting", "name", null, "STRING", array("PK" => true, "NULLABLE" => false, "LENGTH" => 60));
$ddbb->addField("Setting", "type", null, "STRING", array("PK" => true, "NULLABLE" => false, "LENGTH" => 40));
$ddbb->addField("Setting", "value", null, "STRING", array("LENGTH" => 100, "DEFAULT" => NULL));
$ddbb->addField("Setting", "defaultValue", "default_value", "STRING", array("LENGTH" => 100, "DEFAULT" => NULL));

class Setting {
   public function __construct($data = null, $type = null) {     
      global $ddbb;
      if ($data != null) {
         if (!is_array($data)) {
            $data = $ddbb->executePKSelectQuery("SELECT * FROM ".$ddbb->getTable('Setting')." WHERE ".$ddbb->getMapping('Setting', 'name')." = ".NP_DDBB::encodeSQLValue($data, $ddbb->getType('Setting', 'name'))." AND ".$ddbb->getMapping('Setting','type')." = ".NP_DDBB::encodeSQLValue($type, $ddbb->getType('Setting','type')));
         }   
         $ddbb->loadData($this, $data);
      }
   }
   
   public function store() {
      global $ddbb;
      $ddbb->insertObject($this);
      return true;
   }
   
   public function update() {
      global $ddbb;
      
      $sql = "UPDATE ".$ddbb->getTable('Setting')." SET ".$ddbb->getMapping('Setting', 'value')."=".NP_DDBB::encodeSQLValue($this->value, $ddbb->getType('Setting', 'value')).", ".$ddbb->getMapping('Setting', 'defaultValue')."=".NP_DDBB::encodeSQLValue($this->defaultValue, $ddbb->getType('Setting', 'defaultValue'))." WHERE ".$ddbb->getMapping('Setting', 'name')." = ".NP_DDBB::encodeSQLValue($this->name, $ddbb->getType('Setting', 'name'))." AND ".$ddbb->getMapping('Setting', 'type')." = ".NP_DDBB::encodeSQLValue($this->type, $ddbb->getType('Setting', 'type'));

      $ddbb->executeInsertUpdateQuery($sql);

      return true;
   }
   
   public function delete() {
      global $ddbb;

      $sql = "DELETE FROM ".$ddbb->getTable('Setting')." WHERE ".$ddbb->getMapping('Setting', 'name')." = ".NP_DDBB::encodeSQLValue($this->name, $ddbb->getType('Setting', 'name'))." AND ".$ddbb->getMapping('Setting', 'type')." = ".NP_DDBB::encodeSQLValue($this->type, $ddbb->getType('Setting', 'type'));

      return ($ddbb->executeDeleteQuery($sql) > 0);
   }
   
}
?>
