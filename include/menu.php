<style type="text/css">
   #npadmin_menubar {
      margin-bottom: 10px;   
   }

   .menu_logout {
     float: right;
   }   

   em#npadminlabel {
       text-indent: -6em;
       display: block;
       background: url(<?= npadmin_setting('NP-ADMIN', 'BASE_URL') ?>/favicon.ico) center center no-repeat;
       width: 2em;
       overflow: hidden;
   }
   
   em#npadminsettings {
       text-indent: -6em;
       display: block;
       background: url(<?= npadmin_setting('NP-ADMIN', 'BASE_URL') ?>/static/img/settings.png) center center no-repeat;
       width: 2em;
       overflow: hidden;
   }
   
   em#npadminlogout {
       text-indent: 2.5em;
       display: block;
       background: url(<?= npadmin_setting('NP-ADMIN', 'BASE_URL') ?>/static/img/logout.png) left center no-repeat;
   }
   
   #filemenu.visible .yuimenuitemlabel,
   #editmenu.visible .yuimenuitemlabel {
       *zoom: 1;
   }

   #filemenu.visible .yuimenu .yuimenuitemlabel {
       *zoom: normal;
   }

</style>


<script type="text/javascript">
function logout() {
   box_question("userlogout_question", "Are you sure you want to logout?", logoutConfirm);
}

function logoutConfirm() {
   var transaction = YAHOO.util.Connect.asyncRequest('GET', "<?= npadmin_setting('NP-ADMIN', 'BASE_URL') ?>/ajax/users.php?op=logout", {success: function() {document.location.href = "<?= npadmin_setting('NP-ADMIN', 'BASE_URL') ?>"}} );
}

         
<?

function createMenuList($data, $parentId, $menus) {
   $menus[$parentId][] = new Menu($data);
}
   
function createMenus($parentId = 0) {
   global $ddbb, $menus, $PWD;
   
   $login = npadmin_loginData();
   $menus = array();
   
   if ($login != null) {
      $myGroups = $login->getGroups();
   
      $menus[$parentId] = array();
      $sql = "SELECT m.* FROM ".$ddbb->getTable('Menu')." m, ".$ddbb->getTable('MenuGroup')." mg WHERE m.".$ddbb->getMapping('Menu','parentId')." = ".NP_DDBB::encodeSQLValue($parentId, $ddbb->getType('Menu','parentId'));
      $sql .= " AND mg.".$ddbb->getMapping('MenuGroup','menu_id')." = m.".$ddbb->getMapping('Menu','id');
      if (count($myGroups) > 0) {   
         $sql .= " AND ( false ";
         foreach ($myGroups as $group) {
            $sql .= " OR mg.".$ddbb->getMapping('MenuGroup','group_name')." = ".NP_DDBB::encodeSQLValue($group, $ddbb->getType('MenuGroup','group_name'));
         }
         $sql .= ") ";
      }
      $sql .=" ORDER BY m.".$ddbb->getMapping('Menu','parentId').", `".$ddbb->getMapping('Menu','order')."`";
      //echo $sql;
      $ddbb->executeSelectQuery($sql, "createMenuList", array($parentId, &$menus));
   }
   if (array_key_exists($parentId, $menus) && sizeof($menus[$parentId]) > 0) {
      foreach ($menus[$parentId] as $menu) { 
         if ($menu->text === NULL) {
            echo "], [ ";
         } else {
?>
{    
text: "<?= $menu->text ?>",
<? if ($menu->url != null) { ?>
url: "<?= npadmin_setting('NP-ADMIN', 'BASE_URL').'/'.$menu->url ?>"
<? } else { ?>
submenu: {
   id: "menu_<?= $menu->id ?>",
   itemdata: [
   [<? createMenus($menu->id); ?>]
   ]
}
               <? } ?>
           },
<? 
         }
      }
   }
}
?>
YAHOO.util.Event.onDOMReady(function () {

    var aItemData = [
    
        { 
            text: "<em id=\"npadminlabel\">NP-Admin</em>", 
            submenu: { 
                id: "npadmin", 
                itemdata: [
                    "About NP-Admin",
                    { text: "Visit NP-Admin homepage", url: "http://netpecos.org/projects/np-admin" }
                ]
            } 
        },
        <? if (isset($ddbb)) createMenus(); ?>
        <? 
        $login = npadmin_loginData();
        if ($login != null) {
            if ($login->canLogout()) {
        ?>
        { text: "<em id=\"npadminlogout\">Log-out <?= $login->getUser()->user ?></em>", classname: "menu_logout", onclick: { fn: logout }, disabled: false},
        <?   } else { ?>
        { text: "<?= $login->getUser()->user ?>", classname: "menu_logout", disabled: true},
        <? 
            }
        } 
        if ($login == null || !in_array("Administrators", $login->getGroups())) { 
        ?>
        { text: "<em id=\"npadminsettings\">Log-in</em>", classname: "menu_logout", url: "<?= npadmin_setting('NP-ADMIN', 'BASE_URL')?>/panels/mainPanel.php", disabled: false}
        <? 
        } 
        ?>
    ];

    var oMenuBar = new YAHOO.widget.MenuBar("npadmin_menubar", { 
                                                lazyload: true, 
                                                itemdata: aItemData 
                                                });
    oMenuBar.render(document.body);


    // Add a "show" event listener for each submenu.
    
    function onSubmenuShow() {

      var oIFrame, oElement, nOffsetWidth;


      // Keep the left-most submenu against the left edge of the browser viewport
      if (this.id == "npadmin") {
         YAHOO.util.Dom.setX(this.element, 0);
         oIFrame = this.iframe;            
         if (oIFrame) {
	         YAHOO.util.Dom.setX(oIFrame, 0);
         }
         this.cfg.setProperty("x", 0, true);
      }

    }

    // Subscribe to the "show" event for each submenu
    oMenuBar.subscribe("show", onSubmenuShow);
});

</script>

