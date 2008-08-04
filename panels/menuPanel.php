<?
$PWD = "../";
require_once($PWD."include/common.php");
npadmin_security(array("Administrators"));
?>

<?
function html_head() {
   global $PWD;
?>

<style type="text/css">
#mainTabs div.yui-content {
   padding: 15px;
}

#group_form_table td {
   padding: 3px;
}

#group_datatable {
   margin-bottom: 10px;
}

.yui-button#delGroupButton button {
   padding-left: 2em;
   background: url(<?= npadmin_setting('NP-ADMIN', 'BASE_URL') ?>/static/img/del.gif) 5% 50% no-repeat;
}

.yui-button#addGroupButton button {
   padding-left: 2em;
   background: url(<?= npadmin_setting('NP-ADMIN', 'BASE_URL') ?>/static/img/add.gif) 5% 50% no-repeat;
}
</style>

<script> 
   var tabView;
   var tree;
   var contextElements = [];

   function changeTabEventHandler(e) {
   }
   
   YAHOO.util.Event.addListener(window, "load", function() {
      tabView = new YAHOO.widget.TabView('mainTabs');
      tabView.addListener('activeTabChange', changeTabEventHandler);
     
      tree = new YAHOO.widget.TreeView("menuTree");
      tree.setDynamicLoad(loadNodeData, 0); 
              
      var oContextMenu = new YAHOO.widget.ContextMenu("mytreecontextmenu", {
         trigger: "menuTree",
         lazyload: true, 
         itemdata: [
             { text: "Edit", onclick: { fn: function() {} } },
             { text: "Delete", onclick: { fn: function() {} } },
             { text: "Add new entry", onclick: { fn: function() {} } }
         ] });
      
      oContextMenu.subscribe("triggerContextMenu", function (p_oEvent) {
	      var oTextNode = Dom.hasClass(oTarget, "ygtvlabel") ? oTarget : Dom.getAncestorByClassName(oTarget, "ygtvlabel");

         if (oTextNode) {
		      oCurrentTextNode = oTextNodeMap[oTarget.id];
         } else {
            this.cancel();
         }
      });
      
      var postdata = "op=list";
      var transaction = YAHOO.util.Connect.asyncRequest('POST', "<?= npadmin_setting('NP-ADMIN', 'BASE_URL') ?>/ajax/menus.php", {success:menuListCallback}, postdata);
   });
   
   function buildMenu(menu, root, menus) {
      var nodeData;
      
      if (menu.text == null)
         nodeData = {label : "---", title: "Separator"};
      else
         if (menu.url != null)
            nodeData = {label : menu.text, title : "URL &laquo;" + menu.url + "&raquo;"};
         else
            nodeData = {label : menu.text, title : "Group &laquo;" + menu.text + "&raquo;"};
         
      var tmpNode = new YAHOO.widget.MenuNode(nodeData, root, false);
      tmpNode.isLeaf = (menu.text == null || menu.url != null);
      tmpNode.npadmin_id = menu.id;
         
      contextElements.push(tmpNode.labelElId);
      
      for (id in menus["menu_" + menu.id]) {
         if (typeof(menus["menu_" + menu.id][id]) != "function") {
            submenu = menus["menu_" + menu.id][id];
            buildMenu(submenu, tmpNode, menus);
         }
      }
   }
   
   function menuListCallback(response) {
      var menus = YAHOO.lang.JSON.parse(response.responseText);

      for (id in menus["menu_0"]) {
         if (typeof(menus["menu_0"][id]) != "function") {
            menu = menus["menu_0"][id];
            buildMenu(menu, tree.getRoot(), menus);
         }
      } 
      
      new YAHOO.widget.Tooltip("tooltip", {context: contextElements});
      
      tree.draw();
   }
      
   function loadNodeData(node, fnLoadComplete)  { 
      var nodeId = node.npadmin_id;
      var postdata = "op=list&id=" + nodeId;
      var arguments = { 
         "nodeId": nodeId, 
         "fnLoadComplete": fnLoadComplete 
      }
      var transaction = YAHOO.util.Connect.asyncRequest('POST', "<?= npadmin_setting('NP-ADMIN', 'BASE_URL') ?>/ajax/menus.php", {success:function(response) {
         var menus = YAHOO.lang.JSON.parse(response.responseText);
         var nodeId = response.argument.nodeId;
         for (id in menus["menu_" + nodeId]) {
            if (typeof(menus["menu_" + nodeId][id]) != "function") {
               menu = menus["menu_" + nodeId][id];
               buildMenu(menu, node, menus);
            }
         } 
         response.argument.fnLoadComplete();
      }, argument : arguments}, postdata);
   }
</script>
<?
}
?>
<? require_once($PWD."include/header.php"); ?>

<div class="page_title">Menu administration</div>

<div id="mainTabs" class="yui-navset">
    <ul class="yui-nav">
        <li class="selected"><a href="#tab1"><em>Menus</em></a></li>
    </ul>            
    <div class="yui-content">
        <div>
           <div id="menuTree"></div>
        </div>
    </div>
</div>
        

<? require_once($PWD."include/footer.php"); ?>
