<?
require_once($NPADMIN_PATH."include/common.php");

if (session_id() === "") {
session_start();
}

function npadmin_login($user, $password) {
	if (session_id() === "")
		session_start();
	$loginData = new LoginData();
	return $loginData->login($user, $password);
}

function npadmin_logout() {
	if (session_id() === "")
		session_start();
	  if (isset($_SESSION) && array_key_exists('npadmin_logindata', $_SESSION))
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

	$login = npadmin_loginData();

	if ($login == null) {
		if ($showLoginForm)
			npadmin_loginForm();
		else
			die(_("You are not allowed to access this page"));
	} else {
		if (is_array($rols)) {
			if (!$login->isAllowed($rols)) {
				if ($showLoginForm)
					npadmin_loginForm();
				else
					die(_("You are not allowed to access this page"));
			}
		} else {
			// TODO:rols contains panelId
			//$panel = new Panel($rols);


		}
	}
}

function npadmin_isAllowed($rols) {
	$login = npadmin_loginData();
	if ($login != null) {
		if ($rols != null && is_array($rols))
			return $login->isAllowed($rols);
		else if ($rols != null)
			return $login->isAllowed(array($rols));
		else
			return false;
	} else {
		return false;
	}
}

$cacheSettings = null;
function npadmin_setting($type, $name) {
	global $ddbb, $cacheSettings;
	 
	if (isset($ddbb) && $ddbb->isInitialized()) {
		$value = null;
		if ($cacheSettings === null || (!isset($cacheSettings->value) && !isset($cacheSettings->defaultValue)) || ($cacheSettings->value === null && $cacheSettings->defaultValue === null))
			$cacheSettings = new Setting("CACHE_SETTINGS", "NP-ADMIN");
		 
		if ($cacheSettings->value || ($cacheSettings->value === null && $cacheSettings->defaultValue === "true")) {
			$_settingsCache = __npadmin_settings_cache($type);
				
			if (array_key_exists($type, $_settingsCache) && array_key_exists($name, $_settingsCache[$type])) {
				$setting = $_settingsCache[$type][$name];
				if (isset($setting->value) && $setting->value !== null)
					$value = $setting->value;
				else if (isset($setting->defaultValue))
					$value = $setting->defaultValue;
			}
				
			if (isset($_SESSION))
				$_SESSION["npadmin_settingsCache"] = $_settingsCache;
			 
		} else {
			$setting = new Setting($name, $type);
			if (isset($setting->value) && $setting->value !== null)
				$value = $setting->value;
			else if (isset($setting->defaultValue))
				$value = $setting->defaultValue;
		}

		return $value;

	} else {
		switch ($type."_".$name) {
			case "NP-ADMIN_BG_COLOR": return "#9999BB"; break;
			case "NP-ADMIN_YUI_PATH": return "http://yui.yahooapis.com/2.5.2/build/"; break;
			case "NP-ADMIN_BASE_URL": return "../"; break;
		}
		return null;
	}
}

$_settingsCache = null;
function __npadmin_settings_cache($type) {
	global $ddbb, $_settingsCache;

	if ($_settingsCache === null) {
		if (isset($_SESSION)) {
			if (array_key_exists("npadmin_settingsCache", $_SESSION)) {
				$_settingsCache = $_SESSION["npadmin_settingsCache"];
			} else {
				$_settingsCache = array();
				$_SESSION["npadmin_settingsCache"] = $_settingsCache;
			}
		} else {
			$_settingsCache = array();
		}
	}
	if (!array_key_exists($type, $_settingsCache)) {
		if ($type != null) {
			//Console::logSpeed('INI: Cacheo setting tipo '. $type);
			$settings = $ddbb->executeSelectQuery("SELECT * FROM ".$ddbb->getTable('Setting')." WHERE ".$ddbb->getMapping('Setting','type')." = ".NP_DDBB::encodeSQLValue($type, $ddbb->getType('Setting','type')));
			$results = array();
			foreach ($settings as $data) {
				$setting = new Setting($data);
				$results[$setting->name] = $setting;
			}
			$_settingsCache[$type] = $results;
			$_SESSION["npadmin_settingsCache"] = $_settingsCache;
			//Console::logSpeed('FIN: Cacheo setting tipo '. $type);
		}
	}
	 
	return $_settingsCache;
}

function npadmin_panel($panelID) {
	return new Panel($panelID);
}

function npadmin_html_loginForm() {
	$_SESSION["npadmin_login_seed"] = NP_random_string(10);
?>
<div style="visibility: hidden; display:none">
   <div id="login_form_table">
      <div class="bd">
      <input id="npadmin_login_seed" type="hidden" value="<?= $_SESSION["npadmin_login_seed"] ?>"/>
      <form id="npadmin_loginForm">
         <table style="margin: 5px">
            <tr>
              <td rowspan="2" style="padding: 5px;"><img src="<?= npadmin_setting('NP-ADMIN', 'BASE_URL') ?>/static/img/login_big.png"/></td>
              <td style="padding: 10px; padding-bottom: 0px;"><?= _("User name") ?>:</td>
              <td style="padding: 10px; padding-bottom: 0px;"><input type="text" name="user"/></td>
            </tr>
            <tr>
              <td style="padding: 10px; padding-bottom: 0px;"><?= _("Password") ?>:</td>
              <td style="padding: 10px; padding-bottom: 0px;"><input type="password" name="password"/></td>
            </tr>
         </table>
         <input type="hidden" name="op" value="login"/>
      </form>
      </div>
   </div>
</div>
<?	
}

function npadmin_html_changePasswordForm() {
?>
<div style="visibility: hidden; display:none">
   <div id="changePassword_form_table">
      <div class="bd">
      <input id="npadmin_changePassword_seed" type="hidden" value=""/>
      <form id="npadmin_changePasswordForm">
         <table style="margin: 5px">
            <tr>
              <td rowspan="3" style="padding: 5px;"><img src="<?= npadmin_setting('NP-ADMIN', 'BASE_URL') ?>/static/img/change_password_big.png"/></td>
              <td style="padding: 10px; padding-bottom: 0px;"><?= _("Old password") ?>:</td>
              <td style="padding: 10px; padding-bottom: 0px;"><input type="password" name="old_password"/></td>
            </tr>
            <tr>
              <td style="padding: 10px; padding-bottom: 0px;"><?= _("New password") ?>:</td>
              <td style="padding: 10px; padding-bottom: 0px;"><input type="password" name="new_password"/></td>
            </tr>
            <tr>
              <td style="padding: 10px; padding-bottom: 0px;"><?= _("Repeat new password") ?>:</td>
              <td style="padding: 10px; padding-bottom: 0px;"><input type="password" name="new_password_2"/></td>
            </tr>
         </table>
         <input type="hidden" name="op" value="changePassword"/>
      </form>
      </div>
   </div>
</div>
<?	
}
?>