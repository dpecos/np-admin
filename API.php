<?
require_once($PWD."include/common.php");

function npadmin_login($user, $password) {
   if (session_id() === "")
      session_start();
   $loginData = new LoginData();
   return $loginData->login($user, $password);
}

function npadmin_logout() {
   if (session_id() === "")
      session_start();
   if (isset($_SESSION))
      $_SESSION['npadmin_logindata']->logout();
}

function npadmin_loginData() {
   if (session_id() === "")
      session_start();
   if (isset($_SESSION)) {
      return $_SESSION['npadmin_logindata'];
   } else {
      return null;
   }
}

/*function npadmin_isUserLoggedIn() {
   return npadmin_loginData() != null;
}

function npadmin_userAllowed() {
   session_start();
   if (($login = npadmin_loginData()) != null) {
      //return $login->getUser()->user == "admin";
      return $login->userInGroup("Administrators");
   } else {
      return false;
   }
}
*/

function npadmin_loginForm() {
   global $PWD;
   require_once($PWD."include/login.php");
   exit();
}

function npadmin_security($groups = null, $showLoginForm = true) {
   if (session_id() === "")
      session_start();
   $login = npadmin_loginData();
   if ($login == null || $groups != null && !$login->isAllowed($groups)) {
      if ($showLoginForm)
         npadmin_loginForm();
      else 
         die("You are not allowed to access this page");      
   }
}

$_settingsCache = array();

function npadmin_setting($name) {
   global $_settingsCache;
   
   if (!in_array($name, $_settingsCache)) {
      $setting = new Setting($name);
      if ($setting->value !== null)
         $_settingsCache[$name] = $setting->value;
      else 
         $_settingsCache[$name] = $setting->defaultValue;
      //print_r($setting);
   }
   return $_settingsCache[$name];
}
?>
