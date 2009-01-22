<?php
global $ddbb;

$ddbb->addTable("User", "users");
$ddbb->addField("User", "userId", "user_id", "INT", array("PK" => true, "NULLABLE" => false));
$ddbb->addField("User", "user", null, "STRING", array("NULLABLE" => false, "LENGTH" => 20));
$ddbb->addField("User", "password", null, "STRING", array("NULLABLE" => false, "LENGTH" => 60));
$ddbb->addField("User", "email", null, "STRING", array("LENGTH" => 60, "DEFAULT" => NULL));
$ddbb->addField("User", "creationDate", "creation_date", "DATE", array("NULLABLE" => false, "DEFAULT" => "CURRENT_TIMESTAMP"));
$ddbb->addField("User", "realName", "real_name", "STRING", array("LENGTH" => 60, "DEFAULT" => NULL));

class User {
   public function __construct($data = null) {     
      global $ddbb;
      $ddbb->loadData($this, $data);
   }
   
   public function store() {
      global $ddbb;
      $ddbb->insertObject($this);
      return true;
   }
   
   public function delete() {
	   global $ddbb;
	   $sql_1 = "DELETE FROM ".$ddbb->getTable('User')." WHERE ".$ddbb->getMapping('User','userId')." = ".NP_DDBB::encodeSQLValue($this->userId, $ddbb->getType('User','userId'));
	   $sql_2 = "DELETE FROM ".$ddbb->getTable('UserGroup')." WHERE ".$ddbb->getMapping('UserGroup','userId')." = ".NP_DDBB::encodeSQLValue($this->userId, $ddbb->getType('UserGroup','userId'));            
	   $sql_3 = "DELETE FROM ".$ddbb->getTable('UserRol')." WHERE ".$ddbb->getMapping('UserRol','userId')." = ".NP_DDBB::encodeSQLValue($this->userId, $ddbb->getType('UserRol','userId'));            
	   $ddbb->executeDeleteQuery($sql_2);
	   $ddbb->executeDeleteQuery($sql_3);
	   return ($ddbb->executeDeleteQuery($sql_1) > 0);
   }
   
   public function toString() {
      return $this->user;
   }
}
?>
