<?
$NPADMIN_PATH = "../";
require_once($NPADMIN_PATH."private/include/common.php");
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
<? require_once($NPADMIN_PATH."private/include/header.php"); ?>

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
?>

<? require_once($NPADMIN_PATH."private/include/footer.php"); ?>
