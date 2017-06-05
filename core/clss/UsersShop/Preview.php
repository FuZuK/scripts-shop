<?
namespace UsersShop;
final class Preview {
	private $preview_id, $exists = false;
	public static $_previews = array();

	public function __construct($id) {
		global $db;
		$this -> preview_id = $id;
		$this -> exists = $db -> res('SELECT COUNT(*) FROM `users_shop_goods_previews` WHERE `id` = ?', array($this -> preview_id)) == 0 ? false : true;
	}

	public function __get($column) {
		return $this -> getData($column) != null ? $this -> getData($column) : null;
	}

	private function getData($column) {
		return $this -> getAllData() -> $column;
	}

	private function getAllData() {
		$this -> fillData();
		return self::$_previews[$this -> getID()];
	}

	private function fillData() {
		global $db, $set;
		if (!isset(self::$_previews[$this -> getID()])) {
			$preview = $db -> farr('SELECT * FROM `users_shop_goods_previews` WHERE `id` = ?', array($this -> getID()));
			$preview -> preview_list = '/'.$set -> goods_previews_dir.$preview -> id_good."_".$preview -> id."_".(PREVIEW_LIST_WH)."x".(PREVIEW_LIST_WH).".".Shop::PREVIEW_EXTENSION;
			$preview -> preview_page = '/'.$set -> goods_previews_dir.$preview -> id_good."_".$preview -> id."_".($set -> wb ? 250 : 130)."x".($set -> wb ? 250 : 130).".".Shop::PREVIEW_EXTENSION;
			$preview -> preview_zoom = '/'.$set -> goods_previews_dir.$preview -> id_good."_".$preview -> id."_800x800.".Shop::PREVIEW_EXTENSION;
			$preview -> preview_original = '/'.$set -> goods_previews_dir.$preview -> id_good."_".$preview -> id."_original.".Shop::PREVIEW_EXTENSION;
			self::$_previews[$this -> getID()] = $preview;
		}
	}

	public function exists() {
		return $this -> exists;
	}

	public function getID() {
		return $this -> preview_id;
	}

	public function hasPreventPreview() {
		global $db;
		return $db -> res('SELECT COUNT(*) FROM `users_shop_goods_previews` WHERE `id` < ? AND `id_good` = ? ORDER BY `id` DESC LIMIT 1', array($this -> id, $this -> id_good)) == 0 ? false : true;
	}

	public function hasNextPreview() {
		global $db;
		return $db -> res('SELECT COUNT(*) FROM `users_shop_goods_previews` WHERE `id` > ? AND `id_good` = ? ORDER BY `id` ASC LIMIT 1', array($this -> id, $this -> id_good)) == 0 ? false : true;
	}

	public function getPreventPreview() {
		global $db;
		return $db -> farr('SELECT * FROM `users_shop_goods_previews` WHERE `id` < ? AND `id_good` = ? ORDER BY `id` DESC LIMIT 1', array($this -> id, $this -> id_good));
	}

	public function getNextPreview() {
		global $db;
		return $db -> farr('SELECT * FROM `users_shop_goods_previews` WHERE `id` > ? AND `id_good` = ? ORDER BY `id` ASC LIMIT 1', array($this -> id, $this -> id_good));
	}
}
?>