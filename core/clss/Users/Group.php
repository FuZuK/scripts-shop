<?
namespace Users;
class Group {
	public $group_id = null;
	public static $_groups = array();

	public function __construct($group_id) {
		$this -> group_id = $group_id;
	}

	public function getData($var) {
		return $this -> getAllData() -> $var;
	}

	public function getAllData() {
		$this -> fillData();
		return self::$_groups[$this -> group_id];
	}

	private function fillData() {
		global $db;
		if (!isset(self::$_groups[$this -> group_id])) {
			self::$_groups[$this -> group_id] = $db -> farr('SELECT * FROM `users_groups` WHERE `id` = ?', array($this -> group_id));
		}
	}

	public function __get($var) {
		return $this -> getData($var);
	}

	public function __set($var, $val) {
		$this -> $var = $val;
	}

	public function setData($var, $val) {
		global $db;
		$this -> $var = $val;
		$db -> q('UPDATE `users_groups` SET `'.$var.'` = ? WHERE `id` = ?', array($val, $this -> id));
	}
}
?>