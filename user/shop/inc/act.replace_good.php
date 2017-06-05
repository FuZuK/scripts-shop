<?
Users\User::if_user('is_reg');
$good = new UsersShop\Good(intval($_GET['good_id']));
if (!$good -> exists()) {
	$title = 'Ой, ошибочка получилась...';
	include(HEAD);
	echo alerts::error("Товар не найден");
	doc::back("Назад", "/");
	include(FOOT);
}
if ($good -> isDeleted()) {
	$title = 'Ой, ошибочка получилась...';
	include(HEAD);
	echo alerts::error("Товар удален");
	doc::back("Назад", "/");
	include(FOOT);
}
if ($good -> isBlocked()) {
	$title = 'Ой, ошибочка получилась...';
	include(HEAD);
	echo alerts::error("Товар заблокирован");
	doc::back("Назад", "/");
	include(FOOT);
}
$category = $good -> getCategory();
$seller = $good -> getSeller();
if ($seller -> id != $u -> id) {
	$title = 'Ой, ошибочка получилась...';
	include(HEAD);
	echo alerts::error("Это не Ваш товар");
	doc::back("Назад", "/");
	include(FOOT);
}
if (isset($_GET[UserSelect::SELECT_LINK_KEY])) {
	$userSelect = new UserSelect();
	if ($userSelect -> exists() && $userSelect -> typeEquals('own_shop_category') && $userSelect -> selectDataEquals('for', 'replace_good') && $userSelect -> selectDataEquals('good_id', $good -> id) && $userSelect -> selected()) {
		$selected_category = new UsersShop\Category($userSelect -> getSelectedElementValue('category_id'));
		if ($selected_category -> exists())
			if ($selected_category -> id_user == $u -> id)
				$db -> q('UPDATE `users_shop_goods` SET `id_category` = ?, `categories` = ? WHERE `id` = ?', array($selected_category -> id, $selected_category -> categories.$selected_category -> id.'/', $good -> id));
	}
	$userSelect -> delete();
	Doc::loc('/user/shop/?act=good&good_id='.$good -> id);
	exit();
}
$userSelect = new UserSelectBuilder();
$userSelect -> setType('own_shop_category');
$userSelect -> addSelectElement('loc_link', '/user/shop/?act=replace_good&good_id='.$good -> id.'&');
$userSelect -> addSelectElement('for', 'replace_good');
$userSelect -> addSelectElement('good_id', $good -> id);
$userSelect -> create();
$root = UsersShop\Shop::getRootCategory($seller -> id);
Doc::loc('/select/?act=own_shop_category&category_id='.$root -> id.'&'.UserSelect::SELECT_LINK_KEY.'='.$userSelect -> getId());
exit();
?>