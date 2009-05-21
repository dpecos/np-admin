<?
$NPADMIN_PATH = "../";
require_once($NPADMIN_PATH."include/common.php");

global $msg, $file;
$msg = "";
$file = $NPADMIN_PATH."work/ddbb_config.php";

require_once($NPADMIN_PATH."include/common.php");

$path = "";
if (array_key_exists('_PATH', $_POST)) {
   $path = $_POST['_PATH'];
} else {
   $path = npadmin_setting('NP-ADMIN', 'BASE_URL');
}

$ddbb_back = clone $ddbb;
unset($ddbb);

/* Fresh install data */
$data = array (
   'Group' => array(
      array('group_name' => 'Administrators', 'description' => 'NP-Admin administrator users')
   ),
   'GroupRol' => array(
      array('group_id' => 1, 'rol_id' => 1)
   ), 
   'Menu' => array(
      array('id' => 1, 'parent_id' => 0, 'order' => 0, 'text' => 'Main', 'url' => 'panels/mainPanel.php', 'panel_id' => 'mainPanel'),
      array('id' => 2, 'parent_id' => 0, 'order' => 1, 'text' => 'Management'),
      array('id' => 3, 'parent_id' => 2, 'order' => 3, 'text' => 'Users', 'url' => 'panels/userPanel.php', 'panel_id' => 'userPanel'),
      array('id' => 4, 'parent_id' => 2, 'order' => 4, 'text' => 'Groups', 'url' => 'panels/groupPanel.php', 'panel_id' => 'groupPanel'),
      array('id' => 5, 'parent_id' => 2, 'order' => 0, 'text' => 'Menus', 'url' => 'panels/menuPanel.php', 'panel_id' => 'menuPanel'),
      array('id' => 6, 'parent_id' => 0, 'order' => 2, 'text' => 'Configuration'),
      array('id' => 7, 'parent_id' => 6, 'order' => 0, 'text' => 'NP-Admin settings', 'url' => 'panels/settingsPanel.php', 'panel_id'=>'settingsPanel'),
      array('id' => 8, 'parent_id' => 2, 'order' => 2),
      array('id' => 9, 'parent_id' => 2, 'order' => 1, 'text' => 'Panels', 'url' => 'panels/panelPanel.php', 'panel_id' => 'panelPanel'),
      array('id' => 10, 'parent_id' => 6, 'order' => 1, 'url' => 'panels/phpInfoPanel.php', 'panel_id' => 'phpInfoPanel'),
      array('id' => 11, 'parent_id' => 2, 'order' => 5, 'text' => 'Rols', 'url' => 'panels/rolPanel.php', 'panel_id' => 'rolPanel'),
   ),
   'MenuGroup' => array(),
   'MenuRol' => array(
      array('menu_id' => 2, 'rol_id' => 1),
      array('menu_id' => 6, 'rol_id' => 1),
      array('menu_id' => 8, 'rol_id' => 1),
   ),
   'Panel' => array(
      array('id' => 'mainPanel', 'title' => 'NP-Admin Home'),
      array('id' => 'userPanel', 'title' => 'User administration'),
      array('id' => 'groupPanel', 'title' => 'Group administration'),
      array('id' => 'menuPanel', 'title' => 'Menu administration'),
      array('id' => 'settingsPanel', 'title' => 'Settings administration'),
      array('id' => 'panelPanel', 'title' => 'Panels administration'),
      array('id' => 'phpInfoPanel', 'title' => 'PHP Info'),
      array('id' => 'rolPanel', 'title' => 'Rol administration'),
   ),  
   'PanelGroup' => array(),
   'PanelRol' => array(
      array('panel_id' => 'groupPanel', 'rol_id' => 1),
      array('panel_id' => 'mainPanel', 'rol_id' => 1),
      array('panel_id' => 'menuPanel', 'rol_id' => 1),
      array('panel_id' => 'panelPanel', 'rol_id' => 1),
      array('panel_id' => 'settingsPanel', 'rol_id' => 1),
      array('panel_id' => 'userPanel', 'rol_id' => 1),
      array('panel_id' => 'phpInfoPanel', 'rol_id' => 1),
      array('panel_id' => 'rolPanel', 'rol_id' => 1),
   ),
   'Rol' => array(
   		array('rol_name' => 'Administrators', 'description' => 'Administrators')
   ),  
   'Setting' => array(
      array('type' => 'NP-ADMIN', 'name' => 'BASE_URL', 'default_value' => "/np-admin", "value" => $path),
      array('type' => 'NP-ADMIN', 'name' => 'AUTH', 'default_value' => ""),
      array('type' => 'NP-ADMIN', 'name' => 'AUTH_FORM', 'default_value' => "include/login.php"),
      array('type' => 'NP-ADMIN', 'name' => 'YUI_PATH', 'default_value' => 'http://yui.yahooapis.com/2.5.2/build', "value" => $path."/lib/yui_2.5.2/build"),
      array('type' => 'NP-ADMIN', 'name' => 'BG_COLOR', 'default_value' => '#9999BB'),
      array('type' => 'APP', 'name' => 'TITLE', 'default_value' => 'App Name', 'value' => "Example App"),
      array('type' => 'APP', 'name' => 'FORM_MESSAGE', 'default_value' => "You need a valid and granted user/password")
   ), 
   'User' => array(
      array('user' => 'admin', 'password' => 'd033e22ae348aeb5660fc2140aec35850c4da997', 'email' => 'admin@domain.com', 'real_name' => 'NP-Admin main user')
   ),
   'UserGroup' => array(
      array('group_id' => 1, 'user_id' => 1)
   )
);
?>

<?
function html_head() {
   global $NPADMIN_PATH;
?>
<script> 
   var tabView;

   YAHOO.util.Event.addListener(window, "load", function() {
      tabView = new YAHOO.widget.TabView('mainTabs');
      new YAHOO.widget.Button("start_install");
      new YAHOO.widget.Button("start_upgrade");
   });
  
</script>
<?
}
?>
<? require_once($NPADMIN_PATH."include/header.php"); ?>

<div class="page_title">NP-Admin installation</div>

<div id="mainTabs" class="yui-navset">
    <ul class="yui-nav">
        <li class="selected"><a href="#"><em>Install</em></a></li>
        <li><a href="#"><em>Upgrade</em></a></li>
    </ul>            
    <div class="yui-content">
        <div>
<? 
if (isset($_POST) && count($_POST) >= 1) {
  
   if ($_POST['op'] === "install") {
      if (file_exists($file)) {
         unlink($file);
         $msg = "<b>Deleted existing config file. A new one will be generated now.</b><br/><br/>";
         echo $msg;
      }
      
      $ddbb = $ddbb_back;
      $ddbb->setDDBBConfig($_POST);
      
      echo "Step 1: Creating database structure ... \n";
      $sql = $ddbb->createSQLCreateTable();
      foreach (split(";", $sql) as $query) {
         if (strlen(trim($query)) > 0)
            $ddbb->executeInsertUpdateQuery($query);
      }
      echo "Done <br/>\n";
      
      echo "Step 2: Saving DDBB configuration ... \n";
      $fh = fopen($file, 'w') or die("Can't create configuration file ".$file);
      fwrite($fh, "<?\n");
      fwrite($fh, "global \$ddbb_settings;\n");
      fwrite($fh, "\$ddbb_settings = array();\n");
      foreach ($_POST as $k => $v) {
         if (substr($k, 0, 1) != "_")
            fwrite($fh, "\$ddbb_settings['".$k."'] = '".$v."';\n");
      }
      fwrite($fh, "?>");
      fclose($fh);
      echo "Done <br/>";
      
      echo "Step 3: Inserting default data ... \n";
      foreach ($data as $type => $vector) {
         foreach ($vector as $data) {
            $obj = new $type();
            $ddbb->loadData($obj, $data);
            $ddbb->insertObject($obj);
         }
      }
      echo "Done <br/>\n";
      echo "<br/>Installation succeed! Now you can <a href='../panels/settingsPanel.php'>login and edit configuration</a> with user <b>admin</b> and password <b>admin</b>.\n";
   
   } else if ($_POST['op'] === "upgrade") {

      $ddbb = $ddbb_back;
      echo "Step 1: Creating database structure ... \n";
      echo " (New tables) ... \n";
      $sql = $ddbb->createSQLCreateTable();
      foreach (split(";", $sql) as $query) {
         if (strlen(trim($query)) > 0)
            $ddbb->executeInsertUpdateQuery($query);
      }
      echo " (and updating old tables) \n";
      // TODO   
      $sql = $ddbb->createSQLTruncateTable();
      foreach (split(";", $sql) as $query) {
         if (strlen(trim($query)) > 0)
            $ddbb->executeInsertUpdateQuery($query);
      }
      echo "Done <br/>\n";   
      
      echo "Step 2: Updating / Inserting default data ... \n";
      foreach ($data as $type => $vector) {
         foreach ($vector as $data) {
            $obj = new $type();
            $ddbb->loadData($obj, $data);
            try {
               $ddbb->insertObject($obj);
            } catch (Exception $e) {
               echo "<!--".$e->getMessage()."-->\n";
            }                
         }
      }
      echo "Done <br/>\n";
      echo "<br/>Upgrade succeed! Now you can <a href='../panels/settingsPanel.php'>login and edit configuration</a> with your already configure users.\n";
   }
} else {
?>
           <p><? global $msg; echo $msg ?></p>
           <p>In order to start installation, fill next data and click on the "Start..." button:</p>
           <br/>
           <form method="post">
           <table>
              <caption><b>Database</b></caption>
              <tr><td width="150px">HOST</td><td><input type="text" name="HOST" value="<?= isset($ddbb_settings) ? $ddbb_settings['HOST'] : "localhost" ?>"/></td></tr>
              <tr><td width="150px">USER</td><td><input type="text" name="USER" value="<?= isset($ddbb_settings) ? $ddbb_settings['USER'] : "npadmin_user" ?>"/></td></tr>            
              <tr><td width="150px">PASSWORD</td><td><input type="text" name="PASSWD" value="<?= isset($ddbb_settings) ? $ddbb_settings['PASSWD'] : "npadmin_password" ?>"/></td></tr>
              <tr><td width="150px">NAME</td><td><input type="text" name="NAME" value="<?= isset($ddbb_settings) ? $ddbb_settings['NAME'] : "npadmin" ?>"/></td></tr>
              <tr><td width="150px">TABLE PREFIX</td><td><input type="text" name="PREFIX" value="<?= isset($ddbb_settings) ? $ddbb_settings['PREFIX'] : "npadmin_" ?>"/></td></tr> 
           </table>    
           <br/>
           <table>
              <caption><b>Configuration</b></caption>
              <tr><td width="150px">PATH</td><td><input type="text" name="_PATH" value="/np-admin"/></td></tr>
           </table> 
           <br/>
           <input type="hidden" name="op" value="install"/>
           <input type="submit" id="start_install" value="Start installation..."/>
           </form>                   
<? } ?>              
        </div>
        <div>
           <form method="post">
              <input type="hidden" name="op" value="upgrade"/>
              <input type="submit" id="start_upgrade" value="Start upgrade..."/>
           </form>
        </div>
    </div>
</div>
        

<? require_once($NPADMIN_PATH."include/footer.php"); ?>
