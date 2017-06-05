<?
$review = new UsersShop\Review(intval(@$_GET['review_id']));
if (!$review -> exists()) {
	$title = 'Ой, ошибочка получилась...';
	include(HEAD);
	echo alerts::error("Отзыв не найден");
	doc::back("Назад", "/");
	include(FOOT);
}
$good = new UsersShop\Good($review -> id_good);
$us = new Users\User($review -> id_user);
$seller = $good -> getSeller();
if (!($us -> id == $u -> id || adminka::access('shop_edit_reviews'))) {
	$title = 'Ой, ошибочка получилась...';
	include(HEAD);
	echo alerts::error("Вы не оставляли отзыв к этому товару");
	doc::back("Назад", "/user/shop/?act=reviews&good_id=".$good -> id);
	include(FOOT);
}
$title = 'Редактировать отзыв';
include(HEAD);
if (isset($_POST['sfsk']) && ussec::check_p()) {
	$mess = $_POST['mess'];
	$type = $_POST['type'];
	if (TextUtils::length(trim($mess)) < 1)$error = 'Введите отзыв';
	elseif (TextUtils::length($mess) > 1024)$error = 'Отзыв слишком длинный';
	elseif (!in_array($type, array('bad', 'good')))$error = 'Неверный тип отзыва';
	elseif (!Captcha::validate())$error = 'Вы ошиблись при вводе кода с картинки';
	else {
		$db -> q("UPDATE `users_shop_goods_reviews` SET `mess` = ?, `type` = ?, `rating` = ? WHERE `id` = ?", array($mess, $type, ($type == 'bad'?-$good -> price / 10:$good -> price / 100), $review -> id));
		$sold_row = $db -> farr("SELECT * FROM `users_shop_goods_solds` WHERE `id_user` = ? AND `id_good` = ?", array($u -> id, $good -> id));
		if ($type == 'good' && $review -> type == 'bad') {
			if ($review -> effect == 0) {
				$db -> q("UPDATE `users_shop_goods_solds` SET `time_output` = ? WHERE `id` = ?", array($sold_row -> time_output - 3600 * 24 * 2, $sold_row -> id));
				$db -> q("UPDATE `users_shop_goods_reviews` SET `effect` = ? WHERE `id` = ?", array(1, $review -> id));
			}
			if ($good -> isBlocked() && $db -> res('SELECT COUNT(*) FROM `users_shop_goods_reviews` WHERE `id_good` = ? AND `type` = ?', array($good -> id, 'bad')) == 0) {
				$good -> unblock();
			} else {
				$last_blocker_review = $db -> farr('SELECT * FROM `users_shop_goods_reviews` WHERE `id_good` = ? AND `type` = "bad" ORDER BY `time` DESC LIMIT 1', array($good -> id));
				$last_blocker = new Users\User($last_blocker_review -> id_user);
				$good -> block("Отрицательный отзыв пользователя [user]{$last_blocker -> login}[/user]", 0);
			}
			if ($db -> res("SELECT COUNT(*) FROM `users_shop_goods_solds` WHERE `id_good` = ? AND `state` = ?", array($good -> id, 'in_block')) && !$db -> res("SELECT COUNT(*) FROM `users_shop_goods_reviews` WHERE `id_good` = ? AND `type` = ?", array($good -> id, 'bad'))) {
				$db -> q("UPDATE `users_shop_goods_solds` SET `state` = ? WHERE `id_good` = ?", array('wait', $good -> id));
			}
		} elseif ($review -> type == 'good') {
			Users\Notifications::send('shop_good_bad_review', $seller -> id, 'Поступила притензия на ваш товар "'.$good -> name.'"');
			$db -> q("UPDATE `users_shop_goods_solds` SET `state` = ? WHERE `id_good` = ? AND `state` = 'wait'", array('in_block', $good -> id));
			$good -> block("Отрицательный отзыв пользователя [user]{$us -> login}[/user]", 0);
		}
		$good -> recountRating();
		$seller -> recountRating();
		// alerts::msg_sess("Отзыв успешно отредактирован");
		header("Location: /user/shop/?act=reviews&good_id=".$good -> id);
		exit();
	}
}
echo alerts::error();
$el = array(
	array('type' => 'title', 'value' => 'Ваш отзыв:', 'br' => true), 
	array('type' => 'textarea', 'name' => 'mess', 'value' => TextUtils::DBFilter($review -> mess), 'br' => true), 
	array('type' => 'select', 'name' => 'type', 'options' => array('good' => 'Положительный', 'bad' => 'Отрицательный'), 'selected' => $review -> type == 'good' ? 'good' : 'bad', 'br' => true), 
	array('type' => 'captcha', 'br' => true), 
	array('type' => 'ussec'), 
	array('type' => 'submit', 'name' => 'sfsk', 'value' => 'Сохранить')
);
new SMX(array('el' => $el), 'form.tpl');
doc::back("Назад", "/user/shop/?act=reviews&good_id=".$good -> id);
include(FOOT);
?>