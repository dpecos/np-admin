<?
$PWD = "../";
require_once($PWD."include/common.php");

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
