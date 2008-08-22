<? 
class LoginData {
   private $user;
   private $defaultAuthenticator;
   private $alternativeAuthenticator;
   private $usedDefaultAuthenticator;
   
   public function __construct() {
      global $PWD;
      //require_once($PWD."classes/auth/DefaultAuthenticator.class.php");
      
      $this->defaultAuthenticator = new DefaultAuthenticator();
      $auth = npadmin_setting("NP-ADMIN", "AUTH");
      if ($auth != null) 
         $this->alternativeAuthenticator = new $auth();
      else
         $this->alternativeAuthenticator = null;
   }
   
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
   
   public function login($user, $password) {
      global $ddbb;
      $this->clearSession();
      
      $data = null;
      if ($user != null && $password != null) {
         $data = $this->defaultAuthenticator->login($user, $password);

         $this->usedDefaultAuthenticator = true;
      }
      if ($data != null) {
         $this->user = $data[0];
         $this->groups = $data[1];
         $this->storeInSession("npadmin_logindata", $this);
            
         return true;
      } else {
         if ($this->alternativeAuthenticator != null) {
            $data = $this->alternativeAuthenticator->login($user, $password);
            if ($data != null) {
               $this->user = $data[0];
               $this->groups = $data[1];
               $this->storeInSession("npadmin_logindata", $this);

               $this->usedDefaultAuthenticator = false;
                  
               return true;
            } else {
               return false;
            }
         } else {
            return false;
         }
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
   
   public function isLoginFormRequired() {
      if ($this->usedDefaultAuthenticator)
         return $this->defaultAuthenticator->isLoginFormRequired();
      else
         return $this->alternativeAuthenticator->isLoginFormRequired();
   }
   
   public function getFormURL($forceSecondary = false) {
      if ($this->usedDefaultAuthenticator && !$forceSecondary)
         return $this->defaultAuthenticator->getFormURL();
      else
         return $this->alternativeAuthenticator->getFormURL();
   }
}
?>
