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
      
      <? require_once($PWD."include/menu.php") ?>
      
      <style>
         html {
            background-color: <?= npadmin_setting('NP-ADMIN', 'BG_COLOR') ?>;            
         }

         #main_body {
            margin: 20px;
            background-color: #FFFFFF; 
            padding: 15px;
            border: #000000 1px solid;
            /*min-height: 80%;
            height: auto !important;
            height: 80%;*/
         }

         #mainTabs div.yui-content {
            padding: 15px;
         }

         .page_title {
            font-size: 22px;
            font-weight: bold;
            margin-top: 5px;
            margin-bottom: 15px;
         }
      </style>
      
      <script language="javascript" src="<?= npadmin_setting('NP-ADMIN', 'BASE_URL') ?>/static/npadmin_javascript.js"></script>
      
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
   
     
