<?
$PWD = "../";
require_once($PWD."include/common.php");

$returnList = false;

if ($_POST['op'] == "add") {
   $user = new User($_POST);
   if ($user->store())
      echo "OK";
   else 
      echo "ERROR";

} else if ($_POST['op'] == "delete") {
   $list = split(",", $_POST['list']);
   foreach ($list as $id) {
      $user = new User();
      $user->user = $id;
      if (!$user->delete()) {
         echo "ERROR: Unable to delete user '".$id."'";
         return;
      }
   }
   echo "OK";
   
} else if ($_POST['op'] == "listAssignedGroups") {
   $groups = array();

   function createGroupList($group) {
      global $groups;
      $groups[] = $group;
   }

   NP_executeSelect("SELECT g.group_name AS group_name FROM npadmin_users u, npadmin_groups g, npadmin_users_groups ug WHERE u.user = ug.user AND ug.group_name = g.group_name AND u.user = '".$_POST['user']."' ORDER BY 1" , createGroupList);

   echo json_encode($groups);

} else if ($_POST['op'] == "listUnassignedGroups") {
   $groups = array();

   function createGroupList($group) {
      global $groups;
      $groups[] = $group;
   }

   NP_executeSelect("SELECT group_name FROM npadmin_groups WHERE group_name NOT IN (SELECT g.group_name AS group_name FROM npadmin_users u, npadmin_groups g, npadmin_users_groups ug WHERE u.user = ug.user AND ug.group_name = g.group_name AND u.user = '".$_POST['user']."') ORDER BY 1" , createGroupList);

   echo json_encode($groups);
   
} else if ($_POST['op'] == "assignGroups") {
   $user = $_POST['user'];   
   $groups = split(",", $_POST['list']);
   
   $sql = "DELETE FROM ".$ddbb_table['UserGroup']." WHERE ".$ddbb_mapping['UserGroup']['user']." = ".encodeSQLValue($user, $ddbb_types['UserGroup']['user']); 
   NP_executeDelete($sql);
   
   foreach ($groups as $group_name) {
      if ($group_name != "") {
         $ug = new UserGroup(array("group_name" => $group_name, "user" => $user));
         $ug->store();
      }
   }
   
   echo "OK";

} else if ($_POST['op'] == "list" || $_GET['op'] == "list") {
   $returnList = true;
      
} else if ($_POST['op'] == "login") {
   echo npadmin_login($_POST['user'], $_POST['password']) ? "OK" : "ERROR";

} else if ($_POST['op'] == "logout" || $_GET['op'] == "logout") {
   npadmin_logout();
   echo "OK";
}

if ($returnList) {
   $users = array();

   function createUserList($user) {
      global $users;
      $users[] = $user;
   }

   NP_executeSelect("SELECT * FROM npadmin_users ORDER BY 1", createUserList);

   echo json_encode($users); 
} 
?>
