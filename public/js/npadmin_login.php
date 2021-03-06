<?
$NPADMIN_PATH = "../../";
require_once($NPADMIN_PATH."private/include/common.php");
?>

<? if (array_key_exists("referrer", $_GET)) { ?>
var referrer = "<?= $_GET["referrer"] ?>";
<? } else {?>
var referrer = null;
<? } ?>
var npadmin_loginDialog = null;
var npadmin_messageBox = null;
var npadmin_changePasswordDialog = null;
var npadmin_resetPasswordDialog = null;

function cookiesEnabled(){
	var tmpcookie = new Date();
	chkcookie = (tmpcookie.getTime() + '');
	document.cookie = "chkcookie=" + chkcookie + "; path=/";
	if (document.cookie.indexOf(chkcookie,0) < 0) {
		return false;
	} else {
		return true;
	}
}

function npadmin_showLogin(modal, ref) {
	if (!cookiesEnabled()) {
		box_warn("npadmin_cookies", "You have to enable cookies if you want to login");
		return;
	}
	
	if (modal == null)
		modal = true;
		
	if ((ref != null) && (ref.length>0))
		referrer = ref;
		
   if (npadmin_loginDialog == null) {
      npadmin_loginDialog = new YAHOO.widget.Dialog("login_form_table", {
            width: 350,
            effect: {effect:YAHOO.widget.ContainerEffect.FADE, duration:0.25},
            fixedcenter: true,
            draggable: true,
            constraintoviewport: true,
            text: "<?= _("NP-Admin - Login") ?>",
            modal: modal,
            close: !modal,
            buttons: [
               { text:"<?= _("Cancel") ?>", handler: (modal ? doExit : defaultButtonHandler) },
               { text:"<?= _("Login") ?>", handler:doLogin, isDefault:true }
            ]
          });
      npadmin_loginDialog.setHeader("<?= _("NP-Admin - Login") ?>");
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
   } else {
      npadmin_loginDialog.form.reset();
   }
   npadmin_loginDialog.show();
}

         
function doLogin() {
  	npadmin_loginDialog.hide();
   var formObject = document.getElementById('npadmin_loginForm');
   var seed = document.getElementById('npadmin_login_seed');
   if (seed != null) {
      if (formObject.user.value.trim().length == 0 || formObject.password.value.trim().length == 0) {
         box_block("userlogin_block", "<?= _("All the required fields have to be filled") ?>", "npadmin_loginDialog.show()");
      } else {
         npadmin_messageBox = box_msg("userlogin_login", "<?= _("Login") ?>", "<?= _("Checking credentials...") ?>");
         formObject.password.value = np_encrypt("AES", formObject.password.value, seed.value);
         YAHOO.util.Connect.setForm(formObject);
         var transaction = YAHOO.util.Connect.asyncRequest('POST', "<?= npadmin_setting('NP-ADMIN', 'BASE_URL') ?>/public/ajax/users.php", {success:loginCallback});
      }
   } else {
   	box_error("userlogin_error", "<?= _("Your browser does not accept cookies") ?>");
   }
}

function doExit() {
   this.hide();
   if (referrer != null) {
      document.location.href = referrer;
   } else 
      window.history.back();
}

function loginCallback(response) {
   if (response.responseText.trim() == "<?= _("OK") ?>") {
      if (referrer != null)
         document.location.href = referrer;
      else                   
        document.location.reload();
   } else {
      var formObject = document.getElementById('npadmin_loginForm');
      formObject.password.value = "";
      npadmin_messageBox.hide();
      box_error("userlogin_error", "<?= _("Incorrect user/password") ?>", "npadmin_loginDialog.show()");
   }
}

function logout() {
   box_question("userlogout_question", "<?= _("Are you sure you want to logout?") ?>", logoutConfirm);
}

function logoutConfirm() {
   var transaction = YAHOO.util.Connect.asyncRequest('GET', "<?= npadmin_setting('NP-ADMIN', 'BASE_URL') ?>/public/ajax/users.php?op=logout", {success: function() {document.location.href = "<?= npadmin_setting('NP-ADMIN', 'BASE_URL') ?>"}} );
}


function npadmin_showChangePassword() {
	if (npadmin_changePasswordDialog == null) {
      npadmin_changePasswordDialog = new YAHOO.widget.Dialog("changePassword_form_table", {
            width: 350,
            effect: {effect:YAHOO.widget.ContainerEffect.FADE, duration:0.25},
            fixedcenter: true,
            draggable: true,
            constraintoviewport: true,
            text: "<?= _("NP-Admin - Change Password") ?>",
            modal: true,
            close: true,
            buttons: [
               { text:"<?= _("Cancel") ?>", handler: defaultButtonHandler },
               { text:"<?= _("Change") ?>", handler: doChangePassword, isDefault:true }
            ]
          });
      npadmin_changePasswordDialog.setHeader("<?= _("NP-Admin - Change Password") ?>");
      var kl_enter = new YAHOO.util.KeyListener(document, { keys:YAHOO.util.KeyListener.KEY.ENTER },  							
   		  { fn: doChangePassword,
   			scope: npadmin_changePasswordDialog,
   			correctScope:true } );
      var kl_esc = new YAHOO.util.KeyListener(document, { keys:27 },  							
   		  { fn: defaultButtonHandler,
   			scope: npadmin_changePasswordDialog,
   			correctScope:true } );
      npadmin_changePasswordDialog.cfg.queueProperty("keylisteners", [kl_enter, kl_esc]);
      
      npadmin_changePasswordDialog.render(document.body);
   } else {
      npadmin_changePasswordDialog.form.reset();
   }
   npadmin_changePasswordDialog.show();
}

function doChangePassword() {
  	this.hide();
   var formObject = document.getElementById('npadmin_changePasswordForm');
   var seed = document.getElementById('npadmin_changePassword_seed');
   if (seed != null) {
      if (formObject.old_password.value.trim().length == 0 || formObject.new_password.value.trim().length == 0 || formObject.new_password_2.value.trim().length == 0) {
         box_block("changepassword_block", "<?= _("All the required fields have to be filled") ?>", "npadmin_changePasswordDialog.show()");
      } else {
         if (formObject.new_password.value != formObject.new_password_2.value) {
            box_block("changepassword_block", "<?= _("New passwords doesn't match") ?>", "npadmin_changePasswordDialog.show()");
         } else {
            npadmin_messageBox = box_msg("changepassword_change", "<?= _("Change password") ?>", "<?= _("Changing password...") ?>");
            
            if (seed.value != null && seed.value != "")
               startChangePassword({responseText:seed.value});
            else
               var transaction = YAHOO.util.Connect.asyncRequest('GET', "<?= npadmin_setting('NP-ADMIN', 'BASE_URL') ?>/public/ajax/users.php?op=generateSeed", {success:startChangePassword});
         }
      }
   } else {
   	box_error("changepassword_error", "<?= _("Your browser does not accept cookies") ?>");
   }
}

function startChangePassword(response) {
   if (response.responseText != null && response.responseText != "") {
      var formObject = document.getElementById('npadmin_changePasswordForm');
      var seed = document.getElementById('npadmin_changePassword_seed');
      seed.value = response.responseText;
      
      formObject.old_password.value = np_encrypt("AES", formObject.old_password.value, seed.value);
      formObject.new_password.value = np_encrypt("AES", formObject.new_password.value, seed.value);
      formObject.new_password_2.value = formObject.new_password.value;
      
      YAHOO.util.Connect.setForm(formObject);
      var transaction = YAHOO.util.Connect.asyncRequest('POST', "<?= npadmin_setting('NP-ADMIN', 'BASE_URL') ?>/public/ajax/users.php", {success:changePasswordCallback});
   }
}

function changePasswordCallback(response) {
   npadmin_messageBox.hide();
   if (response.responseText.trim() == "OK") {
      box_info("changepassword_result", "<?= _("Password changed correctly") ?>");
   } else {
      box_error("changepassword_result", "<?= _("Incorrect old password") ?>", "npadmin_showChangePassword()");
   }
}

function npadmin_showResetPassword() {
	npadmin_loginDialog.hide();
	if (npadmin_resetPasswordDialog == null) {
      npadmin_resetPasswordDialog = new YAHOO.widget.Dialog("resetPassword_form_table", {
            width: 350,
            effect: {effect:YAHOO.widget.ContainerEffect.FADE, duration:0.25},
            fixedcenter: true,
            draggable: true,
            constraintoviewport: true,
            text: "<?= _("NP-Admin - Reset Password") ?>",
            modal: true,
            close: true,
            buttons: [
               { text:"<?= _("Cancel") ?>", handler: defaultButtonHandler },
               { text:"<?= _("Change") ?>", handler: doResetPassword, isDefault:true }
            ]
          });
      npadmin_resetPasswordDialog.setHeader("<?= _("NP-Admin - Reset Password") ?>");
      var kl_enter = new YAHOO.util.KeyListener(document, { keys:YAHOO.util.KeyListener.KEY.ENTER },  							
   		  { fn: doResetPassword,
   			scope: npadmin_resetPasswordDialog,
   			correctScope:true } );
      var kl_esc = new YAHOO.util.KeyListener(document, { keys:27 },  							
   		  { fn: defaultButtonHandler,
   			scope: npadmin_resetPasswordDialog,
   			correctScope:true } );
      npadmin_resetPasswordDialog.cfg.queueProperty("keylisteners", [kl_enter, kl_esc]);
      
      npadmin_resetPasswordDialog.render(document.body);
   } else {
      npadmin_resetPasswordDialog.form.reset();
   }
   npadmin_resetPasswordDialog.show();
}

function doResetPassword() {
	this.hide();
	var formObject = document.getElementById('npadmin_resetPasswordForm');
	YAHOO.util.Connect.setForm(formObject);
    var transaction = YAHOO.util.Connect.asyncRequest('POST', "<?= npadmin_setting('NP-ADMIN', 'BASE_URL') ?>/public/ajax/users.php", {success:passwordResetResult});
}

function passwordResetResult(response) {
	if (response.responseText.trim() == "OK")
		box_info("resetpassword_info", "Se ha enviado un email a la direcci�n indicada que te permitir� cambiar la password.");
	else
		box_error("resetpassword_error", "Se produjo un error al iniciar el proceso de cambio de password.");
}
