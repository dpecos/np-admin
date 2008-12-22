<?
$NPADMIN_PATH = "../";
require_once($NPADMIN_PATH."include/common.php");

header("Cache-Control: no-cache");
header("Pragma: no-cache");
header("Expires: Mon, 01 Jan 2000 01:00:00 GMT");

npadmin_security(array("Administrators"), false);

$returnList = false;

$_POST = NP_UTF8_decode($_POST);

foreach ($_POST as $k => $v) {
   if ($v === "null")
      $_POST[$k] = null;
}

if (array_key_exists("op", $_POST)) {
   if ($_POST['op'] == "add") {
      print_r($_POST);
      /*$group = new Menu($_POST);
      if ($group->store())
         echo "OK";
      else 
         echo "ERROR";*/
   
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
   
      function createMenus($parentId = 0, $groups) {
         global $ddbb, $menus;
         
         $sql  = "SELECT m.* FROM ".$ddbb->getTable('Menu')." m, ".$ddbb->getTable('MenuGroup')." mg WHERE ".$ddbb->getMapping('Menu','parentId')." = ".NP_DDBB::encodeSQLValue($parentId, $ddbb->getType('Menu','parentId'));
         $sql .= " AND m.".$ddbb->getMapping('Menu','id')." = mg.".$ddbb->getMapping('MenuGroup','menuId');
         $sql .= " AND m.".$ddbb->getMapping('Menu','panelId')." IS NULL";
         if ($groups != null) {
            foreach ($groups as $group) {
               $sql .= " AND mg.".$ddbb->getMapping('MenuGroup','groupName')." = ".NP_DDBB::encodeSQLValue($group, $ddbb->getType('MenuGroup','groupName'));
            }
         }
         $sql .= " UNION SELECT m.* FROM ".$ddbb->getTable('Panel')." p, ".$ddbb->getTable('PanelGroup')." pg, ".$ddbb->getTable('Menu')." m ";
         $sql .= " WHERE m.".$ddbb->getMapping('Menu','parentId')." = ".NP_DDBB::encodeSQLValue($parentId, $ddbb->getType('Menu','parentId'));
         $sql .= " AND p.".$ddbb->getMapping('Panel','id')." = pg.".$ddbb->getMapping('PanelGroup','panelId');
         $sql .= " AND p.".$ddbb->getMapping('Panel','id')." = m.".$ddbb->getMapping('Menu','panelId');
         if (count($groups) > 0) {   
            $sql .= " AND ( false ";
            foreach ($groups as $group) {
               $sql .= " OR pg.".$ddbb->getMapping('PanelGroup','groupName')." = ".NP_DDBB::encodeSQLValue($group, $ddbb->getType('PanelGroup','groupName'));
            }
            $sql .= ") ";
         }
         $sql .= " ORDER BY ".$ddbb->getMapping('Menu','parentId').", `".$ddbb->getMapping('Menu','order')."`";
         
         $ddbb->executeSelectQuery($sql, "createMenuList", array(&$menus, $parentId));
         
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
      
      $groups = null;
      if (array_key_exists("groups", $_POST))
         $groups = split(",", $_POST['groups']);   
      
      createMenus($parentId, $groups);
      
      echo NP_json_encode($menus); 
   } 
}
?>
