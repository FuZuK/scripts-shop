<?
$title .= ' - Новинки';
include(HEAD);
$count_results = $db -> res("SELECT COUNT(*) FROM `users_shop_goods` WHERE `deleted` = '0' AND `in_block` = '0' AND `shop_id_category` != '0'");
$navi = new navi($count_results, '?');
if (!$count_results)doc::listEmpty("Нет новых товаров");
$q = $db -> q("SELECT * FROM `users_shop_goods` WHERE `deleted` = '0' AND `in_block` = '0' AND `shop_id_category` != '0' ORDER BY `shop_time_add` DESC LIMIT ".$navi -> start.", ".$set -> results_on_page, array(time() - (3600 * 24)));
$goods_show_configs['for'] = 'shop_category';
while ($post = $q -> fetch()) {
	$good = new UsersShop\Good($post -> id);
	include(INCLUDS_DIR.'list_goods.php');
}
echo $navi -> show;
doc::back("В магазин", "/shop");
include(FOOT);
?>