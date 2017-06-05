<?
$category = new UsersShop\Category(intval(@$_GET['category_id']));
if (!$category -> exists()) {
	$title = 'Ой, ошибочка получилась...';
	include(HEAD);
	echo alerts::error("Категория не найдена");
	doc::back("Назад", "/");
	include(FOOT);
}
$us = new Users\User($category -> id_user);
if (!(isset($u) && $u -> id == $us -> id)) {
	$title = 'Ой, ошибочка получилась...';
	include(HEAD);
	echo alerts::error("Это не Ваша категория");
	doc::back("Назад", "/");
	include(FOOT);
}
if ($category -> isRoot()) {
	$title = 'Ой, ошибочка получилась...';
	include(HEAD);
	echo alerts::error('Запрещено редактировать корневую категорию');
	doc::back("Назад", "/user/shop/");
	include(FOOT);
}
$title = 'Редактирование категории';
include(HEAD);
if (isset($_POST['sfsk']) && ussec::check_p()) {
	$name = $_POST['name'];
	if (TextUtils::length(trim($name)) < 1)
		$error = 'Введите название';
	elseif (TextUtils::length($name) > 50)
		$error = 'Название слишком длинное';
	elseif ($db -> res('SELECT COUNT(*) FROM `users_shop_categories` WHERE `id_category` = ? AND `name` = ? AND `id` != ?', array($category -> id_category, $name, $category -> id)))
		$error = 'Такая категория уже была создана ранее';
	else {
		$db ->q("UPDATE `users_shop_categories` SET `name` = ? WHERE `id` = ?", array($name, $category -> id));
		alerts::msg_sess("Категория успешно отредактирована");
		header("Location: /user/shop/?act=category&category_id=".$category -> id);
		exit();
	}
}
echo alerts::error();
$el = array();
$el[] = array('type' => 'title', 'value' => 'Название:', 'br' => true);
$el[] = array('type' => 'text', 'name' => 'name', 'value' => TextUtils::DBFilter($category -> name), 'br' => true);
$el[] = array('type' => 'ussec');
$el[] = array('type' => 'submit', 'name' => 'sfsk', 'value' => 'Сохранить', 'br' => true);
new SMX(array('el' => $el, 'method' => 'POST'), 'form.tpl');
doc::back("Назад", "/user/shop/?act=category&category_id={$category -> id}");
include(FOOT);
?>