<?
global $PWD, $ddbb, $ddbb_settings;

error_reporting(E_ALL);
ini_set('display_errors', '1'); 

if (file_exists($PWD."config/ddbb.php"))
   require_once($PWD."config/ddbb.php");
require_once($PWD."lib/np-lib/NPLib_Object.php");
require_once($PWD."lib/np-lib/NPLib_Sql_2.php");
require_once($PWD."lib/np-lib/NPLib_Net.php");
require_once($PWD."lib/np-lib/NPLib_String.php");
require_once($PWD."API.php");

$ddbb = new NP_DDBB($ddbb_settings);

require_once($PWD."classes/Setting.sql.php");
require_once($PWD."classes/User.sql.php");
require_once($PWD."classes/Group.sql.php");
require_once($PWD."classes/UserGroup.sql.php");
require_once($PWD."classes/Menu.sql.php");

require_once($PWD."classes/MenuGroup.sql.php");

/*require_once($PWD."classes/Group.class.php");
require_once($PWD."classes/LoginData.class.php");
require_once($PWD."classes/Menu.class.php");
require_once($PWD."classes/Setting.class.php");
require_once($PWD."classes/User.class.php");
require_once($PWD."classes/UserGroup.class.php");
require_once($PWD."classes/YUI.class.php");*/

function __autoload($class_name) {
   global $PWD;
   require_once($PWD."classes/".$class_name.".class.php");
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
