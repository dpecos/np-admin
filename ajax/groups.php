<?
$NPADMIN_PATH = "../";
require_once($NPADMIN_PATH."include/common.php");

header("Cache-Control: no-cache");
header("Pragma: no-cache");
header("Expires: Mon, 01 Jan 2000 01:00:00 GMT");

npadmin_security(array("Administrators"), false);

$returnList = false;

$_POST = NP_UTF8_decode($_POST);

$authClass = npadmin_setting("NP-ADMIN", "AUTH");
$authenticator = null;
if ($authClass != null) {
	$authenticator = new $authClass;
}

$defaultAuthenticator = new DefaultAuthenticator();

foreach ($_POST as $k => $v) {
   if ($v === "null")
      $_POST[$k] = null;
}

if (array_key_exists("op", $_POST)) {
   if ($_POST['op'] == "add") {
      $group = new Group($_POST);
      if ($group->store())
         echo "OK";
      else 
         echo "ERROR";
   
   } else if ($_POST['op'] == "delete") {
      $list = split(",", $_POST['list']);
      foreach ($list as $id) {
         if ($id != "") {
            $group = new Group();
            $group->groupId = $id;
            if (!$group->delete()) {
               echo "ERROR: Unable to delete group '".$id."'";
               return;
            }
         }
      }
      echo "OK";
      
   } else if ($_POST['op'] == "listAssignedUsers") {

      $users = $defaultAuthenticator->listAssignedUsersToGroup($_POST['group_id']);
      if ($authenticator != null) {
	      if (npadmin_setting("AUTH","DEFAULT_GROUP") != null && npadmin_setting("AUTH","DEFAULT_GROUP") == $_POST['group_id']) {
		      $users = array_merge($users, $authenticator->listUsers());
	      } else {
		      $users = array_merge($users, $authenticator->listAssignedUsersToGroup($_POST['group_id']));
	      }
      }

      echo NP_json_encode($users);

   } else if ($_POST['op'] == "listUnassignedUsers") {

      $users = $defaultAuthenticator->listUnassignedUsersToGroup($_POST['group_id']);
      if ($authenticator != null) {
	      if (npadmin_setting("AUTH","DEFAULT_GROUP") == null || (npadmin_setting("AUTH","DEFAULT_GROUP") != null && npadmin_setting("AUTH","DEFAULT_GROUP") != $_POST['group_id'])) {
		      $users = array_merge($users, $authenticator->listUnassignedUsersToGroup($_POST['group_id']));
	      }
      }

      echo NP_json_encode($users);    
      
   } else if ($_POST['op'] == "assignUsers") {
      $group = $_POST['group_id'];   
      $users = split(",", $_POST['list']);
      
      $sql = "DELETE FROM ".$ddbb->getTable('UserGroup')." WHERE ".$ddbb->getMapping('UserGroup','groupId')." = ".NP_DDBB::encodeSQLValue($group, $ddbb->getType('UserGroup','groupId')); 
      $ddbb->executeDeleteQuery($sql);

      foreach (array_values($users) as $user) {
	      if ($user != "") {
		      if (npadmin_setting("AUTH","DEFAULT_GROUP") == null || (npadmin_setting("AUTH","DEFAULT_GROUP") != null && npadmin_setting("AUTH","DEFAULT_GROUP") != $_POST['group_id'])) {
			      $ug = new UserGroup(array("group_id" => $group, "user_id" => $user));
			      $ug->store();
		      }
	      }
      }
      
      echo "OK";

        
   } else if ($_POST['op'] == "list" || $_GET['op'] == "list") {
      $returnList = true;
   }
   
   if ($returnList) {
	   $groups = $defaultAuthenticator->listGroups();
	   echo NP_json_encode($groups); 
   } 
}
?>
