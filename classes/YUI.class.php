<?

class YUI {
	private $css;
	private $js;
	private $loadComponents;

	public function __construct($path = null) {
   	$this->path = $path;


		$this->css = array (
		   "standar" => array("reset/reset.css", "fonts/fonts.css"),
			"tabview" => array("tabview/assets/skins/sam/tabview.css", "fonts/fonts-min.css"),
			"button" => array("button/assets/skins/sam/button.css"),
			"menu" => array("menu/assets/skins/sam/menu.css"),
			"datatable" => array("datatable/assets/skins/sam/datatable.css"),
			"logger" => array("logger/assets/skins/sam/logger.css"),
			"simpledialog" => array("container/assets/skins/sam/container.css", "button/assets/skins/sam/button.css"),
			"treeview" => array("treeview/assets/skins/sam/treeview.css"),
			"calendar" => array("calendar/assets/skins/sam/calendar.css")
		);

		$this->js = array(
		   "standar" => array("utilities/utilities.js"),
		   "tabview" => array("yahoo-dom-event/yahoo-dom-event.js","element/element.js", "tabview/tabview.js"),
		   "button" => array("yahoo-dom-event/yahoo-dom-event.js", "element/element.js", "button/button.js"),
		   "menu" => array("yahoo-dom-event/yahoo-dom-event.js", "container/container_core.js", "menu/menu.js"),
		   "datatable" => array("yahoo-dom-event/yahoo-dom-event.js","element/element.js", "datasource/datasource.js", "datatable/datatable.js"),
		   "ajax" => array("yahoo/yahoo.js", "connection/connection.js"),
		   "events" => array("event/event.js"),
		   "json" => array("yahoo/yahoo.js", "json/json.js"),
		   "logger" => array("yahoo-dom-event/yahoo-dom-event.js", "logger/logger.js"),
		   "simpledialog" => array("yahoo-dom-event/yahoo-dom-event.js", "animation/animation.js", "dragdrop/dragdrop.js", "element/element.js", "button/button.js", "container/container.js"),
		   "treeview" => array("yahoo/yahoo.js", "event/event.js", "treeview/treeview.js"),
		   "calendar" => array("yahoo-dom-event/yahoo-dom-event.js", "calendar/calendar.js")
		);

		$this->loadComponents = array();
	}

	public function add($component) {
	   $this->loadComponents[] = $component;
	}

	public function dependencies() {
	   $depCSS = array();
	   $depJS = array();

	   foreach ($this->loadComponents as $component) {
	      foreach ($this->css as $cssArray)
	         foreach ($cssArray as $css)
   	         if (!in_array($css, $depCSS))
   	            $depCSS[] = $css;
	      foreach ($this->js as $jsArray)
	         foreach ($jsArray as $js)
   	         if (!in_array($js, $depJS))
   	            $depJS[] = $js;
	   }

	   foreach ($depCSS as $css)
   	   echo '<link rel="stylesheet" type="text/css" href="'.$this->path."/".$css.'"/>'."\n";
   	foreach ($depJS as $js)
   	   echo '<script type="text/javascript" src="'.$this->path."/".$js.'"></script>'."\n";
	}
}

?>