<?
$good = new UsersShop\Good(intval($_GET['good_id']));
if (!$good -> exists()) {
	$title = 'Ой, ошибочка получилась...';
	include(HEAD);
	echo alerts::error("Товар не найден");
	doc::back("Назад", "/");
	include(FOOT);
}
$category = $good -> getCategory();
$seller = $good -> getSeller();
if ($good -> isBlocked()) {
	adminka::accessCheck('shop_unblock_good');
	$title = 'Разблокировать товар';
	include(HEAD);
	if (isset($_POST['sfsk']) && ussec::check_p()) {
		if ($good -> isBlocked())$in_block = '0'; else $in_block = '1';
		if ($db -> res("SELECT COUNT(*) FROM `users_shop_goods_solds` WHERE `id_good` = ? AND `state` = ?", array($good -> id, 'in_block'))) {
			if (!$db -> res("SELECT COUNT(*) FROM `users_shop_goods_reviews` WHERE `id_good` = ? AND `type` = ?", array($good -> id, 'bad'))) {
				$db -> q("UPDATE `users_shop_goods_solds` SET `state` = ? WHERE `id_good` = ?", array('wait', $good -> id));
			}
		}
		adminka::adminsLog("Магазин", "Товары", "Разблокировка товара \"[url=http://$_SERVER[HTTP_HOST]/user/shop/?act=good&good_id=".$good -> id."]".$good -> name."[/url]\"");
		$db -> q("UPDATE `users_shop_goods` SET `in_block` = ? WHERE `id` = ?", array(0, $good -> id));
		alerts::msg_sess("Товар успешно разблокирован");
		header("Location: /user/shop/?act=good&good_id=".$good -> id);
		exit();
	}
	$el = array(
		array('type' => 'title', 'value' => 'Вы действительно хотите разблокировать этот товар?', 'br' => true), 
		array('type' => 'ussec'), 
		array('type' => 'submit', 'name' => 'sfsk', 'value' => 'Да, хочу')
	);
	new SMX(array('el' => $el), 'form.tpl');
} else {
	adminka::accessCheck('shop_block_good');
	$title = 'Заблокировать товар';
	include(HEAD);
	if (isset($_POST['sfsk']) && ussec::check_p()) {
		$msg = $_POST['msg'];
		if (TextUtils::length(trim($msg)) < 1)$error = 'Введите кооментарий';
		else {
			$db -> q("UPDATE `users_shop_goods_solds` SET `state` = ? WHERE `id_good` = ?", array('in_block', $good -> id));
			$db -> q("UPDATE `users_shop_goods` SET `in_block` = ?, `block_id_user` = ?, `block_msg` = ?, `block_time` = ? WHERE `id` = ?", array(1, $u -> id, $msg, time(), $good -> id));
			adminka::adminsLog("Магазин", "Товары", "Блокировка товара \"[url=http://$_SERVER[HTTP_HOST]/user/shop/?act=good&good_id=".$good -> id."]".$good -> name."[/url]\"");
			alerts::msg_sess("Товар успешно заблокирован");
			header("Location: /user/shop/?act=good&good_id=".$good -> id);
			exit();
		}
	}
	echo alerts::error();
	$el = array(
		array('type' => 'title', 'value' => 'Комментарий', 'br' => true), 
		array('type' => 'textarea', 'name' => 'msg', 'br' => true), 
		array('type' => 'ussec'), 
		array('type' => 'submit', 'name' => 'sfsk', 'value' => 'Заблокировать')
	);
	new SMX(array('el' => $el), 'form.tpl');
}
doc::back("Назад", "/user/shop/?act=good&good_id=".$good -> id);
include(FOOT);
?>