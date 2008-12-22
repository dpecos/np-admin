<?
$NPADMIN_PATH = "../";
require_once($NPADMIN_PATH."include/common.php");

header("Cache-Control: no-cache");
header("Pragma: no-cache");
header("Expires: Mon, 01 Jan 2000 01:00:00 GMT");

npadmin_security(array("Administrators"), false);

$returnList = false;

$_POST = NP_UTF8_decode($_POST);

foreach ($_POST as $k => $v) {
   if ($v === "null")
      $_POST[$k] = null;
}

if (array_key_exists("op", $_POST)) {
   if ($_POST['op'] == "add") {
      $panel = new Panel($_POST);
      if ($panel->store())
         echo "OK";
      else 
         echo "ERROR";
   
   } else if ($_POST['op'] == "delete") {
      $list = split(",", $_POST['list']);
      foreach ($list as $id) {
         $panel = new Panel();
         $panel->id = $id;
         if (!$panel->delete()) {
            echo "ERROR: Unable to delete panel '".$id."'";
            return;
         }
      }
      echo "OK";
      
   } else if ($_POST['op'] == "listAssignedGroups") {
      $groups = array();

      $groups = $ddbb->executeSelectQuery("SELECT g.group_name AS group_name FROM ".$ddbb->getTable("Panel")." p, ".$ddbb->getTable("Group")." g, ".$ddbb->getTable("PanelGroup")." pg WHERE p.id = pg.panel_id AND pg.group_name = g.group_name AND p.id = '".$_POST['panel']."' ORDER BY 1");

      echo NP_json_encode($groups);

   } else if ($_POST['op'] == "listUnassignedGroups") {
      $groups = array();

      $groups = $ddbb->executeSelectQuery("SELECT group_name FROM ".$ddbb->getTable("Group")." WHERE group_name NOT IN (SELECT g.group_name AS group_name FROM ".$ddbb->getTable("Panel")." p, ".$ddbb->getTable("Group")." g, ".$ddbb->getTable("PanelGroup")." pg WHERE p.id = pg.panel_id AND pg.group_name = g.group_name AND p.id = '".$_POST['panel']."') ORDER BY 1");

      echo NP_json_encode($groups);
      
   } else if ($_POST['op'] == "assignGroups") {
      $panel = $_POST['panel'];   
      $groups = split(",", $_POST['list']);
      
      $sql = "DELETE FROM ".$ddbb->getTable('PanelGroup')." WHERE ".$ddbb->getMapping('PanelGroup','panelId')." = ".NP_DDBB::encodeSQLValue($panel, $ddbb->getType('PanelGroup','panelId')); 
      $ddbb->executeDeleteQuery($sql);
      
      foreach ($groups as $group_name) {
         if ($group_name != "") {
            $pg = new PanelGroup(array("group_name" => $group_name, "panel_id" => $panel));
            $pg->store();
         }
      }
      
      echo "OK";

   } else if ($_POST['op'] == "list" || $_GET['op'] == "list") {
      $returnList = true;
   }
   
   if ($returnList) {
      $panels = array();
   
      function createPanelList($panel) {
         global $panels;
         $p = new Panel($panel['id']);
         $panel['groups'] = implode(", ", $p->getGroups());
         $panels[] = $panel;
      }
   
      $ddbb->executeSelectQuery("SELECT * FROM ".$ddbb->getTable("Panel")." ORDER BY 1", "createPanelList");
   
      echo NP_json_encode($panels); 
   } 
}
?>
