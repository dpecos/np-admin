<?
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
         $data = $ddbb->executeSelectQuery("SELECT * FROM ".$ddbb->getTable('PanelGroup')." WHERE ".$ddbb->getMapping('PanelGroup','panelId')."=".NP_DDBB::encodeSQLValue($this->id, $ddbb->getType('PanelGroup','panelId')));
         
         $this->groups = array();
         if ($data != null) {
            foreach ($data as $group) {
               $this->groups[] = NP_DDBB::decodeSQLValue($group['group_name'], $ddbb->getType('PanelGroup','groupName'));
            }
         }
      }
      
   }
   
   public function getGroups() {
      return $this->groups;
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
      $sql_2 = "DELETE FROM ".$ddbb->getTable('PanelGroup')." WHERE ".$ddbb->getMapping('PanelGroup','panelId')." = ".NP_DDBB::encodeSQLValue($this->id, $ddbb->getType('PanelGroup','panelId'));            
     
      $ddbb->executeDeleteQuery($sql_2);
      return ($ddbb->executeDeleteQuery($sql_1) > 0);
   }
}
?>