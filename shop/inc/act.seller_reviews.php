<?
$us = new Users\User(intval(@$_GET['user_id']));
if (!$us -> id) {
	$title = 'Ой, ошибочка получилась...';
	include(HEAD);
	echo alerts::error("Пользователь не найден");
	doc::back("Назад", "/shop");
	include(FOOT);
}
$title .= ' - Отзывы о товарах '.$us -> login;
include(HEAD);
$q_add = null;
if (isset($_GET['only']) && in_array(TextUtils::escape($_GET['only']), array('good', 'bad'))) {
	$only = TextUtils::escape($_GET['only']);
	if ($only == 'good')$q_add = " AND `users_shop_goods_reviews`.`type` = 'good'";
	elseif ($only == 'bad')$q_add = " AND `users_shop_goods_reviews`.`type` = 'bad'";
}
$count_results = $db -> res("SELECT COUNT(*) FROM `users_shop_goods` INNER JOIN `users_shop_goods_reviews` ON `users_shop_goods_reviews`.`id_good` = `users_shop_goods`.`id` WHERE `users_shop_goods`.`id_user` = ?$q_add", array($us -> id));
if (!$count_results) {
	doc::listEmpty("Нет отзывов");
}
$navi = new navi($count_results, '?');
$q = $db -> q("SELECT * FROM `users_shop_goods` INNER JOIN `users_shop_goods_reviews` ON `users_shop_goods_reviews`.`id_good` = `users_shop_goods`.`id` WHERE `users_shop_goods`.`id_user` = ?$q_add ORDER BY `users_shop_goods_reviews`.`time` DESC LIMIT ".$navi -> start.", ".$set -> results_on_page, array($us -> id));
$reviews = array();
while ($post = $q -> fetch()) {
	$us = new Users\User($post -> id_user);
	$good = new UsersShop\Good($post -> id_good);
	$reviews[] = array(
		'data' => $post, 
		'us' => $us, 
		'good' => $good
	);
}
$smarty = new SMX();
$smarty -> assign("reviews", $reviews);
$smarty -> display("list.reviews.tpl");
echo $navi -> show;
Doc::back("Назад", "/shop/sellers_rating");
include(FOOT);
?>