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
      
      <? require_once($NPADMIN_PATH."private/include/menu.php") ?>

      <link rel="stylesheet" type="text/css" href="<?= npadmin_setting('NP-ADMIN', 'BASE_URL') ?>/public/css/npadmin_style.php"/>
	  
      <script language="javascript" src="<?= npadmin_setting('NP-ADMIN', 'BASE_URL') ?>/public/js/np-lib/nplib_string.js"></script>
      <script language="javascript" src="<?= npadmin_setting('NP-ADMIN', 'BASE_URL') ?>/public/js/np-lib/nplib_array.js"></script> 
      <script language="javascript" src="<?= npadmin_setting('NP-ADMIN', 'BASE_URL') ?>/public/js/np-lib/security/AES.js"></script>
      <script language="javascript" src="<?= npadmin_setting('NP-ADMIN', 'BASE_URL') ?>/public/js/np-lib/nplib_security.js"></script>
         
      <script language="javascript" src="<?= npadmin_setting('NP-ADMIN', 'BASE_URL') ?>/public/js/npadmin_javascript.php"></script>
      <script language="javascript" src="<?= npadmin_setting('NP-ADMIN', 'BASE_URL') ?>/public/js/npadmin_login.php"></script>
      
      <? if (function_exists("html_head")) html_head() ?>

      <? 
      $login = npadmin_loginData();
        if ($login != null) {      
      ?>
      <title><?= npadmin_setting("APP", "TITLE") ?> - <?= $login->getUser()->user ?></title>
      <? } else { ?>
      <title><?= npadmin_setting("APP", "TITLE") ?></title>
      <? } ?>
      
   </head>
   
   <body class="yui-skin-sam">
   
      <div id="main_body">
         <div id="logger_div" style="float:right"></div>
   
     
