<?
global $ddbb;

$ddbb_table = "users";
$ddbb_mapping = array();
$ddbb_types = array();
$ddbb_sql = array();

$ddbb_mapping['user'] =        "user";
$ddbb_mapping['password'] =    "password";
$ddbb_mapping['email'] =       "email";
$ddbb_mapping['creation_date'] = "creation_date";
$ddbb_mapping['real_name'] =   "real_name";

$ddbb_types['user'] =        "STRING";
$ddbb_types['password'] =    "STRING";
$ddbb_types['email'] =       "STRING";
$ddbb_types['creation_date'] = "DATE";
$ddbb_types['real_name'] =   "STRING";

$ddbb_sql['user'] = array("PK" => true, "NULLABLE" => false, "LENGTH" => 20);
$ddbb_sql['password'] = array("NULLABLE" => false, "LENGTH" => 60);
$ddbb_sql['creation_date'] = array("NULLABLE" => false, "DEFAULT" => "CURRENT_TIMESTAMP");
$ddbb_sql['email'] = array("LENGTH" => 60, "DEFAULT" => NULL);
$ddbb_sql['real_name'] = array("LENGTH" => 60, "DEFAULT" => NULL);

$ddbb->addConfig("User", $ddbb_table, $ddbb_mapping, $ddbb_types, $ddbb_sql);
?>
