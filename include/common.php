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
require_once($NPADMIN_PATH."classes/User.class.php");
require_once($NPADMIN_PATH."classes/Group.class.php");
require_once($NPADMIN_PATH."classes/UserGroup.class.php");
require_once($NPADMIN_PATH."classes/Menu.class.php");
require_once($NPADMIN_PATH."classes/MenuGroup.class.php");
require_once($NPADMIN_PATH."classes/Panel.class.php");
require_once($NPADMIN_PATH."classes/PanelGroup.class.php");

function __autoload($class_name) {
   global $NPADMIN_PATH;
   add_include_path($NPADMIN_PATH);
   
   if (isset($class_name) && $class_name != null && trim($class_name) != "")
      require_once("classes/".trim($class_name).".class.php");
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

$yui_logging = false;
?>
