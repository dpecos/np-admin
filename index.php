<?
$NPADMIN_PATH = "./";
require_once($NPADMIN_PATH."include/common.php");
?>

<?
function html_head() {
   global $NPADMIN_PATH;
?>

<script>
   YAHOO.util.Event.addListener(window, "load", function() {
   });
</script>
<?
}
?>
<? require_once($NPADMIN_PATH."include/header.php"); ?>

<div class="page_title"><?= npadmin_setting("APP", "TITLE") ?></div>

<?
if (npadmin_loginData() == null) {
	npadmin_html_loginForm();
?>
         
<script>
YAHOO.util.Event.addListener(window, "load", function() {
	npadmin_showLogin(false);
});
</script>

<?
}
if (npadmin_isAllowed("Usuarios vacaciones")) {
?>
<b>Acciones:</b><br/>
&nbsp;&raquo; <a href="/arq/gestion/work/gestion_panels/gestionVacaciones.php">Añadir vacaciones</a><br><br>
<?
}
if (npadmin_isAllowed("Usuarios estadisticas SPD")) {
?>
        <?php include("/var/www/arq/gestion/pers/index.html"); ?>
<?
}
?>

<? require_once($NPADMIN_PATH."include/footer.php"); ?>