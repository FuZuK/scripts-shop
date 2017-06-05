<?
class blist {
	public function in ($user_id, $object_id, $object_type) {
		global $db;
		return $db -> res("SELECT COUNT(*) FROM `blacklist` WHERE `id_user` = ? AND `object` = ? AND `object_type` = ?", array(intval($user_id), floatval($object_id), intval($object_type)));
	}
}
?>