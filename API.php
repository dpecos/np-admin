<?
require_once($PWD."include/common.php");

function npadmin_login($user, $password) {
   session_start();
   $loginData = new LoginData();
   return $loginData->login($user, $password);
}

function npadmin_logout() {
   session_start();
   if (isset($_SESSION))
      $_SESSION['npadmin_logindata']->logout();
}

function npadmin_logindata() {
   session_start();
   if (isset($_SESSION)) {
      return $_SESSION['npadmin_logindata'];
   } else {
      return null;
   }
}

function npadmin_isUserLoggedIn() {
   return npadmin_logindata() != null;
}

function npadmin_userallowed() {
   session_start();
   if (($login = npadmin_logindata()) != null) {
      return $login->getUser()->user == "admin";
   } else {
      return false;
   }
}
?>
