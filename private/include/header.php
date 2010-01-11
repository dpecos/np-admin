<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
   <head>
      <? $yui->dependencies(); ?>
   
      <? if ($yui_logging) { ?>
      <script>
         YAHOO.util.Event.addListener(window, "load", function() {
            var div = document.getElementById("logger_div");
            var logReader = new YAHOO.widget.LogReader(div, {verboseOutput:false}); 
            logReader.collapse()
            logReader.show();
         });        
      </script>
      <? } ?>
      
      <?
        if (!defined("NP-ADMIN_HIDE_MENUBAR") || defined("NP-ADMIN_HIDE_MENUBAR") && NP-ADMIN_HIDE_MENUBAR) {
            require_once($NPADMIN_PATH."private/include/menu.php");
        }
      ?>

      <link rel="stylesheet" type="text/css" href="<?= npadmin_setting('NP-ADMIN', 'BASE_URL') ?>/public/css/npadmin_style.php?LANG=<?= NP_LANG ?>"/>
	  
      <script language="javascript" src="<?= npadmin_setting('NP-ADMIN', 'BASE_URL') ?>/public/js/np-lib/nplib_string.js"></script>
      <script language="javascript" src="<?= npadmin_setting('NP-ADMIN', 'BASE_URL') ?>/public/js/np-lib/nplib_array.js"></script> 
      <script language="javascript" src="<?= npadmin_setting('NP-ADMIN', 'BASE_URL') ?>/public/js/np-lib/security/AES.js"></script>
      <script language="javascript" src="<?= npadmin_setting('NP-ADMIN', 'BASE_URL') ?>/public/js/np-lib/nplib_security.js"></script>
         
      <script language="javascript" src="<?= npadmin_setting('NP-ADMIN', 'BASE_URL') ?>/public/js/npadmin_javascript.php?LANG=<?= NP_LANG ?>"></script>
      <script language="javascript" src="<?= npadmin_setting('NP-ADMIN', 'BASE_URL') ?>/public/js/npadmin_login.php?LANG=<?= NP_LANG ?>"></script>
      
      <? if (function_exists("html_head")) html_head() ?>

      <? 
      global $panelData;
      $panelTitle = "";
      if ($panelData != null)
         $panelTitle = $panelData->getTitle()." - ";

      $login = npadmin_loginData();
        if ($login != null) {      
      ?>
    
      <title><?= $panelTitle.npadmin_setting("APP", "TITLE")?> (<?= $login->getUser()->user ?>)</title>
      <? } else { ?>
      <title><?= $panelTitle.npadmin_setting("APP", "TITLE")?></title>
      <? } ?>
      
   </head>
   
   <body class="yui-skin-sam">
   
      <div id="main_body">
         <div id="logger_div" style="float:right"></div>
   
     
