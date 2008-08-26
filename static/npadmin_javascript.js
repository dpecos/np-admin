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
