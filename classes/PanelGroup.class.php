<?
global $ddbb;

$ddbb->addTable("PanelGroup", "panels_groups");
$ddbb->addField("PanelGroup", "panelId", "panel_id", "STRING", array("PK" => true, "NULLABLE" => false, "LENGTH" => 40));
$ddbb->addField("PanelGroup", "groupName", "group_name", "STRING", array("PK" => true, "NULLABLE" => false, "LENGTH" => 40));

class PanelGroup {
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
