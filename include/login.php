<?
$NPADMIN_PATH = "../";
?>
<html>
   <head>
      <? global $yui; $yui->dependencies(); ?>
      
      <link href="<?= npadmin_setting('NP-ADMIN', 'BASE_URL') ?>/static/npadmin_style.css" type="text/css" rel="stylesheet">     
      <style>
         .page_title {
            text-align: center;
            color: red;
            font-size: 22px;
            font-weight: bold;
            margin-top: 35px;
            margin-bottom: 15px;
         }
      </style>
      
      <script language="javascript" src="<?= npadmin_setting('NP-ADMIN', 'BASE_URL') ?>/static/npadmin_javascript.js"></script>
      <script language="javascript" src="<?= npadmin_setting('NP-ADMIN', 'BASE_URL') ?>/static/npadmin_login.php"></script>
      
      <script>
      YAHOO.util.Event.addListener(window, "load", function() {
    	  npadmin_showLogin(true, "<?= $_GET['referrer'] ?>");
      });
      </script>
   </head>

   <body class="yui-skin-sam">

      <div id="main_body">
         <? if (npadmin_loginData() != null) { ?>
         <div class="page_title">You are not allowed to access this page</div>
         <? } ?>
         <div class="page_title"><?= npadmin_setting("APP", "FORM_MESSAGE") ?></div>
         
<? npadmin_html_loginForm(); ?>

      </div>

   </body>

</html>