<?php
global $ddbb;

$ddbb->addTable("UserGroup", "users_groups");
$ddbb->addField("UserGroup", "groupName", "group_name", "STRING", array("PK" => true, "NULLABLE" => false, "LENGTH" => 40));
$ddbb->addField("UserGroup", "user", null, "STRING", array("PK" => true, "NULLABLE" => false, "LENGTH" => 20));

class UserGroup {
   public function __construct($data = null) {     
      global $ddbb;
      $ddbb->loadData($this, $data);
   }
   
   public function store() {
      global $ddbb;
      $ddbb->insertObject($this);
      return true;
   }
}
?>
