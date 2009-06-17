<?
$NPADMIN_PATH = "../";
require_once($NPADMIN_PATH."include/common.php");

header("Cache-Control: no-cache");
header("Pragma: no-cache");
header("Expires: Mon, 01 Jan 2000 01:00:00 GMT");

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

if (array_key_exists("op", $_POST) && ($_POST['op'] == "login" || $_POST['op'] == "logout") || isset($_GET['op']) && $_GET['op'] == "logout") {

	if (array_key_exists("op", $_POST) && $_POST['op'] == "login") {
		$password = AESDecryptCtr($_POST['password'], $_SESSION['npadmin_login_seed'], 256);
		echo npadmin_login($_POST['user'], $password) ? "OK" : "ERROR";

	} else if (array_key_exists("op", $_POST) && $_POST['op'] == "logout" || array_key_exists("op", $_GET) && $_GET['op'] == "logout") {
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
			if ($id != null) {
				$user = new User();
				$user->userId = $id;
				if (!$user->delete()) {
					echo "ERROR: Unable to delete user '".$id."'";
					return;
				}
			}
		}
		echo "OK";

	} else if ($_POST['op'] == "listAssignedGroups") {

		$groups = array();
		$groupsT = $ddbb->executeSelectQuery("SELECT g.* AS group_name FROM  ".$ddbb->getTable("UserGroup")." ug, ".$ddbb->getTable("Group")." g WHERE ug.group_id = g.group_id AND ug.user_id = '".$_POST['user']."' ORDER BY 1");

		if ($groupsT != null) {
			foreach ($groupsT as $data) {
				$groups[] = new Group($data);
			}
		}

		if ($authenticator != null) {
			if ($_POST['user'] < 0 && npadmin_setting("AUTH","DEFAULT_GROUP") != null) {
				$data = $ddbb->executePKSelectQuery("SELECT * FROM ".$ddbb->getTable("Group")." WHERE ".$ddbb->getMapping("Group", "groupId")."=".NP_DDBB::encodeSQLValue(npadmin_setting("AUTH","DEFAULT_GROUP"), $ddbb->getType('Group','groupId')));
				$groups[] = new Group($data);
			}
			//$groups = array_merge($groups, $authenticator->listAssignedGroups($_POST['user']));
		}

		echo NP_json_encode($groups);

	} else if ($_POST['op'] == "listUnassignedGroups") {

		$groups = array();
		$groupsT = $ddbb->executeSelectQuery("SELECT * FROM ".$ddbb->getTable("Group")." WHERE group_id NOT IN (SELECT group_id FROM ".$ddbb->getTable("UserGroup")." WHERE user_id = '".$_POST['user']."') ORDER BY 1");

		if ($groupsT != null) {
			foreach ($groupsT as $data) {
				if ($authenticator == null || ($authenticator != null && npadmin_setting("AUTH","DEFAULT_GROUP") != $data["group_id"]))
					$groups[] = new Group($data);
			}
		}

		/*if ($authenticator != null) {
		 $groups = array_merge($groups, $authenticator->listUnasssignedGroups($_POST['user']));
		 }*/

		echo NP_json_encode($groups);

	} else if ($_POST['op'] == "assignGroups") {
		$user = $_POST['user'];
		$groups = split(",", $_POST['list']);

		$sql = "DELETE FROM ".$ddbb->getTable('UserGroup')." WHERE ".$ddbb->getMapping('UserGroup','userId')." = ".NP_DDBB::encodeSQLValue($user, $ddbb->getType('UserGroup','userId'));
		$ddbb->executeDeleteQuery($sql);

		foreach ($groups as $group) {
			if ($group != "") {
				if (npadmin_setting("AUTH","DEFAULT_GROUP") == null || (npadmin_setting("AUTH","DEFAULT_GROUP") != null && npadmin_setting("AUTH","DEFAULT_GROUP") != $group)) {
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
		$users = array();
		$usersT = $ddbb->executeSelectQuery("SELECT * FROM ".$ddbb->getTable("User")." ORDER BY 1");

		if ($usersT != null) {
			foreach ($usersT as $data) {
				$user = new User($data);
				//$user->creationDate = date("Y-m-d", $user->creationDate);
				$users[] = $user;
			}
		}

		if ($authenticator != null) {
			$users = array_merge($users, $authenticator->listUsers());
		}

		echo NP_json_encode(array("Results" => $users));
	}
}
?>
