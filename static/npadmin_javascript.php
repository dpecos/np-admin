<?
$NPADMIN_PATH = "../";
require_once($NPADMIN_PATH."include/common.php");
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

/*
Array.prototype.sort = function()
{
   var tmp;
   for(var i=0;i<this.length;i++)
   {
       for(var j=0;j<this.length;j++)
       {
           if(this[i]<this[j])
           {
               tmp = this[i];
               this[i] = this[j];
               this[j] = tmp;
           }
       }
   }
};

Array.prototype.unshift = function(item)
{
   this[this.length] = null;
   for(var i=1;i<this.length;i++)
   {
       this[i] = this[i-1];
   }
   this[0] = item;
};

Array.prototype.shift = function()
{
   for(var i=1;i<this.length;i++) {
       this[i-1] = this[i];
   }
   this.length =  this.length-1;
};


Array.prototype.clear = function()
{
   this.length = 0;
};
 */

Array.prototype.contains = function (element)
{
	for (var i = 0; i < this.length; i++) {
		if (this[i] == element)
			return true;
	}
	return false;
};

/*
Array.prototype.shuffle = function()
{
   var i=this.length,j,t;
   while(i--) {
      j=Math.floor((i+1)*Math.random());
      t=arr[i];
      arr[i]=arr[j];
      arr[j]=t;
   }
};

Array.prototype.unique = function()
{
   var a=[],i;
   this.sort();
   for(i=0; i<this.length; i++) {
      if(!a.contains(this[i]))
         a[a.length] = this[i];
   }
   return a;
};

Array.prototype.lastIndexOf = function(n)
{
   var i=this.length;
   while(i--) {
      if(this[i]===n)
         return i;
   }
   return -1;
};
 */
