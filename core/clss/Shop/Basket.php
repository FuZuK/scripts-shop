<?
namespace Shop;
class Basket {
	private function __construct() {}

	public static function addGoodToBasket($good_id, $user_id = null) {
		global $u, $db;
		$us = $u;
		if ($user_id != null)
			$us = new \Users\User($user_id);
		return $db -> q("INSERT INTO `basket` (`id_good`, `id_user`, `time`) VALUES (?, ?, ?)", array($good_id, $us -> id, \TimeUtils::currentTime()));
	}

	public static function deleteGoodFromBasket($good_id, $user_id = null) {
		global $u, $db;
		$us = $u;
		if ($user_id != null)
			$us = new \Users\User($user_id);
		return $db -> q("DELETE FROM `basket` WHERE `id_good` = ? AND `id_user` = ?", array($good_id, $us -> id));;
	}

	public static function hasGood($good_id, $user_id = null) {
		global $u, $db;
		$us = $u;
		if ($user_id != null)
			$us = new \Users\User($user_id);
		return $db -> res("SELECT COUNT(*) FROM `basket` WHERE `id_good` = ? AND `id_user` = ?", array($good_id, $us -> id)) != 0;
	}
}
?>