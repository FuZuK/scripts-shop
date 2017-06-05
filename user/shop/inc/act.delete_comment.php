<?
adminka::accessCheck('shop_delete_comment');
$comment = new UsersShop\Comment(intval(@$_GET['comment_id']));
if (!$comment -> exists()) {
	$title = 'Ой, ошибочка получилась...';
	include(HEAD);
	echo alerts::error("Комментарий не найден");
	doc::back("Назад", "/");
	include(FOOT);
}
$us = new Users\User($comment -> id_user);
$good = new UsersShop\Good($comment -> id_good);
$category = $good -> getCategory();
$seller = $good -> getSeller();
if (ussec::check_g()) {
	adminka::adminsLog("Магазин", "Комментарии", "Удален комментарий к товару \"[url=http://$_SERVER[HTTP_HOST]/user/shop/?act=good&good_id={$good -> id}]{$good -> name}[/url]\"");
	$db -> q("DELETE FROM `users_shop_goods_comments` WHERE `id` = ?", array($comment -> id));
}
header("Location: /user/shop/?act=good&good_id=".$good -> id);
exit();
?>