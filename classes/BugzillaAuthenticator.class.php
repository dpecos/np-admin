<? 
class BugzillaAuthenticator {

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
         $isAdmin = false;
         foreach ($bugzillaGroups as $bg) {
            if ($bg['name'] === npadmin_setting("AUTH_BUGZILLA", "BUGZILLA_ADMIN_GROUP")) {
               $isAdmin = true;
               $groups = array_merge($groups, array(npadmin_setting("AUTH_BUGZILLA", "NPADMIN_ADMIN_GROUP")));
            }
            $groups = array_merge($groups, array("bugzilla_".$bg['name']));
         }
         if (!$isAdmin) {
            $groups = array_merge($groups, array(npadmin_setting("AUTH_BUGZILLA", "DEFAULT_NPADMIN_GROUP")));
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
   
   public function getFormURL() {
      global $PWD;
      return $PWD."soporte_panels/login.php";
   }
}

?>