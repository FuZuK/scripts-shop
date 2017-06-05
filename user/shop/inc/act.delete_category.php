<?
$category = new UsersShop\Category(intval(@$_GET['category_id']));
if (!$category -> exists()) {
	$title = 'Ой, ошибочка получилась...';
	include(HEAD);
	echo alerts::error("Категория не найдена");
	doc::back("Назад", "/user/shop/");
	include(FOOT);
}
$us = new Users\User($category -> id_user);
if (!(isset($u) && $u -> id == $us -> id)) {
	$title = 'Ой, ошибочка получилась...';
	include(HEAD);
	echo alerts::error("Это не Ваша категория");
	doc::back("Назад", "/user/shop/");
	include(FOOT);
}
if ($category -> isRoot()) {
	$title = 'Ой, ошибочка получилась...';
	include(HEAD);
	echo alerts::error('Запрещено удалять корневую категорию');
	doc::back("Назад", "/user/shop/");
	include(FOOT);
}
$title = 'Удаление категории';
include(HEAD);
if (isset($_POST['delete']) && ussec::check_p()) {
	$category -> delete();
	header("Location: ?act=category&category_id={$category -> id_category}");
}
echo alerts::error();
$el = array();
$el[] = array('type' => 'title', 'value' => 'Подтвердите удаление категории:', 'br' => true);
$el[] = array('type' => 'ussec');
$el[] = array('type' => 'submit', 'name' => 'delete', 'value' => 'Удалить', 'br' => true);
new SMX(array('el' => $el, 'method' => 'POST'), 'form.tpl');
doc::back("Назад", "/user/shop/?act=category&category_id={$category -> id}");
include(FOOT);
?>