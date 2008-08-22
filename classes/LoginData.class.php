<? 
class LoginData {
   private $user;
   
   private function clearSession() {
      if (!isset($_SESSION)) {
		   session_start();
	   }
	   session_unset();
   }
   
   private function storeInSession($name, $data) {
      $_SESSION[$name] = $data;
   }
     
   public function getUser() {
      return $this->user;
   }
   
   private function doLogin($user, $password) {
      global $ddbb;
      $sql = "SELECT * FROM ".$ddbb->getTable('User')." WHERE ".$ddbb->getMapping('User','user')." = ".NP_DDBB::encodeSQLValue($user, $ddbb->getType('User','user'))." AND ".$ddbb->getMapping('User','password')." = ".NP_DDBB::encodeSQLValue($password, $ddbb->getType('User','password'));
      return $ddbb->executePKSelectQuery($sql);
   }
   
   public function login($user, $password) {
      global $ddbb;
      $this->clearSession();
      if (($data = $this->doLogin($user, $password)) != null) {
         $this->user = new User($data);
         $this->groups = array();
         $ddbb->executeSelectQuery("SELECT g.group_name AS group_name FROM ".$ddbb->getTable("User")." u, ".$ddbb->getTable("Group")." g, ".$ddbb->getTable("UserGroup")." ug WHERE u.user = ug.user AND ug.group_name = g.group_name AND u.user = '".$user."' ORDER BY 1", "__addToGroup", array(&$this->groups));
         $this->storeInSession("npadmin_logindata", $this);
         return true;
      } else {
         return false;
      }
   }
   
   public function logout() {
      session_destroy();
   }
   
   public function userInGroup($group) {
      return in_array($group, $this->groups);
   }
   
   public function isAllowed($groups) {
      foreach ($groups as $group) {
         if ($this->userInGroup($group))
            return true;
      }
      return false;
   }
}

function __addToGroup($data, &$groups) {
   $groups[] = $data['group_name'];
}

?>
