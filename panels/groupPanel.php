<?
$PWD = "../";
require_once($PWD."include/common.php");

if (!npadmin_userAllowed()) {
   if (npadmin_loginData() == null)
      redirect ($PWD."login.php?referer=".$_SERVER["PHP_SELF"]);
   else
      echo "User unauthorized!";
}
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
</style>

<script> 
   var tabView;
   var dataSource;
   var columnDefs;
   var groupDatatable;
    
   function changeTabEventHandler(e) {
   }
   
   YAHOO.util.Event.addListener(window, "load", function() {
      tabView = new YAHOO.widget.TabView('mainTabs');
      tabView.addListener('activeTabChange', changeTabEventHandler);
     
      columnDefs = [ 
         {key:"group_name", label:"Name", sortable:true},
         {key:"description", label:"Description", sortable:true},
	   ]; 
	        
	   dataSource = new YAHOO.util.DataSource("<?= $PWD ?>ajax/groups.php?");
	   dataSource.responseType = YAHOO.util.DataSource.TYPE_JSON; 
      dataSource.connXhrMode = "queueRequests"; 
      dataSource.responseSchema = {
            fields: ["group_name","description"]
      };

      groupDatatable = new YAHOO.widget.DataTable("group_datatable", columnDefs, dataSource, {initialRequest:"op=list"});
      groupDatatable.subscribe("rowMouseoverEvent", groupDatatable.onEventHighlightRow);
      groupDatatable.subscribe("rowMouseoutEvent", groupDatatable.onEventUnhighlightRow);
      groupDatatable.subscribe("rowClickEvent", groupDatatable.onEventSelectRow);

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
                 }
             }
         }
      };

      var contextMenu = new YAHOO.widget.ContextMenu("mycontextmenu", { trigger:groupDatatable.getTbodyEl() });
      contextMenu.addItems(["Delete Item"]);
      contextMenu.render("group_datatable");
      contextMenu.clickEvent.subscribe(onContextMenuClick, groupDatatable);

   });
   
   function addGroup() {
      var formObject = document.getElementById('group_form');
      if (formObject.group_name.value.trim().length == 0)
         box_block("groupadd_block", "All the required fields have to be filled");
      else {
         YAHOO.util.Connect.setForm(formObject); 
         var transaction = YAHOO.util.Connect.asyncRequest('POST', "<?= $PWD ?>ajax/groups.php", {success:addGroupCallback});
      }
   }
   
   function addGroupCallback(response) {
      
      if (response.responseText.trim() == "OK") {
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
            list += record.getData("group_name") + ",";
         }
         list = list.substring(0, list.length - 1);
         this.hide();
      }
     
      var postdata = "op=delete&list=" + list;
      var transaction = YAHOO.util.Connect.asyncRequest('POST', "<?= $PWD ?>ajax/groups.php", {success:deleteGroupsCallback}, postdata);
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
<?
}
?>
<? require_once($PWD."include/header.php"); ?>

<div class="page_title">Group administration</div>

<div id="mainTabs" class="yui-navset">
    <ul class="yui-nav">
        <li class="selected"><a href="#tab1"><em>List of groups</em></a></li>
        <li><a href="#tab2"><em>Add new group</em></a></li>
    </ul>            
    <div class="yui-content">
        <div>
           <div id="group_datatable"></div>
           <input type="button" value="Delete selected" onclick="javascript:deleteGroups()"/>
        </div>
        <div>
           <table id="group_form_table">
              <form id="group_form">
                 <tr><td>Group name:</td><td><input type="text" name="group_name"</td><tr>
                 <tr><td>Description:</td><td><input type="text" name="description"</td><tr>
                 <input type="hidden" name="op" value="add"/>
              </form>
              <tr><td colspan="2"><input type="button" value="Create group" onclick="javascript:addGroup()"/></td></tr>
           </table>
        </div>
    </div>
</div>

<? require_once($PWD."include/footer.php"); ?>
