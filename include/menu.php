<style type="text/css">
   #npadmin_menubar {
      margin-bottom: 10px;  
      top:0px; 
   }

   .menu_rightside {
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
       text-indent: 2.5em;          
       display: block;
       background: url(<?= npadmin_setting('NP-ADMIN', 'BASE_URL') ?>/static/img/settings.png) left center no-repeat;
       height: 20px;
   }
   
   em#npadminlogout {
       text-indent: 2.5em;
       font-style: italic;
       display: block;
       background: url(<?= npadmin_setting('NP-ADMIN', 'BASE_URL') ?>/static/img/logout.png) left center no-repeat;
   }
   
   em#npadminlogin {
       text-indent: 2.5em;
       font-style: italic;
       display: block;
       background: url(<?= npadmin_setting('NP-ADMIN', 'BASE_URL') ?>/static/img/login.png) left center no-repeat;
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
   
   if (npadmin_setting("NP-ADMIN","CACHE_MENUS") && isset($_SESSION) && array_key_exists("npadmin_menus_".$parentId, $_SESSION))
        $menus = $_SESSION["npadmin_menus_".$parentId];
   else if ($login != null) {
      $myRols = $login->getRolsIds();
   
      $menus[$parentId] = array();
      $sql = "SELECT m.* FROM ".$ddbb->getTable('Menu')." m, ".$ddbb->getTable('MenuRol')." mg";
      $sql .= " WHERE m.".$ddbb->getMapping('Menu','parentId')." = ".NP_DDBB::encodeSQLValue($parentId, $ddbb->getType('Menu','parentId'));
      $sql .= " AND mg.".$ddbb->getMapping('MenuRol','menuId')." = m.".$ddbb->getMapping('Menu','menuId');
      $sql .= " AND m.".$ddbb->getMapping('Menu','panelId')." IS NULL";
      if (count($myRols) > 0) {   
         $sql .= " AND ( false ";
         foreach ($myRols as $rol) {
            $sql .= " OR mg.".$ddbb->getMapping('MenuRol','rolId')." = ".NP_DDBB::encodeSQLValue($rol, $ddbb->getType('MenuRol','rolId'));
         }
         $sql .= ") ";
      }
      $sql .= " UNION SELECT m.* FROM ".$ddbb->getTable('Panel')." p, ".$ddbb->getTable('PanelRol')." pg, ".$ddbb->getTable('Menu')." m ";
      $sql .= " WHERE m.".$ddbb->getMapping('Menu','parentId')." = ".NP_DDBB::encodeSQLValue($parentId, $ddbb->getType('Menu','parentId'));
      $sql .= " AND p.".$ddbb->getMapping('Panel','id')." = pg.".$ddbb->getMapping('PanelRol','panelId');
      $sql .= " AND p.".$ddbb->getMapping('Panel','id')." = m.".$ddbb->getMapping('Menu','panelId');
      if (count($myRols) > 0) {   
         $sql .= " AND ( false ";
         foreach ($myRols as $rol) {
            $sql .= " OR pg.".$ddbb->getMapping('PanelRol','rolId')." = ".NP_DDBB::encodeSQLValue($rol, $ddbb->getType('PanelRol','rolId'));
         }
         $sql .= ") ";
      }
      $sql .=" ORDER BY ".$ddbb->getMapping('Menu','parentId').", `".$ddbb->getMapping('Menu','order')."`";
      //echo $sql;
      $ddbb->executeSelectQuery($sql, "createMenuList", array($parentId, &$menus));
      //print_r($menus);
      if (npadmin_setting("NP-ADMIN","CACHE_MENUS") && isset($_SESSION))
         $_SESSION["npadmin_menus_".$parentId] = $menus;
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
   id: "menu_<?= $menu->menuId ?>",
   itemdata: [
   [<? createMenus($menu->menuId); ?>]
   ]
}
<? } ?>
},
<? 
         }
      }
   }
}

$login = npadmin_loginData();
?>
YAHOO.util.Event.onDOMReady(function () {

    var aItemData = [
        { 
            text: "<em id=\"npadminlabel\">NP-Admin</em>", 
            submenu: { 
                id: "npadmin", 
                itemdata: [    
                    <?
                    if ($login == null || !in_array("Administrators", $login->getRolsNames())) { 
                    ?>
                    [
                     { text: "<em id=\"npadminsettings\">Administration login</em>", url: "<?= npadmin_setting('NP-ADMIN', 'BASE_URL')?>/panels/mainPanel.php", disabled: false}
                    ],
                    <? 
                    }
                    ?>
                    [
                     "About NP-Admin",
                     { text: "Visit NP-Admin site", url: "http://code.google.com/p/np-admin/", target: "_new"}
                    ]
                ]
            } 
        },
        <? 
        if (isset($ddbb)) 
         createMenus(); 

        if ($login != null) {
        ?>
           { text: "<em id=\"npadminlogin\"><?= $login->getUser()->user ?></em>", classname: "menu_rightside", 
              submenu: {
                 id: "npadminlogin_menu",
                 itemdata: [
                    [
                       {text: "Change my password", url: "javascript:npadmin_showChangePassword()", disabled: false}
                    ],
                    [
                 <?
                 if ($login->canLogout()) {
                 ?>
                       { text: "<em id=\"npadminlogout\">Log-out <?= $login->getUser()->user ?></em>", onclick: { fn: logout }, disabled: false},
                 <? } else { ?>
                       { text: "<?= $login->getUser()->user ?>", disabled: true},
                 <? 
                    }
                 ?>  
                    ]
                 ]
              }
           },   
        <?    
        } else {
        ?>
           { text: "<em id=\"npadminlogin\">Login</em>", classname: "menu_rightside", url: "javascript: npadmin_showLogin(false)", disabled: false},
        <? 
        }        
        ?>     
    ];

    var oMenuBar = new YAHOO.widget.MenuBar("npadmin_menubar");
    oMenuBar.addItems(aItemData);
    oMenuBar.cfg.setProperty("zindex", "9");
    //oMenuBar.cfg.setProperty("position", "fixed");
    oMenuBar.render(document.body);
    
    if (YAHOO.env.ua.ie > 0 && YAHOO.env.ua.ie < 7) {
      if (npadmin_setting("NP-ADMIN", "IE6_FIXED_MENU")) {
         YAHOO.util.Event.addListener(window, "scroll", function() {
            var menu = document.getElementById("npadmin_menubar");
             menu.style.margin="0px";
             oMenuBar.moveTo(0,0);
             menu.style.left="0px";
             menu.style.margin="-10px 0 0 0";
             oMenuBar.render(document.body);
          });
      }
    } else {
       var menu = document.getElementById("npadmin_menubar");
       if (menu != null) {
           menu.style.position = "fixed";
           menu.style.width = "100%";
           menu.style.left = "0px";
       }
    }

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