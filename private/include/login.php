<?
$NPADMIN_PATH = "../";
?>
<html>
   <head>
      <? global $yui; $yui->dependencies(); ?>
      
      <link href="<?= npadmin_setting('NP-ADMIN', 'BASE_URL') ?>/public/css/npadmin_style.css" type="text/css" rel="stylesheet">     
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
      
      <script language="javascript" src="<?= npadmin_setting('NP-ADMIN', 'BASE_URL') ?>/public/js/np-lib/nplib_common.js"></script>
      <script language="javascript" src="<?= npadmin_setting('NP-ADMIN', 'BASE_URL') ?>/public/js/np-lib/nplib_string.js"></script>
      <script language="javascript" src="<?= npadmin_setting('NP-ADMIN', 'BASE_URL') ?>/public/js/np-lib/security/AES.js"></script>
      <script language="javascript" src="<?= npadmin_setting('NP-ADMIN', 'BASE_URL') ?>/public/js/np-lib/nplib_security.js"></script>
            
      <script language="javascript" src="<?= npadmin_setting('NP-ADMIN', 'BASE_URL') ?>/public/js/npadmin_javascript.php"></script>
      <script language="javascript" src="<?= npadmin_setting('NP-ADMIN', 'BASE_URL') ?>/public/js/npadmin_login.php"></script>
      
      <script>
      YAHOO.util.Event.addListener(window, "load", function() {
         npadmin_showLogin(true, "<?= array_key_exists("referrer", $_GET) ? $_GET['referrer'] : npadmin_setting('NP-ADMIN', 'BASE_URL') ?>");
      });
      </script>
   </head>

   <body class="yui-skin-sam">

      <div id="main_body">
         <? if (npadmin_loginData() != null) { ?>
         <div class="page_title"><?= _("You are not allowed to access this page") ?></div>
         <? } ?>
         <div class="page_title"><?= npadmin_setting("APP", "FORM_MESSAGE") ?></div>
         
<? npadmin_html_loginForm(); ?>

      </div>

   </body>

</html>
