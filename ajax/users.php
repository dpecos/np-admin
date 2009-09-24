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
		$password = NP_decrypt("AES", $_POST['password'], $_SESSION['npadmin_login_seed']);
		echo npadmin_login($_POST['user'], $password) ? "OK" : "ERROR";

	} else if (array_key_exists("op", $_POST) && $_POST['op'] == "logout" || array_key_exists("op", $_GET) && $_GET['op'] == "logout") {
		npadmin_logout();
		echo "OK";
	}
	
} else if (array_key_exists("op", $_GET) && ($_GET['op'] == "generateSeed")) {
   if (npadmin_loginData() != null) {
      $_SESSION["npadmin_login_seed"] = NP_random_string(10);
      echo $_SESSION["npadmin_login_seed"];
   }
} else if (array_key_exists("op", $_POST) && ($_POST['op'] == "changePassword")) {
   $loginData = npadmin_loginData();
   if ($loginData != null) {
      $userId = $loginData->getUser()->userId;
      $old_password = NP_decrypt("AES", $_POST['old_password'], $_SESSION['npadmin_login_seed']);
      $new_password = NP_decrypt("AES", $_POST['new_password'], $_SESSION['npadmin_login_seed']);
      
      if (DefaultAuthenticator::login($loginData->getUser()->user, $old_password)) {
         $user = new User();
         $user->userId = $userId;
         $user->password = NP_hash("SHA1", $new_password);
        
         if ($ddbb->updateObject($user) !== null)
            echo "OK";
         else
            echo "User not found";
      } else {
         echo "NOK";
      }
   }
} else if (array_key_exists("op", $_POST) && ($_POST['op'] == "resetPassword")) {
   $email = $_POST['email'];
   if ($email != null && strlen($email) > 0) {
   		$passwd = DefaultAuthenticator::resetPassword($email);
		
   		if ($passwd != null) {
	   		// send email
	   		$body = "Link para cambio de contraseña: ".npadmin_setting('NP-ADMIN', 'BASE_URL')."/panels/resetPasswordPanel.php?x=".$passwd;
	   		//echo $body;
	   		NP_sendMail("noreply@soporte-caixa.iberia.grupotecnocom", $email, "Cambio de contraseña", $body);
	   		echo "OK";
   		}
   		
   }   
} else if (array_key_exists("op", $_POST) && ($_POST['op'] == "confirmResetPassword")) {
	$email = $_POST['email'];
	$passwd1 = $_POST['password1'];
	$passwd2 = $_POST['password2'];
	$x = $_POST['x'];
	
	if ($passwd1 === $passwd2) {
		$sql = "SELECT * FROM ".$ddbb->getTable("UserTmpPassword")." WHERE ".$ddbb->getMapping("UserTmpPassword", "tmpPassword")."=".NP_DDBB::encodeSQLValue($x, "STRING");
		$data = $ddbb->executePKSelectQuery($sql);
		if ($data != null) {
			$userTmpPassword = new UserTmpPassword($data);
			if ($userTmpPassword->email === $email) {
				 $auth = new SoporteAuthenticator();
				 $auth->changePassword($userTmpPassword->userId, $passwd1);
				 $userTmpPassword->delete();
			}
		}
	}
	NP_redirect("../index.php");
} else {

	npadmin_security(array("Administrators"), false);

	$returnList = false;

	if (!array_key_exists("op", $_POST))
	exit;

	if ($_POST['op'] == "add") {
		$_POST['password'] = NP_hash("SHA1", $_POST['password']);
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