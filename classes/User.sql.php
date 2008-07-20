<?
global $npsql_dbconfig, $ddbb_table, $ddbb_mapping, $ddbb_types;

$ddbb_table['User'] = $npsql_dbconfig["PREFIX"]."users";

$ddbb_mapping['User']['user'] =        "user";
$ddbb_mapping['User']['password'] =    "password";
$ddbb_mapping['User']['email'] =       "email";
$ddbb_mapping['User']['creation_date'] = "creation_date";
$ddbb_mapping['User']['real_name'] =   "real_name";

$ddbb_types['User']['user'] =        "STRING";
$ddbb_types['User']['password'] =    "STRING";
$ddbb_types['User']['email'] =       "STRING";
$ddbb_types['User']['creation_date'] = "DATE";
$ddbb_types['User']['real_name'] =   "STRING";
?>
