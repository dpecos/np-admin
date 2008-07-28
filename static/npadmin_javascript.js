function box(id, title, text, icon) {
   var dialog = new YAHOO.widget.SimpleDialog(id, 
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
   dialog.setHeader(title);  
   return dialog;
}

function defaultButtonHandler() {
   if (this.form) {
      this.cancel();
      this.form.reset();
   } else
      this.hide();
}

function box_block(id, text) {
   dialog = box(id, "Message", text, YAHOO.widget.SimpleDialog.ICON_BLOCK);
   var buttons = [ 
      { text:"OK", handler:defaultButtonHandler, isDefault:true }
	];
	dialog.cfg.queueProperty("buttons", buttons);
	dialog.render(document.body);
   dialog.show();
}

function box_info(id, text) {
   dialog = box(id, "Information", text, YAHOO.widget.SimpleDialog.ICON_INFO);
   var buttons = [ 
      { text:"OK", handler:defaultButtonHandler, isDefault:true }
	];
	dialog.cfg.queueProperty("buttons", buttons);
	dialog.render(document.body);
   dialog.show();
}

function box_warn(id, text) {
   dialog = box(id, "Warning", text, YAHOO.widget.SimpleDialog.ICON_WARN);
   var buttons = [ 
      { text:"OK", handler:defaultButtonHandler, isDefault:true }
	];
	dialog.cfg.queueProperty("buttons", buttons);
	dialog.render(document.body);
   dialog.show();
}

function box_error(id, text) {
   dialog = box(id, "Error", text, YAHOO.widget.SimpleDialog.ICON_ALARM);
   var buttons = [ 
      { text:"OK", handler:defaultButtonHandler, isDefault:true }
	];
	dialog.cfg.queueProperty("buttons", buttons);   
  	dialog.render(document.body);
   dialog.show();
}

function box_question(id, text, handleYes, handleNo) {
   dialog = box(id, "Question", text, YAHOO.widget.SimpleDialog.ICON_HELP, {});
   
   if (handleYes == null) handleYes = defaultButtonHandler;
   if (handleNo == null) handleNo = defaultButtonHandler;
   var buttons = [ 
      { text:"Yes", handler:handleYes },
	   { text:"No", handler:handleNo, isDefault:true } 
	];
	
	dialog.cfg.queueProperty("buttons", buttons);
	dialog.render(document.body);
   dialog.show();
}

String.prototype.ltrim = function() {
   return this.replace(/^\s+/, "");
}

String.prototype.rtrim = function() {
   return this.replace(/\s+$/, "");
}

String.prototype.trim = function() {
   return this.replace(/^\s+|\s+$/g,"");
}

function emptyList(listId) {
   var list = document.getElementById(listId);
   if (list) {
      while (list.firstChild) {
         list.removeChild(list.firstChild);
      }
   }
}
