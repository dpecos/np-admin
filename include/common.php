<?
global $PWD, $ddbb_table, $ddbb_mapping, $ddbb_types, $ddbb_settings;

$ddbb_table = array();
$ddbb_mapping = array();
$ddbb_types = array();	

require_once($PWD."config/ddbb.php");
require_once($PWD."lib/np-lib/NPLib_Object.php");
require_once($PWD."lib/np-lib/NPLib_Sql.php");
require_once($PWD."lib/np-lib/NPLib_Net.php");
require_once($PWD."lib/np-lib/NPLib_String.php");
require_once($PWD."API.php");

__NP_initDDBB($ddbb_settings);
require_once($PWD."classes/User.sql.php");
require_once($PWD."classes/Group.sql.php");
require_once($PWD."classes/UserGroup.sql.php");
require_once($PWD."classes/Menu.sql.php");

function __autoload($class_name) {
   global $PWD;
   require_once($PWD."classes/".$class_name.".class.php");
}

$yui = new YUI($PWD."lib/yui_2.5.2/build/");

$yui->add("standar");
$yui->add("menu");
$yui->add("tabview");
$yui->add("button");
$yui->add("simpledialog");
$yui->add("datatable");
$yui->add("ajax");
$yui->add("event");
$yui->add("json");

$yui_logging = false;
?>
