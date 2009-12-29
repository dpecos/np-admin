<?
$NPADMIN_PATH = "../../";
require_once($NPADMIN_PATH."private/include/common.php");

global $ddbb;
$userTmpPassword = null;

if (!array_key_exists("x", $_GET)) {
	NP_redirect("../index.php");
} else {
	$x = $_GET['x'];
	$sql = "SELECT * FROM ".$ddbb->getTable("UserTmpPassword")." WHERE ".$ddbb->getMapping("UserTmpPassword", "tmpPassword")."=".NP_DDBB::encodeSQLValue($x, "STRING");
	//echo $sql;
	$data = $ddbb->executePKSelectQuery($sql);
	if ($data != null) {
		$userTmpPassword = new UserTmpPassword($data);
		$today = time();
		$diffDays = floor(($today - strtotime($userTmpPassword->creationDate)) / (60*60*24));
		if ($diffDays > npadmin_setting("NP-ADMIN", "RESET_PASSWORD_DAYS")) {
			Logger::info("npadmin", "Reset password entry too old! (".$userTmpPassword->creationDate.", ".$userTmpPassword->email.", ".$userTmpPassword->tmpPassword.")");
			$userTmpPassword->delete();
			NP_redirect("../index.php");
		}
	} else {
		NP_redirect("../index.php");
	}
}
?>

<?
function html_head() {
   global $NPADMIN_PATH;
?>

<style type="text/css">
</style>

<script> 
   YAHOO.util.Event.addListener(window, "load", function() {
	  
     
   });
  
</script>
<?
}
?>
<? require_once($NPADMIN_PATH."private/include/header.php"); ?>
Cambio de contraseña:
<form action="<?= npadmin_setting('NP-ADMIN', 'BASE_URL') ?>/public/ajax/users.php" method="post">
	<table>
		<tr><td>Introduce tu dirección de correo:</td><td><input type="textbox" name="email"/></td></tr>
		<tr><td>Introduce la nueva contraseña:</td><td><input type="password" name="password1"/></td></tr>
		<tr><td>Repite la nueva contraseña:</td><td><input type="password" name="password2"/></td></tr>
		<tr><td colspan="2"><input type="submit" value="Cambiar"/></td></tr>
	</table>
	<input type="hidden" name="x" value="<?= $userTmpPassword->tmpPassword ?>"/>
	<input type="hidden" name="op" value="confirmResetPassword"/>
</form>
<? require_once($NPADMIN_PATH."private/include/footer.php"); ?>
