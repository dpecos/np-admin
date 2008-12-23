<? 
class BugzillaAuthenticator {
   public $prefix = "bugzilla_";

   private function getCookieUserId() {
      if (isset($_COOKIE) &&isset($_COOKIE['Bugzilla_login'])) {
         return $_COOKIE['Bugzilla_login'];
      } else {
         return null;
      }
   }
   
   public function login($user, $password) {
      global $ddbb;
      $cookie = $this->getCookieUserId();
      if ($cookie != null) {
         $sql = "SELECT * FROM profiles WHERE userid = ".$cookie;
         $data = $ddbb->executePKSelectQuery($sql);
         $mappedData = array(
            "user" => $data['realname']
         );
         $user = new User($mappedData);
         
         $sql = "SELECT DISTINCT g.name FROM profiles p, user_group_map m, groups g WHERE p.userid = m.user_id AND m.group_id = g.id AND p.userid = ".$cookie;
         $bugzillaGroups = $ddbb->executeSelectQuery($sql);

         $groups = array();
         $groupsObj = $this->listGroups($cookie);
         foreach ($groupsObj as $g) {
            $groups[] = $g->group_name;
         }
         
         return array($user, $groups);
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
	   $user->user = $data['login_name'];
	   $user->email = $data['login_name'];
	   $user->real_name = $data['realname'];
	   $user->creation_date = "-";
	   $user->external_userID = $data['userid'];
	   return $user;
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

   private function createGroups($sql) {
	   global $ddbb;

	   $bugzillaGroups = $ddbb->executeSelectQuery($sql);

	   $groups = array();
	   $isAdmin = false;
	   if ($bugzillaGroups != null && count($bugzillaGroups)) {
		   foreach ($bugzillaGroups as $bg) {
			   if ($bg['name'] === npadmin_setting("AUTH_BUGZILLA", "BUGZILLA_ADMIN_GROUP")) {
				   $isAdmin = true;
				   $g = new Group();
				   $g->group_name = npadmin_setting("AUTH_BUGZILLA", "NPADMIN_ADMIN_GROUP");
				   $g->description = "Bugzilla \"".$bg['name']."\" users group - NP-Admin administrators group";
				   $groups = array_merge($groups, array($g));
			   } 
			   $g = new Group();
			   $g->group_name = $this->prefix.$bg['name'];
			   $g->description = "Bugzilla \"".$bg['name']."\" users group";
			   $groups = array_merge($groups, array($g));
		   }
	   }
         /*if (!$isAdmin) {
            $groups = array_merge($groups, array(npadmin_setting("AUTH_BUGZILLA", "NPADMIN_DEFAULT_GROUP")));
	 }*/
	   return $groups;

   }

   public function listGroups() {
	   $sql = "SELECT DISTINCT name FROM groups";
	   return $this->createGroups($sql);
   }

   public function listAssignedGroups($loginName) {
	   $sql = "SELECT DISTINCT g.name FROM profiles p, user_group_map m, groups g WHERE p.userid = m.user_id AND m.group_id = g.id AND p.login_name= '".$loginName."'";
	   $groups = $this->createGroups($sql);
	   return $groups;
   }

   public function listUnasssignedGroups($loginName) {
	   $sql = "SELECT name from groups WHERE name not in (Select DISTINCT g.name FROM profiles p, user_group_map m, groups g WHERE p.userid = m.user_id AND m.group_id = g.id AND p.login_name = '".$loginName."')";
	   return $this->createGroups($sql);
   }
      
   public function listAssignedUsers($groupName) {
	   global $ddbb;
	   $sql = "SELECT DISTINCT p.* FROM profiles p, user_group_map m, groups g WHERE p.userid = m.user_id AND m.group_id = g.id AND g.name= '".$groupName."'";
	   	   
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

   public function listUnassignedUsers($groupName) {
      global $ddbb;
	   $sql = "SELECT * from groups WHERE id not in (Select DISTINCT g.id FROM profiles p, user_group_map m, groups g WHERE p.userid = m.user_id AND m.group_id = g.id AND g.name= '".$groupName."')";
	   	   
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
   
  
}

?>
