<?
$PWD = "../";
require_once($PWD."include/common.php");
$panelData = npadmin_panel("panelPanel");
npadmin_security($panelData->getGroups());
?>

<?
function html_head() {
   global $PWD;
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
</style>

<script> 
   var tabView;
   var dataSource;
   var columnDefs;
   var addPanelDialog;
   var panelDatatable;
    
   function changeTabEventHandler(e) {
   }
   
   YAHOO.util.Event.addListener(window, "load", function() {
      tabView = new YAHOO.widget.TabView('mainTabs');
      tabView.addListener('activeTabChange', changeTabEventHandler);
     
      columnDefs = [ 
         {key:"id", label:"ID", sortable:true},
         {key:"title", label:"Title", editor:"textbox", sortable:true},
         {key:"groups", label:"Groups"}
	   ]; 
	        
	   dataSource = new YAHOO.util.DataSource("<?= npadmin_setting('NP-ADMIN', 'BASE_URL') ?>/ajax/panels.php?");
  	   dataSource.connMethodPost = true;
	   dataSource.responseType = YAHOO.util.DataSource.TYPE_JSON; 
      dataSource.connXhrMode = "queueRequests"; 
      dataSource.responseSchema = {
            fields: ["id","title","groups"]
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
                 }
             }
         }
      };

      var contextMenu = new YAHOO.widget.ContextMenu("mycontextmenu", { trigger:panelDatatable.getTbodyEl() });
      contextMenu.addItems(["Delete Item"]);
      contextMenu.render("panel_datatable");
      contextMenu.clickEvent.subscribe(onContextMenuClick, panelDatatable);

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
      if (!YAHOO.lang.isString(list)) {
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
   
</script>
<?
}
?>
<? require_once($PWD."include/header.php"); ?>

<div class="page_title"><?= $panelData->getTitle() ?></div>

<div id="mainTabs" class="yui-navset">
    <ul class="yui-nav">
        <li class="selected"><a href="#"><em>List of panels</em></a></li>
    </ul>            
    <div class="yui-content">
        <div>
           <div id="panel_datatable"></div>
           <div id="panel_buttons"/>
        </div>
    </div>
</div>

<div style="visibility: hidden; display:none">
  <div id="panel_form_table">
     <div class="bd">
        <form id="panel_form">
           <table >
              <tr><td>ID:</td><td><input type="text" name="id"</td><tr>
              <tr><td>Title:</td><td><input type="text" name="title"</td><tr>
              <input type="hidden" name="op" value="add"/>
           </table>
        </form>
     </div>
  </div>
</div> 
        

<? require_once($PWD."include/footer.php"); ?>
