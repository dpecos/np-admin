<? 
/** 
 * @package np-admin
 * @version 20090624
 * 
 * @author Daniel Pecos Martínez
 * @copyright Copyright (c) Daniel Pecos Martínez 
 * @license http://www.gnu.org/licenses/lgpl.html  LGPL License
 */
class DefaultAuthenticator {
   
   private static function doLogin($user, $password) {
      global $ddbb;
      $sql = "SELECT * FROM ".$ddbb->getTable('User')." WHERE ".$ddbb->getMapping('User','user')." = ".NP_DDBB::encodeSQLValue($user, $ddbb->getType('User','user'))." AND ".$ddbb->getMapping('User','password')." = ".NP_DDBB::encodeSQLValue($password, $ddbb->getType('User','password'));
      return $ddbb->executePKSelectQuery($sql);
   }
   
   public static function login($user, $password) {
      global $ddbb;
      
      $password = NP_hash("SHA1", $password);
      
      if (($data = DefaultAuthenticator::doLogin($user, $password)) != null) {
         $groups = DefaultAuthenticator::listAssignedGroupsToUser($data['user_id']);
         $rols = DefaultAuthenticator::listAssignedRolsToUser($data['user_id']);
         $user = new User($data);
         return array($user, $rols, $groups);
      } else {
         return null;
      }
   }
   
   public static function canLogout() {
      return true;
   }
   
   public static function isLoginFormRequired() {
      return true;
   }


   private function createUserList($sql) {
	   global $ddbb;
	   $users = array();

	   $queryData = $ddbb->executeSelectQuery($sql);
	   if ($queryData != null) {
		   foreach ($queryData as $idx=>$data) {
			   $user = new User($data);
			   $users[] = $user;
		   }
	   }
	   return $users;

   }

   private static function createGroupList($sql) {
	   global $ddbb;
	   $groups = array();

	   $queryData = $ddbb->executeSelectQuery($sql);
	   if ($queryData != null) {
		   foreach ($queryData as $idx=>$data) {
			   $group = new Group($data);
			   $groups[] = $group;
		   }
	   }
	   return $groups;

   }

   private static function createRolList($sql) {
	   global $ddbb;
	   $rols = array();

	   $queryData = $ddbb->executeSelectQuery($sql);
	   if ($queryData != null) {
		   foreach ($queryData as $idx=>$data) {
			   $rol = new Rol($data);
			   $rols[] = $rol;
		   }
	   }
	   return $rols;

   }

   public static function listGroups() {
	   global $ddbb;

	   $sql = "SELECT * FROM ".$ddbb->getTable("Group")." ORDER BY ".$ddbb->getMapping('Group','groupName');

	   return DefaultAuthenticator::createGroupList($sql);
   }

   public static function listRols() {
	   global $ddbb;

	   $sql = "SELECT * FROM ".$ddbb->getTable("Rol")." ORDER BY ".$ddbb->getMapping('Rol','rolName');

	   return DefaultAuthenticator::createRolList($sql);
   }

   public static function listAssignedRolsToUser($userId) {
	   global $ddbb;

	   $sql = "SELECT r.* FROM ".$ddbb->getTable("Rol")." r, ".$ddbb->getTable("UserRol")." ur WHERE ur.rol_id = r.rol_id AND ur.user_id = ".$userId." UNION SELECT r.* FROM ".$ddbb->getTable("Rol")." r, ".$ddbb->getTable("GroupRol")." gr, ".$ddbb->getTable("UserGroup")." ug WHERE r.rol_id = gr.rol_id AND gr.group_id = ug.group_id AND ug.user_id = ".$userId;

	   return DefaultAuthenticator::createRolList($sql);
   }

   public static function listAssignedGroupsToUser($userId) {
	   global $ddbb;

	   $sql = "SELECT g.* FROM ".$ddbb->getTable("Group")." g, ".$ddbb->getTable("UserGroup")." ug WHERE ug.group_id = g.group_id AND ug.user_id = ".$userId." ORDER BY 1";

	   return DefaultAuthenticator::createGroupList($sql);
   }

   public static function listAssignedUsersToGroup($groupId) {
	   global $ddbb;

	   $sql = "SELECT u.* FROM ".$ddbb->getTable("User")." u, ".$ddbb->getTable("UserGroup")." ug WHERE u.user_id >= 0 AND u.user_id=ug.user_id AND ug.group_id = ".$groupId." ORDER BY 1";

	   return DefaultAuthenticator::createUserList($sql);;
   }

   public static function listUnassignedUsersToGroup($groupId) {
	   global $ddbb;

	   $sql = "SELECT * FROM ".$ddbb->getTable("User")." WHERE user_id >= 0 AND user_id NOT IN (SELECT user_id FROM ".$ddbb->getTable("UserGroup")." WHERE group_id = ".$groupId.") ORDER BY 1";

	   return DefaultAuthenticator::createUserList($sql);;
   }

   public static function listAssignedUsersToRol($rolId) {
	   global $ddbb;

	   $sql = "SELECT u.* FROM ".$ddbb->getTable("User")." u, ".$ddbb->getTable("UserRol")." ug WHERE u.user_id >= 0 AND u.user_id=ug.user_id AND ug.rol_id = ".$rolId." ORDER BY 1";

	   return DefaultAuthenticator::createUserList($sql);;
   }

   public static function listUnassignedUsersToRol($rolId) {
	   global $ddbb;

	   $sql = "SELECT * FROM ".$ddbb->getTable("User")." WHERE user_id >= 0 AND user_id NOT IN (SELECT user_id FROM ".$ddbb->getTable("UserRol")." WHERE rol_id = ".$rolId.") ORDER BY 1";

	   return DefaultAuthenticator::createUserList($sql);;
   }

   public static function listAssignedGroupsToRol($rolId) {
	   global $ddbb;

	   $sql = "SELECT g.* FROM ".$ddbb->getTable("Group")." g, ".$ddbb->getTable("GroupRol")." gr WHERE g.group_id=gr.group_id AND gr.rol_id = ".$rolId." ORDER BY 1";

	   return DefaultAuthenticator::createGroupList($sql);;
   }

   public static function listUnassignedGroupsToRol($rolId) {
	   global $ddbb;

	   $sql = "SELECT * FROM ".$ddbb->getTable("Group")." WHERE group_id NOT IN (SELECT group_id FROM ".$ddbb->getTable("GroupRol")." WHERE rol_id = ".$rolId.") ORDER BY 1";

	   return DefaultAuthenticator::createGroupList($sql);;
   }
}
?>