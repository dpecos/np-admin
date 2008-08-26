<? 
class DefaultAuthenticator {
   
   private function doLogin($user, $password) {
      global $ddbb;
      $sql = "SELECT * FROM ".$ddbb->getTable('User')." WHERE ".$ddbb->getMapping('User','user')." = ".NP_DDBB::encodeSQLValue($user, $ddbb->getType('User','user'))." AND ".$ddbb->getMapping('User','password')." = ".NP_DDBB::encodeSQLValue($password, $ddbb->getType('User','password'));
      return $ddbb->executePKSelectQuery($sql);
   }
   
   public function login($user, $password) {
      global $ddbb;
      
      $password = sha1($password);
      
      if (($data = $this->doLogin($user, $password)) != null) {
         $groups = array();
         $ddbb->executeSelectQuery("SELECT g.group_name AS group_name FROM ".$ddbb->getTable("User")." u, ".$ddbb->getTable("Group")." g, ".$ddbb->getTable("UserGroup")." ug WHERE u.user = ug.user AND ug.group_name = g.group_name AND u.user = '".$user."' ORDER BY 1", "__addToGroup", array(&$groups));
         $user = new User($data);
         return array($user, $groups);
      } else {
         return null;
      }
   }
   
   public function canLogout() {
      return true;
   }
   
   public function isLoginFormRequired() {
      return true;
   }
  
}

function __addToGroup($data, &$groups) {
   $groups[] = $data['group_name'];
}

?>