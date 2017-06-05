<?
$category = new UsersShop\Category(intval(@$_GET['category_id']));
if (!$category -> exists()) {
	$title = 'Ой, ошибочка получилась...';
	include(HEAD);
	echo alerts::error("Категория не найдена");
	doc::back("Назад", "/");
	include(FOOT);
}
$us = new Users\User($category -> id_user);
$title = TextUtils::DBFilter($category -> getName());
include(HEAD);
$q = $db -> q("SELECT * FROM `users_shop_categories` WHERE `id_category` = ? ORDER BY `name` ASC", array($category -> id));
while ($cat = $q -> fetch()) {
	$count_goods = $db -> res("SELECT COUNT(*) FROM `users_shop_goods` WHERE `categories` LIKE ? AND `deleted` = '0' AND `in_block` = '0'", array("%/".$cat -> id."/%"));
	echo "<div class='content_mess'>\n";
	echo "<div class='list_us_info'>\n";
	echo Doc::showImage('/images/folder_blue.png', array('class' => 'ic_big'))." ".Doc::showLink('/user/shop/?act=category&category_id='.$cat -> id, TextUtils::DBFilter($cat -> name))." <span>($count_goods)</span>";
	echo "</div>\n";
	echo Doc::addClear();
	echo "</div>\n";
}
$count_results = $db -> res("SELECT COUNT(*) FROM `users_shop_goods` WHERE `id_category` = ? AND `deleted` = '0' AND `in_block` = '0'", array($category -> id));
$navi = new navi($count_results, "?");
$q = $db -> q("SELECT * FROM `users_shop_goods` WHERE `id_category` = ? AND `deleted` = '0' AND `in_block` = '0' ORDER BY `time_add` DESC LIMIT ".$navi -> start.", ".$set -> results_on_page, array($category -> id));
while ($good = $q -> fetch()) {
	$good = new UsersShop\Good($good -> id);
	include(INCLUDS_DIR.'list_goods.php');
}
CActions::setSeparator('<br />');
CActions::setShowType(CActions::SHOW_ALL);
if (isset($u) && $u -> id == $us -> id) {
	echo "<hr>\n";
	CActions::addAction('/user/shop/?act=add_good&category_id='.$category -> id, 'Добавить товар', '/images/add1.png');
	CActions::addAction('/user/shop/?act=add_category&category_id='.$category -> id, 'Добавить категорию', '/images/add1.png');
	if (!$category -> isRoot()) {
		CActions::addAction('/user/shop/?act=edit_category&category_id='.$category -> id, 'Редактировать', '/images/edit.png');
		CActions::addAction('/user/shop/?act=replace_category&category_id='.$category -> id, 'Переместить', '/images/move.png');
		CActions::addAction('/user/shop/?act=delete_category&category_id='.$category -> id, 'Удалить', '/images/delete.png');
	}
	echo CActions::showActions();
}
$count_deleted_goods = $db -> res('SELECT COUNT(*) FROM `users_shop_goods` WHERE `deleted` = ? AND `id_user` = ?', array(1, $us -> id));
$count_blocked_goods = $db -> res('SELECT COUNT(*) FROM `users_shop_goods` WHERE `in_block` = ? AND `id_user` = ?', array(1, $us -> id));
if ($category -> isRoot() && ((adminka::access('shop_view_deleted_goods') || isset($u) && $us -> id == $u -> id) && $count_deleted_goods > 0 || (adminka::access('shop_view_blocked_goods') || isset($u) && $us -> id == $u -> id) && $count_blocked_goods > 0)) {
	echo "<hr>\n";
	if ((adminka::access('shop_view_deleted_goods') || isset($u) && $us -> id == $u -> id) && $count_deleted_goods > 0)
		CActions::addAction('/user/shop/?act=deleted_goods&user_id='.$us -> id, 'Удаленные товары ('.$count_deleted_goods.')', '/images/restore.png');
	if ((adminka::access('shop_view_blocked_goods') || isset($u) && $us -> id == $u -> id) && $count_blocked_goods > 0)
		CActions::addAction('/user/shop/?act=blocked_goods&user_id='.$us -> id, 'Заблокированные товары ('.$count_blocked_goods.')', '/images/restore.png');
	echo CActions::showActions();
}
if ($category -> isRoot())
	doc::back("Назад", '/user/'.$us -> id);
else {
	$back_category = new UsersShop\Category($category -> id_category);
	doc::back(TextUtils::DBFilter($back_category -> getName()), '/user/shop/?act=category&category_id='.$category -> id_category);
}
include(FOOT);
?>