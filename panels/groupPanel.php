<?
$NPADMIN_PATH = "../";
require_once($NPADMIN_PATH."include/common.php");
$panelData = npadmin_panel("groupPanel");
npadmin_security($panelData->getGroups());
?>

<?
function html_head() {
   global $NPADMIN_PATH;
?>

<style type="text/css">
#group_form_table td {
   padding: 3px;
}

#users_form_table {
   margin-top: 5px;
   margin-bottom: 5px;
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

.yui-button#saveUsersButton button {
   padding-left: 2em;
   background: url(<?= npadmin_setting('NP-ADMIN', 'BASE_URL') ?>/static/img/save.gif) 5% 50% no-repeat;
}
</style>

<script> 
   var tabView;
   var dataSource;
   var columnDefs;
   var addGroupDialog;
   var groupDatatable;
   var group_list;
    
   function changeTabEventHandler(e) {
   }
   
   YAHOO.util.Event.addListener(window, "load", function() {
      tabView = new YAHOO.widget.TabView('mainTabs');
      tabView.addListener('activeTabChange', changeTabEventHandler);
     
      columnDefs = [ 
         {key:"group_name", label:"Name", sortable:true},
         {key:"description", label:"Description", sortable:true},
	   ]; 
	        
	   dataSource = new YAHOO.util.DataSource("<?= npadmin_setting('NP-ADMIN', 'BASE_URL') ?>/ajax/groups.php?");
  	   dataSource.connMethodPost = true;
	   dataSource.responseType = YAHOO.util.DataSource.TYPE_JSON; 
      dataSource.connXhrMode = "queueRequests"; 
      dataSource.responseSchema = {
            fields: ["group_name","description"]
      };
      dataSource.doBeforeCallback = function(oRequest , oFullResponse , oParsedResponse) {
         group_list.getMenu().clearContent();
         group_list.set("label", "Select group");
         emptyList("unassigned_users");
         emptyList("assigned_users");
         
         for (id in oParsedResponse.results.reverse()) {
            var group = oParsedResponse.results[id];
            if (typeof(group) != "function")
               group_list.getMenu().addItem({ text: group.group_name, value: group.group_name, onclick: { fn: populateUsersLists } });
         }
         group_list.getMenu().render(document.body);
         return oParsedResponse;
      };

      groupDatatable = new YAHOO.widget.DataTable("group_datatable", columnDefs, dataSource, {initialRequest:"op=list"});
      groupDatatable.subscribe("rowMouseoverEvent", groupDatatable.onEventHighlightRow);
      groupDatatable.subscribe("rowMouseoutEvent", groupDatatable.onEventUnhighlightRow);
      groupDatatable.subscribe("rowClickEvent", groupDatatable.onEventSelectRow);
      var delGroupButton = new YAHOO.widget.Button({ 
            label:"Delete selected groups", 
            id:"delGroupButton", 
            container:"group_buttons" });
      delGroupButton.on("click", deleteGroups);
      var addGroupButton = new YAHOO.widget.Button({ 
            label:"Create new group...", 
            id:"addGroupButton", 
            container:"group_buttons" });
      addGroupButton.on("click", showNewGroupDialog);
      
      addGroupDialog = new YAHOO.widget.Dialog("group_form_table", { 
			   effect: {effect:YAHOO.widget.ContainerEffect.FADE, duration:0.25},
			   fixedcenter: true,
			   draggable: true,
			   constraintoviewport: true,
			   text: "Create new group",
			   modal: true,
			   close: false,
            buttons: [ 
               { text:"Cancel", handler:defaultButtonHandler },
	            { text:"Add", handler:addGroup, isDefault:true } 
	         ],
	         form: YAHOO.util.Dom.get("group_form")
			 });
	   addGroupDialog.setHeader("Add group");
			 
      var onContextMenuClick = function(p_sType, p_aArgs, p_myDataTable) {
         var task = p_aArgs[1];
         if(task) {
             var elRow = this.contextEventTarget;
             elRow = p_myDataTable.getTrEl(elRow);

             if(elRow) {
                 switch(task.index) {
                     case 0: 
                         var oRecord = p_myDataTable.getRecord(elRow);
                         box_question("groupdel_question", "Are you sure you want to delete the selected group?", function() {
                            this.hide(); 
                            deleteGroupsConfirm(oRecord.getData("group_name"));                         
                         });
                         break;
                     case 1:
                         var oRecord = p_myDataTable.getRecord(elRow);
                         var group = oRecord.getData("group_name");
                         recoverDataUsersLists(group);
                         tabView.set("activeTab",tabView.getTab(1));
                         break;                         
                 }
             }
         }
      };

      var contextMenu = new YAHOO.widget.ContextMenu("mycontextmenu", { trigger:groupDatatable.getTbodyEl() });
      contextMenu.addItems(["Delete Item", "Manage users"]);
      contextMenu.render("group_datatable");
      contextMenu.clickEvent.subscribe(onContextMenuClick, groupDatatable);
      
      group_list = new YAHOO.widget.Button("group_list", {
            type: "menu",  
            menu: "group_list_select"
      });    
      
      new YAHOO.util.DDTarget("unassigned_users");
      new YAHOO.util.DDTarget("assigned_users");
      var saveUsersButton = new YAHOO.widget.Button({ 
            label:"Save", 
            id:"saveUsersButton", 
            container:"users_buttons" });
      saveUsersButton.on("click", assignUsers);

   });
   
   function showNewGroupDialog() {
      addGroupDialog.render(document.body);
      addGroupDialog.show();
   }
   
   function addGroup() {
      var formObject = document.getElementById('group_form');
      if (formObject.group_name.value.trim().length == 0)
         box_block("groupadd_block", "All the required fields have to be filled");
      else {
         YAHOO.util.Connect.setForm(formObject); 
         var transaction = YAHOO.util.Connect.asyncRequest('POST', "<?= npadmin_setting('NP-ADMIN', 'BASE_URL') ?>/ajax/groups.php", {success:addGroupCallback});
      }
   }
   
   function addGroupCallback(response) {
      
      if (response.responseText.trim() == "OK") {
         addGroupDialog.hide();
         box_info("groupadd_result", "Group added correctly!");
        
         var formObject = document.getElementById('group_form');
         formObject.reset();

         var count = groupDatatable.getRecordSet().getLength();
         groupDatatable.deleteRows(0,count);

         dataSource.sendRequest("op=list", {success : groupDatatable.onDataReturnAppendRows, scope: groupDatatable})

         tabView.set('activeIndex', 0);
      } else {
         box_error("groupadd_result", response.responseText);
      }
   }
   
   function deleteGroups() {
      var l = groupDatatable.getSelectedRows().length;
      if (l > 0)
         box_question("groupdel_question", "Are you sure you want to delete the " + l + " selected groups?", deleteGroupsConfirm);
      else 
         box_warn("groupdel_warn", "No groups selected");
   }
     
   function deleteGroupsConfirm(list) {
      if (!YAHOO.lang.isString(list)) {
         var list = "";
         var rows = groupDatatable.getSelectedRows();
         for (var id in rows) {  
            var record = groupDatatable.getRecord(rows[id]);
            if (record != null)
               list += record.getData("group_name") + ",";
         }
         list = list.substring(0, list.length - 1);
         this.hide();
      }
     
      var postdata = "op=delete&list=" + list;
      var transaction = YAHOO.util.Connect.asyncRequest('POST', "<?= npadmin_setting('NP-ADMIN', 'BASE_URL') ?>/ajax/groups.php", {success:deleteGroupsCallback}, postdata);
   }
   
   function deleteGroupsCallback(response) {
      if (response.responseText.trim() == "OK") {
         var count = groupDatatable.getRecordSet().getLength();
         groupDatatable.deleteRows(0, count);

         dataSource.sendRequest("op=list", {success : groupDatatable.onDataReturnAppendRows, scope: groupDatatable})
      } else {
         box_error("groupdel_result", response.responseText);
      }
   }
   
</script>

<script type="text/javascript">
DDList = function(id, sGroup, config) {
    DDList.superclass.constructor.call(this, id, sGroup, config);

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
      group = p_oItem.cfg.getProperty("text");
      recoverDataUsersLists(group);
   }
   
   function recoverDataUsersLists(group) {
      group_list.set("label", group);

      emptyList("unassigned_users");
      emptyList("assigned_users");
      var transaction = YAHOO.util.Connect.asyncRequest('POST', "<?= npadmin_setting('NP-ADMIN', 'BASE_URL') ?>/ajax/groups.php", {success:userListCallback, argument:["unassigned_users"]}, "op=listUnassignedUsers&group_name="+group);
      var transaction = YAHOO.util.Connect.asyncRequest('POST', "<?= npadmin_setting('NP-ADMIN', 'BASE_URL') ?>/ajax/groups.php", {success:userListCallback, argument:["assigned_users"]}, "op=listAssignedUsers&group_name="+group);   
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
            user_element.setAttribute("id", listId + "_" + user.user);
            user_element.className = "li_" + listId;
            usersList.appendChild(user_element);
            new DDList(listId + "_" + user.user);
         }
      }   
   }
   
   function assignUsers() {
      var group = group_list.get("label");
      if (group != "Select group") {
         var parseList = function(listName) {
              ul = YAHOO.util.Dom.get(listName)
              var items = ul.getElementsByTagName("li");
              var list = "";
              for (i=0; i<items.length; i=i+1) {
                  list += items[i].innerHTML + ",";
              }
              list = list.substring(0, list.length - 1);
              return list;
          };

          var list = parseList("assigned_users");
          var transaction = YAHOO.util.Connect.asyncRequest('POST', "<?= npadmin_setting('NP-ADMIN', 'BASE_URL') ?>/ajax/groups.php", {success:assignUsersCallback, argument:[group]}, "op=assignUsers&group_name="+group+"&list="+list);
       }
   }

   function assignUsersCallback(response) {
      var group_name = response.argument[0];
      box_info("user_users_info", "Groups configuration saved correctly!");
      recoverDataUsersLists(group_name);
   }  
</script>

<?
}
?>
<? require_once($NPADMIN_PATH."include/header.php"); ?>

<div class="page_title"><?= $panelData->getTitle() ?></div>

<div id="mainTabs" class="yui-navset">
    <ul class="yui-nav">
        <li class="selected"><a href="#"><em>List of groups</em></a></li>
        <li><a href="#"><em>Group's users</em></a></li>
    </ul>            
    <div class="yui-content">
        <div>
           <div id="group_datatable"></div>
           <div id="group_buttons"></div>
        </div>
        <div>  
           Group: <input type="button" id="group_list" name="group_list" value="Select group"/>
           <select id="group_list_select" name="group_list_select"></select>
           <table id="users_form_table">
              <tr><td>
                 <h3>Unassigned users</h3>
                 <ul id="unassigned_users" class="draglist"></ul>
              </td><td>
                 <h3>Assigned users</h3>
                 <ul id="assigned_users" class="draglist"></ul>
              </td></tr>
           </table>
           <div id="users_buttons"/>
        </div>
    </div>
</div>

<div style="visibility: hidden; display:none">
  <div id="group_form_table">
     <div class="bd">
        <form id="group_form">
           <table >
              <tr><td>Group name:</td><td><input type="text" name="group_name"/></td><tr>
              <tr><td>Description:</td><td><input type="text" name="description"/></td><tr>
              <input type="hidden" name="op" value="add"/>
           </table>
        </form>
     </div>
  </div>
</div> 
        

<? require_once($NPADMIN_PATH."include/footer.php"); ?>
