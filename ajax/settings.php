<?
$PWD = "../";
require_once($PWD."include/common.php");

header("Cache-Control: no-cache");
header("Pragma: no-cache");
header("Expires: Mon, 01 Jan 2000 01:00:00 GMT");

npadmin_security(array("Administrators"), false);

$returnList = false;

if (array_key_exists("op", $_POST)) {
   if ($_POST['op'] == "add") {
      $setting = new Setting($_POST);
      if ($setting->store())
         echo "OK";
      else 
         echo "ERROR";
         
   } if ($_POST['op'] == "update") {
      $setting = new Setting($_POST);
      if ($setting->update())
         echo "OK";
      else 
         echo "ERROR";
         
   } else if ($_POST['op'] == "delete") {
      $list = split(",", $_POST['list']);
      foreach ($list as $id) {
         $d = split("#", $id);
         $setting = new Setting($d[1], $d[0]);
         if (!$setting->delete()) {
            echo "ERROR: Unable to delete setting '".$id[1]."'";
            return;
         }
      }
      echo "OK";
      
   } else if ($_POST['op'] == "list" || isset($_GET['op']) && $_GET['op'] == "list") {
      $returnList = true;
   }
   
   if ($returnList) {
      $settings = array();
   
      function createSettingsList($setting) {
         global $settings;
         $settings[] = $setting;
      }
   
      if (in_array('type', array_keys($_POST)) && isset($_POST['type']) && $_POST['type'] != "ALL")
         $sql = "SELECT * FROM ".$ddbb->getTable("Setting")." WHERE ".$ddbb->getMapping('Setting','type')." = ".NP_DDBB::encodeSQLValue($_POST['type'], $ddbb->getType('Setting','type'))." ORDER BY type, name";
      else 
         $sql = "SELECT * FROM ".$ddbb->getTable("Setting")." ORDER BY type, name";
         
      $ddbb->executeSelectQuery($sql, "createSettingsList");
   
      echo json_encode($settings); 
   } 
}
?>
