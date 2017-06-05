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
$title = 'Реклама товара';
include(HEAD);
if (isset($_POST['sfsk']) && ussec::check_p()) {
	$name = $_POST['name'];
	if (TextUtils::length(trim($name)) < 5)$error = 'Название не должно быть меньше 5-ти символов';
	elseif (TextUtils::length($name) > 30)$error = 'Название не должно быть больше 30-ти символов';
	elseif ($u -> money_personal < $set -> advt_good_price)$error = 'У Вас недостаточно средств';
	else {
		if ($db -> res('SELECT COUNT(*) FROM `users_shop_goods_advt` WHERE `id_good` = ?', array($good -> id)) != 0)
			$db -> q('UPDATE `users_shop_goods_advt` SET `time` = ?, `name` = ? WHERE `id_good` = ?', array(TimeUtils::currentTime(), $name, $good -> id));
		else
			$db -> q("INSERT INTO `users_shop_goods_advt` (`id_user`, `id_good`, `time`, `name`) VALUES (?, ?, ?, ?)", array($u -> id, $good -> id, time(), $name));
		$db -> q("UPDATE `users` SET `money_personal` = ?, `money` = ? WHERE `id` = ?", array($u -> money_personal - $set -> advt_good_price, $u -> money - $set -> advt_good_price, $u -> id));
		alerts::msg_sess("Реклама товара успешно куплена");
		header("Location: /user/shop/?act=good&good_id=".$good -> id);
		exit();
	}
}
echo "<div class='content mod'>\n";
echo "После покупки рекламы, ссылка на Ваш товар \"<a href='/user/shop/?act=good&good_id={$good -> id}'>".TextUtils::DBFilter($good -> name)."</a>\" будет показанна вверху сайта. Она будет закреплена там, пока остальные товары ее не подвинут.<br />\n";
echo "Стоимость рекламы товара - <span class='wmr_blue'>{$set -> advt_good_price} WMR</span>\n";
echo "</div>\n";
echo "<hr>\n";
echo alerts::error();
$el = array(
	array('type' => 'title', 'value' => 'Название товара:', 'br' => true), 
	array('type' => 'text', 'name' => 'name', 'value' => TextUtils::DBFilter($good -> name), 'br' => true, 'alert' => 'Не меньше 5-ти и не больше 30-ти символов'), 
	array('type' => 'ussec'), 
	array('type' => 'submit', 'name' => 'sfsk', 'value' => 'Купить')
);
new SMX(array('el' => $el), 'form.tpl');
doc::back('Назад', "/user/shop/?act=good&good_id=".$good -> id);
include(FOOT);
?>