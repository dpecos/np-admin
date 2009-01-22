<?php
global $ddbb;

$ddbb->addTable("UserRol", "users_rols");
$ddbb->addField("UserRol", "rolId", "rol_id", "INT", array("PK" => true, "NULLABLE" => false));
$ddbb->addField("UserRol", "userId", "user_id", "INT", array("PK" => true, "NULLABLE" => false));

class UserRol {
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
