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
      
      <link rel="stylesheet" type="text/css" href="<?= $PWD ?>npadmin_styles.css"/>
      <script language="javascript" src="<?= $PWD ?>npadmin_javascript.js"></script>
      
      <? if (function_exists("html_head")) html_head() ?>
      
   </head>
   
   <body class="yui-skin-sam">
   
      <div id="main_body">
         <div id="logger_div" style="float:right"></div>
   
     
