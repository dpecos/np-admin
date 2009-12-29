<?
$NPADMIN_PATH = "../../";
require_once($NPADMIN_PATH."private/include/common.php");

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
      
   } else if ($_POST['op'] == "listAssignedRols") {
      $rols = array();

      $rolsT = $ddbb->executeSelectQuery("SELECT * FROM ".$ddbb->getTable("Panel")." p, ".$ddbb->getTable("Rol")." r, ".$ddbb->getTable("PanelRol")." pr WHERE p.id = pr.panel_id AND pr.rol_id = r.rol_id AND p.id = '".$_POST['panel']."' ORDER BY 1");

      if ($rolsT != null) {
	      foreach ($rolsT as $data) {
		      $rols[] = new Rol($data);
	      }
      }

      echo NP_json_encode($rols);

   } else if ($_POST['op'] == "listUnassignedRols") {
      $rols = array();

      $rolsT = $ddbb->executeSelectQuery("SELECT * FROM ".$ddbb->getTable("Rol")." WHERE rol_name NOT IN (SELECT r.rol_name AS rol_name FROM ".$ddbb->getTable("Panel")." p, ".$ddbb->getTable("Rol")." r, ".$ddbb->getTable("PanelRol")." pr WHERE p.id = pr.panel_id AND pr.rol_id = r.rol_id AND p.id = '".$_POST['panel']."') ORDER BY 1");

      if ($rolsT != null) {
	      foreach ($rolsT as $data) {
		      $rols[] = new Rol($data);
	      }
      }

      echo NP_json_encode($rols);
      
   } else if ($_POST['op'] == "assignRols") {
      $panel = $_POST['panel'];   
      $rols = split(",", $_POST['list']);
      
      $sql = "DELETE FROM ".$ddbb->getTable('PanelRol')." WHERE ".$ddbb->getMapping('PanelRol','panelId')." = ".NP_DDBB::encodeSQLValue($panel, $ddbb->getType('PanelRol','panelId')); 
      $ddbb->executeDeleteQuery($sql);
      echo $sql;
      
      foreach ($rols as $rol_id) {
         if ($rol_id != "") {
            $pr = new PanelRol(array("rol_id" => $rol_id, "panel_id" => $panel));
            $pr->store();
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
         $p = new Panel($panel);
         //$panel['rols'] = implode(", ", $p->getRols());
         $panels[] = $panel;
      }
   
      $ddbb->executeSelectQuery("SELECT * FROM ".$ddbb->getTable("Panel")." ORDER BY 1", "createPanelList");
   
      echo NP_json_encode(array("Results" => $panels)); 
   } 
}
?>
