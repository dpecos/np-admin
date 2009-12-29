<?
$NPADMIN_PATH = "../../";
require_once($NPADMIN_PATH."private/include/common.php");

$panelData = npadmin_panel("settingsPanel");
npadmin_security($panelData->getRols());
?>

<?
$npyui_setting = new NP_YUI("Setting", array("ajaxURL" => "../ajax/settings.php", "idFields" => "type, name"));
$npyui_setting->setColumns(array(
   "type" => array("label" => "Type", "sortable" => true),
   "name" => array("label" => "Name", "sortable" => true),
   "default_value" => array("label" => "Default Value"),
   "value" => array("label" => "Current Value", "editor" => "new YAHOO.widget.TextboxCellEditor({asyncSubmitter: updateSettingDatatableField})"),
));
$npyui_setting->setDataFields(array("type", "name", "default_value", "value"));
?>

<?
function html_head() {
   global $NPADMIN_PATH;
?>

<style type="text/css">
<?
global $npyui_setting;
$npyui_setting->generateCSS();
?>
</style>

<script>
<?
global $npyui_setting;
$npyui_setting->generateJS();
?>
</script>

<script>   
   var type_list = null;
   
   YAHOO.util.Event.addListener(window, "load", function() {
      tabView = new YAHOO.widget.TabView('mainTabs');
      
      SettingHooks = [];
      SettingHooks["afterInsertDialog"] = function(formObject) {
         if (formObject.name.value.trim().length == 0 || formObject.type.value.trim().length == 0) {
            box_block("settingadd_block", "All the required fields have to be filled");
            return false;
         } else {
            return true;
         }
      }
      SettingHooks["onLoad"] = function(response) {
         type_list.getMenu().clearContent();
         type_list.set("label", "ALL");
         
         var types = new Array();
         var menu = new Array();
         menu[0] = {text: "ALL", onclick: { fn: showTypeSettings }};
         
         for (id in response.results) {
            var setting = response.results[id];
            if (typeof(setting) != "function") {
               if (!types.contains(setting.type)) {
                  types[types.length] = setting.type;
                  menu[menu.length] = {text: setting.type, onclick: { fn: showTypeSettings }};
               }
            }
         }
         
         type_list.getMenu().addItems(menu);
         
         type_list.getMenu().render(document.body);
      }
      <?
      global $npyui_setting;
      $npyui_setting->generateInitJS("SettingHooks");
      ?>
   
      
      type_list = new YAHOO.widget.Button("type_list", {
         type: "menu",  
         menu: "type_list_select"
      });
   });
   
   
   function showTypeSettings(p_sType, p_aArgs, p_oItem) {
      var type = p_oItem.cfg.getProperty("text");
      var count = setting_datatable.getRecordSet().getLength();
      setting_datatable.deleteRows(0,count);
      type_list.set("label", type);
      setting_dataSource.sendRequest("op=list&type=" + type, {success : setting_datatable.onDataReturnAppendRows, scope: setting_datatable})
   }
        
</script>
<?
}
?>
<? require_once($NPADMIN_PATH."private/include/header.php"); ?>

<div class="page_title"><?= $panelData->getTitle() ?></div>

<div id="mainTabs" class="yui-navset">
    <ul class="yui-nav">
        <li class="selected"><a href="#"><em>Settings</em></a></li>    
    </ul>            
    <div class="yui-content">
        <div>
           <div id="Setting_buttons"></div>
           Type: <input type="button" id="type_list" name="type_list" value="ALL"/>
           <select id="type_list_select" name="type_list_select"></select>
           <div id="Setting_datatable"></div>
        </div>
    </div>
</div>


<div style="visibility: hidden; display:none">
  <div id="Setting_form_table">
     <div class="bd">
        <form id="Setting_form">
           <table >
              <tr><td>Type:</td><td><input type="text" name="type"/></td><tr>               
              <tr><td>Name:</td><td><input type="text" name="name"/></td>
              <tr><td>Default value:</td><td><input type="text" name="default_value"/></td><tr>
              <tr><td>Value:</td><td><input type="text" name="value"/></td><tr>
              <input type="hidden" name="op" value="add"/>
           </table>
        </form>
     </div>
  </div>
</div>  

<? NP_YUI_generateHTML("Entry"); ?>

<? require_once($NPADMIN_PATH."private/include/footer.php"); ?>
