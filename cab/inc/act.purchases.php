<?
$title = 'Мои покупки';
include(HEAD);
$count_results = $db -> res("SELECT COUNT(*) FROM `users_shop_goods_solds` WHERE `id_user` = ? AND `state` != ?", array($u -> id, 'return'));
if (!$count_results) {
	doc::listEmpty("Вы не совершали покупок");
}
$navi = new navi($count_results, '?');
$q = $db -> q("SELECT * FROM `users_shop_goods_solds` WHERE `id_user` = ? AND `state` != ? ORDER BY `time` DESC LIMIT ".$navi -> start.", ".$set-> results_on_page, array($u -> id, 'return'));
while ($post = $q -> fetch()) {
	$good = new UsersShop\Good($post -> id_good);
	include(INCLUDS_DIR.'list_goods.php');
}
echo $navi -> show;
doc::back("Назад", "/cab");
include(FOOT);