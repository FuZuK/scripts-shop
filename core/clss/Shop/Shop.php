<?
namespace Shop;
final class Shop {
	public static function getRootCategory() {
		global $db;
		return new Category($db -> res('SELECT `id` FROM `shop_categories` WHERE `id_category` = ? LIMIT 1', array(-1)));
	}

	public static function findCategoryByName($name, $category_id) {
		global $db;
		return new Category($db -> res('SELECT `id` FROM `shop_categories` WHERE `name` = ? AND `id_category` = ?', array($name, $category_id)));
	}
}
?>