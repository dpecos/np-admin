<?
$PWD = "../";

$file = $PWD."config/ddbb.php";

global $msg;
$msg = "";
if (file_exists($file)) {
   unlink($file);
   $msg = "<b>Deleted existing config file. A new one will be generated now.</b><br/><br/>";
}

require_once($PWD."include/common.php");
$ddbb_back = clone $ddbb;
unset($ddbb);

if (!array_key_exists('_PATH', $_POST))
   $_POST['_PATH'] = "";

/* Fresh install data */
$data = array (
   'Setting' => array(
      array('type' => 'NP-ADMIN', 'name' => 'BASE_URL', 'default_value' => "/np-admin", "value" => $_POST['_PATH']),
      array('type' => 'NP-ADMIN', 'name' => 'AUTH', 'default_value' => ""),
      array('type' => 'NP-ADMIN', 'name' => 'AUTH_FORM', 'default_value' => "include/login.php"),
      array('type' => 'NP-ADMIN', 'name' => 'YUI_PATH', 'default_value' => 'http://yui.yahooapis.com/2.5.2/build', "value" => $_POST['_PATH']."/lib/yui_2.5.2/build"),
      array('type' => 'NP-ADMIN', 'name' => 'BG_COLOR', 'default_value' => '#9999BB'),
      array('type' => 'APP', 'name' => 'TITLE', 'default_value' => 'App Name', 'value' => "Example App")
   ), 
   'User' => array(
      array('user' => 'admin', 'password' => 'd033e22ae348aeb5660fc2140aec35850c4da997', 'email' => 'admin@estilohacker.com', 'real_name' => 'NP-Admin main user')
   ),
   'Group' => array(
      array('group_name' => 'Administrators', 'description' => 'NP-Admin administrator users')
   ),
   'UserGroup' => array(
      array('group_name' => 'Administrators', 'user' => 'admin')
   ),
   'Menu' => array(
      array('id' => 1, 'parent_id' => 0, 'order' => 0, 'text' => 'Main', 'url' => 'panels/mainPanel.php'),
      array('id' => 2, 'parent_id' => 0, 'order' => 1, 'text' => 'Management'),
      array('id' => 3, 'parent_id' => 2, 'order' => 0, 'text' => 'Users', 'url' => 'panels/userPanel.php'),
      array('id' => 4, 'parent_id' => 2, 'order' => 1, 'text' => 'Groups', 'url' => 'panels/groupPanel.php'),
      array('id' => 5, 'parent_id' => 2, 'order' => 3, 'text' => 'Menus', 'url' => 'panels/menuPanel.php'),
      array('id' => 6, 'parent_id' => 0, 'order' => 2, 'text' => 'Configuration'),
      array('id' => 7, 'parent_id' => 6, 'order' => 0, 'text' => 'NP-Admin settings', 'url' => 'panels/settingsPanel.php'),
      array('id' => 8, 'parent_id' => 2, 'order' => 2)
   ),
   'MenuGroup' => array(
      array('menu_id' => 1, 'group_name' => 'Administrators'),
      array('menu_id' => 2, 'group_name' => 'Administrators'),
      array('menu_id' => 3, 'group_name' => 'Administrators'),
      array('menu_id' => 4, 'group_name' => 'Administrators'),
      array('menu_id' => 5, 'group_name' => 'Administrators'),
      array('menu_id' => 6, 'group_name' => 'Administrators'),
      array('menu_id' => 7, 'group_name' => 'Administrators'),
      array('menu_id' => 8, 'group_name' => 'Administrators')
   )   
);
?>

<?
function html_head() {
   global $PWD;
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
<? require_once($PWD."include/header.php"); ?>

<div class="page_title">NP-Admin installation</div>

<div id="mainTabs" class="yui-navset">
    <ul class="yui-nav">
        <li class="selected"><a href="#"><em>Install</em></a></li>
        <li><a href="#"><em>Upgrade</em></a></li>
    </ul>            
    <div class="yui-content">
        <div>
<? 
if (isset($_POST) && count($_POST) > 1) {
   
   if ($_POST['op'] === "install") {
      $ddbb = $ddbb_back;
      $ddbb->setDDBBConfig($_POST);
      
      echo "Step 1: Creating database structure ... \n";
      $sql = $ddbb->createSQLCreateTable();
      foreach (split(";", $sql) as $query) {
         $ddbb->executeInsertUpdateQuery($query);
      }
      echo "Done <br/>\n";
      
      echo "Step 3: Saving DDBB configuration ... \n";
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
      echo "<br/>Installation succeed! Now you can <a href='settingsPanel.php'>login and edit configuration</a> with user <b>admin</b> and password <b>admin</b>.\n";
   
   } else if ($_POST['op'] === "upgrade") {
      $ddbb = $ddbb_back;
      echo "Step 1: Creating database structure ... \n";
      echo " (New tables) ... \n";
      $sql = $ddbb->createSQLCreateTable();
      foreach (split(";", $sql) as $query) {
         $ddbb->executeInsertUpdateQuery($query);
      }
      echo " (and updating old tables) \n";
      // TODO      
      echo "Done <br/>";
      
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
      echo "<br/>Upgrade succeed! Now you can <a href='settingsPanel.php'>login and edit configuration</a> with your already configure users.\n";
   }
} else {
?>
           <p><? global $msg; echo $msg ?></p>
           <p>In order to start installation, fill next data and click on the "Start..." button:</p>
           <br/>
           <form method="post">
           <table>
              <caption><b>Database</b></caption>
              <tr><td width="150px">HOST</td><td><input type="text" name="HOST" value="localhost"/></td></tr>
              <tr><td width="150px">USER</td><td><input type="text" name="USER" value="npadmin_user"/></td></tr>            
              <tr><td width="150px">PASSWORD</td><td><input type="text" name="PASSWD" value="npadmin_password"/></td></tr>
              <tr><td width="150px">NAME</td><td><input type="text" name="NAME" value="npadmin"/></td></tr>
              <tr><td width="150px">TABLE PREFIX</td><td><input type="text" name="PREFIX" value="npadmin_"/></td></tr> 
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
        

<? require_once($PWD."include/footer.php"); ?>
