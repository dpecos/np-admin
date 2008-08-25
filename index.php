<?
$PWD = "./";
require_once($PWD."include/common.php");
?>

<?
function html_head() {
   global $PWD;
?>

<script> 

   YAHOO.util.Event.addListener(window, "load", function() {
     
   });
  
</script>
<?
}
?>
<? require_once($PWD."include/header.php"); ?>

<div class="page_title"><?= npadmin_setting("APP", "TITLE") ?></div>      

<? require_once($PWD."include/footer.php"); ?>