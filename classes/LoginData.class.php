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
   
   public function __construct() {
   }
   
   public function getUser() {
      return $this->user;
   }
   
   private function doLogin($user, $password) {
      global $ddbb_table, $ddbb_mapping, $ddbb_types;
      $sql = "SELECT * FROM ".$ddbb_table['User']." WHERE ".$ddbb_mapping['User']['user']." = ".encodeSQLValue($user, $ddbb_types['User']['user'])." AND ".$ddbb_mapping['User']['password']." = ".encodeSQLValue($password, $ddbb_types['User']['password']);
      return NP_executePKSelect($sql);
   }
   
   public function login($user, $password) {
      $this->clearSession();
      if (($data = $this->doLogin($user, $password)) != null) {
         $this->user = new User($data);
         $this->groups = array();
         NP_executeSelect("SELECT g.group_name AS group_name FROM npadmin_users u, npadmin_groups g, npadmin_users_groups ug WHERE u.user = ug.user AND ug.group_name = g.group_name AND u.user = '".$user."' ORDER BY 1", "__addToGroup", array(&$this->groups));
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
