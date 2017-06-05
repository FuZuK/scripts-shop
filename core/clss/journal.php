<? # by Killer
class journal {
	public static function send ($author_id, $user_id, $cat, $object_type, $id_object, $id_object_moth, $last_time = 0) {
		global $db;
		$au = new Users\User($author_id);
		$us = new Users\User($user_id);
		if ($db -> res("SELECT COUNT(*) FROM `journal` WHERE `id_user` = ? AND `cat` = ? AND `object_type` = ? AND `id_object` = ?", array($us -> id, $cat, $object_type, $id_object))) {
			$post = $db -> farr("SELECT * FROM `journal` WHERE `id_user` = ? AND `cat` = ? AND `object_type` = ? AND `id_object` = ?", array($us -> id, $cat, $object_type, $id_object));
			$db -> q("UPDATE `journal` SET `time` = ?, `read` = ?, `id_author` = ?, `id_object_moth` = ? WHERE `id` = ?", array(time(), 0, $au -> id, $id_object_moth, $post -> id));
			$jid = $post -> id;
		} else {
			$db -> q("INSERT INTO `journal` (`id_author`, `id_user`, `cat`, `object_type`, `id_object`, `id_object_moth`, `time`, `last_time`) VALUES (?, ?, ?, ?, ?, ?, ?, ?)", array($au -> id, $us -> id, $cat, $object_type, $id_object, $id_object_moth, time(), $last_time));
			$jid = $db -> lastInsertId();
		}
		return $jid;
	}
	public static function update($cat, $object_id, $link='/cab') {
		global $u, $db;
		if (isset($u)) {
			if ($db -> res("SELECT COUNT(*) FROM `journal` WHERE `id_user` = ? AND `cat` = ? AND `id_object` = ? AND `read` = ?", array($u -> id, $cat, $object_id, 0))) {
				$db -> q("UPDATE `journal` SET `read` = ?, `last_time` = ? WHERE `id_user` = ? AND `cat` = ? AND `id_object` = ? AND `read` = ?", array(1, time(), $u -> id, $cat, $object_id, 0));
				header("Location: $link");
				//exit();
			}
		}
	}
}
?>