<?
$category = new UsersShop\Category(intval(@$_GET['category_id']));
if (!$category -> exists()) {
	$title = 'Ой, ошибочка получилась...';
	include(HEAD);
	echo alerts::error("Категория не найдена");
	doc::back("Назад", "/");
	include(FOOT);
}
$category = $db -> farr('SELECT * FROM `users_shop_categories` WHERE `id` = ?', array(intval($_GET['category_id'])));
$us = new Users\User($category -> id_user);
if (!(isset($u) && $u -> id == $us -> id)) {
	$title = 'Ой, ошибочка получилась...';
	include(HEAD);
	echo alerts::error("Категория не найдена");
	doc::back("Назад", "/user/");
	include(FOOT);
}
$title = 'Добавление категории';
include(HEAD);
if (isset($_POST['sfsk']) && ussec::check_p()) {
	$name = $_POST['name'];
	if (TextUtils::length(trim($name)) < 1)$error = 'Введите название';
	elseif (TextUtils::length($name) > 50)$error = 'Название слишком длинное';
	else {
		$names_array = explode('/', $name);
		if (count($names_array) > 0) {
			$prevent_category = $category;
			foreach ($names_array as $new_category_name) {
				if (!$db -> res('SELECT COUNT(*) FROM `users_shop_categories` WHERE `id_category` = ? AND `name` = ?', array($prevent_category -> id, $new_category_name))) {
					$db -> q("INSERT INTO `users_shop_categories` (`name`, `id_category`, `categories`, `id_user`) VALUES (?, ?, ?, ?)", array($new_category_name, $prevent_category -> id, $prevent_category -> categories.$prevent_category -> id.'/', $u -> id));
					$prevent_category = new UsersShop\Category($db -> lastInsertId());
				} else {
					$prevent_category = UsersShop\Shop::findCategoryByName($new_category_name, $prevent_category -> id);
				}
			}
		} else {
			if (!$db -> res('SELECT COUNT(*) FROM `users_shop_categories` WHERE `id_category` = ? AND `name` = ?', array($category -> id, $name))) {
				$db -> q("INSERT INTO `users_shop_categories` (`name`, `id_category`, `categories`, `id_user`) VALUES (?, ?, ?, ?, ?)", array($name, $category -> id, $category -> categories.$category -> id.'/', $u -> id));
			}
		}
		alerts::msg_sess("Категория успешно добавлена");
		header("Location: /user/shop/?act=category&category_id=".$category -> id);
		exit();
	}
}
echo alerts::error();
$el = array();
$el[] = array('type' => 'title', 'value' => 'Название:', 'br' => true);
$el[] = array('type' => 'text', 'name' => 'name', 'br' => true);
$el[] = array('type' => 'ussec');
$el[] = array('type' => 'submit', 'name' => 'sfsk', 'value' => 'Добавить', 'br' => true);
new SMX(array('el' => $el, 'method' => 'POST'), 'form.tpl');
doc::back("Назад", "/user/shop/?act=category&category_id={$category -> id}");
include(FOOT);
?>