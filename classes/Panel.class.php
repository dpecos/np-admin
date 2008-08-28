<?
class Panel {
   
   public function __construct($panelId = null) {
      global $ddbb;
      if ($panelId != null) {
         $data = $ddbb->executePKSelectQuery("SELECT * FROM ".$ddbb->getTable('Panel')." WHERE ".$ddbb->getMapping('Panel','id')."=".NP_DDBB::encodeSQLValue($panelId, $ddbb->getType('Panel','id')));
         $ddbb->loadData($this, $data);
         
         $data = $ddbb->executeSelectQuery("SELECT * FROM ".$ddbb->getTable('PanelGroup')." WHERE ".$ddbb->getMapping('PanelGroup','panelId')."=".NP_DDBB::encodeSQLValue($panelId, $ddbb->getType('PanelGroup','panelId')));
         
         $this->groups = array();
         foreach ($data as $group) {
            $this->groups[] = NP_DDBB::decodeSQLValue($group['group_name'], $ddbb->getType('PanelGroup','groupName'));
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
}
?>