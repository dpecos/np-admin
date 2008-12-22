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
            $group->group_name = $id;
            if (!$group->delete()) {
               echo "ERROR: Unable to delete group '".$id."'";
               return;
            }
         }
      }
      echo "OK";
      
   } else if ($_POST['op'] == "listAssignedUsers") {

      $users = $ddbb->executeSelectQuery("SELECT ug.user AS user FROM  ".$ddbb->getTable("UserGroup")." ug WHERE ug.group_name = '".$_POST['group_name']."' ORDER BY 1");
      if ($authenticator != null) {
	      if ($users == null)
		      $users = array();
	      $users = array_merge($users, $authenticator->listAssignedUsers($_POST['group_name']));
      }

      echo NP_json_encode($users);

   } else if ($_POST['op'] == "listUnassignedUsers") {

      $users = $ddbb->executeSelectQuery("SELECT user FROM ".$ddbb->getTable("User")." WHERE user NOT IN (SELECT user FROM ".$ddbb->getTable("UserGroup")." WHERE group_name = '".$_POST['group_name']."') ORDER BY 1");

      if ($authenticator != null) {
	      if ($users == null)
		      $users = array();
	      $users = array_merge($users, $authenticator->listUnasssignedUsers($_POST['group_name']));
      }

      echo NP_json_encode($users);    
      
   } else if ($_POST['op'] == "assignUsers") {
      $group = $_POST['group_name'];   
      $users = split(",", $_POST['list']);
      
      $sql = "DELETE FROM ".$ddbb->getTable('UserGroup')." WHERE ".$ddbb->getMapping('UserGroup','groupName')." = ".NP_DDBB::encodeSQLValue($group, $ddbb->getType('UserGroup','groupName')); 
      $ddbb->executeDeleteQuery($sql);

      foreach ($users as $user) {
         if ($user != "") {
      		$is_authenticator_group = false;
      		if ($authenticator != null) {
      		   if (strstr($group_name, $authenticator->prefix) !== false) {
      			   $is_authenticator_group = true;
      		   }
      		}
       		if (!$is_authenticator_group) {
      		   $ug = new UserGroup(array("group_name" => $group, "user" => $user));
      		   $ug->store();
      		}
         }
      }
      
      echo "OK";

        
   } else if ($_POST['op'] == "list" || $_GET['op'] == "list") {
      $returnList = true;
   }
   
   if ($returnList) {
	   $groups = $ddbb->executeSelectQuery("SELECT * FROM ".$ddbb->getTable("Group")." ORDER BY ".$ddbb->getMapping('Group','groupName')." DESC");

	   $authClass = npadmin_setting("NP-ADMIN", "AUTH");

	   if ($authClass != null) {
		   $authenticator = new $authClass;
		   $groups = array_merge($groups, $authenticator->listGroups());
	   }

	   echo NP_json_encode($groups); 
   } 
}
?>
