<?
$PWD = "../";
require_once($PWD."include/common.php");

header("Cache-Control: no-cache");
header("Pragma: no-cache");
header("Expires: Mon, 01 Jan 2000 01:00:00 GMT");

npadmin_security(array("Administrators"), false);

$returnList = false;

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
      $setting = new Setting($id);
      if (!$setting->delete()) {
         echo "ERROR: Unable to delete setting '".$id."'";
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

   NP_executeSelect("SELECT * FROM npadmin_settings ORDER BY 1", "createSettingsList");

   echo json_encode($settings); 
} 
?>
