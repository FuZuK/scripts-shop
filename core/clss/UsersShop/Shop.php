<?
namespace UsersShop;
final class Shop {
	const PREVIEW_EXTENSION = 'jpg';
	const PREVIEW_MIME_TYPE = 'image/jpeg';

	private function __construct() {}

	public static function getRootCategory($user_id) {
		global $db;
		return new Category($db -> res('SELECT `id` FROM `users_shop_categories` WHERE `id_category` = ? AND `id_user` = ?', array(-1, $user_id)));
	}

	public static function hasRootCategory($user_id) {}

	public static function findCategoryByName($name, $category_id) {
		global $db;
		return new Category($db -> res('SELECT `id` FROM `users_shop_categories` WHERE `name` = ? AND `id_category` = ?', array($name, $category_id)));
	}

	public static function goodExists($good_id) {
		$good = new Good($good_id);
		return $good -> exists();
	}

	public static function goodBlocked($good_id) {
		$good = new Good($good_id);
		return $good -> isBlocked();
	}

	public static function goodDeleted($good_id) {
		$good = new Good($good_id);
		return $good -> isDeleted();
	}

	public static function userBoughtGood($good_id, $user_id = null) {
		global $db, $u;
		$us = $u;
		if ($user_id != null)
			$us = new \Users\User($user_id);
		$good = new Good($good_id);
		return $db -> res('SELECT COUNT(*) FROM `users_shop_goods_solds` WHERE `id_user` = ? AND `id_good` = ? AND `state` != "return"', array($us -> id, $good -> id)) != 0;
	}

	public static function showMoneyFormed($money) {
		return sprintf("%01.2f", $money);
	}
}
?>