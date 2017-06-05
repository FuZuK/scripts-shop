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
	echo alerts::error("Товар удален");
	doc::back("Назад", "/");
	include(FOOT);
}
$category = $good -> getCategory();
$seller = $good -> getSeller();
include_once(INCLUDS_DIR.'good_info.php');
?>