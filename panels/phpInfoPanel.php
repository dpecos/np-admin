<?
$PWD = "../";
require_once($PWD."include/common.php");
$panelData = npadmin_panel("phpInfoPanel");
npadmin_security($panelData->getGroups());
?>

<?
function html_head() {
   global $PWD;
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
<? require_once($PWD."include/header.php"); ?>

<div class="page_title"><?= $panelData->getTitle() ?></div>

<div id="mainTabs" class="yui-navset">
    <ul class="yui-nav">
        <li class="selected"><a href="#"><em>PHP</em></a></li>
    </ul>            
    <div class="yui-content">
        <div>
           <? phpinfo(); ?>
        </div>
    </div>
</div>
        

<? require_once($PWD."include/footer.php"); ?>
