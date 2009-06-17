<?
$NPADMIN_PATH = "../";
require_once($NPADMIN_PATH."include/common.php");
$panelData = npadmin_panel("panelPanel");
npadmin_security($panelData->getRols());
?>

<?
function html_head() {
   global $NPADMIN_PATH;
?>

<style type="text/css">
#panel_form_table td {
   padding: 3px;
}

#panel_datatable {
   margin-bottom: 10px;
}

.yui-button#delPanelButton button {
   padding-left: 2em;
   background: url(<?= npadmin_setting('NP-ADMIN', 'BASE_URL') ?>/static/img/del.gif) 5% 50% no-repeat;
}

.yui-button#addPanelButton button {
   padding-left: 2em;
   background: url(<?= npadmin_setting('NP-ADMIN', 'BASE_URL') ?>/static/img/add.gif) 5% 50% no-repeat;
}

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

li.li_unassigned_rols {
    background-color: #D1E6EC;
    border:1px solid #7EA6B2;
}

li.li_assigned_rols {
    background-color: #D8D4E2;
    border:1px solid #6B4C86;
}

.yui-button#saveRolsButton button {
   padding-left: 2em;
   background: url(<?= npadmin_setting('NP-ADMIN', 'BASE_URL') ?>/static/img/save.gif) 5% 50% no-repeat;
}
</style>

<script> 
   var tabView;
   var dataSource;
   var columnDefs;
   var addPanelDialog;
   var panelDatatable;
   var panel_list;
   
   function changeTabEventHandler(e) {
   }
   
   YAHOO.util.Event.addListener(window, "load", function() {
      tabView = new YAHOO.widget.TabView('mainTabs');
      tabView.addListener('activeTabChange', changeTabEventHandler);
     
      columnDefs = [ 
         {key:"id", label:"ID", sortable:true},
         {key:"title", label:"Title", editor:"textbox", sortable:true}
	   ]; 
	        
      dataSource = new YAHOO.util.DataSource("<?= npadmin_setting('NP-ADMIN', 'BASE_URL') ?>/ajax/panels.php?");
      dataSource.connMethodPost = true;
      dataSource.responseType = YAHOO.util.DataSource.TYPE_JSON; 
      dataSource.connXhrMode = "queueRequests"; 
      dataSource.responseSchema = {
    		resultsList: "Results",
            fields: ["id","title","rols"]
      };
      dataSource.doBeforeCallback = function(oRequest , oFullResponse , oParsedResponse) {
         panel_list.getMenu().clearContent();
         panel_list.set("label", "Select panel");
         emptyList("unassigned_rols");
         emptyList("assigned_rols");
         
         for (id in oParsedResponse.results) {
            var panel = oParsedResponse.results[id];
            if (typeof(panel) != "function")
               panel_list.getMenu().addItem({ text: panel.title, value: panel.id, onclick: { fn: populateRolsLists } });
         }
         panel_list.getMenu().render(document.body);
         return oParsedResponse;
      };

      panelDatatable = new YAHOO.widget.DataTable("panel_datatable", columnDefs, dataSource, {initialRequest:"op=list"});
      panelDatatable.subscribe("rowMouseoverEvent", panelDatatable.onEventHighlightRow);
      panelDatatable.subscribe("rowMouseoutEvent", panelDatatable.onEventUnhighlightRow);
      panelDatatable.subscribe("rowClickEvent", panelDatatable.onEventSelectRow);
      panelDatatable.subscribe("cellMouseoutEvent", panelDatatable.onEventUnhighlightCell);
      panelDatatable.subscribe("cellClickEvent", panelDatatable.onEventShowCellEditor);
      var delPanelButton = new YAHOO.widget.Button({ 
            label:"Delete selected panels", 
            id:"delPanelButton", 
            container:"panel_buttons" });
      delPanelButton.on("click", deletePanels);
      var addPanelButton = new YAHOO.widget.Button({ 
            label:"Create new panel...", 
            id:"addPanelButton", 
            container:"panel_buttons" });
      addPanelButton.on("click", showNewPanelDialog);
      
      addPanelDialog = new YAHOO.widget.Dialog("panel_form_table", { 
			   effect: {effect:YAHOO.widget.ContainerEffect.FADE, duration:0.25},
			   fixedcenter: true,
			   draggable: true,
			   constraintoviewport: true,
			   text: "Create new panel",
			   modal: true,
			   close: false,
            buttons: [ 
               { text:"Cancel", handler:defaultButtonHandler },
	            { text:"Add", handler:addPanel, isDefault:true } 
	         ],
	         form: YAHOO.util.Dom.get("panel_form")
			 });
	   addPanelDialog.setHeader("Add panel");
			 
      var onContextMenuClick = function(p_sType, p_aArgs, p_myDataTable) {
         var task = p_aArgs[1];
         if(task) {
             var elRow = this.contextEventTarget;
             elRow = p_myDataTable.getTrEl(elRow);

             if(elRow) {
                 switch(task.index) {
                     case 0: 
                         var oRecord = p_myDataTable.getRecord(elRow);
                         box_question("paneldel_question", "Are you sure you want to delete the selected panel?", function() {
                            this.hide(); 
                            deletePanelsConfirm(oRecord.getData("id"));                         
                         });
                         break;
                     case 1:
                         var oRecord = p_myDataTable.getRecord(elRow);
                         var panel = oRecord.getData("id");
                         var panel_title = oRecord.getData("title");
                         recoverDataRolsLists(panel, panel_title);
                         tabView.set("activeTab",tabView.getTab(1));
                         break;
                 }
             }
         }
      };

      var contextMenu = new YAHOO.widget.ContextMenu("mycontextmenu", { trigger:panelDatatable.getTbodyEl() });
      contextMenu.addItems(["Delete Item", "Manage rols"]);
      contextMenu.render("panel_datatable");
      contextMenu.clickEvent.subscribe(onContextMenuClick, panelDatatable);
      
      panel_list = new YAHOO.widget.Button("panel_list", {
            type: "menu",  
            menu: "panel_list_select"
      });    
      
      new YAHOO.util.DDTarget("unassigned_rols");
      new YAHOO.util.DDTarget("assigned_rols");
      var saveRolsButton = new YAHOO.widget.Button({ 
            label:"Save", 
            id:"saveRolsButton", 
            container:"rols_buttons" });
      saveRolsButton.on("click", assignRols);
   });
   
   function showNewPanelDialog() {
      addPanelDialog.render(document.body);
      addPanelDialog.show();
   }
   
   function addPanel() {
      var formObject = document.getElementById('panel_form');
      if (formObject.id.value.trim().length == 0)
         box_block("paneladd_block", "All the required fields have to be filled");
      else {
         YAHOO.util.Connect.setForm(formObject); 
         var transaction = YAHOO.util.Connect.asyncRequest('POST', "<?= npadmin_setting('NP-ADMIN', 'BASE_URL') ?>/ajax/panels.php", {success:addPanelCallback});
      }
   }
   
   function addPanelCallback(response) {
      
      if (response.responseText.trim() == "OK") {
         addPanelDialog.hide();
         box_info("paneladd_result", "Panel added correctly!");
        
         var formObject = document.getElementById('panel_form');
         formObject.reset();

         var count = panelDatatable.getRecordSet().getLength();
         panelDatatable.deleteRows(0,count);

         dataSource.sendRequest("op=list", {success : panelDatatable.onDataReturnAppendRows, scope: panelDatatable})

         tabView.set('activeIndex', 0);
      } else {
         box_error("paneladd_result", response.responseText);
      }
   }
   
   function deletePanels() {
      var l = panelDatatable.getSelectedRows().length;
      if (l > 0)
         box_question("paneldel_question", "Are you sure you want to delete the " + l + " selected panels?", deletePanelsConfirm);
      else 
         box_warn("paneldel_warn", "No panels selected");
   }
     
   function deletePanelsConfirm(list) {
      if (YAHOO.lang.isObject(list)) {
         var list = "";
         var rows = panelDatatable.getSelectedRows();
         for (var id in rows) {  
            var record = panelDatatable.getRecord(rows[id]);
            if (record != null)
               list += record.getData("id") + ",";
         }
         list = list.substring(0, list.length - 1);
         this.hide();
      }
     
      var postdata = "op=delete&list=" + list;
      var transaction = YAHOO.util.Connect.asyncRequest('POST', "<?= npadmin_setting('NP-ADMIN', 'BASE_URL') ?>/ajax/panels.php", {success:deletePanelsCallback}, postdata);
   }
   
   function deletePanelsCallback(response) {
      if (response.responseText.trim() == "OK") {
         var count = panelDatatable.getRecordSet().getLength();
         panelDatatable.deleteRows(0, count);

         dataSource.sendRequest("op=list", {success : panelDatatable.onDataReturnAppendRows, scope: panelDatatable})
      } else {
         box_error("paneldel_result", response.responseText);
      }
   }
   
   function populateRolsLists(p_sType, p_aArgs, p_oItem) {
      if (p_oItem != null) {
         panel_id = p_oItem.value;
         panel_text = p_oItem.cfg.getProperty("text");
      } else {
         panel_id = p_sType;
         panel_text = p_aArgs;
      }
      recoverDataRolsLists(panel_id, panel_text);
   }
   
   function recoverDataRolsLists(panel_id, panel_text) {
      for (itemIdx in panel_list.getMenu().getItems()) {
         var item = panel_list.getMenu().getItem(parseInt(itemIdx));
         if (item.value == panel_id) {
            panel_list.getMenu().activeItem = item 
            break;
         }
      }
      panel_list.set("label", panel_text);

      emptyList("unassigned_rols");
      emptyList("assigned_rols");
      var transaction = YAHOO.util.Connect.asyncRequest('POST', "<?= npadmin_setting('NP-ADMIN', 'BASE_URL') ?>/ajax/panels.php", {success:rolListCallback, argument:["unassigned_rols"]}, "op=listUnassignedRols&panel="+panel_id);
      var transaction = YAHOO.util.Connect.asyncRequest('POST', "<?= npadmin_setting('NP-ADMIN', 'BASE_URL') ?>/ajax/panels.php", {success:rolListCallback, argument:["assigned_rols"]}, "op=listAssignedRols&panel="+panel_id);   
   }
   
   function rolListCallback(response) {
      var listId = response.argument[0];
      
      rolsList = document.getElementById(listId);
      
      data = YAHOO.lang.JSON.parse(response.responseText);

      for(id in data) {
         rol = data[id];
         if (typeof(rol) != "function") {
            var rol_element = document.createElement('li');
            rol_element.innerHTML = rol.rolName;
            rol_element.setAttribute("id", listId + "_" + rol.rolId);
            rol_element.setAttribute("title", rol.rolId);
            rol_element.className = "li_" + listId;
            rolsList.appendChild(rol_element);
            new DDList(listId + "_" + rol.rolId);
         }
      }   
   }
   
   function assignRols() {
      if (panel_list.getMenu().activeItem != null) {
         var panel = panel_list.getMenu().activeItem.value;

         var parseList = function(listName) {
              ul = YAHOO.util.Dom.get(listName)
              var items = ul.getElementsByTagName("li");
              var list = "";
              for (i=0; i<items.length; i=i+1) {
		      //list += items[i].innerHTML + ",";
		      list += items[i].title + ",";
              }
              list = list.substring(0, list.length - 1);
              return list;
          };

          var list = parseList("assigned_rols");
          var transaction = YAHOO.util.Connect.asyncRequest('POST', "<?= npadmin_setting('NP-ADMIN', 'BASE_URL') ?>/ajax/panels.php", {success:assignRolsCallback, argument:[panel]}, "op=assignRols&panel="+panel+"&list="+list);
       }
   }

   function assignRolsCallback(response) {
      var panel = response.argument[0];
      box_info("panel_rols_info", "Rols configuration saved correctly!");
      recoverDataRolsLists(panel);
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
</script>

<?
}
?>
<? require_once($NPADMIN_PATH."include/header.php"); ?>

<div class="page_title"><?= $panelData->getTitle() ?></div>

<div id="mainTabs" class="yui-navset">
    <ul class="yui-nav">
        <li class="selected"><a href="#"><em>List of panels</em></a></li>
        <li><a href="#"><em>Panels' rols</em></a></li>
    </ul>            
    <div class="yui-content">
        <div>
           <div class="buttonBox" id="panel_buttons"></div>
           <div id="panel_datatable"></div>
        </div>
        <div>  
           <div class="buttonBox" id="rols_buttons"></div>
           Panel: <input type="button" id="panel_list" name="panel_list" value="Select panel"/>
           <select id="panel_list_select" name="panel_list_select"></select>
           <table id="rols_form_table">
              <tr><td>
                 <h3>Unassigned rols</h3>
                 <ul id="unassigned_rols" class="draglist"></ul>
              </td><td>
                 <h3>Assigned rols</h3>
                 <ul id="assigned_rols" class="draglist"></ul>
              </td></tr>
           </table>
        </div>
    </div>
</div>

<div style="visibility: hidden; display:none">
  <div id="panel_form_table">
     <div class="bd">
        <form id="panel_form">
           <table >
              <tr><td>ID:</td><td><input type="text" name="id"/></td><tr>
              <tr><td>Title:</td><td><input type="text" name="title"/></td><tr>
              <input type="hidden" name="op" value="add"/>
           </table>
        </form>
     </div>
  </div>
</div> 
        

<? require_once($NPADMIN_PATH."include/footer.php"); ?>
