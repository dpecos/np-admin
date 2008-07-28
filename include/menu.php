<style type="text/css">
   
   em#npadminlabel {
       text-indent: -6em;
       display: block;
       background: url(http://netpecos.org/favicon.ico) center center no-repeat;
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
   var transaction = YAHOO.util.Connect.asyncRequest('GET', "<?= $PWD ?>ajax/users.php?op=logout", {success: function() {document.location.href = "/~dani/np-admin/"}} );
}

         
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
        
        {   
            text: "Home",
            url: "<?= $PWD ?>index.php"
        },     

        { 
            text: "Management", 
            submenu: {  
                id: "management", 
                itemdata: [
                  [
                     { text: "Users", helptext: "Ctrl + u", url: "<?= $PWD ?>panels/userPanel.php"},
                     { text: "Groups", helptext: "Ctrl + g", url: "<?= $PWD ?>panels/groupPanel.php" }
                  ], [
                     { text: "Menus", helptext: "Ctrl + m"},
                  ]
                ] 
            }           
        },

        { text: "Configuration", disabled: false,
          submenu: {
             id: "configuration",
             itemdata: [
               [ 
                 {text: "DDBB settings"},
                 {text: "Application settings"},
                 {text: "NP-Admin settings"}
               ]
             ]
          }
        },
        
        { text: "Logout", classname: "menu_logout", onclick: { fn: logout }, disabled: false}
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

