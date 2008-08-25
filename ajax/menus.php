<?
$PWD = "../";
require_once($PWD."include/common.php");

header("Cache-Control: no-cache");
header("Pragma: no-cache");
header("Expires: Mon, 01 Jan 2000 01:00:00 GMT");

npadmin_security(array("Administrators"), false);

$returnList = false;

if (array_key_exists("op", $_POST)) {
   if ($_POST['op'] == "add") {
      $group = new Group($_POST);
      if ($group->store())
         echo "OK";
      else 
         echo "ERROR";
   
   } else if ($_POST['op'] == "delete") {
      $list = split(",", $_POST['list']);
      foreach ($list as $id) {
         $group = new Group();
         $group->group_name = $id;
         if (!$group->delete()) {
            echo "ERROR: Unable to delete group '".$id."'";
            return;
         }
      }
      echo "OK";
      
   } else if ($_POST['op'] == "list" || $_GET['op'] == "list") {
      $returnList = true;
   }
   
   if ($returnList) {
      $menus = array();
   
      function createMenuList($data, &$menus, $parentId) {
         $menus["menu_".$parentId][] = new Menu($data);
      }
   
      function createMenus($parentId = 0) {
         global $ddbb, $menus;
         
         $ddbb->executeSelectQuery("SELECT * FROM ".$ddbb->getTable('Menu')." WHERE ".$ddbb->getMapping('Menu','parentId')." = ".NP_DDBB::encodeSQLValue($parentId, $ddbb->getType('Menu','parentId'))." ORDER BY ".$ddbb->getMapping('Menu','parentId').", `".$ddbb->getMapping('Menu','order')."`", "createMenuList", array(&$menus, $parentId));
   
         if (isset($menus["menu_".$parentId]) && sizeof($menus["menu_".$parentId]) > 0) {
            foreach ($menus["menu_".$parentId] as $menu) { 
               /*if ($menu->text === NULL) {
                  echo "], [ ";
               } else {*/
                  //createMenus($menu->id);
               //}
            }
         }
      }
      if (isset($_POST['id']))
         $parentId = $_POST['id'];
      else
         $parentId = 0;
      createMenus($parentId);
      
      echo json_encode($menus); 
   } 
}
?>
