<?
namespace Shop;
final class Category {
	private $category_id = 0, $exists = false;
	public static $_categories;

	public function __construct($id) {
		global $db;
		$this -> category_id = $id;
		$this -> exists = $db -> res('SELECT COUNT(*) FROM `shop_categories` WHERE `id` = ?', array($this -> category_id)) == 0 ? false : true;
	}

	public function __get($column) {
		return $this -> getData($column) != null ? $this -> getData($column) : null;
	}

	private function getData($column) {
		return $this -> getAllData() -> $column;
	}

	private function getAllData() {
		$this -> fillData();
		return self::$_categories[$this -> getID()];
	}

	private function fillData() {
		global $db;
		if (!isset(self::$_categories[$this -> getID()])) {
			$data = null;
			if ($this -> exists())
				$data = $db -> farr('SELECT * FROM `shop_categories` WHERE `id` = ?', array($this -> getID()));
			else {
				if (!$db -> res('SELECT COUNT(*) FROM `shop_categories` WHERE `id_category` = ?', array(-1)))
					$db -> q('INSERT INTO `shop_categories` (`id_category`, `name`, `categories`, `upload`, `pos`) VALUES (?, ?, ?, ?, ?)', array(-1, 'Магазин', '/', 1, 1));
				$data = $db -> farr('SELECT * FROM `shop_categories` WHERE `id_category` = ?', array(-1));
			}
			self::$_categories[$this -> getID()] = $data;
		}
	}

	public function exists() {
		return $this -> exists;
	}

	public function getID() {
		return $this -> category_id;
	}

	public function delete($full = false) {
		global $db;
		// if (!$full) {
		// 	$db -> q('UPDATE `shop_categories` SET `deleted` = ? WHERE `id` = ?', array(1, $this -> id));
		// } else {
			$this -> deleteGoods();
			$this -> deleteCategories();
			$db -> q('DELETE FROM `shop_categories` WHERE `id` = ?', array($this -> id));
		// }
	}

	public function deleteGoods() {
		global $db;
		$q = $db -> q('SELECT * FROM `users_shop_goods` WHERE `shop_id_category` = ?', array($this -> id));
		while ($good = $q -> fetch()) {
			$good = new \UsersShop\Good($good -> id);
			$good -> deleteFromShop();
		}
	}

	public function deleteCategories() {
		global $db;
		$q = $db -> q('SELECT * FROM `shop_categories` WHERE `id_category` = ?', array($this -> id));
		while ($category = $q -> fetch()) {
			$category = new Category($category -> id);
			$category -> delete();
		}
	}

	public function getFullPathString() {
		global $db;
		$fullPathString = null;
		$explode_categories = preg_match_all("|/([0-9]{1,})|", $this -> categories, $matches);
		unset($matches[0]);
		unset($matches[1][0]);
		if (count($matches[1]) > 0) {
			foreach ($matches[1] as $category_id) {
				$category = new Category($category_id);
				$fullPathString .= \TextUtils::DBFilter($category -> name)." &raquo; ";
			}
		}
		$fullPathString .= $this -> name;
		return $fullPathString;
	}

	public function isRoot() {
		return $this -> id == Shop::getRootCategory() -> id;
	}

	public function getCategory() {
		return new Category($this -> id_category);
	}
}
?>