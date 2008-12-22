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
       background: url(<?= npadmin_setting('NP-ADMIN', 'BASE_URL') ?>/static/img/glider.png) center center no-repeat;
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
   global $ddbb, $menus, $NPADMIN_PATH;
   
   $login = npadmin_loginData();
   $menus = array();
   
   if ($login != null) {
      $myGroups = $login->getGroups();
   
      $menus[$parentId] = array();
      $sql = "SELECT m.* FROM ".$ddbb->getTable('Menu')." m, ".$ddbb->getTable('MenuGroup')." mg";
      $sql .= " WHERE m.".$ddbb->getMapping('Menu','parentId')." = ".NP_DDBB::encodeSQLValue($parentId, $ddbb->getType('Menu','parentId'));
      $sql .= " AND mg.".$ddbb->getMapping('MenuGroup','menuId')." = m.".$ddbb->getMapping('Menu','id');
      $sql .= " AND m.".$ddbb->getMapping('Menu','panelId')." IS NULL";
      if (count($myGroups) > 0) {   
         $sql .= " AND ( false ";
         foreach ($myGroups as $group) {
            $sql .= " OR mg.".$ddbb->getMapping('MenuGroup','groupName')." = ".NP_DDBB::encodeSQLValue($group, $ddbb->getType('MenuGroup','groupName'));
         }
         $sql .= ") ";
      }
      $sql .= " UNION SELECT m.* FROM ".$ddbb->getTable('Panel')." p, ".$ddbb->getTable('PanelGroup')." pg, ".$ddbb->getTable('Menu')." m ";
      $sql .= " WHERE m.".$ddbb->getMapping('Menu','parentId')." = ".NP_DDBB::encodeSQLValue($parentId, $ddbb->getType('Menu','parentId'));
      $sql .= " AND p.".$ddbb->getMapping('Panel','id')." = pg.".$ddbb->getMapping('PanelGroup','panelId');
      $sql .= " AND p.".$ddbb->getMapping('Panel','id')." = m.".$ddbb->getMapping('Menu','panelId');
      if (count($myGroups) > 0) {   
         $sql .= " AND ( false ";
         foreach ($myGroups as $group) {
            $sql .= " OR pg.".$ddbb->getMapping('PanelGroup','groupName')." = ".NP_DDBB::encodeSQLValue($group, $ddbb->getType('PanelGroup','groupName'));
         }
         $sql .= ") ";
      }
      $sql .=" ORDER BY ".$ddbb->getMapping('Menu','parentId').", `".$ddbb->getMapping('Menu','order')."`";
      //echo $sql;
      $ddbb->executeSelectQuery($sql, "createMenuList", array($parentId, &$menus));
      //print_r($menus);
   }
   if (array_key_exists($parentId, $menus) && sizeof($menus[$parentId]) > 0) {
      foreach ($menus[$parentId] as $menu) { 
         if ($menu->text === NULL && $menu->panelId === NULL) {
            echo "], [ ";
         } else {

echo "{";
if ($menu->panelId != null) { 
   if ($menu->text != null) { 
      echo "text: \"".$menu->text."\",";
   } else { 
      echo "text: \"".$menu->panel->getTitle()."\",";
   }
   if ($menu->url != null) { 
      echo "url: \"".npadmin_setting('NP-ADMIN', 'BASE_URL').'/'.$menu->url."\"";
   } else { 
      if (NP_startsWith("http", $menu->panel->getURL()))
         echo "url: \"".$menu->panel->getURL()."\"";
      else
         echo "url: \"".npadmin_setting('NP-ADMIN', 'BASE_URL').'/'.$menu->panel->getURL()."\"";
   }        
} else if ($menu->url != null) {
   echo "text: \"".$menu->text."\",";
   if (NP_startsWith("http", $menu->url)) {
      echo "url: \"".$menu->url."\",";
      echo "target: \"_new\"";
   } else
      echo "url: \"".npadmin_setting('NP-ADMIN', 'BASE_URL').'/'.$menu->url."\"";
} else { 
?>
text: "<?= $menu->text ?>",
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
    oMenuBar.cfg.setProperty("zindex", "9");
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

    window.onscroll = function () {
	    document.getElementById("npadmin_menubar").style.margin="0px";
	    oMenuBar.moveTo(0,0);
	    document.getElementById("npadmin_menubar").style.left="0px";
	    document.getElementById("npadmin_menubar").style.margin="-10px 0 0 0";
	    oMenuBar.render(document.body);
    }
});

</script>

