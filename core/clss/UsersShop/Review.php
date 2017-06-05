<?
namespace UsersShop;
final class Review {
	private $review_id, $exists = false;
	public static $_reviews = array();

	public function __construct($id) {
		global $db;
		$this -> review_id = $id;
		$this -> exists = $db -> res('SELECT COUNT(*) FROM `users_shop_goods_reviews` WHERE `id` = ?', array($this -> review_id)) == 0 ? false : true;
	}

	public function __get($column) {
		return $this -> getData($column) != null ? $this -> getData($column) : null;
	}

	private function getData($column) {
		return $this -> getAllData() -> $column;
	}

	private function getAllData() {
		$this -> fillData();
		return self::$_reviews[$this -> getID()];
	}

	private function fillData() {
		global $db;
		if (!isset(self::$_reviews[$this -> getID()])) {
			self::$_reviews[$this -> getID()] = $db -> farr('SELECT * FROM `users_shop_goods_reviews` WHERE `id` = ?', array($this -> getID()));
		}
	}

	public function exists() {
		return $this -> exists;
	}

	public function getID() {
		return $this -> review_id;
	}
}
?>