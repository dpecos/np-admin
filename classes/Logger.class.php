<?php
/** 
 * @package np-admin
 * @version 20090624
 * 
 * @author Daniel Pecos Martínez
 * @copyright Copyright (c) Daniel Pecos Martínez 
 * @license http://www.gnu.org/licenses/lgpl.html  LGPL License
 */
function npadmin_error($number, $msg,$file,$line) {
	Logger::error("npadmin", $number." file: ".$file." line: ".$line.": ".$msg);
} 

class Logger {
    
	static function init($names) {
		set_error_handler('npadmin_error');
		
		global $NPADMIN_PATH;
		date_default_timezone_set("Europe/Madrid");
		
		foreach ($names as $name) {	
			//$file = $NPADMIN_PATH."log/".$name."_".date("Ymd").".log";
			$logfile = npadmin_setting("NPLOG", "LOG_FILE_".$name);
			if ($logfile != null) {
   			$logfile = str_replace("#date#", date("Ymd"), $logfile);
   			NPLogger::init($name, $NPADMIN_PATH.$logfile, "ALL");
		   }
		}
	}
	
	static function debug($name, $msg) { NPLogger::debug($name, $msg); }
	static function info($name, $msg) { NPLogger::info($name, $msg);}
	static function error($name, $msg) { NPLogger::error($name, $msg); }	
	static function isEnabled() { return NPLogger::isEnabled(); }
}
?>