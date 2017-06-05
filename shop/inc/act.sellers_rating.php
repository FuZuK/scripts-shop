<?
$title .= ' - Рейтинг продавцов';
include(HEAD);
if (isset($u)) {
	echo "<div class='content_mess'>\n";
	echo "Вы на {$u -> rating_place}-ом месте по рейтингу<br />\n";
	echo "</div>\n";
}
$count_results = $db -> res("SELECT COUNT(*) FROM `users` WHERE `rating_place` > '0'");
$navi = new navi($count_results, '?');
if (!$count_results)echo Doc::listEmpty("Нет продавцов");
$q = $db -> q("SELECT * FROM `users` WHERE `rating_place` > '0' ORDER BY `rating_place` ASC LIMIT {$navi -> start}, {$set -> results_on_page}");
$users = array();
while ($us = $q -> fetch()) {
	$us = new Users\User($us -> id);
	$all_solds_price = $db -> res("SELECT CEIL(SUM(`price_with_percentage`)) FROM `users_shop_goods_solds` WHERE `id_seller` = ? AND `state` != 'return'", array($us -> id));
	$all_purchases_price = $db -> res("SELECT CEIL(SUM(`price`)) FROM `users_shop_goods_solds` WHERE `id_user` = ? AND `state` != ?", array($us -> id, 'return'));
	$all_goods_price = $db -> res("SELECT ROUND(SUM(`price`)) FROM `users_shop_goods` WHERE `id_user` = ? AND `deleted` = '0' AND `in_block` = '0' AND `shop_id_category` != '0'", array($us -> id));
	$count_goods = $db -> res("SELECT COUNT(*) FROM `users_shop_goods` WHERE `id_user` = ? AND `deleted` = '0' AND `in_block` = '0' AND `shop_id_category` != '0'", array($us -> id));
	$medium_price = intval($count_goods > 0 ? $all_goods_price / $count_goods : 0);
	$count_solds = $db -> res("SELECT COUNT(*) FROM `users_shop_goods_solds` WHERE `id_seller` = ? AND `state` != 'return'", array($us -> id));
	$count_purchases = $db -> res("SELECT COUNT(*) FROM `users_shop_goods_solds` WHERE `id_user` = ? AND `state` != 'return'", array($us -> id));
	$cound_all_deals = $count_solds + $count_purchases;
	$all_deals_price = $all_solds_price + $all_purchases_price;
	$infos = "Рейтинг: <span class='".($us -> rating > 0?"green":"red")."'>{$us -> rating}</span><br />\n";
	$infos .= "Безопасных сделок: {$cound_all_deals}<br />\n";
	$infos .= "На сумму: <span class='wmr_blue'>{$all_deals_price} WMR</span><br />\n";
	$infos .= "Продажи: <span class='wmr_blue'>{$all_solds_price} WMR</span><br />\n";
	$infos .= "Покупки: <span class='wmr_blue'>{$all_purchases_price} WMR</span><br />\n";
	$infos .= "Средняя стоимость товара: <span class='wmr_blue'>{$medium_price} WMR</span><br />\n";
	if ($us -> good_reviews > 0)$infos .= "<a href='/shop/seller_reviews/{$us -> id}?only=good' class='green'>Положительных отзывов: {$us -> good_reviews}</a><br />\n";
	if ($us -> bad_reviews > 0)$infos .= "<a href='/shop/seller_reviews/{$us -> id}?only=bad' class='red'>Отрицательных отзывов: {$us -> bad_reviews}</a><br />\n";
	$infos .= "<a href='/shop/seller/{$us -> id}'>Все товары продавца &raquo;</a><br />\n";
	$users[] = array(
		'us' => $us, 
		'info' => $infos
	);
}
$sets = array('rating' => true, 'hr' => true, 'rating' => true);
$smarty = new SMX();
$smarty -> assign("sets", $sets);
$smarty -> assign("users", $users);
$smarty -> display("list.users.tpl");
echo $navi -> show();
echo "<hr>\n";
echo "<div class='content_mess' style='overflow: hidden;'>\n";
echo "<div class='wety' style='border-bottom: 1px solid #CCCCCC;'>\n";
echo "Статистика сайта\n";
echo "</div>\n";
include_once(INCLUDS_DIR.'stat.php');
echo "</div>\n";
doc::back("Назад", "/");
include(FOOT);
?>