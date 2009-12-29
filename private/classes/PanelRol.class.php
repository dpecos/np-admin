<?
/** 
 * @package np-admin
 * @version 20090624
 * 
 * @author Daniel Pecos Martínez
 * @copyright Copyright (c) Daniel Pecos Martínez 
 * @license http://www.gnu.org/licenses/lgpl.html  LGPL License
 */
global $ddbb;

$ddbb->addTable("PanelRol", "panels_rols");
$ddbb->addField("PanelRol", "panelId", "panel_id", "STRING", array("PK" => true, "NULLABLE" => false, "LENGTH" => 40));
$ddbb->addField("PanelRol", "rolId", "rol_id", "INT", array("PK" => true, "NULLABLE" => false));

class PanelRol {
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
