<? 
class BugzillaAuthenticator {
   public $prefix = "bugzilla_";

/*   private function getCookieUserId() {
      if (isset($_COOKIE) &&isset($_COOKIE['Bugzilla_login'])) {
         return $_COOKIE['Bugzilla_login'];
      } else {
         return null;
      }
   }
 */
   private function getCookieUserId() {
     if (isset($_COOKIE) &&isset($_COOKIE['Bugzilla_login'])) {
       $user = $_COOKIE['Bugzilla_login'];
       if ($user == 1){
         if (isset($_COOKIE['sudo'])){
           return $_COOKIE['sudo'];
         } else {
           return $_COOKIE['Bugzilla_login'];
         }
       } else {
         return $_COOKIE['Bugzilla_login'];
       }
     } else {
      return null;
     }
  }


   
   public function login($user, $password) {
	   global $ddbb;
	   if ($user == null && $password == null) {
		   $cookie = $this->getCookieUserId();
		   if ($cookie != null) {
			   $sql = "SELECT * FROM profiles WHERE userid = ".$cookie;
			   $data = $ddbb->executePKSelectQuery($sql);

			   $user = $this->createUser($data);

			   //$sql = "SELECT DISTINCT g.name FROM profiles p, user_group_map m, groups g WHERE p.userid = m.user_id AND m.group_id = g.id AND p.userid = ".$cookie;
			   //$bugzillaGroups = $ddbb->executeSelectQuery($sql);

			   //$groups = array();
			   $groupsObj = $this->listAssignedGroups($cookie);
	 /*foreach ($groupsObj as $g) {
	    $groups[] = $g->groupName;
	 }*/

			   return array($user, null, $groupsObj);
		   } else {
			   return null;
		   }
	   } else {
		   return null;
	   }
   }
   
   public function canLogout() {
      return false;
   }
   
   public function isLoginFormRequired() {
      return false;
   }

   private function createUser($data) {
	   $user = new User();
	   $user->userId = -1 * $data['userid'];
	   $user->user = $data['realname'];
	   $user->email = $data['login_name'];
	   $user->creation_date = "-";
	   //$user->external_userID = $data['userid'];
	   return $user;
   }

  private function createGroups($sql) {
	   global $ddbb;

	   $bugzillaGroups = $ddbb->executeSelectQuery($sql);

	   $groups = array();
	   $isAdmin = false;
	   if ($bugzillaGroups != null && count($bugzillaGroups)) {
		   foreach ($bugzillaGroups as $bg) {
			   /*if ($bg['name'] === npadmin_setting("AUTH_BUGZILLA", "BUGZILLA_ADMIN_GROUP")) {
				   $isAdmin = true;
				   $g = new Group();
				   $g->groupId = -1 * $bg['id'];
				   $g->groupName = npadmin_setting("AUTH_BUGZILLA", "NPADMIN_ADMIN_GROUP");
				   $g->description = "Bugzilla \"".$bg['name']."\" users group - NP-Admin administrators group";
				   //$groups = array_merge($groups, array($g));
				   $groups[] = $g;
			   } */
			   $g = new Group();
			   $g->groupId = -1 * $bg['id'];
			   $g->groupName = $this->prefix.$bg['name'];
			   $g->description = "Bugzilla \"".$bg['name']."\" users group";
			   //$groups = array_merge($groups, array($g));
			   $groups[] = $g;
		   }
	   }
         /*if (!$isAdmin) {
            $groups = array_merge($groups, array(npadmin_setting("AUTH_BUGZILLA", "NPADMIN_DEFAULT_GROUP")));
	 }*/
	   return $groups;

   }

   public function listUsers() {
      global $ddbb;
      $users = array();

      $sql = "SELECT * FROM profiles order by login_name";
      $queryData = $ddbb->executeSelectQuery($sql);
      foreach ($queryData as $idx=>$data) {
	      $user = $this->createUser($data);
	      $users[] = $user;
      }
      return $users;
   }

   public function listGroups() {
	   $sql = "SELECT DISTINCT * FROM groups";
	   return $this->createGroups($sql);
   }

   public function listAssignedGroups($userId) {
	   $sql = "SELECT DISTINCT g.* FROM profiles p, user_group_map m, groups g WHERE p.userid = m.user_id AND m.group_id = g.id AND p.userid= '".$userId."'";
	   $groups = $this->createGroups($sql);
	   return $groups;
   }

  public function listUnasssignedGroups($userId) {
	   $sql = "SELECT * from groups WHERE name not in (Select DISTINCT g.name FROM profiles p, user_group_map m, groups g WHERE p.userid = m.user_id AND m.group_id = g.id AND p.userid= '".$userId."')";
	   return $this->createGroups($sql);
   }
      
   private function createUserList($sql) {
	   global $ddbb;
	   $users = array();

	   $queryData = $ddbb->executeSelectQuery($sql);
	   if ($queryData != null) {
		   foreach ($queryData as $idx=>$data) {
			   $user = $this->createUser($data);
			   $users[] = $user;
		   }
	   }
	   return $users;

   }

   public function listAssignedUsersToGroup($groupId) {
	   global $ddbb;

	   $sql = "SELECT p.* FROM ".$ddbb->getTable("UserGroup")." ug, profiles p WHERE ug.user_id < 0 AND ug.group_id = ".$groupId." AND p.userid = ug.user_id * -1 ORDER BY p.realname";

	   return $this->createUserList($sql);;
   }

   public function listUnassignedUsersToGroup($groupId) {
	   global $ddbb;

	   $sql = "SELECT * FROM profiles WHERE userid NOT IN (SELECT p.userid FROM ".$ddbb->getTable("UserGroup")." ug, profiles p WHERE ug.user_id < 0 AND ug.group_id = ".$groupId." AND p.userid = ug.user_id * -1) ORDER BY realname";

	   return $this->createUserList($sql);;
   }

   public function listAssignedUsersToRol($rolId) {
	   global $ddbb;

	   $sql = "SELECT p.* FROM ".$ddbb->getTable("UserRol")." ur, profiles p WHERE ur.user_id < 0 AND ur.rol_id = ".$_POST['rol_id']." AND p.userid = ur.user_id * -1 ORDER BY p.realname";

	   return $this->createUserList($sql);;
   }

   public function listUnassignedUsersToRol($rolId) {
	   global $ddbb;

	   $sql = "SELECT * FROM profiles WHERE userid NOT IN (SELECT p.userid FROM ".$ddbb->getTable("UserRol")." ur, profiles p WHERE ur.user_id < 0 AND ur.rol_id = ".$_POST['rol_id']." AND p.userid = ur.user_id * -1) ORDER BY realname";

	   return $this->createUserList($sql);;
   }
  
   public function getUser($userid) {
	   global $ddbb;
	   $data = $ddbb->executePKSelectQuery("SELECT * FROM profiles WHERE userid=".$userid);
	   return $this->createUser($data);
   }
  
}

?>
