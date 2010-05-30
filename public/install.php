<?
$NPADMIN_PATH = "../";

global $msg, $file;
$msg = "";
$file = $NPADMIN_PATH."private/config/ddbb_config.php";

$delete_config = false;
if (!file_exists($file)) {
   $delete_config = true;
   fclose(fopen($file, "wb"));
}
require_once($NPADMIN_PATH."private/include/common.php");
if ($delete_config) {
   unlink($file);
}

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
      array('menuId' => 1, 'parent_id' => 0, 'order' => 0, 'text' => 'Main', 'url' => 'public/panels/mainPanel.php', 'panel_id' => 'mainPanel'),
      array('menuId' => 2, 'parent_id' => 0, 'order' => 1, 'text' => 'Management'),
      array('menuId' => 3, 'parent_id' => 0, 'order' => 2, 'text' => 'Configuration'),
      array('menuId' => 4, 'parent_id' => 2, 'order' => 0, 'text' => 'Menus', 'url' => 'public/panels/menuPanel.php', 'panel_id' => 'menuPanel'),
      array('menuId' => 5, 'parent_id' => 2, 'order' => 1, 'text' => 'Panels', 'url' => 'public/panels/panelPanel.php', 'panel_id' => 'panelPanel'),
      array('menuId' => 6, 'parent_id' => 2, 'order' => 2, 'text' => 'Applications', 'url' => 'public/panels/appsPanel.php', 'panel_id' => 'appsPanel'),
      array('menuId' => 7, 'parent_id' => 2, 'order' => 3),
      array('menuId' => 8, 'parent_id' => 2, 'order' => 4, 'text' => 'Users', 'url' => 'public/panels/userPanel.php', 'panel_id' => 'userPanel'),
      array('menuId' => 9, 'parent_id' => 2, 'order' => 5, 'text' => 'Groups', 'url' => 'public/panels/groupPanel.php', 'panel_id' => 'groupPanel'),
      array('menuId' => 10, 'parent_id' => 2, 'order' => 6, 'text' => 'Rols', 'url' => 'public/panels/rolPanel.php', 'panel_id' => 'rolPanel'),
      array('menuId' => 11, 'parent_id' => 3, 'order' => 0, 'text' => 'NP-Admin settings', 'url' => 'public/panels/settingsPanel.php', 'panel_id'=>'settingsPanel'),
      array('menuId' => 12, 'parent_id' => 3, 'order' => 1, 'url' => 'public/panels/phpInfoPanel.php', 'panel_id' => 'phpInfoPanel'),
   ),
   'MenuGroup' => array(),
   'MenuRol' => array(
      array('menu_id' => 2, 'rol_id' => 1),
      array('menu_id' => 3, 'rol_id' => 1),
      array('menu_id' => 7, 'rol_id' => 1),
   ),
   'Panel' => array(
      array('id' => 'mainPanel', 'title' => 'NP-Admin Home'),
      array('id' => 'userPanel', 'title' => 'User administration'),
      array('id' => 'groupPanel', 'title' => 'Group administration'),
      array('id' => 'menuPanel', 'title' => 'Menu administration'),
      array('id' => 'settingsPanel', 'title' => 'Settings administration'),
      array('id' => 'panelPanel', 'title' => 'Panels administration'),
      array('id' => 'appsPanel', 'title' => 'Applications administration'),
      array('id' => 'phpInfoPanel', 'title' => 'PHP Info'),
      array('id' => 'rolPanel', 'title' => 'Rol administration'),
   ),  
   'PanelGroup' => array(),
   'PanelRol' => array(
      array('panel_id' => 'groupPanel', 'rol_id' => 1),
      array('panel_id' => 'mainPanel', 'rol_id' => 1),
      array('panel_id' => 'menuPanel', 'rol_id' => 1),
      array('panel_id' => 'panelPanel', 'rol_id' => 1),
      array('panel_id' => 'appsPanel', 'rol_id' => 1),
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
      array('type' => 'NP-ADMIN', 'name' => 'AUTH', 'default_value' => "DefaultAuthenticator"),
      array('type' => 'NP-ADMIN', 'name' => 'AUTH_FORM', 'default_value' => "private/include/login.php"),
      array('type' => 'NP-ADMIN', 'name' => 'YUI_PATH', 'default_value' => 'http://yui.yahooapis.com/2.7.0/build', "value" => null),
      array('type' => 'NP-ADMIN', 'name' => 'CACHE_SETTINGS', 'default_value' => "true"),
      array('type' => 'NP-ADMIN', 'name' => 'CACHE_MENUS', 'default_value' => "false"),
      array('type' => 'NP-ADMIN', 'name' => 'LANGUAGE_LIST', 'default_value' => "en_US,es_ES"),
      array('type' => 'NP-ADMIN', 'name' => 'LANGUAGE_DEFAULT', 'default_value' => "en_US"),
      array('type' => 'NP-ADMIN_LNF', 'name' => 'BODY_BG_COLOR', 'default_value' => '#9999BB'),
      array('type' => 'NP-ADMIN_LNF', 'name' => 'TITLE_BG_COLOR', 'default_value' => '#BCC1D6'),
      array('type' => 'NP-ADMIN_LNF', 'name' => 'IE6_FIXED_MENU', 'default_value' => "true"),
      array('type' => 'NPLOG', 'name' => 'LOG_FILE_npadmin', 'default_value' => 'private/log/npadmin_#date#.log'),
      array('type' => 'NPLOG', 'name' => 'LOG_FILE_nplib', 'default_value' => 'private/log/nplib_#date#.log'),
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
<style>
/* Mismo contenido que npadmin_style.php -> en este punto no se puede hacer petición a ese fichero ya que puede que no exista la configuracion de BBDD y redirija de nuevo a install.php */
html {
    background-color: #9999BB;            
}

#main_body {
    margin: 20px;
    margin-top: 44px;
    background-color: #FFFFFF; 
    padding: 15px;
    border: #000000 1px solid;
    /*min-height: 80%;
    height: auto !important;
    height: 80%;*/
}

#mainTabs div.yui-content {
    padding: 15px;
}

.page_title {
    font-size: 22px;
    font-weight: bold;
    font-family: "Arial";
    margin-top: 5px;
    margin-bottom: 15px;
    background-color: #BCC1D6;         
    border: 2px solid black;
    padding:10px;
}

.buttonBox {
    border: 1px solid black;
    margin-bottom:10px;
    padding:10px;
    background-color: rgb(190,211,206);
}

table .npadmin_login {
    margin: 10px;
}

table .npadmin_login td {
    padding: 10px;
    padding-bottom: 0px;
}
</style>
<?
}
?>
<? require_once($NPADMIN_PATH."private/include/header.php"); ?>

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
      echo "<br/>Installation succeed! Now you can <a href='panels/settingsPanel.php'>login and edit configuration</a> with user <b>admin</b> and password <b>admin</b>.\n";
   
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
      echo "<br/>Upgrade succeed! Now you can <a href='panels/settingsPanel.php'>login and edit configuration</a> with your already configure users.\n";
   }
} else {
	
	$path = substr($_SERVER["PHP_SELF"], 0, strpos($_SERVER["PHP_SELF"], "public/install.php"));
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
              <tr><td width="150px">PATH</td><td><input type="text" name="_PATH" value="<?= $path ?>"/></td></tr>
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
        

<? require_once($NPADMIN_PATH."private/include/footer.php"); ?>
