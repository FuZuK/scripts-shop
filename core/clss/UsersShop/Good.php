<?
namespace UsersShop;
final class Good {
	private $good_id = 0, $exists = false;
	public $file_ext = "dat";
	public static $_goods = array();

	public function __construct($id) {
		global $db;
		$this -> good_id = $id;
		$this -> exists = $db -> res('SELECT COUNT(*) FROM `users_shop_goods` WHERE `id` = ?', array($this -> good_id)) == 0 ? false : true;
	}

	public function __get($column) {
		return $this -> getData($column) != null ? $this -> getData($column) : null;
	}

	private function getData($column) {
		return $this -> getAllData() -> $column;
	}

	private function getAllData() {
		$this -> fillData();
		return self::$_goods[$this -> getID()];
	}

	private function fillData() {
		global $db;
		if (!isset(self::$_goods[$this -> getID()])) {
			$good = $db -> farr('SELECT * FROM `users_shop_goods` WHERE `id` = ?', array($this -> getID()));
			self::$_goods[$this -> getID()] = $good;
		}
	}

	public function reloadData() {
		unset(self::$_goods[$this -> getID()]);
		$this -> fillData();
	}

	public function getCountPreviews() {
		global $db;
		return $db -> res('SELECT COUNT(*) FROM `users_shop_goods_previews` WHERE `id_good` = ?', array($this -> getID()));
	}

	public function getMainPreview() {
		global $db;
		if ($this -> getCountPreviews() == 0) {
			$obj = (object) null;
			$obj -> preview_list = $obj -> preview_page = $obj -> preview_zoom = $obj -> preview_origin = '/images/no_photo.jpg';
			return $obj;
		}
		if (isset($_GET['preview_id']) && $db -> res("SELECT COUNT(*) FROM `users_shop_goods_previews` WHERE `id_good` = ? AND `id` = ?", array($this -> id, intval($_GET['preview_id']))))
			$preview_id = $db -> res("SELECT `id` FROM `users_shop_goods_previews` WHERE `id_good` = ? AND `id` = ?", array($this -> id, intval($_GET['preview_id'])));
		else
			$preview_id = $db -> res("SELECT `id` FROM `users_shop_goods_previews` WHERE `id_good` = ? ORDER BY `id` ASC LIMIT 1", array($this -> id));
		return new Preview($preview_id);
	}

	public function exists() {
		return $this -> exists;
	}

	public function getFilePath() {
		return GOODS.$this -> getID().'.'.$this -> file_ext;
	}

	public function getID() {
		return $this -> good_id;
	}

	public function getCountGoodReviews() {
		global $db;
		return $db -> res("SELECT COUNT(*) FROM `users_shop_goods_reviews` WHERE `id_good` = ? AND `type` = ?", array($this -> getID(), 'good'));
	}

	public function getCountBadReviews() {
		global $db;
		return $db -> res("SELECT COUNT(*) FROM `users_shop_goods_reviews` WHERE `id_good` = ? AND `type` = ?", array($this -> getID(), 'bad'));
	}

	public function getCountCopiesEnd() {
		global $db;
		return $this -> getData('copies') - $this -> getCountCopiesSold();
	}

	public function getCountCopiesSold() {
		global $db;
		return $db -> res("SELECT COUNT(*) FROM `users_shop_goods_solds` WHERE `id_good` = ?", array($this -> getID()));
	}

	public function getDownloadLink() {
		global $u;
		return '/user/shop/?act=download&good_id='.$this -> getId();
	}

	public function getDiscountForUser($user_id) {
		global $db;
		$us = new \Users\User($user_id);
		$seller = new \Users\User($this -> id_user);
		$price_bought_user_goods = $db -> res("SELECT SUM(`price`) FROM `users_shop_goods_solds` WHERE `id_user` = ? AND `id_seller` = ? AND `state` != ?", array($us -> id, $seller -> id, 'return'));
		if ($this -> discount >= 3 && $this -> discount <= 50 && $this -> discount_price >= 100 && $price_bought_user_goods >= $this -> discount_price)
			return ceil(($this -> price / 100) * $this -> discount);
		return 0;
	}

	public function getPriceWithPercents() {
		global $set;
		return floor($this -> price - (($this -> price / 100) * $set -> percentage));
	}

	public function delete($full = false) {
		global $db, $u;
		$db -> q('UPDATE `users_shop_goods` SET `deleted` = ?, `deleted_id_user` = ?, `deleted_time` = ? WHERE `id` = ?', array(1, $u -> id, \TimeUtils::currentTime(), $this -> id));
		$this -> reloadData();
	}

	public function deleteFromShop() {
		global $db;
		$db -> q('UPDATE `users_shop_goods` SET `shop_id_category` = ? WHERE `id` = ?', array(0, $this -> id));
	}

	public function restore() {
		global $db;
		$db -> q('UPDATE `users_shop_goods` SET `deleted` = ? WHERE `id` = ?', array(0, $this -> id));
		if (!$this -> getCategory() -> exists())
			$db -> q('UPDATE `users_shop_goods` SET `id_category` = ?, `categories` = ? WHERE `id` = ?', array(Shop::getRootCategory($this -> id_user) -> id, Shop::getRootCategory($this -> id_user) -> categories.Shop::getRootCategory($this -> id_user) -> id.'/', $this -> id));
		$this -> reloadData();
	}

	public function getCategory() {
		return new Category($this -> id_category);
	}

	public function getShopCategory() {
		return new \Shop\Category($this -> shop_id_category);
	}

	public function block($msg = null, $user_id = -1) {
		global $db, $u;
		$us = $u;
		if ($user_id != -1)
			$us = new \Users\User($user_id);
		$db -> q('UPDATE `users_shop_goods` SET `in_block` = ?, `block_id_user` = ?, `block_time` = ?, `block_msg` = ? WHERE `id` = ?', array(1, $us -> id, \TimeUtils::currentTime(), $msg, $this -> id));
		$this -> reloadData();
	}

	public function unblock() {
		global $db;
		$db -> q('UPDATE `users_shop_goods` SET `in_block` = ? WHERE `id` = ?', array(0, $this -> id));
		$this -> reloadData();
	}

	public function isDeleted() {
		return $this -> deleted == 1;
	}

	public function isBlocked() {
		return $this -> in_block == 1;
	}

	public function recountRating() {
		global $db;
		$rating = $db -> res("SELECT SUM(`rating`) FROM `users_shop_goods_reviews` WHERE `id_good` = ?", array($this -> id));
		$db -> q("UPDATE `users_shop_goods` SET `rating` = ? WHERE `id` = ?", array($rating, $this -> id));
		$this -> rating = $rating;
		return $rating;
	}

	public function getSeller() {
		return new \Users\User($this -> id_user);
	}

	public function isAddedToShop() {
		return $this -> shop_id_category != 0;
	}

	public function hasGlueWithGood($good_id) {
		global $db;
		return $db -> res('SELECT COUNT(*) FROM `users_shop_goods_glues` WHERE (`id_good_one` = ? AND `id_good_two` = ?) OR (`id_good_one` = ? AND `id_good_two` = ?)', array($this -> id, $good_id, $good_id, $this -> id)) != 0;
	}

	public function deleteGlueWithGood($good_id) {
		global $db;
		return $db -> q('DELETE FROM `users_shop_goods_glues` WHERE (`id_good_one` = ? AND `id_good_two` = ?) OR (`id_good_one` = ? AND `id_good_two` = ?)', array($this -> id, $good_id, $good_id, $this -> id));
	}

	public function addGlueWithGood($good_id) {
		global $db;
		return $db -> q('INSERT INTO `users_shop_goods_glues` (`id_good_one`, `id_good_two`) VALUES (?, ?)', array($this -> id, $good_id));
	}
}
?>