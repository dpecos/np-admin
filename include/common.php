<?
global $NPADMIN_PATH, $ddbb, $ddbb_settings;

error_reporting(E_ALL);
ini_set('display_errors', '1'); 

if (file_exists($NPADMIN_PATH."work/ddbb_config.php"))
   require_once($NPADMIN_PATH."work/ddbb_config.php");

global $NPLIB_PATH;
if (!isset($NPLIB_PATH)) 
   $NPLIB_PATH = $NPADMIN_PATH."lib/np-lib/";

require_once($NPLIB_PATH."includes.php");
/*require_once($NPLIB_PATH."NPLib_Object.php");
require_once($NPLIB_PATH."NPLib_Sql_2.php");
require_once($NPLIB_PATH."NPLib_Net.php");
require_once($NPLIB_PATH."NPLib_String.php");*/

$ddbb = new NP_DDBB($ddbb_settings);

require_once($NPADMIN_PATH."API.php");

require_once($NPADMIN_PATH."classes/Setting.class.php");
require_once($NPADMIN_PATH."classes/Rol.class.php");
require_once($NPADMIN_PATH."classes/Group.class.php");
require_once($NPADMIN_PATH."classes/GroupRol.class.php");
require_once($NPADMIN_PATH."classes/User.class.php");
require_once($NPADMIN_PATH."classes/UserRol.class.php");
require_once($NPADMIN_PATH."classes/UserGroup.class.php");
require_once($NPADMIN_PATH."classes/Menu.class.php");
require_once($NPADMIN_PATH."classes/MenuGroup.class.php");
require_once($NPADMIN_PATH."classes/MenuRol.class.php");
require_once($NPADMIN_PATH."classes/Panel.class.php");
require_once($NPADMIN_PATH."classes/PanelRol.class.php");
require_once($NPADMIN_PATH."classes/PanelGroup.class.php");

require_once($NPADMIN_PATH."classes/Logger.class.php");

Logger::init(array("npadmin", "nplib"));
$vars = "";
if (strlen($_SERVER["QUERY_STRING"]) > 0)
	$vars = $_SERVER["QUERY_STRING"];
else {
	foreach ($_POST as $k => $v)
		$vars .= $k."=".$v."&";
	$vars = substr($vars, 0, strlen($vars)-1);
}
Logger::info("npadmin", "Request received: ".$_SERVER["REQUEST_URI"].$vars);


function __autoload($class_name) {
   global $NPADMIN_PATH;
   NP_addIncludePath($NPADMIN_PATH);
   
   if (isset($class_name) && $class_name != null && trim($class_name) != "")
      require_once("classes/".trim($class_name).".class.php");
}

define('NP_DEFAULT_LANG', npadmin_setting('NP-ADMIN', 'LANGUAGE_DEFAULT'));
if (isset($_GET) && array_key_exists("LANG", $_GET)) {
     define('NP_LANG', $_GET[LANG]);
     setcookie('NP_LANG', $_GET[LANG]);
} else {
    define('NP_LANG', $_COOKIE['NP_LANG'] != NULL ? $_COOKIE['NP_LANG'] : NP_DEFAULT_LANG);
}
    
putenv("LC_ALL=".NP_LANG);
setlocale(LC_ALL, NP_LANG);
bindtextdomain("messages", $NPADMIN_PATH."/work/i18n");
textdomain("messages");

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

$yui_logging = false;
?>
