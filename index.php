<?
if (file_exists("private/config/ddbb_config.php")) {
  header("Location: public/index.php");
} else {
  header("Location: public/install.php");
}
?>
