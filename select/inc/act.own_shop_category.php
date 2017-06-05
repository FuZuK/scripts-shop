<?
Users\User::if_user('is_reg');
$userSelect = new UserSelect();
if (!$userSelect -> exists()) {
	$title = 'Ой, ошибочка получилась...';
	include(HEAD);
	echo alerts::error('Доступ запрещен');
	include(FOOT);
}
if (!$userSelect -> typeEquals('own_shop_category')) {
	$title = 'Ой, ошибочка получилась...';
	include(HEAD);
	echo alerts::error('Доступ запрещен');
	include(FOOT);
}
$category = new UsersShop\Category(intval(@$_GET['category_id']));
if (!$category -> exists()) {
	$title = 'Ой, ошибочка получилась...';
	include(HEAD);
	echo alerts::error('Категория не найдена');
	include(FOOT);
}
$us = new Users\User($category -> id_user);
if ($u -> id != $us -> id) {
	$title = 'Ой, ошибочка получилась...';
	include(HEAD);
	echo alerts::error('Можно выбирать только свои категории');
	include(FOOT);
}
if (isset($_GET['select_this'])) {
	$userSelect -> setSelectedElementValue('category_id', $category -> id);
	$userSelect -> setSelected(true);
	Doc::loc($userSelect -> getSelectElementValue('loc_link').UserSelect::SELECT_LINK_KEY.'='.$userSelect -> getId());
	exit();
}
$title = TextUtils::DBFilter($category -> getName());
include_once(HEAD);
echo "<div class='content'>\n";
echo Doc::showLink('?act=own_shop_category&category_id='.$category -> id.'&'.UserSelect::SELECT_LINK_KEY.'='.$userSelect -> getId().'&select_this', '&raquo; 	Выбрать категорию');
echo "</div>\n";
$q = $db -> q("SELECT * FROM `users_shop_categories` WHERE `id_category` = ? ORDER BY `name` ASC", array($category -> id));
while ($cat = $q -> fetch()) {
	$count_goods = $db -> res("SELECT COUNT(*) FROM `users_shop_goods` WHERE `categories` LIKE ?", array("%/".$cat -> id."/%"));
	echo "<div class='content_mess'>\n";
	echo "<div class='list_us_info'>\n";
	echo Doc::showImage('/images/folder_blue.png', array('class' => 'ic_big'))." ".Doc::showLink('?act=own_shop_category&category_id='.$cat -> id.'&'.UserSelect::SELECT_LINK_KEY.'='.$userSelect -> getId(), TextUtils::DBFilter($cat -> name));
	echo "</div>\n";
	echo Doc::addClear();
	echo "</div>\n";
}
if ($category -> isRoot())
	doc::back("Отмена", $userSelect -> getSelectElementValue('loc_link').UserSelect::SELECT_LINK_KEY.'='.$userSelect -> getId());
else {
	$back_category = new UsersShop\Category($category -> id_category);
	doc::back(TextUtils::DBFilter($back_category -> getName()), '?act=own_shop_category&category_id='.$back_category -> id.'&'.UserSelect::SELECT_LINK_KEY.'='.$userSelect -> getId());
}
?>