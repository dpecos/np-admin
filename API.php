<?
require_once($NPADMIN_PATH."include/common.php");

if (session_id() === "")
   session_start();
      
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
      if (isset($_SESSION['npadmin_logindata']))
         return $_SESSION['npadmin_logindata'];
   }

   $loginData = new LoginData();
   if (!$loginData->isLoginFormRequired()) {
      if (npadmin_login(null, null))
         return $_SESSION['npadmin_logindata'];
      else 
         return null;
   } else 
      return null;
}

function npadmin_loginForm() {
   require_once(npadmin_setting("NP-ADMIN", "AUTH_FORM"));
   exit();
}

function npadmin_security($rols = null, $showLoginForm = true) {
	/* AJAX calls have $showLoginForm set to false, because it makes no sense to show a login form */
	if (session_id() === "")
		session_start();
	$login = npadmin_loginData();

	if ($login == null) {
		if ($showLoginForm)
			npadmin_loginForm();
		else 
			die("You are not allowed to access this page");      
	} else {
		if (is_array($rols)) {
			if (!$login->isAllowed($rols)) {
				if ($showLoginForm)
					npadmin_loginForm();
				else 
					die("You are not allowed to access this page");      
			}
		} else {
			// TODO:rols contains panelId
			//$panel = new Panel($rols);


		}
	}
}

function npadmin_setting($type, $name) {
   global $_settingsCache, $ddbb;
   
   if ($_settingsCache == null)
      $_settingsCache = array();
   
   if (isset($ddbb) && $ddbb->isInitialized()) {

      if (!in_array($type."_".$name, $_settingsCache)) {
         $setting = new Setting($name, $type);
         if (isset($setting->value) && $setting->value !== null)
            $_settingsCache[$type."_".$name] = $setting->value;
         else if (isset($setting->defaultValue)) 
            $_settingsCache[$type."_".$name] = $setting->defaultValue;
         else 
            return null;
         //print_r($setting);
      }
      return $_settingsCache[$type."_".$name];
      
   } else {
      switch ($type."_".$name) {
         case "NP-ADMIN_BG_COLOR": return "#9999BB"; break;
         case "NP-ADMIN_YUI_PATH": return "http://yui.yahooapis.com/2.5.2/build/"; break;
         case "NP-ADMIN_BASE_URL": return "../"; break;
      }
      return null;
   }
}

function npadmin_panel($panelID) {
   return new Panel($panelID);     
}
?>
