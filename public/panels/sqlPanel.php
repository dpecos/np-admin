<?
$NPADMIN_PATH = "../../";
require_once($NPADMIN_PATH."private/include/common.php");
npadmin_security(array("Administrators"));

if (!array_key_exists('action', $_GET))
	$_GET['action'] = "ALL";
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
<? require_once($NPADMIN_PATH."private/include/header.php"); ?>

<div class="page_title">SQL administration</div>

<?
if (array_key_exists('app', $_GET)) {
	$ddbb = null;
	$file = $NPADMIN_PATH.$_GET['app']."/npadmin_install.php";
	if (file_exists($file))
		require_once($file);
	else {
		Logger::error("npadmin", "File $file doesn't exists or it's not readable");
		die ("ERROR: File $file doesn't exists or it's not readable");
	}
}
if (array_key_exists('install', $_POST)) {
	$ddbb->setDDBBConfig($_POST);

	echo "Step 1: Creating database structure ... \n";
	$sql = $ddbb->createSQLCreateTable();
	foreach (split(";", $sql) as $query) {
		if (strlen(trim($query)) > 0)
			$ddbb->executeInsertUpdateQuery($query);
	}
	echo "Done <br/><br/>\n";

	echo "Go to the new application: <a href='".$NPADMIN_PATH.$_POST['app']."'>app?</a>";


} else {
?>

<div id="mainTabs" class="yui-navset">
    <ul class="yui-nav">
        <? if ($_GET['action'] == "S" || ($_GET['action'] == "ALL")) { ?><li class="selected"><a href="#"><em>SQL: Table structure</em></a></li><? } ?>
        <? if ($_GET['action'] == "D" || ($_GET['action'] == "ALL")) { ?><li class="selected"><a href="#"><em>SQL: Table data</em></a></li><? } ?>
        <? if ($_GET['action'] == "DS" || ($_GET['action'] == "ALL")) { ?><li class="selected"><a href="#"><em>SQL: Table structure & Data</em></a></li><? } ?>
    </ul>            
    <div class="yui-content">
        <div>
           <pre><?= ($_GET['action'] == "S" || ($_GET['action'] == "ALL")) ? $ddbb->createSQLCreateTable(false) : ""; ?></pre>

           <p>Do you want to create this structure in the database?</p>
           <form method="POST" action="<?= $_SELF."?app=".$_GET['app'] ?>">
           <table>
              <caption><b>Database Connections</b></caption>
              <tr><td width="150px">HOST</td><td><input type="text" name="HOST" value="<?= isset($ddbb_settings) ? $ddbb_settings['HOST'] : "localhost" ?>"/></td></tr>
              <tr><td width="150px">USER</td><td><input type="text" name="USER" value="<?= isset($ddbb_settings) ? $ddbb_settings['USER'] : "" ?>"/></td></tr>
              <tr><td width="150px">PASSWORD</td><td><input type="text" name="PASSWD" value="<?= isset($ddbb_settings) ? $ddbb_settings['PASSWD'] : "" ?>"/></td></tr>
              <tr><td width="150px">NAME</td><td><input type="text" name="NAME" value="<?= isset($ddbb_settings) ? $ddbb_settings['NAME'] : "" ?>"/></td></tr>
              <tr><td width="150px">TABLE PREFIX</td><td><input type="text" name="PREFIX" value="<?= isset($ddbb_settings) ? $ddbb_settings['PREFIX'] : "" ?>"/></td></tr>
           </table>
	   <input type="hidden" name="app" value="<?= $_GET['app'] ?>"/>
	   <input type="hidden" name="install" value="true"/>
           <input type="submit" value="Do it!"/>
	   </form>
 
        </div>
        <div>
           <pre><?= ($_GET['action'] == "D" || ($_GET['action'] == "ALL")) ? $ddbb->createSQLDataTable() : ""; ?></pre>
        </div>
        <div>
           <pre><?= ($_GET['action'] == "DS" || ($_GET['action'] == "ALL")) ? $ddbb->createSQLCreateTable(true) : ""; ?></pre>
        </div>
    </div>
</div>
        
<? } ?>

<? require_once($NPADMIN_PATH."private/include/footer.php"); ?>
