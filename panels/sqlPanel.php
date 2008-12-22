<?
$NPADMIN_PATH = "../";
require_once($NPADMIN_PATH."include/common.php");
npadmin_security(array("Administrators"));
?>

<?
function html_head() {
   global $NPADMIN_PATH;
?>
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

<div class="page_title">SQL administration</div>

<div id="mainTabs" class="yui-navset">
    <ul class="yui-nav">
        <li class="selected"><a href="#"><em>SQL: Table structure & Data</em></a></li>
        <li><a href="#"><em>SQL: Table structure</em></a></li>
        <li><a href="#"><em>SQL: Table data</em></a></li>
    </ul>            
    <div class="yui-content">
        <div>
           <pre><?= $ddbb->createSQLCreateTable(true); ?></pre>
        </div>
        <div>
           <pre><?= $ddbb->createSQLCreateTable(false); ?></pre>
        </div>
        <div>
           <pre><?= $ddbb->createSQLDataTable(); ?></pre>
        </div>
    </div>
</div>
        

<? require_once($NPADMIN_PATH."include/footer.php"); ?>
