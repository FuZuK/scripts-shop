<?
adminka::accessCheck('shop_delete_good');
$good = new UsersShop\Good(intval(@$_GET['good_id']));
if (!$good -> exists()) {
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
$el = array();
$title = 'Удалить товар';
include(HEAD);
if (isset($_POST['delete']) && ussec::check_p()) {
	header("Location: /shop/category/{$good -> shop_id_category}");
	$good -> deleteFromShop();
	alerts::msg_sess('Товар успешно удален');
	exit();
}
$el[] = array('type' => 'title', 'value' => 'Подтвердите удаление товара:', 'br' => true);
$el[] = array('type' => 'ussec');
$el[] = array('type' => 'submit', 'name' => 'delete', 'value' => 'Удалить', 'br' => true);
new SMX(array('el' => $el, 'method' => 'POST'), 'form.tpl');
Doc::back("Назад", "/shop/good/{$good -> id}");
include(FOOT);
?>