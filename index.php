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

<? require_once($NPADMIN_PATH."include/footer.php"); ?>