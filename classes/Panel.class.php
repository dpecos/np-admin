<?
global $ddbb;

$ddbb->addTable("Panel", "panels");
$ddbb->addField("Panel", "id", null, "STRING", array("PK" => true, "NULLABLE" => false, "LENGTH" => 40));
$ddbb->addField("Panel", "title", null, "STRING", array("NULLABLE" => false, "LENGTH" => 60));

class Panel {
   
   public function __construct($panel = null) {
      global $ddbb;
      
      if (gettype($panel) == "array") {
         $ddbb->loadData($this, $panel);
      } else if ($panel != null) {
         $data = $ddbb->executePKSelectQuery("SELECT * FROM ".$ddbb->getTable('Panel')." WHERE ".$ddbb->getMapping('Panel','id')."=".NP_DDBB::encodeSQLValue($panel, $ddbb->getType('Panel','id')));
         $ddbb->loadData($this, $data);
      }
      
      if (isset($this->id) && $this->id != null) {   
         $data = $ddbb->executeSelectQuery("SELECT * FROM ".$ddbb->getTable('PanelRol')." WHERE ".$ddbb->getMapping('PanelRol','panelId')."=".NP_DDBB::encodeSQLValue($this->id, $ddbb->getType('PanelRol','panelId')));
         
         $this->rols = array();
         if ($data != null) {
            foreach ($data as $rol) {
               $this->rols[] = NP_DDBB::decodeSQLValue($rol['rol_id'], $ddbb->getType('PanelRol','rolId'));
            }
         }
      }
      
   }
   
   public function getRols() {
      return $this->rols;
   }
   
   public function getTitle() {
      return $this->title;
   }
   
   public function getURL() {
      return "work/".$this->id.".php";
   }
   
   public function store() {
      global $ddbb;
      $ddbb->insertObject($this);
      return true;
   }
   
   public function delete() {
      global $ddbb;
     
      $sql_1 = "DELETE FROM ".$ddbb->getTable('Panel')." WHERE ".$ddbb->getMapping('Panel','id')." = ".NP_DDBB::encodeSQLValue($this->id, $ddbb->getType('Panel','id'));
      $sql_2 = "DELETE FROM ".$ddbb->getTable('PanelRol')." WHERE ".$ddbb->getMapping('PanelRol','panelId')." = ".NP_DDBB::encodeSQLValue($this->id, $ddbb->getType('PanelRol','panelId'));            
     
      $ddbb->executeDeleteQuery($sql_2);
      return ($ddbb->executeDeleteQuery($sql_1) > 0);
   }
}
?>
