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
      $rol = new Rol($_POST);
      if ($rol->store())
         echo "OK";
      else
         echo "ERROR";

   } else if ($_POST['op'] == "delete") {
      $list = split(",", $_POST['list']);
      foreach ($list as $id) {
         if ($id != "") {
            $rol = new Rol();
            $rol->rolId = $id;
            if (!$rol->delete()) {
               echo "ERROR: Unable to delete rol '".$id."'";
               return;
            }
         }
      }
      echo "OK";

   } else if ($_POST['op'] == "listAssignedUsers") {

      $users = $defaultAuthenticator->listAssignedUsersToRol($_POST['rol_id']);
      if ($authenticator != null) {
	      $users = array_merge($users, $authenticator->listAssignedUsersToRol($_POST['rol_id']));
      }

      echo NP_json_encode($users);

   } else if ($_POST['op'] == "listUnassignedUsers") {

      $users = $defaultAuthenticator->listUnassignedUsersToRol($_POST['rol_id']);
      if ($authenticator != null) {
	      $users = array_merge($users, $authenticator->listUnassignedUsersToRol($_POST['rol_id']));
      }

      echo NP_json_encode($users);

    } else if ($_POST['op'] == "listAssignedGroups") {

      $groups = $defaultAuthenticator->listAssignedGroupsToRol($_POST['rol_id']);
      if ($authenticator != null) {
	      $groups = array_merge($groups, $authenticator->listAssignedGroupsToRol($_POST['rol_id']));
      }

      echo NP_json_encode($groups);

   } else if ($_POST['op'] == "listUnassignedGroups") {

      $groups = $defaultAuthenticator->listUnassignedGroupsToRol($_POST['rol_id']);
      if ($authenticator != null) {
	      $groups = array_merge($groups, $authenticator->listUnassignedGroupsToRol($_POST['rol_id']));
      }

      echo NP_json_encode($groups);

   } else if ($_POST['op'] == "assignUsers") {
      $rol = $_POST['rol_id'];
      $users = split(",", $_POST['list']);

      $sql = "DELETE FROM ".$ddbb->getTable('UserRol')." WHERE ".$ddbb->getMapping('UserRol','rolId')." = ".NP_DDBB::encodeSQLValue($rol, $ddbb->getType('UserRol','rolId'));
      $ddbb->executeDeleteQuery($sql);

      foreach (array_values($users) as $user) {
	      if ($user != "") {
		      $ug = new UserRol(array("rol_id" => $rol, "user_id" => $user));
		      $ug->store();
	      }
      }

      echo "OK";

    } else if ($_POST['op'] == "assignGroups") {
      $rol = $_POST['rol_id'];
      $groups = split(",", $_POST['list']);

      $sql = "DELETE FROM ".$ddbb->getTable('GroupRol')." WHERE ".$ddbb->getMapping('GroupRol','rolId')." = ".NP_DDBB::encodeSQLValue($rol, $ddbb->getType('GroupRol','rolId'));
      $ddbb->executeDeleteQuery($sql);

      foreach (array_values($groups) as $group) {
	      if ($group != "") {
		      $gr = new GroupRol(array("rol_id" => $rol, "group_id" => $group));
		      $gr->store();
	      }
      }

      echo "OK";

   } else if ($_POST['op'] == "list" || $_GET['op'] == "list") {
      $returnList = true;
   }

   if ($returnList) {
	   $rols = $defaultAuthenticator->listRols();

	   echo NP_json_encode(array("Results" => $rols));
   }
}
?>
