<?
$good = new UsersShop\Good(intval(@$_GET['good_id']));
if (!$good -> exists()) {
	$title = 'Ой, ошибочка получилась...';
	include(HEAD);
	echo alerts::error("Товар не найден");
	doc::back("Назад", "/");
	include(FOOT);
}
if ($good -> isDeleted() && !adminka::access('shop_view_deleted_goods')) {
	$title = 'Ой, ошибочка получилась...';
	include(HEAD);
	echo alerts::error("Товар не найден");
	doc::back("Назад", "/");
	include(FOOT);
}
if (!$good -> isAddedToShop()) {
	$title = 'Ой, ошибочка получилась...';
	include(HEAD);
	echo alerts::error("Товар не был добавлен в магазин");
	doc::back("Назад", "/shop/good/{$good -> id}");
	include(FOOT);
}
// if ($good -> isBlocked() && !adminka::access('shop_view_blocked_goods')) {
// 	$title = 'Ой, ошибочка получилась...';
// 	include(HEAD);
// 	echo alerts::error("Товар заблокирован");
// 	doc::back("Назад", "/");
// 	include(FOOT);
// }
$category = $good -> getShopCategory();
$seller = $good -> getSeller();
$show_good_info_configs['for'] = 'shop';
include_once(INCLUDS_DIR.'good_info.php');
?>