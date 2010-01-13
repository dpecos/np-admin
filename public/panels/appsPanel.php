<?
$NPADMIN_PATH = "../../";
require_once($NPADMIN_PATH."private/include/common.php");
npadmin_security(array("Administrators"));

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

<div class="page_title">Apps administration</div>

<?
function npadmin_create_inserts($app, $doInsert = false) {
	global $ddbb;
	$references = array();
	$result_txt = "";

	$rols = "";
	$groups = "";
	$panels = "";
	$menus = "";
	
	$result_txt .= " ---- ROLES ----\n";
	foreach ($app->rols as $key => $data) {
		$r = new Rol($data);
		$references[$key] = $r->name;
		$result_txt .= $key." => ".$ddbb->insertObject($r, true)."\n";
		if ($doInsert) {
			$references[$key] = $ddbb->insertObject($r, false);
			$rols .= $references[$key].",";
		}
	}	
	$result_txt .= "\n ---- GROUPS ----\n";
	foreach ($app->groups as $key => $data) {
                $g = new Group($data);
		$result_txt .= $key." => ".$ddbb->insertObject($g, true)."\n";
                if ($doInsert) {
                        $references[$key] = $ddbb->insertObject($g, false);
			$groups .= $references[$key].",";
			foreach ($data['rols'] as $r) {
				$result_txt .= "	Assigned rol: $r\n";
				$gr = new GroupRol(array("rol_id" => $references[$r], "group_id" => $references[$key]));
				$gr->store();
			}
                } else {
			foreach ($data['rols'] as $r) {
				$result_txt .= "	Assigned rol: $r\n";
			}
                }
	}

	$result_txt .= "\n ---- PANELS ----\n";
	foreach ($app->panels as $key => $data) {
                $p = new Panel($data);
		$result_txt .= $key." => ".$ddbb->insertObject($p, true)."\n";
                if ($doInsert) {
                        $ddbb->insertObject($p, false);
			$references[$key] = $p->id;
			$panels.= $references[$key].",";
			foreach ($data['rols'] as $r) {
				$result_txt .= "	Assigned rol: $r\n";
				$gr = new PanelRol(array("rol_id" => $references[$r], "panel_id" => $references[$key]));
				$gr->store();
			}
                } else {
			foreach ($data['rols'] as $r) {
				$result_txt .= "	Assigned rol: $r\n";
			}
                }
	}

	$result_txt .= "\n ---- MENUS ----\n";
	foreach ($app->menus as $key => $data) {
                $m = new Menu($data);
		$result_txt .= $key." => ".$ddbb->insertObject($m, true)."\n";
                if ($doInsert) {
			if (NP_startsWith("#", $m->panelId))
				$m->panelId = $references[$m->panelId];
                        $references[$key] = $ddbb->insertObject($m, false);
			$menus .= $references[$key].",";
			foreach ($data['rols'] as $r) {
				$result_txt .= "	Assigned rol: $r\n";
				$mr = new MenuRol(array("rol_id" => $references[$r], "menu_id" => $references[$key]));
				$mr->store();
			}
                } else {
			foreach ($data['rols'] as $r) {
				$result_txt .= "	Assigned rol: $r\n";
			}
                }
	}
	if ($doInsert) {
		$rols = substr($rols, 0, strlen($rols) - 1);
		$groups = substr($groups, 0, strlen($groups) - 1);
		$panels = substr($panels, 0, strlen($panels) - 1);
		$menus = substr($menus, 0, strlen($menus) - 1);
		$app->register(array("rols"=>$rols, "groups"=>$groups, "panels"=>$panels, "menus"=>$menus));
	}

	return $result_txt;
}

global $app;
 
define("NP-ADMIN_INSTALL", "true");

$result = "";

if (array_key_exists('app', $_GET)) {

	$file = $NPADMIN_PATH.$_GET['app']."/npadmin_install.php";
	if (file_exists($file))
		require($file);
	else {
		Logger::error("npadmin", "File $file doesn't exists or it's not readable");
		die ("ERROR: File $file doesn't exists or it's not readable");
	}

	if (!array_key_exists('install', $_POST)) {
		$result = npadmin_create_inserts($app, false);
	}
}

if (array_key_exists('install', $_POST)) {

	echo "Step 1: Inserting np-admin data ... \n";
	echo "<pre>".npadmin_create_inserts($app, true)."</pre>";

	$ddbb = new NP_DDBB($_POST);
	require($file);

	echo "Step 2: Creating database structure ... \n";
	$sql = $ddbb->createSQLCreateTable();
	foreach (split(";", $sql) as $query) {
		if (strlen(trim($query)) > 0)
			$ddbb->executeInsertUpdateQuery($query);
	}

	echo "Done <br/><br/>\n";

	echo "Go to the new application: <a href='".$NPADMIN_PATH.$_POST['app']."'>".$app->name."</a>";


} else {

	if (array_key_exists('app', $_GET)) {

?>

<div id="mainTabs" class="yui-navset">
    <ul class="yui-nav">
	<li class="selected"><a href="#"><em>App installation</em></a></li>
	<li><a href="#"><em>App np-admin modifications</em></a></li>
	<li><a href="#"><em>App SQL modifications</em></a></li>
    </ul>            
    <div class="yui-content">
	<div>

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
           <pre><?= $result ?></pre>
 	</div>
        <div>
           <pre><?= $app->ddbb->createSQLCreateTable(false) ?></pre>
        </div>
    </div>
</div>
        
<? 
	} else { 
	
		if (array_key_exists('uninstall', $_GET)) {
			$apl = $_GET['uninstall'];
			$sql = "SELECT * FROM npadmin_applications WHERE app_id = $apl";
			$data = $ddbb->executePKSelectQuery($sql);

			if ($data != null) {
			
				$app = new Application($data);
				foreach (explode(",", $app->list_rols) as $x) {
					$o = new Rol(array("rol_id" => $x));
					$o->delete();
				}
				foreach (explode(",", $app->list_groups) as $x) {
					$o = new Group(array("group_id" => $x));
					$o->delete();
				}
				foreach (explode(",", $app->list_panels) as $x) {
					$o = new Panel(array("id" => $x));
					$o->delete();
				}
				foreach (explode(",", $app->list_menus) as $x) {
					$o = new Menu(array("menu_id" => $x));
					$o->delete();
				}

				$app->delete();
			}
		}
?>

<div id="mainTabs" class="yui-navset">
    <ul class="yui-nav">
        <li class="selected"><a href="#"><em>Installed applications</em></a></li>
    </ul>
    <div class="yui-content">
        <div>
		<table>
<?
	function printApp($apl) {
		echo "<tr><td><a href='".$_SERVER['PHP_SELF']."?uninstall=".$apl['app_id']."'>".$apl['name']."</a></td></tr>";
	}

	$sql = "SELECT app_id, name FROM npadmin_applications";
	$ddbb->executeSelectQuery($sql, "printApp");
?>
		</table>
	</div>
    </div>
</div>


<?
	}
} 
?>

<? require_once($NPADMIN_PATH."private/include/footer.php"); ?>
