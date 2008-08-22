<style type="text/css">
   
   em#npadminlabel {
       text-indent: -6em;
       display: block;
       background: url(<?= npadmin_setting('NP-ADMIN', 'BASE_URL') ?>/favicon.ico) center center no-repeat;
       width: 2em;
       overflow: hidden;
   }


   /*
       Setting the "zoom" property to "1" triggers the "hasLayout" 
       property in IE.  This is necessary to fix a bug IE where 
       mousing mousing off a the text node of MenuItem instance's 
       text label, or help text without the mouse actually exiting the
       boundaries of the MenuItem instance will result in the losing  
       the background color applied when it is selected.
   */
   
   #filemenu.visible .yuimenuitemlabel,
   #editmenu.visible .yuimenuitemlabel {

       *zoom: 1;

   }


/*
	Remove "hasLayout" from the submenu of the file menu.			
*/

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
$menus = array();

function createMenuList($data, $parentId) {
   global $menus;
   $menus[$parentId][] = new Menu($data);
}
   
function createMenus($parentId = 0) {
   global $ddbb, $menus, $PWD;

   $menus[$parentId] = array();

   $ddbb->executeSelectQuery("SELECT * FROM ".$ddbb->getTable('Menu')." WHERE ".$ddbb->getMapping('Menu','parentId')." = ".NP_DDBB::encodeSQLValue($parentId, $ddbb->getType('Menu','parentId'))." ORDER BY ".$ddbb->getMapping('Menu','parentId').", `".$ddbb->getMapping('Menu','order')."`", "createMenuList", array($parentId));

   if (sizeof($menus[$parentId]) > 0) {
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
   } else {
      echo "{ }";
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
        { text: "Logout (<?= $login->getUser()->user ?>)", classname: "menu_logout", onclick: { fn: logout }, disabled: false}
        <?   } else { ?>
        { text: "<?= $login->getUser()->user ?>", classname: "menu_logout", disabled: true},
        //{ text: "NP-Admin Login", classname: "menu_logout", url: "<?= npadmin_setting('NP-ADMIN', 'BASE_URL')?>/include/login.php", disabled: false}
        <? 
            }
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

