<?
Users\User::if_user('is_reg');
$good = new UsersShop\Good(intval($_GET['good_id']));
if (!$good -> exists()) {
	$title = 'Ой, ошибочка получилась...';
	include(HEAD);
	echo alerts::error("Товар не найден");
	echo Doc::back('Назад', '/');
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
$seller = $good -> getSeller();
if (!(isset($u) && $seller -> id == $u -> id)) {
	$title = 'Ой, ошибочка получилась...';
	include(HEAD);
	echo alerts::error("Это не Ваш товар");
	echo Doc::back('Назад', '/user/shop/?act=good&good_id='.$good -> id);
	include(FOOT);
}
$title = 'Склеивание товаров';
include(HEAD);
if (isset($_GET['delete']) && ussec::check_g()) {
	$second_good = new UsersShop\Good(intval($_GET['delete']));
	if ($second_good -> exists() && $second_good -> hasGlueWithGood($good -> id)) 
		$good -> deleteGlueWithGood($second_good -> id);
	alerts::msg_sess('Клей успешно удален');
	header("Location: ?act=glue_goods&good_id={$good -> id}");
	exit();
}
if (isset($_POST['add_glue']) && ussec::check_p()) {
	$second_good = new UsersShop\Good(intval(@$_POST['good_for_glue']));
	if (!$second_good -> exists())
		$error = 'Товар не найден';
	elseif ($second_good -> id == $good -> id)
		$error = 'Этот товар уже имеет клей с выбранным товаром';
	elseif ($second_good -> getSeller() -> id != $u -> id)
		$error = 'Это не Ваш товар';
	elseif ($second_good -> hasGlueWithGood($good -> id))
		$error = 'Этот товар уже имеет клей с выбранным товаром';
	elseif (!$second_good -> isAddedToShop())
		$error = 'Выбранный товар должен быть добавлен в Магазин';
	else {
		$good -> addGlueWithGood($second_good -> id);
		alerts::msg_sess('Клей успешно создан');
		header("Location: ?act=glue_goods&good_id={$good -> id}");
		exit();
	}
}
echo alerts::error();
$q = $db -> q('SELECT * FROM `users_shop_goods_glues` WHERE `id_good_one` = ? OR `id_good_two` = ?', array($good -> id, $good -> id));
if ($q -> rowCount() > 0) {
	echo "<div class='content'>\n";
	echo "Существующие клеи:<br />\n";
	while ($post = $q -> fetch()) {
		echo "<div>\n";
		$var = 'id_good_'.($post -> id_good_two == $good -> id ? 'one' : 'two');
		$second_good = new UsersShop\Good($post -> $var);
		echo Doc::showLink('/user/shop/?act=good&good_id='.$second_good -> id, $second_good -> getCategory() -> getFullPathString().' &raquo; '.$second_good -> name).' '.Doc::showLink('?act=glue_goods&good_id='.$good -> id.'&delete='.$second_good -> id.'&'.ussec::link(), "<span style='color: red; font-size: 10px; vertical-align: 2px;'><b>x</b></span>");
		echo "</div>\n";
	}
	echo "</div>\n";
}
$options = array();
$q = $db -> q('SELECT * FROM `users_shop_goods` WHERE `id_user` = ? AND `deleted` = "0" AND `in_block` = "0" AND `id` != ?', array($u -> id, $good -> id));
while ($post = $q -> fetch()) {
	$second_good = new UsersShop\Good($post -> id);
	if (!$good -> hasGlueWithGood($second_good -> id) && $second_good -> isAddedToShop())
	$options[$second_good -> id] = $second_good -> getCategory() -> getFullPathString().' &raquo; '.$second_good -> name;
}
if (count($options) > 0) {
	$select = array('type' => 'select', 'name' => 'good_for_glue', 'options' => $options, 'br' => true);
	new SMX(array('el' => array(
		array('type' => 'title', 'value' => 'Выберите товар:', 'br' => true), 
		$select, 
		array('type' => 'ussec'), 
		array('type' => 'submit', 'name' => 'add_glue', 'value' => 'Добавить клей')
	)), 'form.tpl');
}
echo "<div class='content_redi'>\n";
echo "Склеивание разнотипных товаров приведет к их блокировке, без каких либо пояснений, возможности разблокировки или нового размещения.\n";
echo "</div>\n";
doc::back("Назад", "/user/shop/?act=good&good_id=".$good -> id);
include(FOOT);
?>