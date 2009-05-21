<?
global $ddbb;

$ddbb->addTable("GroupRol", "groups_rols");
$ddbb->addField("GroupRol", "groupId", "group_id", "INT", array("PK" => true, "NULLABLE" => false, "LENGTH" => 11));
$ddbb->addField("GroupRol", "rolId", "rol_id", "INT", array("PK" => true, "NULLABLE" => false));

class GroupRol {
	public function __construct($data = null) {
		global $ddbb;
		$ddbb->loadData($this, $data);
	}

	public function store() {
		global $ddbb;
		$ddbb->insertObject($this);
		return true;
	}
}
?>
