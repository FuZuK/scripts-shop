<?
Users\User::if_user('is_reg');
$category = new UsersShop\Category(intval($_GET['category_id']));
if (!$category -> exists()) {
	$title = 'Ой, ошибочка получилась...';
	include(HEAD);
	echo alerts::error("Категория не найдена");
	doc::back("Назад", "/");
	include(FOOT);
}
$us = new Users\User($category -> id_user);
if ($us -> id != $u -> id) {
	$title = 'Ой, ошибочка получилась...';
	include(HEAD);
	echo alerts::error("Это не Ваша категория");
	doc::back("Назад", "/");
	include(FOOT);
}
if (isset($_GET[UserSelect::SELECT_LINK_KEY])) {
	$userSelect = new UserSelect();
	if ($userSelect -> exists() && $userSelect -> typeEquals('own_shop_category') && $userSelect -> selectDataEquals('for', 'replace_category') && $userSelect -> selectDataEquals('category_id', $category -> id) && $userSelect -> selected()) {
		$selected_category = new UsersShop\Category($userSelect -> getSelectedElementValue('category_id'));
		if ($selected_category -> exists())
			if ($selected_category -> id_user == $u -> id) {
				// TODO replacing goods and categories
				$db -> q('UPDATE `users_shop_categories` SET `id_category` = ?, `categories` = ? WHERE `id` = ?', array($selected_category -> id, $selected_category -> categories.$selected_category -> id.'/', $category -> id));
				$q = $db -> q('SELECT * FROM `users_shop_categories` WHERE `categories` LIKE ?', array('%/'.$category -> id.'/%'));
				while ($post = $q -> fetch()) {
					$db -> q('UPDATE `users_shop_categories` SET `categories` = ? WHERE `id` = ?', array(str_replace($category -> categories, $selected_category -> categories.$selected_category -> id.'/', $post -> categories), $post -> id));
				}
				$q = $db -> q('SELECT * FROM `users_shop_goods` WHERE `categories` LIKE ?', array('%/'.$category -> id.'/%'));
				while ($post = $q -> fetch()) {
					$db -> q('UPDATE `users_shop_goods` SET `categories` = ? WHERE `id` = ?', array(str_replace($category -> categories, $selected_category -> categories.$selected_category -> id.'/', $post -> categories), $post -> id));
				}
			}
	}
	$userSelect -> delete();
	Doc::loc('/user/shop/?act=category&category_id='.$category -> id);
	exit();
}
$userSelect = new UserSelectBuilder();
$userSelect -> setType('own_shop_category');
$userSelect -> addSelectElement('loc_link', '/user/shop/?act=replace_category&category_id='.$category -> id.'&');
$userSelect -> addSelectElement('for', 'replace_category');
$userSelect -> addSelectElement('category_id', $category -> id);
$userSelect -> create();
$root = UsersShop\Shop::getRootCategory($us -> id);
Doc::loc('/select/?act=own_shop_category&category_id='.$root -> id.'&'.UserSelect::SELECT_LINK_KEY.'='.$userSelect -> getId());
exit();
?>