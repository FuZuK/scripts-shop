<?
namespace Users;
class NotificationsSettings {
	public $user_id = null;
	public static $_notifications_settings = array();

	public function __construct($user_id) {
		global $db;
		$this -> user_id = $user_id;
	}

	public function addRow() {
		global $db;
		$db -> q('INSERT INTO `users_notifications_settings` (`id_user`) VALUES (?)', array($this -> user_id));
	}

	public function getData($var) {
		return $this -> getAllData() -> $var;
	}

	public function getAllData() {
		$this -> fillData();
		return self::$_notifications_settings[$this -> user_id];
	}

	private function fillData() {
		global $db;
		if (!isset(self::$_notifications_settings[$this -> user_id])) {
			if ($db -> res('SELECT COUNT(*) FROM `users_notifications_settings` WHERE `id_user` = ?', array($this -> user_id)) == 0)
				$this -> addRow();
			self::$_notifications_settings[$this -> user_id] = $db -> farr('SELECT * FROM `users_notifications_settings` WHERE `id_user` = ?', array($this -> user_id));
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
		$this -> fillData();
		$this -> $var = $val;
		$db -> q('UPDATE `users_notifications_settings` SET `'.$var.'` = ? WHERE `id` = ?', array($val, $this -> id));
	}
}
?>