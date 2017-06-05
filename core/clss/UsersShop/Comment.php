<?
namespace UsersShop;
final class Comment {
	private $comment_id, $exists = false;
	public static $_comments = array();

	public function __construct($id) {
		global $db;
		$this -> comment_id = $id;
		$this -> exists = $db -> res('SELECT COUNT(*) FROM `users_shop_goods_comments` WHERE `id` = ?', array($this -> comment_id)) == 0 ? false : true;
	}

	public function __get($column) {
		return $this -> getData($column) != null ? $this -> getData($column) : null;
	}

	private function getData($column) {
		return $this -> getAllData() -> $column;
	}

	private function getAllData() {
		$this -> fillData();
		return self::$_comments[$this -> getID()];
	}

	private function fillData() {
		global $db;
		if (!isset(self::$_comments[$this -> getID()]))
			self::$_comments[$this -> getID()] = $db -> farr('SELECT * FROM `users_shop_goods_comments` WHERE `id` = ?', array($this -> getID()));
	}

	public function exists() {
		return $this -> exists;
	}

	public function getID() {
		return $this -> comment_id;
	}
}
?>