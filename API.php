<?
require_once($PWD."include/common.php");

if (session_id() === "")
   session_start();
      
function npadmin_login($user, $password) {
   if (session_id() === "")
      session_start();
   $loginData = new LoginData();
   return $loginData->login($user, sha1($password));
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
      if (isset($_SESSION['npadmin_logindata']))
         return $_SESSION['npadmin_logindata'];
      else 
         return null;
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

function npadmin_setting($type, $name) {
   global $_settingsCache;
   
   if (!in_array($type."_".$name, $_settingsCache)) {
      $setting = new Setting($name, $type);
      if ($setting->value !== null)
         $_settingsCache[$type."_".$name] = $setting->value;
      else 
         $_settingsCache[$type."_".$name] = $setting->defaultValue;
      //print_r($setting);
   }
   return $_settingsCache[$type."_".$name];
}
?>
