<?
$NPADMIN_PATH = "../";
require_once($NPADMIN_PATH."include/common.php");
$panelData = npadmin_panel("panelName");
npadmin_security($panelData->getGroups());
?>

<?
function html_head() {
   global $NPADMIN_PATH;
?>

<style type="text/css">
</style>

<script> 
   var tabView;

   YAHOO.util.Event.addListener(window, "load", function() {
      tabView = new YAHOO.widget.TabView('mainTabs');
     
   });
  
</script>
<?
}
?>
<? require_once($NPADMIN_PATH."include/header.php"); ?>

<div class="page_title"><?= $panelData->getTitle() ?></div>

<div id="mainTabs" class="yui-navset">
    <ul class="yui-nav">
        <li class="selected"><a href="#"><em>Tab 1</em></a></li>
    </ul>            
    <div class="yui-content">
        <div>
           
        </div>
    </div>
</div>
        

<? require_once($NPADMIN_PATH."include/footer.php"); ?>
