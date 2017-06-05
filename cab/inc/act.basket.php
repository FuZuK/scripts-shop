<?
$title = 'Корзина';
include(HEAD);
if (isset($_GET['add']) && UsersShop\Shop::goodExists(intval($_GET['add'])) && !UsersShop\Shop::goodBlocked(intval($_GET['add'])) && !UsersShop\Shop::goodDeleted(intval($_GET['add'])) && !Shop\Basket::hasGood(intval($_GET['add']))) {
	$good = new UsersShop\Good(intval($_GET['add']));
	Shop\Basket::addGoodToBasket($good -> id);
	alerts::msg_sess("Товар успешно добавлен в корзину");
	header("Location: /shop/good/".$good -> id);
	exit();
}
if (isset($_GET['delete']) && Shop\Basket::hasGood(intval($_GET['delete']))) {
	Shop\Basket::deleteGoodFromBasket(intval($_GET['delete']));
	alerts::msg_sess("Товар успешно удален из корзины");
	header("Location: /cab/basket");
	exit();
}
$count_results = $db -> res("SELECT COUNT(*) FROM `basket` INNER JOIN `users_shop_goods` ON `basket`.`id_good` = `users_shop_goods`.`id` WHERE `basket`.`id_user` = ? AND `users_shop_goods`.`in_block` = '0' AND `users_shop_goods`.`deleted` = '0' AND `users_shop_goods`.`shop_id_category` != '0'", array($u -> id));
if (!$count_results)
echo alerts::list_empty('Корзина пуста');
$navi = new navi($count_results, '?');
$q = $db -> q("SELECT * FROM `basket` INNER JOIN `users_shop_goods` ON `basket`.`id_good` = `users_shop_goods`.`id` WHERE `basket`.`id_user` = ? AND `users_shop_goods`.`in_block` = '0' AND `users_shop_goods`.`deleted` = '0' AND `users_shop_goods`.`shop_id_category` != '0' ORDER BY `basket`.`time` DESC LIMIT ".$navi -> start.", ".$set -> results_on_page, array($u -> id));
$goods_show_configs['for'] = 'basket';
while ($post = $q -> fetch()) {
	$good = new UsersShop\Good($post -> id_good);
	include(INCLUDS_DIR.'list_goods.php');
}
echo $navi -> show;
doc::back("Назад", "/cab");
include(FOOT);
?>