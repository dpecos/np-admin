<?
$PWD = "../";
require_once($PWD."include/common.php");

header("Cache-Control: no-cache");
header("Pragma: no-cache");
header("Expires: Mon, 01 Jan 2000 01:00:00 GMT");

$_POST = NP_UTF8_decode($_POST);

foreach ($_POST as $k => $v) {
   if ($v === "null")
      $_POST[$k] = null;
}

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
      
      $groups = $ddbb->executeSelectQuery("SELECT g.".$ddbb->getMapping('Group','groupName')." AS group_name FROM ".$ddbb->getTable("User")." u, ".$ddbb->getTable("Group")." g, ".$ddbb->getTable("UserGroup")." ug WHERE u.".$ddbb->getMapping('User','user')." = ug.".$ddbb->getMapping('UserGroup','user')." AND ug.".$ddbb->getMapping('UserGroup','groupName')." = g.".$ddbb->getMapping('Group','groupName')." AND u.".$ddbb->getMapping('User','user')." = '".$_POST['user']."' ORDER BY 1");

      echo NP_json_encode($groups);

   } else if ($_POST['op'] == "listUnassignedGroups") {
      $groups = array();
      
      $groups = $ddbb->executeSelectQuery("SELECT ".$ddbb->getMapping('Group','groupName')." FROM ".$ddbb->getTable("Group")." WHERE ".$ddbb->getMapping('Group','groupName')." NOT IN (SELECT g.".$ddbb->getMapping('Group','groupName')." AS group_name FROM ".$ddbb->getTable("User")." u, ".$ddbb->getTable("Group")." g, ".$ddbb->getTable("UserGroup")." ug WHERE u.".$ddbb->getMapping('User','user')." = ug.".$ddbb->getMapping('UserGroup','user')." AND ug.".$ddbb->getMapping('UserGroup','groupName')." = g.".$ddbb->getMapping('Group','groupName')." AND u.".$ddbb->getMapping('User','user')." = '".$_POST['user']."') ORDER BY 1");

      echo NP_json_encode($groups);
      
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

      echo NP_json_encode($users); 
   } 
}
?>
