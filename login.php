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
      </style>
      <script language="javascript" src="<?= $PWD ?>static/npadmin_javascript.js"></script>
      <script>
         var loginDialog = null;
         YAHOO.util.Event.addListener(window, "load", function() {
            loginDialog = new YAHOO.widget.Dialog("login_form_table", { 
                  width: 350, 
			         effect: {effect:YAHOO.widget.ContainerEffect.FADE, duration:0.25},
			         fixedcenter: true,
			         draggable: true,
			         constraintoviewport: true,
			         text: "Create new user",
			         modal: true,
			         close: false,
                  buttons: [ 
                     { text:"Cancel", handler:doExit },
	                  { text:"Login", handler:doLogin, isDefault:true } 
	               ]
			       });
	         loginDialog.setHeader("Login");
	         loginDialog.render(document.body);
            loginDialog.show();
         });
         
         function doLogin() {
            var formObject = document.getElementById('login_form');
            if (formObject.user.value.trim().length == 0 || formObject.password.value.trim().length == 0)
               box_block("userlogin_block", "All the required fields have to be filled");
            else {
               YAHOO.util.Connect.setForm(formObject); 
               var transaction = YAHOO.util.Connect.asyncRequest('POST', "<?= $PWD ?>ajax/users.php", {success:loginCallback});
            }
         }
         
         function doExit() {
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

      <!--link rel="stylesheet" type="text/css" href="<?= $PWD ?>npadmin_styles.css"/-->
      <script language="javascript" src="<?= $PWD ?>npadmin_javascript.js"></script>
      
   </head>
   
   <body class="yui-skin-sam">
   
      <div id="main_body">
         
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
