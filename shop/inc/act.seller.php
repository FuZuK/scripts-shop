<?
$us = new Users\User(intval(@$_GET['seller_id']));
if (!$us -> id) {
	$title = 'Ой, ошибочка получилась...';
	include(HEAD);
	echo alerts::error("Пользователь не найден");
	include(FOOT);
}
$title .= ' - Товары '.$us -> login;
include(HEAD);
$count_results = $db -> res("SELECT COUNT(*) FROM `users_shop_goods` WHERE `id_user` = ? AND `deleted` = '0' AND `in_block` = '0' AND `shop_id_category` != '0'", array($us -> id));
$navi = new navi($count_results, "?");
$q = $db -> q("SELECT * FROM `users_shop_goods` WHERE `id_user` = ? AND `deleted` = '0' AND `in_block` = '0' AND `shop_id_category` != '0' ORDER BY `shop_time_add` DESC LIMIT ".$navi -> start.", ".$set -> results_on_page, array($us -> id));
$goods_show_configs['for'] = 'shop_category';
while ($post = $q -> fetch()) {
	$good = new UsersShop\Good($post -> id);
	include(INCLUDS_DIR.'list_goods.php');
}
echo $navi -> show;
Doc::back("Назад", $set -> profile_page.$us -> id);
include(FOOT);
?>