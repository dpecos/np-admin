<?
$NPADMIN_PATH = "../";
?>
<html>
   <head>
      <? global $yui; $yui->dependencies(); ?>
      <style>
         table {
            margin: 10px;
         }
         td {
            padding: 10px;
            padding-bottom: 0px;
         }
         .page_title {
            text-align: center;
            color: red;
            font-size: 22px;
            font-weight: bold;
            margin-top: 35px;
            margin-bottom: 15px;
         }
      </style>
      <script>
         var loginDialog = null;
         YAHOO.util.Event.addListener(window, "load", function() {
            loginDialog = new YAHOO.widget.Dialog("login_form_table", { 
                  width: 350, 
			         effect: {effect:YAHOO.widget.ContainerEffect.FADE, duration:0.25},
			         fixedcenter: true,
			         draggable: true,
			         constraintoviewport: true,
			         text: "NP-Admin Login",
			         modal: true,
			         close: false,
                  buttons: [ 
                     { text:"Cancel", handler:npadmin_doExit },
	                  { text:"Login", handler:npadmin_doLogin, isDefault:true } 
	               ]
			      });
	          loginDialog.setHeader("NP-Admin Login");
	          loginDialog.render(document.body);
            loginDialog.show();
         });
         
         function npadmin_doLogin() {
            var formObject = document.getElementById('login_form');
            if (formObject.user.value.trim().length == 0 || formObject.password.value.trim().length == 0)
               box_block("userlogin_block", "All the required fields have to be filled");
            else {
               YAHOO.util.Connect.setForm(formObject); 
               var transaction = YAHOO.util.Connect.asyncRequest('POST', "<?= npadmin_setting('NP-ADMIN', 'BASE_URL') ?>/ajax/users.php", {success:loginCallback});
            }
         }
         
         function npadmin_doExit() {
            window.history.back();
         }
         
         function loginCallback(response) {   
            if (response.responseText.trim() == "OK") 
               document.location.reload();
            else {
               var formObject = document.getElementById('login_form');
               formObject.password.value = "";
               box_error("userlogin_error", "Incorrect user/password");
            }
         }
      </script>
      <script language="javascript" src="<?= npadmin_setting('NP-ADMIN', 'BASE_URL') ?>/static/npadmin_javascript.js"></script>
   </head>
   
   <body class="yui-skin-sam">
   
      <div id="main_body">
         <? if (npadmin_loginData() != null) { ?>
         <div class="page_title">You are not allowed to access this page</div>
         <? } ?>
         <div class="page_title"><?= npadmin_setting("APP", "FORM_MESSAGE") ?></div>
         <div style="visibility: hidden; display:none">
           <div id="login_form_table">
              <div class="bd">
              <form id="login_form">
                 <table>
                    <tr><td>User name:</td><td><input type="text" name="user"/></td><tr>
                    <tr><td>Password:</td><td><input type="password" name="password"/></td><tr>
                 </table>
                 <input type="hidden" name="op" value="login"/>
              </form>
              </div>
           </div>
         </div>   
               
      </div>
      
   </body>
   
</html>
