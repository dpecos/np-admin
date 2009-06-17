<?
$NPADMIN_PATH = "../";
require_once($NPADMIN_PATH."include/common.php");
?>

<? if (array_key_exists("referrer", $_GET)) { ?>
var referrer = "<?= $_GET["referrer"] ?>";
<? } else {?>
var referrer = null;
<? } ?>
var npadmin_loginDialog = null;
var npadmin_messageBox = null;

function npadmin_showLogin(modal, ref) {
			if (modal == null)
				modal = true;
			if (ref != null)
				referrer = ref;
				
            npadmin_loginDialog = new YAHOO.widget.Dialog("login_form_table", {
                  width: 350,
			         effect: {effect:YAHOO.widget.ContainerEffect.FADE, duration:0.25},
			         fixedcenter: true,
			         draggable: true,
			         constraintoviewport: true,
			         text: "NP-Admin Login",
			         modal: modal,
			         close: !modal,
                  buttons: [
                      { text:"Cancel", handler: (modal ? doExit : defaultButtonHandler) },
	                  { text:"Login", handler:doLogin, isDefault:true }
	               ]
			       });
	        npadmin_loginDialog.setHeader("NP-Admin Login");
            var kl_enter = new YAHOO.util.KeyListener(document, { keys:YAHOO.util.KeyListener.KEY.ENTER },  							
					  { fn: doLogin,
						scope: npadmin_loginDialog,
						correctScope:true } );
            var kl_esc = new YAHOO.util.KeyListener(document, { keys:27 },  							
					  { fn: (modal ? doExit : defaultButtonHandler),
						scope: npadmin_loginDialog,
						correctScope:true } );
            npadmin_loginDialog.cfg.queueProperty("keylisteners", [kl_enter, kl_esc]);
            
	        npadmin_loginDialog.render(document.body);
            npadmin_loginDialog.show();
}

         
function doLogin() {
        	npadmin_loginDialog.hide();
            var formObject = document.getElementById('npadmin_loginForm');
            var seed = document.getElementById('npadmin_login_seed');
            if (seed != null) {
	            if (formObject.user.value.trim().length == 0 || formObject.password.value.trim().length == 0) {
	               box_block("userlogin_block", "All the required fields have to be filled", "npadmin_loginDialog.show()");
	            } else {
	               npadmin_messageBox = box_msg("userlogin_login", "Login", "Checking credentials...");
	               formObject.password.value = AESEncryptCtr(formObject.password.value, seed.value, 256);
	               YAHOO.util.Connect.setForm(formObject);
	               var transaction = YAHOO.util.Connect.asyncRequest('POST', "<?= npadmin_setting('NP-ADMIN', 'BASE_URL') ?>/ajax/users.php", {success:loginCallback});
	            }
            } else {
            	box_error("userlogin_error", "Your browser does not accept cookies");
            }
}

function doExit() {
        npadmin_loginDialog.hide();
        if (referrer != null)
            document.location.href = referrer;
        else 
            window.history.back();
}

function loginCallback(response) {
            if (response.responseText.trim() == "OK") {
               if (referrer != null)
                  document.location.href = referrer;
               else                   
                 document.location.reload();
            } else {
               var formObject = document.getElementById('npadmin_loginForm');
               formObject.password.value = "";
               npadmin_messageBox.hide();
               box_error("userlogin_error", "Incorrect user/password", "npadmin_loginDialog.show()");
            }
}