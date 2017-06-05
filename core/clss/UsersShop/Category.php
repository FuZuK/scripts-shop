<?
namespace UsersShop;
final class Category {
	private $category_id = 0, $exists = false;
	public static $_categories;

	public function __construct($id) {
		global $db;
		$this -> category_id = $id;
		$this -> exists = $db -> res('SELECT COUNT(*) FROM `users_shop_categories` WHERE `id` = ?', array($this -> category_id)) == 0 ? false : true;
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
		if (!isset(self::$_categories[$this -> getID()]))
			self::$_categories[$this -> getID()] = $db -> farr('SELECT * FROM `users_shop_categories` WHERE `id` = ?', array($this -> getID()));
	}

	public function reload() {
		global $db;
		unset(self::$_categories[$this -> getID()]);
		$this -> fillData();
	}

	public function exists() {
		return $this -> exists;
	}

	public function getID() {
		return $this -> category_id;
	}

	public function isRoot() {
		return $this -> getID() == Shop::getRootCategory($this -> id_user) -> id;
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

	public function delete($full = false) {
		global $db;
		$this -> deleteGoods();
		$this -> deleteCategories();
		$db -> q('DELETE FROM `users_shop_categories` WHERE `id` = ?', array($this -> id));
	}

	public function deleteGoods() {
		global $db;
		$q = $db -> q('SELECT * FROM `users_shop_goods` WHERE `id_category` = ?', array($this -> id));
		while ($good = $q -> fetch()) {
			$good = new Good($good -> id);
			$good -> delete();
		}
	}

	public function deleteCategories() {
		global $db;
		$q = $db -> q('SELECT * FROM `users_shop_categories` WHERE `id_category` = ?', array($this -> id));
		while ($category = $q -> fetch()) {
			$category = new Category($category -> id);
			$category -> delete();
		}
	}

	public function getName() {
		$us = new \Users\User($this -> id_user);
		return $this -> isRoot() ? 'Магазин '.$us -> login : $this -> name;
	} 
}
?>