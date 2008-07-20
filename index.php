<?
global $PWD;
$PWD = "./";
require_once($PWD."include/common.php");

$yui->add("menu");
$yui->add("tabview");
$yui->add("button");

?>

<? require_once("include/header.php"); ?>


<script>
       var oPanel = new YAHOO.widget.Panel("wellcome", { constraintoviewport: true, fixedcenter: true, width: "400px", zIndex: 1});
       oPanel.setHeader("NP - Admin");
       oPanel.setBody("Wellcome to NP-Admin!");
       oPanel.render(document.body);
</script>

<? require_once("include/footer.php"); ?>
