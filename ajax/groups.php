<?
$PWD = "../";
require_once($PWD."include/common.php");

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

   NP_executeSelect("SELECT * FROM npadmin_groups", createGroupList);

   echo json_encode($groups); 
} 
?>
