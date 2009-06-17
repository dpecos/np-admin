<? 
class LoginData {
   private $user;
   private $defaultAuthenticator;
   private $alternativeAuthenticator;
   private $usedDefaultAuthenticator;
   
   public function __construct() {
      $this->defaultAuthenticator = new DefaultAuthenticator();
      $this->alternativeAuthenticator = null;

      $auth = npadmin_setting("NP-ADMIN", "AUTH");
      if ($auth != null) 
         $this->alternativeAuthenticator = new $auth();
         
      $this->usedDefaultAuthenticator = ($this->alternativeAuthenticator == null);
   }
   
   private function clearSession() {
	   if (!isset($_SESSION)) {
		   session_start();
	   }
	   $seed = null;
	   if (array_key_exists("npadmin_login_seed", $_SESSION))
	      $seed = $_SESSION['npadmin_login_seed'];
	   session_unset();
	   if ($seed != null)
	      $_SESSION['npadmin_login_seed'] = $seed;
   }
   
   private function storeInSession($name, $data) {
      $_SESSION[$name] = $data;
   }
     
   public function getUser() {
      return $this->user;
   }
   
   public function login($user, $password) {
	   global $ddbb;
	   if ($user != null && $password != null)
	       $this->clearSession();

	   $data = null;

	   if (!$this->usedDefaultAuthenticator) {
		   $data = $this->alternativeAuthenticator->login($user, $password);
		   if ($data != null) {
			   $this->user = $data[0];
			   $this->rols = $this->defaultAuthenticator->listAssignedRolsToUser($this->user->userId);
			   //$this->groups = array_merge($data[2], $this->defaultAuthenticator->listAssignedGroupsToUser($this->user->userId));
			   $this->groups = $this->defaultAuthenticator->listAssignedGroupsToUser($this->user->userId);

			   if (npadmin_setting("AUTH","DEFAULT_GROUP") != null) {
				   /*$data = $ddbb->executePKSelectQuery("SELECT * FROM ".$ddbb->getTable("Group")." WHERE ".$ddbb->getMapping("Group", "groupId")."=".NP_DDBB::encodeSQLValue(npadmin_setting("AUTH","DEFAULT_GROUP"), $ddbb->getType('Group','groupId')));
				   $this->groups = array_merge(array(new Group($data)), $this->groups);*/

				   $data = $ddbb->executeSelectQuery("SELECT r.* FROM ".$ddbb->getTable("Rol")." r, ".$ddbb->getTable("GroupRol")." gr WHERE gr.rol_id=r.rol_id AND ".$ddbb->getMapping("GroupRol", "groupId")."=".NP_DDBB::encodeSQLValue(npadmin_setting("AUTH","DEFAULT_GROUP"), $ddbb->getType('GroupRol','groupId')));
				   foreach ($data as $idx => $dataRol) {
					   $this->rols = array_merge(array(new Rol($dataRol)), $this->rols);
				   }

			   }

			   $this->storeInSession("npadmin_logindata", $this);

			   $this->usedDefaultAuthenticator = false;
			   return true;
		   }
	   } 

	   $data = $this->defaultAuthenticator->login($user, $password);
	   if ($data != null) {
		   $this->user = $data[0];
		   $this->rols = $data[1];
		   $this->groups = $data[2];
		   $this->storeInSession("npadmin_logindata", $this);

		   $this->usedDefaultAuthenticator = true;
		   return true;
	   } else {
		   $this->usedDefaultAuthenticator = true;
		   return false;
	   }
   }
   
   public function logout() {
      session_destroy();
   }
   
   public function canLogout() {
      if ($this->usedDefaultAuthenticator)
         return $this->defaultAuthenticator->canLogout();
      else
         return $this->alternativeAuthenticator->canLogout();
   }
   
   public function userInRol($rol) {
	   $byId = true;
	   if (is_string($rol))
		   $byId = false;
	   else if (is_int($rol))
		   $byId = true;

	   foreach ($this->rols as $g) {
		   if ($byId) {
			   if ($g->rolId == $rol)
				   return true;
		   } else {
			   if ($g->rolName == $rol)
				   return true;
		   }
	   }
	   return false;
   }
   
   public function isAllowed($rols) {
	   foreach ($rols as $rol) {
		   if ($this->userInRol($rol))
			   return true;
	   }
	   return false;
   }
   
   public function isLoginFormRequired() {
      if ($this->usedDefaultAuthenticator)
         return $this->defaultAuthenticator->isLoginFormRequired();
      else
         return $this->alternativeAuthenticator->isLoginFormRequired();
   }
   
   public function getRolsNames() {
	   $data = array();
	   foreach ($this->rols as $g) {
		   $data[] = $g->rolName;
	   }
	   return $data;
   }

   public function getRolsIds() {
	   $data = array();
	   foreach ($this->rols as $g) {
		   $data[] = $g->rolId;
	   }
	   return $data;
   }

   public function getGroups() {
	   return $this->groups;
   }
}
?>
