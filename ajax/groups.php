<?
$PWD = "../";
require_once($PWD."include/common.php");

header("Cache-Control: no-cache");
header("Pragma: no-cache");
header("Expires: Mon, 01 Jan 2000 01:00:00 GMT");

npadmin_security(array("Administrators"), false);

$returnList = false;

if ($_POST['op'] == "add") {
   $group = new Group($_POST);
   if ($group->store())
      echo "OK";
   else 
      echo "ERROR";

} else if ($_POST['op'] == "delete") {
   $list = split(",", $_POST['list']);
   foreach ($list as $id) {
      $group = new Group();
      $group->group_name = $id;
      if (!$group->delete()) {
         echo "ERROR: Unable to delete group '".$id."'";
         return;
      }
   }
   echo "OK";
   
} else if ($_POST['op'] == "list" || $_GET['op'] == "list") {
   $returnList = true;
}

if ($returnList) {
   $groups = array();

   function createGroupList($group) {
      global $groups;
      $groups[] = $group;
   }

   $ddbb->executeSelectQuery("SELECT * FROM ".$ddbb->getTable("Group")." ORDER BY 1", "createGroupList");

   echo json_encode($groups); 
} 
?>
