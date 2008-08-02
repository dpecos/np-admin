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

#setting_form_table td {
   padding: 3px;
}

#settings_datatable {
   margin-bottom: 10px;
}

.yui-button#delSettingButton button {
   padding-left: 2em;
   background: url(<?= npadmin_setting('BASE_URL') ?>/static/img/del.gif) 5% 50% no-repeat;
}

.yui-button#addSettingButton button {
   padding-left: 2em;
   background: url(<?= npadmin_setting('BASE_URL') ?>/static/img/add.gif) 5% 50% no-repeat;
}
</style>

<script> 
   var tabView;
   var dataSource;
   var columnDefs;
   var addSettingDialog
   var settingsDatatable;
    
   function changeTabEventHandler(e) {
   }
   
   YAHOO.util.Event.addListener(window, "load", function() {
      YAHOO.example.InlineCellEditing = new function() {
      
         tabView = new YAHOO.widget.TabView('mainTabs');
         
         columnDefs = [
            {key:"name", label:"Name", sortable: true},
            {key:"value", editor:"textbox", label: "Value"}
         ];

         dataSource = new YAHOO.util.DataSource("<?= npadmin_setting('BASE_URL') ?>/ajax/settings.php?");
         dataSource.connMethodPost = true;
         dataSource.responseType = YAHOO.util.DataSource.TYPE_JSON; 
         dataSource.connXhrMode = "queueRequests"; 
         dataSource.responseSchema = {
            fields: ["name","value"]
         };

         settingsDatatable = new YAHOO.widget.DataTable("settings_datatable", columnDefs, dataSource, {initialRequest:"op=list"});
         settingsDatatable.subscribe("rowMouseoverEvent", settingsDatatable.onEventHighlightRow);
         settingsDatatable.subscribe("rowMouseoutEvent", settingsDatatable.onEventUnhighlightRow);
         settingsDatatable.subscribe("rowClickEvent", settingsDatatable.onEventSelectRow);
         settingsDatatable.subscribe("cellMouseoutEvent", settingsDatatable.onEventUnhighlightCell);
         settingsDatatable.subscribe("cellClickEvent", settingsDatatable.onEventShowCellEditor);
         /*settingsDatatable.subscribe("editorUpdateEvent", function(oArgs) {
            if(oArgs.editor.column.key === "active") {
                this.saveCellEditor();
            }
         });
         settingsDatatable.subscribe("editorBlurEvent", function(oArgs) {
            this.cancelCellEditor();
         });*/
         // When cell is edited, pulse the color of the row yellow
         var onCellEdit = function(oArgs) {
            var elCell = oArgs.editor.cell;
            var elRow = settingsDatatable.getTrEl(elCell);
            var oOldValue = oArgs.oldData;
            var oNewValue = oArgs.newData;
            
            var oRecord = settingsDatatable.getRecord(elRow);
            var name = oRecord.getData("name");

            var postdata = "op=update&name=" + name + "&value=" + oNewValue;
            var transaction = YAHOO.util.Connect.asyncRequest('POST', "<?= npadmin_setting('BASE_URL') ?>/ajax/settings.php", {success:updateSettingsCallback}, postdata);

            // Grab the row el and the 2 colors

            var origColor = YAHOO.util.Dom.getStyle(elRow.cells[0], "backgroundColor");
            var pulseColor = "#ff0";

            // Create a temp anim instance that nulls out when anim is complete
            var rowColorAnim = new YAHOO.util.ColorAnim(elRow.cells, {
                    backgroundColor:{to:origColor, from:pulseColor}, duration:2});
            var onComplete = function() {
                rowColorAnim = null;
                YAHOO.util.Dom.setStyle(elRow.cells, "backgroundColor", "");
            }
            rowColorAnim.onComplete.subscribe(onComplete);
            rowColorAnim.animate();
         }
         settingsDatatable.subscribe("editorSaveEvent", onCellEdit);


         var delSettingButton = new YAHOO.widget.Button({ 
               label:"Delete selected settings", 
               id:"delSettingButton", 
               container:"setting_buttons" });
         delSettingButton.on("click", deleteSettings);
         var addSettingButton = new YAHOO.widget.Button({ 
               label:"Create new setting...", 
               id:"addSettingButton", 
               container:"setting_buttons" });
         addSettingButton.on("click", showNewSettingDialog);
         
         addSettingDialog = new YAHOO.widget.Dialog("setting_form_table", { 
			   effect: {effect:YAHOO.widget.ContainerEffect.FADE, duration:0.25},
			   fixedcenter: true,
			   draggable: true,
			   constraintoviewport: true,
			   text: "Create new setting",
			   modal: true,
			   close: false,
            buttons: [ 
               { text:"Cancel", handler:defaultButtonHandler },
	            { text:"Add", handler:addSetting, isDefault:true } 
	         ],
	         form: YAHOO.util.Dom.get("setting_form")
			});
	      addSettingDialog.setHeader("Add setting");
	      
	      var onContextMenuClick = function(p_sType, p_aArgs, p_myDataTable) {
            var task = p_aArgs[1];
            if(task) {
                var elRow = this.contextEventTarget;
                elRow = p_myDataTable.getTrEl(elRow);

                if(elRow) {
                    switch(task.index) {
                        case 0: 
                            var oRecord = p_myDataTable.getRecord(elRow);
                            box_question("settingdel_question", "Are you sure you want to delete the selected setting?", function() {
                               this.hide(); 
                               deleteSettingsConfirm(oRecord.getData("name"));                         
                            });
                            break;
                    }
                }
            }
         };

         var contextMenu = new YAHOO.widget.ContextMenu("mycontextmenu", { trigger:settingsDatatable.getTbodyEl() });
         contextMenu.addItems(["Delete Setting"]);
         contextMenu.render("settings_datatable");
         contextMenu.clickEvent.subscribe(onContextMenuClick, settingsDatatable);
      };
   });   
      
   function showNewSettingDialog() {
      addSettingDialog.render(document.body);
      addSettingDialog.show();
   }
   
   function addSetting() {
      var formObject = document.getElementById('setting_form');
      if (formObject.name.value.trim().length == 0)
         box_block("settingadd_block", "All the required fields have to be filled");
      else {
         YAHOO.util.Connect.setForm(formObject); 
         var transaction = YAHOO.util.Connect.asyncRequest('POST', "<?= npadmin_setting('BASE_URL') ?>/ajax/settings.php", {success:addSettingCallback});
      }
   }
   
   function addSettingCallback(response) {
      
      if (response.responseText.trim() == "OK") {
         addSettingDialog.hide();
         box_info("settingadd_result", "Setting added correctly!");
        
         var formObject = document.getElementById('setting_form');
         formObject.reset();

         var count = settingsDatatable.getRecordSet().getLength();
         settingsDatatable.deleteRows(0,count);

         dataSource.sendRequest("op=list", {success : settingsDatatable.onDataReturnAppendRows, scope: settingsDatatable})

         tabView.set('activeIndex', 0);
      } else {
         box_error("settingadd_result", response.responseText);
      }
   }
   
   function updateSettingsCallback(response) {
      if (response.responseText.trim() == "OK") {
         /*var count = settingsDatatable.getRecordSet().getLength();
         settingsDatatable.deleteRows(0, count);

         dataSource.sendRequest("op=list", {success : settingsDatatable.onDataReturnAppendRows, scope: settingsDatatable})*/
      } else {
         box_error("settingupdate_result", response.responseText);
      }
   }
   
   function deleteSettings() {
      var l = settingsDatatable.getSelectedRows().length;
      if (l > 0)
         box_question("settingdel_question", "Are you sure you want to delete the " + l + " selected settings?", deleteSettingsConfirm);
      else 
         box_warn("settingdel_warn", "No settings selected");
   }
     
   function deleteSettingsConfirm(list) {
      if (!YAHOO.lang.isString(list)) {
         var list = "";
         var rows = settingsDatatable.getSelectedRows();
         for (var id in rows) {  
            var record = settingsDatatable.getRecord(rows[id]);
            list += record.getData("name") + ",";
         }
         list = list.substring(0, list.length - 1);
         this.hide();
      }
     
      var postdata = "op=delete&list=" + list;
      var transaction = YAHOO.util.Connect.asyncRequest('POST', "<?= npadmin_setting('BASE_URL') ?>/ajax/settings.php", {success:deleteSettingsCallback}, postdata);
   }
   
   function deleteSettingsCallback(response) {
      if (response.responseText.trim() == "OK") {
         var count = settingsDatatable.getRecordSet().getLength();
         settingsDatatable.deleteRows(0, count);

         dataSource.sendRequest("op=list", {success : settingsDatatable.onDataReturnAppendRows, scope: settingsDatatable})
      } else {
         box_error("settingdel_result", response.responseText);
      }
   }
</script>
<?
}
?>
<? require_once($PWD."include/header.php"); ?>

<div class="page_title">Settings administration</div>

<div id="mainTabs" class="yui-navset">
    <ul class="yui-nav">
        <li class="selected"><a href="#tab1"><em>Settings</em></a></li>
    </ul>            
    <div class="yui-content">
        <div>
           <div id="settings_datatable"></div>
           <div id="setting_buttons"/>
        </div>
    </div>
</div>    

<div style="visibility: hidden; display:none">
  <div id="setting_form_table">
     <div class="bd">
        <form id="setting_form">
           <table >
              <tr><td>Name:</td><td><input type="text" name="name"</td><tr>
              <tr><td>Value:</td><td><input type="text" name="value"</td><tr>
              <input type="hidden" name="op" value="add"/>
           </table>
        </form>
     </div>
  </div>
</div>     

<? require_once($PWD."include/footer.php"); ?>
