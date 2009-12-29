<?
$NPADMIN_PATH = "../../";
require_once($NPADMIN_PATH."private/include/common.php");
?>
var npadmin_dialogs = [];

function box(id, title, text, icon) {
	var dialog = null;
	if (npadmin_dialogs[id] == null) {
		dialog = new YAHOO.widget.SimpleDialog(id,
				{ width: "300px",
					effect: {effect:YAHOO.widget.ContainerEffect.FADE, duration:0.25},
					fixedcenter: true,
					visible: false,
					draggable: true,
					close: false,
					icon: icon,
					text: text,
					constraintoviewport: true,
					modal: true
				} );
		npadmin_dialogs[id] = dialog;
	} else {
		dialog = npadmin_dialogs[id];
	}
	dialog.setHeader(title);
	
    var kl_enter = new YAHOO.util.KeyListener(document, { keys:YAHOO.util.KeyListener.KEY.ENTER },  							
			  { fn: defaultButtonHandler,
				scope: dialog,
				correctScope:true } );
    var kl_esc = new YAHOO.util.KeyListener(document, { keys:27 },  							
			  { fn: (defaultButtonHandler),
				scope: dialog,
				correctScope:true } );
    dialog.cfg.queueProperty("keylisteners", [kl_enter, kl_esc]);
    
	return dialog;
}

function defaultButtonHandler() {
	if (this.form) {
		this.cancel();
		this.form.reset();
	} else
		this.hide();

	if (this.callback_func != null)
		setTimeout(this.callback_func, 50);
}

function box_block(id, text, fn) {
	dialog = box(id, "<?= _("Message") ?>", text, YAHOO.widget.SimpleDialog.ICON_BLOCK);
	var buttons = [
	               { text:"<?= _("OK") ?>", handler:defaultButtonHandler, isDefault:true }
	               ];
	dialog.cfg.queueProperty("buttons", buttons);
	dialog.callback_func = fn;
	dialog.render(document.body);
	dialog.show();
	
	return dialog;
}

function box_info(id, text, fn) {
	dialog = box(id, "<?= _("Information") ?>", text, YAHOO.widget.SimpleDialog.ICON_INFO);
	var buttons = [
	               { text:"<?= _("OK") ?>", handler:defaultButtonHandler, isDefault:true }
	               ];
	dialog.cfg.queueProperty("buttons", buttons);
	dialog.callback_func = fn;
	dialog.render(document.body);
	dialog.show();
	
	return dialog;
}

function box_warn(id, text, fn) {
	dialog = box(id, "<?= _("Warning") ?>", text, YAHOO.widget.SimpleDialog.ICON_WARN);
	var buttons = [
	               { text:"<?= _("OK") ?>", handler:defaultButtonHandler, isDefault:true }
	               ];
	dialog.cfg.queueProperty("buttons", buttons);
	dialog.callback_func = fn;
	dialog.render(document.body);
	dialog.show();
	
	return dialog;
}

function box_error(id, text, fn) {
	dialog = box(id, "<?= _("Error") ?>", text, YAHOO.widget.SimpleDialog.ICON_ALARM);
	var buttons = [
	               { text:"<?= _("OK") ?>", handler:defaultButtonHandler, isDefault:true }
	               ];
	dialog.cfg.queueProperty("buttons", buttons);
	dialog.callback_func = fn;
	dialog.render(document.body);
	dialog.show();
	
	return dialog;
}

function box_msg(id, title, text) {
	dialog = box(id, title, text, YAHOO.widget.SimpleDialog.ICON_INFO);
	dialog.render(document.body);
	dialog.show();
	
	return dialog;
}

function box_question(id, text, handleYes, handleNo) {
	dialog = box(id, "<?= _("Question") ?>", text, YAHOO.widget.SimpleDialog.ICON_HELP, {});

	if (handleYes == null) handleYes = defaultButtonHandler;
	if (handleNo == null) handleNo = defaultButtonHandler;
	var buttons = [
	               { text:"<?= _("Yes") ?>", handler:handleYes },
	               { text:"<?= _("No") ?>", handler:handleNo, isDefault:true }
	               ];

	dialog.cfg.queueProperty("buttons", buttons);
	dialog.render(document.body);
	dialog.show();
	
	return dialog;
}

function emptyList(listId) {
	var list = document.getElementById(listId);
	if (list) {
		while (list.firstChild) {
			list.removeChild(list.firstChild);
		}
	}
}

function changeLanguage(lang) {
    href = window.location.href;
    if (href.indexOf("?") >= 0) {
        i = href.indexOf("LANG=");
        if (i >= 0) {
            href = href.substring(0, i+5) + lang + href.substring(i+10);
        } else
            href += "&LANG=" + lang;
    } else {
        i = href.indexOf("LANG=");
        if (i >= 0) { 
            href = href.substring(0, i+5) + lang + href.substring(i+10);
        } else
            href += "?LANG=" + lang;
    }
    window.location.href = href;
}	

String.prototype.trim = function() {
	return this.replace(/^\s+|\s+$/g,"");
}
