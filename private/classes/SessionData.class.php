<?php
/** 
 * @package np-admin
 * @version 20090624
 * 
 * @author Daniel Pecos Martínez
 * @copyright Copyright (c) Daniel Pecos Martínez 
 * @license http://www.gnu.org/licenses/lgpl.html  LGPL License
 */
global $ddbb;

$ddbb->addTable("SessionData", "sessions");
$ddbb->addField("SessionData", "cookieValue", null, "STRING", array("PK" => true, "NULLABLE" => false, "LENGTH" => 40));
$ddbb->addField("SessionData", "data", null, "TEXT", array("NULLABLE" => false));

class SessionData {
   public function __construct($ses_id, $user, $password) {     
      $this->data = array();
      $this->data['ses_id'] = $ses_id;
      $this->data['username'] = $user;
      $this->data['password'] = $password;
      if ($this->data['username'] != null)
          $this->cookieValue = sha1($this->data['ses_id'].$this->data['username'].$this->data['password']);
      else
          $this->cookieValue = null;

      $auth = npadmin_setting("NP-ADMIN", "AUTH");
      $this->data['authenticator'] = $auth;
   }
   
   private function store() {
      $this->tmp = $this->data;
      $this->data = serialize($this);

      global $ddbb;
      $ddbb->insertObject($this);

      $this->data = $this->tmp;
      unset($this->tmp);

      setcookie("NPADMIN", $this->cookieValue, time()+60*60*24 , "/");

      return true;
   }

   private function load($cookieValue) {
      global $ddbb;
      $sql = "SELECT * FROM ".$ddbb->getTable('SessionData')." WHERE ".$ddbb->getMapping('SessionData','cookieValue')." = ".NP_DDBB::encodeSQLValue($cookieValue, $ddbb->getType('SessionData','cookieValue'));
      $result = $ddbb->executePKSelectQuery($sql);  
      $tmp = unserialize($result['data']);
      $this->data = $tmp->data;
      return $this;
   }
   
   private function delete() {
	   global $ddbb;
	   $sql_1 = "DELETE FROM ".$ddbb->getTable('SessionData')." WHERE ".$ddbb->getMapping('SessionData','cookieValue')." = ".NP_DDBB::encodeSQLValue($this->cookieValue, $ddbb->getType('SessionData','cookieValue'));
	   return ($ddbb->executeDeleteQuery($sql_1) > 0);
   }

   public static function loadCookie() {
       $session = null;
       if (array_key_exists("NPADMIN", $_COOKIE)) {
           $cookieValue = $_COOKIE["NPADMIN"];
           $session = new SessionData();
           $session->cookieValue = $cookieValue;
           $session->load($cookieValue);
       }
       return $session;
   }

   public function getUser() {
      return $this->data['user'];
   }

   public function login() {
       global $ddbb;

       $auth = new $this->data['authenticator']();
       $data = $auth->login($this->data['username'], $this->data['password']);
       if ($data != null) {
           $this->data['user'] = $data[0];
           $this->data['rols'] = $data[1];
           $this->data['groups'] = $data[2];

           try {
               $this->store();
           } catch (Exception $e) {
               $this->delete();
               $this->store();
           }

           return true;
       } else {
           return false;
       }
   }

   public function logout() {
      $this->delete();
      setcookie("NPADMIN", "", 0, "/");
   }

   public function canLogout() {
       $auth = new $this->data['authenticator']();
       return $auth->canLogout();
   }

   public function userInRol($rol) {
       $byId = true;
       if (is_string($rol))
           $byId = false;
       else if (is_int($rol))
           $byId = true;

       foreach ($this->data['rols'] as $g) {
           if ($byId) {
               if ($g->rolId == $rol)
                   return true;
           } else {
               if ($g->rolName == $rol)
                   return true;
           }
       }
       return false;
   }

   public function isAllowed($rols) {
       foreach ($rols as $rol) {
           if ($this->userInRol($rol))
               return true;
       }
       return false;
   }

   public function isLoginFormRequired() {
       $auth = new $this->data['authenticator']();
       $auth->isLoginFormRequired();
   }

   public function getRolsNames() {
       $data = array();
       foreach ($this->data['rols'] as $g) {
           $data[] = $g->rolName;
       }
       return $data;
   }

   public function getRolsIds() {
       $data = array();
       foreach ($this->data['rols'] as $g) {
           $data[] = $g->rolId;
       }
       return $data;
   }

   public function getGroups() {
       return $this->data['groups'];
   }

}
?>
