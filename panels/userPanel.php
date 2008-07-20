<?
$PWD = "../";
require_once($PWD."include/common.php");

if (!npadmin_userallowed()) {
   if (npadmin_logindata() == null)
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

#user_form_table td {
   padding: 3px;
}

#user_datatable {
   margin-bottom: 10px;
}
</style>

<script> 
   var tabView;
   var dataSource;
   var columnDefs;
   var userDatatable;
    
   function changeTabEventHandler(e) {
   }
   
   YAHOO.util.Event.addListener(window, "load", function() {
      tabView = new YAHOO.widget.TabView('mainTabs');
      tabView.addListener('activeTabChange', changeTabEventHandler);
     
      columnDefs = [ 
         {key:"user", label:"User", sortable:true},
         {key:"creation_date", label:"Creation date", formatter:YAHOO.widget.DataTable.formatDate, sortable:true, sortOptions:{defaultDir:YAHOO.widget.DataTable.CLASS_DESC}},
         {key:"email", sortable:true},
         {key:"real_name", label:"Real name", sortable:true},
	   ]; 
	        
	   dataSource = new YAHOO.util.DataSource("<?= $PWD ?>ajax/users.php?");
	   dataSource.responseType = YAHOO.util.DataSource.TYPE_JSON; 
      dataSource.connXhrMode = "queueRequests"; 
      dataSource.responseSchema = {
            fields: ["user","creation_date","email","real_name"]
      };

      userDatatable = new YAHOO.widget.DataTable("user_datatable", columnDefs, dataSource, {initialRequest:"op=list"});
      userDatatable.subscribe("rowMouseoverEvent", userDatatable.onEventHighlightRow);
      userDatatable.subscribe("rowMouseoutEvent", userDatatable.onEventUnhighlightRow);
      userDatatable.subscribe("rowClickEvent", userDatatable.onEventSelectRow);

      var onContextMenuClick = function(p_sType, p_aArgs, p_myDataTable) {
         var task = p_aArgs[1];
         if(task) {
             var elRow = this.contextEventTarget;
             elRow = p_myDataTable.getTrEl(elRow);

             if(elRow) {
                 switch(task.index) {
                     case 0: 
                         var oRecord = p_myDataTable.getRecord(elRow);
                         box_question("userdel_question", "Are you sure you want to delete the selected user?", function() {
                            deleteUsersConfirm(oRecord.getData("user"));
                            this.hide(); 
                         });
                 }
             }
         }
      };

      var contextMenu = new YAHOO.widget.ContextMenu("mycontextmenu", { trigger:userDatatable.getTbodyEl() });
      contextMenu.addItems(["Delete Item"]);
      contextMenu.render("user_datatable");
      contextMenu.clickEvent.subscribe(onContextMenuClick, userDatatable);

   });
   
   function addUser() {
      var formObject = document.getElementById('user_form');
      if (formObject.user.value.trim().length == 0 || formObject.password.value.trim().length == 0)
         box_block("useradd_block", "All the required fields have to be filled");
      else {
         YAHOO.util.Connect.setForm(formObject); 
         var transaction = YAHOO.util.Connect.asyncRequest('POST', "<?= $PWD ?>ajax/users.php", {success:addUserCallback});
      }
   }
   
   function addUserCallback(response) {
      
      if (response.responseText.trim() == "OK") {
         box_info("useradd_result", "User added correctly!");
        
         var formObject = document.getElementById('user_form');
         formObject.reset();

         var count = userDatatable.getRecordSet().getLength();
         userDatatable.deleteRows(0,count);

         dataSource.sendRequest("op=list", {success : userDatatable.onDataReturnAppendRows, scope: userDatatable})

         tabView.set('activeIndex', 0);
      } else {
         box_error("useradd_result", response.responseText);
      }
   }
   
   function deleteUsers() {
      var l = userDatatable.getSelectedRows().length;
      if (l > 0)
         box_question("userdel_question", "Are you sure you want to delete the " + l + " selected users?", deleteUsersConfirm);
      else 
         box_warn("userdel_warn", "No users selected");
   }
     
   function deleteUsersConfirm(list) {
      if (list == null) { 
         var list = "";
         var rows = userDatatable.getSelectedRows();
         for (var id in rows) {  
            var record = userDatatable.getRecord(rows[id]);
            list += record.getData("user") + ",";
         }
         list = list.substring(0, list.length - 1);
         this.hide();
      }
     

      var postdata = "op=delete&list=" + list;
      var transaction = YAHOO.util.Connect.asyncRequest('POST', "<?= $PWD ?>ajax/users.php", {success:deleteUsersCallback}, postdata);
   }
   
   function deleteUsersCallback(response) {
      if (response.responseText.trim() == "OK") {
         var count = userDatatable.getRecordSet().getLength();
         userDatatable.deleteRows(0, count);

         dataSource.sendRequest("op=list", {success : userDatatable.onDataReturnAppendRows, scope: userDatatable})
      } else {
         box_error("userdel_result", response.responseText);
      }
   }
   
</script>
<?
}
?>
<? require_once($PWD."include/header.php"); ?>

<div class="page_title">User administration</div>

<div id="mainTabs" class="yui-navset">
    <ul class="yui-nav">
        <li class="selected"><a href="#tab1"><em>List of users</em></a></li>
        <li><a href="#tab2"><em>Add new user</em></a></li>
        <li><a href="#tab3"><em>User's groups</em></a></li>
    </ul>            
    <div class="yui-content">
        <div>
           <div id="user_datatable"></div>
           <input type="button" value="Delete selected" onclick="javascript:deleteUsers()"/>
        </div>
        <div>
           <table id="user_form_table">
              <form id="user_form">
                 <tr><td>User name:</td><td><input type="text" name="user"</td><tr>
                 <tr><td>Password:</td><td><input type="password" name="password"</td><tr>
                 <tr><td>Real Name:</td><td><input type="text" name="real_name"</td><tr>
                 <tr><td>Email:</td><td><input type="text" name="email"</td><tr>
                 <input type="hidden" name="op" value="add"/>
              </form>
              <tr><td colspan="2"><input type="button" value="Create user" onclick="javascript:addUser()"/></td></tr>
           </table>
        </div>
    </div>
</div>

<? require_once($PWD."include/footer.php"); ?>
