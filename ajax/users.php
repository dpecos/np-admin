<?
global $PWD;
$PWD = "../";
require_once($PWD."include/common.php");

$returnList = false;

if ($_POST['op'] == "add") {
   $user = new User($_POST);
   if ($user->store())
      echo "OK";
   else 
      echo "ERROR";

} else if ($_POST['op'] == "delete") {
   $list = split(",", $_POST['list']);
   foreach ($list as $id) {
      $user = new User();
      $user->user = $id;
      if (!$user->delete()) {
         echo "ERROR: Unable to delete user '".$id."'";
         return;
      }
   }
   echo "OK";
} else if ($_POST['op'] == "list" || $_GET['op'] == "list") {
   $returnList = true;
}

if ($returnList) {
   $users = array();

   function createUserList($user) {
      global $users;
      $users[] = $user;
   }

   NP_executeSelect("SELECT * FROM npadmin_users", createUserList);

   echo json_encode($users); 
} 
?>
