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
   var group_list;

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
      
      group_list = new YAHOO.widget.Button("group_list", {
            type: "menu",  
            menu: "group_list_select"
      }); 
      var transaction = YAHOO.util.Connect.asyncRequest('POST', "<?= npadmin_setting('NP-ADMIN', 'BASE_URL') ?>/ajax/groups.php", {success:groupListCallback}, postdata);
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
      tree.removeChildren(tree.getRoot());
       
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
   
   function groupListCallback(response) {
      var groups = YAHOO.lang.JSON.parse(response.responseText);
      group_list.getMenu().clearContent();
      
      for (idx in groups){
          var group = groups[idx];
          if (typeof(group) != "function")
            group_list.getMenu().addItem({ text: group.group_name, value: group.group_name, onclick: {fn: filterMenus}});
      }
      
      group_list.getMenu().render(document.body);
   }
      
   function filterMenus(p_sType, p_aArgs, p_oItem) {
      var group = p_oItem.cfg.getProperty("text");
      group_list.set("label", group);
      var postdata = "op=list&groups=" + group;
      var transaction = YAHOO.util.Connect.asyncRequest('POST', "<?= npadmin_setting('NP-ADMIN', 'BASE_URL') ?>/ajax/menus.php", {success:menuListCallback}, postdata);
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
        <li class="selected"><a href="#"><em>Menus</em></a></li>
    </ul>            
    <div class="yui-content">
        <div>
           <input type="button" id="group_list" name="group_list" value="All groups"/>
           <select id="group_list_select" name="group_list_select"></select>
           <div id="menuTree"></div>
        </div>
    </div>
</div>
        

<? require_once($PWD."include/footer.php"); ?>
