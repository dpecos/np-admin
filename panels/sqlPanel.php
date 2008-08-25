<?
$PWD = "../";
require_once($PWD."include/common.php");
npadmin_security(array("Administrators"));
?>

<?
function html_head() {
   global $PWD;
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
<? require_once($PWD."include/header.php"); ?>

<div class="page_title">SQL administration</div>

<div id="mainTabs" class="yui-navset">
    <ul class="yui-nav">
        <li class="selected"><a href="#tab1"><em>SQL</em></a></li>
    </ul>            
    <div class="yui-content">
        <div>
           <pre><?= $ddbb->createSQLCreateTable(true); ?></pre>
        </div>
    </div>
</div>
        

<? require_once($PWD."include/footer.php"); ?>