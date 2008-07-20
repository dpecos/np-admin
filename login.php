<?
$PWD = "./";
require_once($PWD."include/common.php");
?>

<?
function html_head() {
   global $PWD;
?>
<script>
   function doLogin() {
      var formObject = document.getElementById('login_form');
      if (formObject.user.value.trim().length == 0 || formObject.password.value.trim().length == 0)
         box_block("userlogin_block", "All the required fields have to be filled");
      else {
         YAHOO.util.Connect.setForm(formObject); 
         var transaction = YAHOO.util.Connect.asyncRequest('POST', "<?= $PWD ?>ajax/users.php", {success:loginCallback});
      }
   }
   
   function loginCallback(response) {   
      if (response.responseText.trim() == "OK") 
         document.location.href = "<?= $_GET['referer'] ?>";
      else {
         var formObject = document.getElementById('login_form');
         formObject.password.value = "";
         box_error("userlogin_error", "Incorrect user/password");
      }
   }
</script>
<?
}
?>
<? require_once($PWD."include/header.php"); ?>

<form id="login_form">  
User: <input type="text" name="user"/><br/>
Password: <input type="password" name="password"/><br/>
<input type="hidden" name="op" value="login"/><br/>
<input type="button" value="login" onclick="javascript:doLogin()"/>
</form>

<? require_once($PWD."include/footer.php"); ?>
