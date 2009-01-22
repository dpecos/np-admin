<?
global $ddbb;

$ddbb->addTable("GroupRol", "groups_rols");
$ddbb->addField("GroupRol", "groupId", "group_id", "INT", array("PK" => true, "NULLABLE" => false, "LENGTH" => 11));
#$ddbb->addField("GroupRol", "rolName", "rol_name", "STRING", array("PK" => true, "NULLABLE" => false, "LENGTH" => 40));
$ddbb->addField("GroupRol", "rolId", "rol_id", "INT", array("PK" => true, "NULLABLE" => false));

class GroupRol {
}
?>
