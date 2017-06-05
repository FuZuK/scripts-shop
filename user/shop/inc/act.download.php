<?
Users\User::if_user('is_reg');
$good = new UsersShop\Good(intval(@$_GET['good_id']));
if (!$good -> exists()) {
	$title = 'Ой, ошибочка получилась...';
	include(HEAD);
	echo alerts::error("Товар не найден");
	doc::back("Назад", "/");
	include(FOOT);
}
$good = new UsersShop\Good(intval(@$_GET['good_id']));
$category = $db -> farr('SELECT * FROM `users_shop_categories` WHERE `id` = ?', array($good -> id_category));
$seller = $good -> getSeller();
if (!adminka::access('shop_download_goods') && $u -> id != $seller -> id && !$db -> res("SELECT COUNT(*) FROM `users_shop_goods_solds` WHERE `id_good` = ? AND `id_user` = ? AND `state` != ?", array($good -> id, $u -> id, 'return'))) {
	$title = 'Ой, ошибочка получилась...';
	include(HEAD);
	echo alerts::error("Вы не покупали этот товар");
	doc::back("Назад", "/user/shop/?act=good&good_id={$good -> id}");
	include(FOOT);
}
if (!$u -> id != $seller -> id && !$db -> res("SELECT COUNT(*) FROM `users_shop_goods_solds` WHERE `id_good` = ? AND `id_user` = ? AND `state` != ?", array($good -> id, $u -> id, 'return'))) {
	adminka::adminsLog("Магазин", "Товары", "Скачан товар \"[url=http://$_SERVER[HTTP_HOST]/user/shop/?act=good&good_id={$good -> id}]{$good -> name}[/url]\"");
}
files::fileDL($good -> getFilePath(), TextUtils::retranslit(preg_replace('|\s|', '_', $good -> name)).'_'.$good -> id.'_'.date("d.m.Y").'_'.$u -> id."_".strtolower(SITE_NAME).".".$good -> ext, files::extToMime($good -> ext));
exit();
?>