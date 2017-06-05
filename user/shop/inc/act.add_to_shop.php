<?
Users\User::if_user('is_reg');
$good = new UsersShop\Good(intval($_GET['good_id']));
if (!$good -> exists() || $good -> isDeleted() && !adminka::access('shop_view_deleted_goods')) {
	$title = 'Ой, ошибочка получилась...';
	include(HEAD);
	echo alerts::error("Товар не найден");
	echo Doc::back('Назад', '/');
	include(FOOT);
}
$category = $good -> getCategory();
$seller = $good -> getSeller();
if (!(isset($u) && $seller -> id == $u -> id)) {
	$title = 'Ой, ошибочка получилась...';
	include(HEAD);
	echo alerts::error("Это не Ваш товар");
	echo Doc::back('Назад', '/user/shop/?act=good&good_id='.$good -> id);
	include(FOOT);
}
if ($good -> isBlocked() == 1) {
	$title = 'Ой, ошибочка получилась...';
	include(HEAD);
	echo alerts::error("Запрещено добавлять в магазин заблокированные товары");
	doc::back("Назад", '/user/shop/?act=good&good_id='.$good -> id);
	include(FOOT);
}
if ($good -> isDeleted() == 1) {
	$title = 'Ой, ошибочка получилась...';
	include(HEAD);
	echo alerts::error("Запрещено добавлять в магазин удаленные товары");
	doc::back("Назад", '/user/shop/?act=good&good_id='.$good -> id);
	include(FOOT);
}
$title = 'Перемещение товара';
include(HEAD);
if (isset($_POST['sfsk']) && ussec::check_p()) {
	$shop_category_id = intval(@$_POST['shop_category']);
	$shop_category = new Shop\Category($shop_category_id);
	if (!$shop_category -> exists())
		$error = 'Категория не найдена';
	elseif ($shop_category -> upload == 0)
		$error = 'Сюда выгружать запрещено';
	else {
		$db -> q('UPDATE `users_shop_goods` SET `shop_id_category` = ?, `shop_categories` = ?, `shop_time_add` = ? WHERE `id` = ?', array($shop_category -> id, $shop_category -> categories.$shop_category -> id.'/', TimeUtils::currentTime(), $good -> id));
		header('Location: ?act=add_to_shop&good_id='.$good -> id);
		exit();
	}
}
echo alerts::error();
$options = array();
$root = new Shop\Category(0);
$q = $db -> q('SELECT * FROM `shop_categories` WHERE `id_category` = ?', array($root -> id));
while ($new_category = $q -> fetch()) {
	$options_new = array();
	$q2 = $db -> q('SELECT * FROM `shop_categories` WHERE `upload` = ? AND `categories` LIKE ?', array(1, "%/{$new_category -> id}/%"));
	while ($new_category2 = $q2 -> fetch()) {
		$new_category2 = new Shop\Category($new_category2 -> id);
		$options_new[$new_category2 -> id] = $new_category2 -> getFullPathString();
	}
	$options[$new_category -> name] = $options_new;
}
$select = array('type' => 'select', 'name' => 'shop_category', 'options' => $options, 'br' => true);
if ($good -> isAddedToShop()) {
	if (isset($_GET['delete']) && ussec::check_g()) {
		$good -> deleteFromShop();
		header('Location: ?act=add_to_shop&good_id='.$good -> id);
		exit();
	}
	$current_category = $good -> getShopCategory();
	$select['selected'] = $current_category -> id;
	echo "<div class='content'>\n";
	echo "<span class='form_q'>Текущая категория:</span> <a href='/shop/category/{$current_category -> id}'><span class='form_a'>{$current_category -> getFullPathString()}</span></a><br />\n";
	echo "<div style='margin-top: 4px;'>\n";
	echo Doc::showImage('/images/delete.png', array('class' => ICON_CLASS, 'width' => ICON_WH, 'height' => ICON_WH)).' '.Doc::showLink('?act=add_to_shop&good_id='.$good -> id.'&delete&'.ussec::link(), 'Удалить из магазина');
	echo "</div>\n";
	echo "</div>\n";
	echo "<hr>\n";
}
new SMX(array('el' => array(
	array('type' => 'title', 'value' => 'Выберите категорию:', 'br' => true), 
	$select, 
	array('type' => 'ussec'), 
	array('type' => 'submit', 'name' => 'sfsk', 'value' => $good -> isAddedToShop() ? 'Переместить' : 'Добавить')
)), 'form.tpl');
Doc::back("Назад", "/user/shop/?act=good&good_id=".$good -> id);
include(FOOT);
?>