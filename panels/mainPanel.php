<?
$NPADMIN_PATH = "../";
require_once($NPADMIN_PATH."include/common.php");
$panelData = npadmin_panel("mainPanel");
npadmin_security($panelData->getRols());
?>

<?
function html_head() {
   global $NPADMIN_PATH;
?>
<script> 
   var tabView;

   YAHOO.util.Event.addListener(window, "load", function() {
      //tabView = new YAHOO.widget.TabView('mainTabs');
     
   });
  
</script>
<?
}
?>
<? require_once($NPADMIN_PATH."include/header.php"); ?>

<div class="page_title"><?= $panelData->getTitle() ?></div>
	
	<ul>
		<li>PHPDOC: <a href="../lib/phpdoc/docbuilder/builder.php?setting_useconfig=np-admin&dataform=true">Generate Documentation</a></li>
		<li>PHPDOC: <a href="../work/documentation">View Documentation</a></li>
	</ul>
		
<!--div id="mainTabs" class="yui-navset">
    <ul class="yui-nav">
        <li class="selected"><a href="#tab1"><em>Tab 1</em></a></li>
    </ul>            
    <div class="yui-content">
        <div>
           
        </div>
    </div>
</div-->
        

<? require_once($NPADMIN_PATH."include/footer.php"); ?>
