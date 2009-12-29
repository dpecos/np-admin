<?
$NPADMIN_PATH = "../../";
require_once($NPADMIN_PATH."private/include/common.php");
$panelData = npadmin_panel("logsName")
//npadmin_security($panelData->getRols());
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
<? require_once($NPADMIN_PATH."private/include/header.php"); ?>

<div class="page_title"><?= $panelData->getTitle() ?></div>

<div id="mainTabs" class="yui-navset">
    <ul class="yui-nav">
        <li class="selected"><a href="#"><em>Logs</em></a></li>
    </ul>            
    <div class="yui-content">
        <div>
        <pre><?
           $data = NPLogger::loggerInfo("npadmin");
           //print_r($data); 
           readfile($data["logfile"]); 
           ?>
        </pre>
        </div>
    </div>
</div>
        

<? require_once($NPADMIN_PATH."private/include/footer.php"); ?>
