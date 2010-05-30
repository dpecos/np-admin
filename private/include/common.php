<?
global $NPADMIN_PATH, $ddbb, $ddbb_settings;

error_reporting(E_ALL);
ini_set('display_errors', '1'); 

global $NPLIB_PATH;
if (!isset($NPLIB_PATH)) 
   $NPLIB_PATH = $NPADMIN_PATH."private/lib/np-lib/";
require_once($NPLIB_PATH."includes.php");

if (file_exists($NPADMIN_PATH."private/config/ddbb_config.php")) {
   require_once($NPADMIN_PATH."private/config/ddbb_config.php");
} else {
    $location = $NPADMIN_PATH."public/install.php";
    if (!NP_endsWith($location, $_SERVER["PHP_SELF"])) {
        header("Location: ".$location);
        die();
    }
}

$ddbb = new NP_DDBB($ddbb_settings);

require_once($NPADMIN_PATH."API.php");

require_once($NPADMIN_PATH."private/classes/Setting.class.php");
require_once($NPADMIN_PATH."private/classes/Rol.class.php");
require_once($NPADMIN_PATH."private/classes/Group.class.php");
require_once($NPADMIN_PATH."private/classes/GroupRol.class.php");
require_once($NPADMIN_PATH."private/classes/User.class.php");
require_once($NPADMIN_PATH."private/classes/UserGroup.class.php");
require_once($NPADMIN_PATH."private/classes/UserRol.class.php");
require_once($NPADMIN_PATH."private/classes/Menu.class.php");
require_once($NPADMIN_PATH."private/classes/MenuRol.class.php");
require_once($NPADMIN_PATH."private/classes/Panel.class.php");
require_once($NPADMIN_PATH."private/classes/PanelRol.class.php");
//require_once($NPADMIN_PATH."private/classes/UserTmpPassword.class.php");
require_once($NPADMIN_PATH."private/classes/Logger.class.php");
require_once($NPADMIN_PATH."private/classes/Application.class.php");
require_once($NPADMIN_PATH."private/classes/SessionData.class.php");

Logger::init(array("npadmin", "nplib"));
$vars = "";
if (strlen($_SERVER["QUERY_STRING"]) > 0)
	$vars = $_SERVER["QUERY_STRING"];
else {
	foreach ($_POST as $k => $v)
		$vars .= $k."=".$v."&";
	$vars = substr($vars, 0, strlen($vars)-1);
}
if (strlen($vars) > 0)
   $vars = "?".$vars;
Logger::info("npadmin", "Request received: ".NP_get_server_url().$_SERVER['PHP_SELF'].$vars);


function __autoload($class_name) {
   global $NPADMIN_PATH;
   NP_addIncludePath($NPADMIN_PATH);
   
   if (isset($class_name) && $class_name != null && trim($class_name) != "")
      require_once("private/classes/".trim($class_name).".class.php");
}

define('NP_DEFAULT_LANG', npadmin_setting('NP-ADMIN', 'LANGUAGE_DEFAULT'));
if (isset($_GET) && array_key_exists("LANG", $_GET)) {
     define('NP_LANG', $_GET["LANG"]);
     setcookie('NP_LANG', $_GET["LANG"], 0, "/");
} else {
    define('NP_LANG', array_key_exists('NP_LANG', $_COOKIE) && $_COOKIE['NP_LANG'] != NULL ? $_COOKIE['NP_LANG'] : NP_DEFAULT_LANG);
}
#Logger::debug("npadmin", "Language set to ".NP_LANG);
    
putenv("LC_ALL=".NP_LANG);
setlocale(LC_ALL, NP_LANG);
bindtextdomain("messages", $NPADMIN_PATH."/private/i18n");
textdomain("messages");

if (npadmin_setting("NP-ADMIN", "MAIL_SERVER") != null) {
	ini_set("SMTP", npadmin_setting("NP-ADMIN", "MAIL_SERVER"));
	ini_set("smtp_port", npadmin_setting("NP-ADMIN", "MAIL_PORT"));
}

$yui = new YUI(npadmin_setting('NP-ADMIN', 'YUI_PATH'));

$yui->add("standar");
$yui->add("menu");
$yui->add("tabview");
$yui->add("button");
$yui->add("simpledialog");
$yui->add("datatable");
$yui->add("ajax");
$yui->add("event");
$yui->add("json");
$yui->add("treeview");
$yui->add("calendar");
$yui->add("editor");

$yui_logging = false;
?>
