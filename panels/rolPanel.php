<?
$NPADMIN_PATH = "../";
require_once($NPADMIN_PATH."include/common.php");
$panelData = npadmin_panel("rolPanel");
npadmin_security($panelData->getRols());
?>

<?
function html_head() {
   global $NPADMIN_PATH;
?>

<style type="text/css">
#rol_form_table td {
   padding: 3px;
}

#users_form_table {
   margin-top: 5px;
   margin-bottom: 5px;
}
#rol_datatable {
   margin-bottom: 10px;
}

.yui-button#delRolButton button {
   padding-left: 2em;
   background: url(<?= npadmin_setting('NP-ADMIN', 'BASE_URL') ?>/static/img/del.gif) 5% 50% no-repeat;
}

.yui-button#addRolButton button {
   padding-left: 2em;
   background: url(<?= npadmin_setting('NP-ADMIN', 'BASE_URL') ?>/static/img/add.gif) 5% 50% no-repeat;
}
</style>

<style type="text/css">

ul.draglist { 
    position: relative;
    width: 200px; 
    height:240px;
    background: #f7f7f7;
    border: 1px solid gray;
    list-style: none;
    margin:0;
    margin-right: 10px;
    padding:0;
}

ul.draglist li {
    margin: 1px;
    cursor: move; 
}

ul.draglist_alt { 
    position: relative;
    width: 200px; 
    list-style: none;
    margin:0;
    padding:0;
    /*
       The bottom padding provides the cushion that makes the empty 
       list targetable.  Alternatively, we could leave the padding 
       off by default, adding it when we detect that the list is empty.
    */
    padding-bottom:20px;
}

ul.draglist_alt li {
    margin: 1px;
    cursor: move; 
}

li.li_unassigned_users {
    background-color: #D1E6EC;
    border:1px solid #7EA6B2;
}

li.li_assigned_users {
    background-color: #D8D4E2;
    border:1px solid #6B4C86;
}

li.li_unassigned_groups {
    background-color: #D1E6EC;
    border:1px solid #7EA6B2;
}

li.li_assigned_groups {
    background-color: #D8D4E2;
    border:1px solid #6B4C86;
}

.yui-button#saveUsersButton button {
   padding-left: 2em;
   background: url(<?= npadmin_setting('NP-ADMIN', 'BASE_URL') ?>/static/img/save.gif) 5% 50% no-repeat;
}

.yui-button#saveGroupsButton button {
   padding-left: 2em;
   background: url(<?= npadmin_setting('NP-ADMIN', 'BASE_URL') ?>/static/img/save.gif) 5% 50% no-repeat;
}
</style>

<script> 
   var tabView;
   var dataSource;
   var columnDefs;
   var addRolDialog;
   var rolDatatable;
   var rol_user_list;
   var rol_group_list;
    
   function changeTabEventHandler(e) {
   }
   
   YAHOO.util.Event.addListener(window, "load", function() {
      tabView = new YAHOO.widget.TabView('mainTabs');
      tabView.addListener('activeTabChange', changeTabEventHandler);
     
      columnDefs = [ 
         {key:"rolName", label:"Name", sortable:true},
         {key:"description", label:"Description", sortable:true},
	   ]; 
	        
	   dataSource = new YAHOO.util.DataSource("<?= npadmin_setting('NP-ADMIN', 'BASE_URL') ?>/ajax/rols.php?");
  	   dataSource.connMethodPost = true;
	   dataSource.responseType = YAHOO.util.DataSource.TYPE_JSON; 
      dataSource.connXhrMode = "queueRequests"; 
      dataSource.responseSchema = {
            fields: ["rolId", "rolName","description"]
      };
      dataSource.doBeforeCallback = function(oRequest , oFullResponse , oParsedResponse) {
         rol_user_list.getMenu().clearContent();
         rol_user_list.set("label", "Select rol");
         emptyList("unassigned_users");
         emptyList("assigned_users");

	 rol_group_list.getMenu().clearContent();
         rol_group_list.set("label", "Select rol");
         emptyList("unassigned_groups");
         emptyList("assigned_groups");
         
         for (id in oParsedResponse.results) {
            var rol = oParsedResponse.results[id];
            if (typeof(rol) != "function") {
               rol_user_list.getMenu().addItem({ text: rol.rolName, value: rol.rolId, onclick: { fn: populateUsersLists } });
	       rol_group_list.getMenu().addItem({ text: rol.rolName, value: rol.rolId, onclick: { fn: populateGroupsLists } });
	    }
         }
         rol_user_list.getMenu().render(document.body);
         rol_group_list.getMenu().render(document.body);
         return oParsedResponse;
      };

      rolDatatable = new YAHOO.widget.DataTable("rol_datatable", columnDefs, dataSource, {initialRequest:"op=list"});
      rolDatatable.subscribe("rowMouseoverEvent", rolDatatable.onEventHighlightRow);
      rolDatatable.subscribe("rowMouseoutEvent", rolDatatable.onEventUnhighlightRow);
      rolDatatable.subscribe("rowClickEvent", rolDatatable.onEventSelectRow);
      var delRolButton = new YAHOO.widget.Button({ 
            label:"Delete selected rols", 
            id:"delRolButton", 
            container:"rol_buttons" });
      delRolButton.on("click", deleteRols);
      var addRolButton = new YAHOO.widget.Button({ 
            label:"Create new rol...", 
            id:"addRolButton", 
            container:"rol_buttons" });
      addRolButton.on("click", showNewRolDialog);
      
      addRolDialog = new YAHOO.widget.Dialog("rol_form_table", { 
			   effect: {effect:YAHOO.widget.ContainerEffect.FADE, duration:0.25},
			   fixedcenter: true,
			   draggable: true,
			   constraintoviewport: true,
			   text: "Create new rol",
			   modal: true,
			   close: false,
            buttons: [ 
               { text:"Cancel", handler:defaultButtonHandler },
	            { text:"Add", handler:addRol, isDefault:true } 
	         ],
	         form: YAHOO.util.Dom.get("rol_form")
			 });
	   addRolDialog.setHeader("Add rol");
			 
      var onContextMenuClick = function(p_sType, p_aArgs, p_myDataTable) {
         var task = p_aArgs[1];
         if(task) {
             var elRow = this.contextEventTarget;
             elRow = p_myDataTable.getTrEl(elRow);

             if(elRow) {
                 switch(task.index) {
                     case 0: 
                         var oRecord = p_myDataTable.getRecord(elRow);
                         box_question("roldel_question", "Are you sure you want to delete the selected rol?", function() {
                            this.hide(); 
                            deleteRolsConfirm(oRecord.getData("rolId"));                         
                         });
                         break;
                     case 1:
                         var oRecord = p_myDataTable.getRecord(elRow);
                         var rolId = oRecord.getData("rolId");
                         var rolName = oRecord.getData("rolName");
                         recoverDataUsersLists(rolId, rolName);
                         tabView.set("activeTab",tabView.getTab(1));
                         break;                         
                      case 2:
                         var oRecord = p_myDataTable.getRecord(elRow);
                         var rolId = oRecord.getData("rolId");
                         var rolName = oRecord.getData("rolName");
                         recoverDataGroupsLists(rolId, rolName);
                         tabView.set("activeTab",tabView.getTab(2));
                         break;                         
                 }
             }
         }
      };

      var contextMenu = new YAHOO.widget.ContextMenu("mycontextmenu", { trigger:rolDatatable.getTbodyEl() });
      contextMenu.addItems(["Delete Item", "Manage users", "Manage groups"]);
      contextMenu.render("rol_datatable");
      contextMenu.clickEvent.subscribe(onContextMenuClick, rolDatatable);
      
      rol_user_list = new YAHOO.widget.Button("rol_user_list", {
            type: "menu",  
            menu: "rol_user_list_select"
      });    
      
      rol_group_list = new YAHOO.widget.Button("rol_group_list", {
            type: "menu",  
            menu: "rol_group_list_select"
      });    
      
      new YAHOO.util.DDTarget("unassigned_users");
      new YAHOO.util.DDTarget("assigned_users");
      new YAHOO.util.DDTarget("unassigned_groups");
      new YAHOO.util.DDTarget("assigned_groups");

      var saveUsersButton = new YAHOO.widget.Button({ 
            label:"Save users", 
            id:"saveUsersButton", 
            container:"users_buttons" });
      saveUsersButton.on("click", assignUsers);

      var saveGroupsButton = new YAHOO.widget.Button({ 
            label:"Save groups", 
            id:"saveGroupsButton", 
            container:"groups_buttons" });
      saveGroupsButton.on("click", assignGroups);

   });
   
   function showNewRolDialog() {
      addRolDialog.render(document.body);
      addRolDialog.show();
   }
   
   function addRol() {
      var formObject = document.getElementById('rol_form');
      if (formObject.rol_name.value.trim().length == 0)
         box_block("roladd_block", "All the required fields have to be filled");
      else {
         YAHOO.util.Connect.setForm(formObject); 
         var transaction = YAHOO.util.Connect.asyncRequest('POST', "<?= npadmin_setting('NP-ADMIN', 'BASE_URL') ?>/ajax/rols.php", {success:addRolCallback});
      }
   }
   
   function addRolCallback(response) {
      
      if (response.responseText.trim() == "OK") {
         addRolDialog.hide();
         box_info("roladd_result", "Rol added correctly!");
        
         var formObject = document.getElementById('rol_form');
         formObject.reset();

         var count = rolDatatable.getRecordSet().getLength();
         rolDatatable.deleteRows(0,count);

         dataSource.sendRequest("op=list", {success : rolDatatable.onDataReturnAppendRows, scope: rolDatatable})

         tabView.set('activeIndex', 0);
      } else {
         box_error("roladd_result", response.responseText);
      }
   }
   
   function deleteRols() {
      var l = rolDatatable.getSelectedRows().length;
      if (l > 0)
         box_question("roldel_question", "Are you sure you want to delete the " + l + " selected rols?", deleteRolsConfirm);
      else 
         box_warn("roldel_warn", "No rols selected");
   }
     
   function deleteRolsConfirm(list) {
      if (YAHOO.lang.isObject(list)) {
         var list = "";
         var rows = rolDatatable.getSelectedRows();
         for (var id in rows) {  
            var record = rolDatatable.getRecord(rows[id]);
            if (record != null)
               list += record.getData("rolId") + ",";
         }
         list = list.substring(0, list.length - 1);
         this.hide();
      }
     
      var postdata = "op=delete&list=" + list;
      var transaction = YAHOO.util.Connect.asyncRequest('POST', "<?= npadmin_setting('NP-ADMIN', 'BASE_URL') ?>/ajax/rols.php", {success:deleteRolsCallback}, postdata);
   }
   
   function deleteRolsCallback(response) {
      if (response.responseText.trim() == "OK") {
         var count = rolDatatable.getRecordSet().getLength();
         rolDatatable.deleteRows(0, count);

         dataSource.sendRequest("op=list", {success : rolDatatable.onDataReturnAppendRows, scope: rolDatatable})
      } else {
         box_error("roldel_result", response.responseText);
      }
   }
   
</script>

<script type="text/javascript">
DDList = function(id, sRol, config) {
    DDList.superclass.constructor.call(this, id, sRol, config);

    var el = this.getDragEl();
    YAHOO.util.Dom.setStyle(el, "opacity", 0.67); // The proxy is slightly transparent

    this.goingUp = false;
    this.lastY = 0;
};

YAHOO.extend(DDList, YAHOO.util.DDProxy, {

    startDrag: function(x, y) {
        // make the proxy look like the source element
        var dragEl = this.getDragEl();
        var clickEl = this.getEl();
        YAHOO.util.Dom.setStyle(clickEl, "visibility", "hidden");

        dragEl.innerHTML = clickEl.innerHTML;

        YAHOO.util.Dom.setStyle(dragEl, "color", YAHOO.util.Dom.getStyle(clickEl, "color"));
        YAHOO.util.Dom.setStyle(dragEl, "backgroundColor", YAHOO.util.Dom.getStyle(clickEl, "backgroundColor"));
        YAHOO.util.Dom.setStyle(dragEl, "border", "2px solid gray");
    },

    endDrag: function(e) {

        var srcEl = this.getEl();
        var proxy = this.getDragEl();

        // Show the proxy element and animate it to the src element's location
        YAHOO.util.Dom.setStyle(proxy, "visibility", "");
        var a = new YAHOO.util.Motion( 
            proxy, { 
                points: { 
                    to: YAHOO.util.Dom.getXY(srcEl)
                }
            }, 
            0.2, 
            YAHOO.util.Easing.easeOut 
        )
        var proxyid = proxy.id;
        var thisid = this.id;

        // Hide the proxy and show the source element when finished with the animation
        a.onComplete.subscribe(function() {
                YAHOO.util.Dom.setStyle(proxyid, "visibility", "hidden");
                YAHOO.util.Dom.setStyle(thisid, "visibility", "");
            });
        a.animate();
    },

    onDragDrop: function(e, id) {

        // If there is one drop interaction, the li was dropped either on the list,
        // or it was dropped on the current location of the source element.
        if (YAHOO.util.DragDropMgr.interactionInfo.drop.length === 1) {

            // The position of the cursor at the time of the drop (YAHOO.util.Point)
            var pt = YAHOO.util.DragDropMgr.interactionInfo.point; 

            // The region occupied by the source element at the time of the drop
            var region = YAHOO.util.DragDropMgr.interactionInfo.sourceRegion; 

            // Check to see if we are over the source element's location.  We will
            // append to the bottom of the list once we are sure it was a drop in
            // the negative space (the area of the list without any list items)
            if (!region.intersect(pt)) {
                var destEl = YAHOO.util.Dom.get(id);
                var destDD = YAHOO.util.DragDropMgr.getDDById(id);
                destEl.appendChild(this.getEl());
                destDD.isEmpty = false;
                YAHOO.util.DragDropMgr.refreshCache();
            }

        }
    },

    onDrag: function(e) {

        // Keep track of the direction of the drag for use during onDragOver
        var y = YAHOO.util.Event.getPageY(e);

        if (y < this.lastY) {
            this.goingUp = true;
        } else if (y > this.lastY) {
            this.goingUp = false;
        }

        this.lastY = y;
    },

    onDragOver: function(e, id) {
    
        var srcEl = this.getEl();
        var destEl = YAHOO.util.Dom.get(id);

        // We are only concerned with list items, we ignore the dragover
        // notifications for the list.
        if (destEl.nodeName.toLowerCase() == "li") {
            var orig_p = srcEl.parentNode;
            var p = destEl.parentNode;

            if (this.goingUp) {
                p.insertBefore(srcEl, destEl); // insert above
            } else {
                p.insertBefore(srcEl, destEl.nextSibling); // insert below
            }

            YAHOO.util.DragDropMgr.refreshCache();
        }
    }
});


   function populateUsersLists(p_sType, p_aArgs, p_oItem) {
      rolId = p_oItem.value;
      rolName = p_oItem.cfg.getProperty("text");
      recoverDataUsersLists(rolId, rolName);
   }
   
   function recoverDataUsersLists(rolId, rolName) {
      rol_user_list.set("label", rolName);
      rol_user_list.value = rolId;

      emptyList("unassigned_users");
      emptyList("assigned_users");
      var transaction = YAHOO.util.Connect.asyncRequest('POST', "<?= npadmin_setting('NP-ADMIN', 'BASE_URL') ?>/ajax/rols.php", {success:userListCallback, argument:["unassigned_users"]}, "op=listUnassignedUsers&rol_id="+rolId);
      var transaction = YAHOO.util.Connect.asyncRequest('POST', "<?= npadmin_setting('NP-ADMIN', 'BASE_URL') ?>/ajax/rols.php", {success:userListCallback, argument:["assigned_users"]}, "op=listAssignedUsers&rol_id="+rolId);   
   }
   
   function userListCallback(response) {
      var listId = response.argument[0];
      
      usersList = document.getElementById(listId);
      
      data = YAHOO.lang.JSON.parse(response.responseText);

      for(id in data) {
         user = data[id];
         if (typeof(user) != "function") {
            var user_element = document.createElement('li');
            user_element.innerHTML = user.user;
            user_element.setAttribute("id", listId + "_" + user.userId);
            user_element.setAttribute("title", user.userId);
            user_element.className = "li_" + listId;
            usersList.appendChild(user_element);
            new DDList(listId + "_" + user.userId);
         }
      }   
   }
   
   function assignUsers() {
	   var rolName = rol_user_list.get("label");
	   var rolId = rol_user_list.value;
	   if (rolName != "Select rol") {
		   var parseList = function(listName) {
			   ul = YAHOO.util.Dom.get(listName)
				   var items = ul.getElementsByTagName("li");
			   var list = "";
			   for (i=0; i<items.length; i=i+1) {
				   list += items[i].title+ ",";
			   }
			   list = list.substring(0, list.length - 1);
			   return list;
		   };

		   var list = parseList("assigned_users");
		   var transaction = YAHOO.util.Connect.asyncRequest('POST', "<?= npadmin_setting('NP-ADMIN', 'BASE_URL') ?>/ajax/rols.php", {success:assignUsersCallback, argument:[rolId, rolName]}, "op=assignUsers&rol_id="+rolId+"&list="+list);
	   }
   }

   function assignUsersCallback(response) {
      var rolId = response.argument[0];
      var rolName = response.argument[1];
      box_info("user_users_info", "Rols configuration saved correctly!");
      recoverDataUsersLists(rolId, rolName);
   }  

   function populateGroupsLists(p_sType, p_aArgs, p_oItem) {
      rolId = p_oItem.value;
      rolName = p_oItem.cfg.getProperty("text");
      recoverDataGroupsLists(rolId, rolName);
   }

   function recoverDataGroupsLists(rolId, rolName) {
      rol_group_list.set("label", rolName);
      rol_group_list.value = rolId;

      emptyList("unassigned_groups");
      emptyList("assigned_groups");
      var transaction = YAHOO.util.Connect.asyncRequest('POST', "<?= npadmin_setting('NP-ADMIN', 'BASE_URL') ?>/ajax/rols.php", {success:groupListCallback, argument:["unassigned_groups"]}, "op=listUnassignedGroups&rol_id="+rolId);
      var transaction = YAHOO.util.Connect.asyncRequest('POST', "<?= npadmin_setting('NP-ADMIN', 'BASE_URL') ?>/ajax/rols.php", {success:groupListCallback, argument:["assigned_groups"]}, "op=listAssignedGroups&rol_id="+rolId);   
   }
   
   function groupListCallback(response) {
      var listId = response.argument[0];
      
      groupsList = document.getElementById(listId);
      
      data = YAHOO.lang.JSON.parse(response.responseText);

      for(id in data) {
         group = data[id];
         if (typeof(group) != "function") {
            var group_element = document.createElement('li');
            group_element.innerHTML = group.groupName;
            group_element.setAttribute("id", listId + "_" + group.groupId);
            group_element.setAttribute("title", group.groupId);
            group_element.className = "li_" + listId;
            groupsList.appendChild(group_element);
            new DDList(listId + "_" + group.groupId);
         }
      }   
   }
   
   function assignGroups() {
	   var rolName = rol_group_list.get("label");
	   var rolId = rol_group_list.value;
	   if (rolName != "Select rol") {
		   var parseList = function(listName) {
			   ul = YAHOO.util.Dom.get(listName)
				   var items = ul.getElementsByTagName("li");
			   var list = "";
			   for (i=0; i<items.length; i=i+1) {
				   list += items[i].title+ ",";
			   }
			   list = list.substring(0, list.length - 1);
			   return list;
		   };

		   var list = parseList("assigned_groups");
		   var transaction = YAHOO.util.Connect.asyncRequest('POST', "<?= npadmin_setting('NP-ADMIN', 'BASE_URL') ?>/ajax/rols.php", {success:assignGroupsCallback, argument:[rolId, rolName]}, "op=assignGroups&rol_id="+rolId+"&list="+list);
	   }
   }

   function assignGroupsCallback(response) {
      var rolId = response.argument[0];
      var rolName = response.argument[1];
      box_info("user_groups_info", "Rols configuration saved correctly!");
      recoverDataGroupsLists(rolId, rolName);
   }  
</script>

<?
}
?>
<? require_once($NPADMIN_PATH."include/header.php"); ?>

<div class="page_title"><?= $panelData->getTitle() ?></div>

<div id="mainTabs" class="yui-navset">
    <ul class="yui-nav">
        <li class="selected"><a href="#"><em>List of rols</em></a></li>
        <li><a href="#"><em>Rol's users</em></a></li>
        <li><a href="#"><em>Rol's groups</em></a></li>
    </ul>            
    <div class="yui-content">
        <div>
           <div class="buttonBox" id="rol_buttons"></div>
           <div id="rol_datatable"></div>
        </div>
        <div>  
           <div class="buttonBox" id="users_buttons"></div>
           Rol: <input type="button" id="rol_user_list" name="rol_user_list" value="Select rol"/>
           <select id="rol_user_list_select" name="rol_user_list_select"></select>
           <table id="users_form_table">
              <tr><td>
                 <h3>Unassigned users</h3>
                 <ul id="unassigned_users" class="draglist"></ul>
              </td><td>
                 <h3>Assigned users</h3>
                 <ul id="assigned_users" class="draglist"></ul>
              </td></tr>
           </table>
        </div>
         <div>  
           <div class="buttonBox" id="groups_buttons"></div>
           Rol: <input type="button" id="rol_group_list" name="rol_group_list" value="Select rol"/>
           <select id="rol_group_list_select" name="rol_group_list_select"></select>
           <table id="groups_form_table">
              <tr><td>
                 <h3>Unassigned groups</h3>
                 <ul id="unassigned_groups" class="draglist"></ul>
              </td><td>
                 <h3>Assigned groups</h3>
                 <ul id="assigned_groups" class="draglist"></ul>
              </td></tr>
           </table>
        </div>
    </div>
</div>

<div style="visibility: hidden; display:none">
  <div id="rol_form_table">
     <div class="bd">
        <form id="rol_form">
           <table >
              <tr><td>Rol name:</td><td><input type="text" name="rol_name"/></td><tr>
              <tr><td>Description:</td><td><input type="text" name="description"/></td><tr>
              <input type="hidden" name="op" value="add"/>
           </table>
        </form>
     </div>
  </div>
</div> 
        

<? require_once($NPADMIN_PATH."include/footer.php"); ?>
