<?
if (isset($_GET['user_id'])) {
	$us = new Users\User(intval($_GET['user_id']));
	if (!$us -> id) {
		$title = 'Ой, ошибочка получилась...';
		include(HEAD);
		echo alerts::error("Пользователь не найден");
		doc::back("Назад", "/");
		include(FOOT);
	}
} else {
	Users\User::if_user('is_reg');
	$us = $u;
}
if (!$db -> res('SELECT COUNT(*) FROM `users_shop_categories` WHERE `id_user` = ? AND `id_category` = ?', array($us -> id, -1)))
	$db -> q('INSERT INTO `users_shop_categories` (`id_user`, `id_category`, `name`, `time_add`, `categories`) VALUES (?, ?, ?, ?, ?)', array($us -> id, -1, 'Магазин', TimeUtils::currentTime(), '/'));
$root = $db -> farr('SELECT * FROM `users_shop_categories` WHERE `id_category` = ? AND `id_user` = ?', array(-1, $us -> id));
header("Location: ?act=category&category_id=".$root -> id);
exit();
$title = 'Магазин '.$us -> login;
include(HEAD);
doc::back("Назад", "/user/");
include(FOOT);
?>