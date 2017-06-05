<?
$us = new Users\User(intval(@$_GET['user_id']));
if (!$us -> exists()) {
	$title = 'Ой, ошибочка получилась...';
	include(HEAD);
	echo alerts::error('Пользователь не найден');
	Doc::back('Назад', '/');
	include(FOOT);
}
if (!(adminka::access('shop_view_blocked_goods') || isset($u) && $us -> id == $u -> id)) {
	$title = 'Ой, ошибочка получилась...';
	include(HEAD);
	echo alerts::error('Доступ закрыт');
	Doc::back('Назад', '/');
	include(FOOT);
}
$title = 'Заблокированные товары '.$us -> login;
include(HEAD);
$count_results = $db -> res("SELECT COUNT(*) FROM `users_shop_goods` WHERE `in_block` = ? AND `id_user` = ?", array(1, $us -> id));
$navi = new navi($count_results, "?act=deleted_goods&user_id={$us -> id}");
$q = $db -> q("SELECT * FROM `users_shop_goods` WHERE `in_block` = ? AND `id_user` = ? ORDER BY `time_add` DESC LIMIT ".$navi -> start.", ".$set -> results_on_page, array(1, $us -> id));
while ($good = $q -> fetch()) {
	$good = new UsersShop\Good($good -> id);
	include(INCLUDS_DIR.'list_goods.php');
}
doc::back('Назад', '?user_id='.$us -> id);
include(FOOT);
?>