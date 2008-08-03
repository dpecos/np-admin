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
            background-color: <?= npadmin_setting('NP-ADMIN', 'BG_COLOR') ?>; //#dfb8df;            
         }

         #npadmin_menubar {
            margin-bottom: 10px;   
         }

         .menu_logout {
           float: right;
         }

         #main_body {
            margin: 20px;
            background-color: #FFFFFF; 
            padding: 15px;
            border: #000000 1px solid;
            height: 80%;
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
      
   </head>
   
   <body class="yui-skin-sam">
   
      <div id="main_body">
         <div id="logger_div" style="float:right"></div>
   
     
