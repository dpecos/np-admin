<?
$PWD = "../";
require_once($PWD."include/common.php");

header("Cache-Control: no-cache");
header("Pragma: no-cache");
header("Expires: Mon, 01 Jan 2000 01:00:00 GMT");

if (array_key_exists("op", $_POST) && ($_POST['op'] == "login" || $_POST['op'] == "logout") || isset($_GET['op']) && $_GET['op'] == "logout") {
      
   if ($_POST['op'] == "login") {
      echo npadmin_login($_POST['user'], $_POST['password']) ? "OK" : "ERROR";

   } else if ($_POST['op'] == "logout" || $_GET['op'] == "logout") {
      npadmin_logout();
      echo "OK";
   }

} else {

   npadmin_security(array("Administrators"), false);

   $returnList = false;

   if (!array_key_exists("op", $_POST))
      exit;

   if ($_POST['op'] == "add") {
      $_POST['password'] = sha1($_POST['password']);
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

      $ddbb->executeSelectQuery("SELECT g.group_name AS group_name FROM ".$ddbb->getTable("User")." u, ".$ddbb->getTable("Group")." g, ".$ddbb->getTable("UserGroup")." ug WHERE u.user = ug.user AND ug.group_name = g.group_name AND u.user = '".$_POST['user']."' ORDER BY 1" , "createGroupList");

      echo json_encode($groups);

   } else if ($_POST['op'] == "listUnassignedGroups") {
      $groups = array();

      function createGroupList($group) {
         global $groups;
         $groups[] = $group;
      }

      $ddbb->executeSelectQuery("SELECT group_name FROM ".$ddbb->getTable("Group")." WHERE group_name NOT IN (SELECT g.group_name AS group_name FROM ".$ddbb->getTable("User")." u, ".$ddbb->getTable("Group")." g, ".$ddbb->getTable("UserGroup")." ug WHERE u.user = ug.user AND ug.group_name = g.group_name AND u.user = '".$_POST['user']."') ORDER BY 1" , "createGroupList");

      echo json_encode($groups);
      
   } else if ($_POST['op'] == "assignGroups") {
      $user = $_POST['user'];   
      $groups = split(",", $_POST['list']);
      
      $sql = "DELETE FROM ".$ddbb->getTable('UserGroup')." WHERE ".$ddbb->getMapping('UserGroup','user')." = ".NP_DDBB::encodeSQLValue($user, $ddbb->getType('UserGroup','user')); 
      $ddbb->executeDeleteQuery($sql);
      
      foreach ($groups as $group_name) {
         if ($group_name != "") {
            $ug = new UserGroup(array("group_name" => $group_name, "user" => $user));
            $ug->store();
         }
      }
      
      echo "OK";

   } else if ($_POST['op'] == "list" || $_GET['op'] == "list") {
      $returnList = true;
   }

   if ($returnList) {
      $users = array();

      function createUserList($user) {
         global $users;
         $users[] = $user;
      }

      $ddbb->executeSelectQuery("SELECT * FROM ".$ddbb->getTable("User")." ORDER BY 1", "createUserList");

      echo json_encode($users); 
   } 
}
?>
