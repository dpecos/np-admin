<?php
/** 
 * @package np-admin
 * @version 20100103
 * 
 * @author Daniel Pecos Martínez
 * @copyright Copyright (c) Daniel Pecos Martínez 
 * @license http://www.gnu.org/licenses/lgpl.html  LGPL License
 */
global $ddbb;

$ddbb->addTable("Application", "applications");
$ddbb->addField("Application", "appId", "app_id", "INT", array("PK" => true, "NULLABLE" => false, "AUTO_INCREMENT" => true));
$ddbb->addField("Application", "name", null, "STRING", array("NULLABLE" => false, "LENGTH" => 40));
$ddbb->addField("Application", "version", null, "INT", array("NULLABLE" => false));
$ddbb->addField("Application", "author", null, "STRING", array("NULLABLE" => false, "LENGTH" => 150));
$ddbb->addField("Application", "url", null, "STRING", array("NULLABLE" => true, "LENGTH" => 150, "DEFAULT" => null));
$ddbb->addField("Application", "list_groups", null, "STRING", array("NULLABLE" => true, "LENGTH" => 100, "DEFAULT" => null));
$ddbb->addField("Application", "list_rols", null, "STRING", array("NULLABLE" => true, "LENGTH" => 100, "DEFAULT" => null));
$ddbb->addField("Application", "list_panels", null, "STRING", array("NULLABLE" => true, "LENGTH" => 100, "DEFAULT" => null));
$ddbb->addField("Application", "list_menus", null, "STRING", array("NULLABLE" => true, "LENGTH" => 100, "DEFAULT" => null));

class Application {
   public function __construct($data = null) {     
      global $ddbb;
      $ddbb->loadData($this, $data);
   }
   
   public function store() {
      global $ddbb;
      $ddbb->insertObject($this);
      return true;
   }

   public function register($data) {
      global $ddbb;
      $this->list_groups = $data['groups'];
      $this->list_rols = $data['rols'];
      $this->list_panels = $data['panels'];
      $this->list_menus = $data['menus'];
      $this->store();
   }


   public function delete() {
      global $ddbb;

      $sql_1 = "DELETE FROM ".$ddbb->getTable('Application')." WHERE ".$ddbb->getMapping('Application','appId')." = ".NP_DDBB::encodeSQLValue($this->appId, $ddbb->getType('Application','appId'));
      return ($ddbb->executeDeleteQuery($sql_1) > 0);
   }

}
?>
