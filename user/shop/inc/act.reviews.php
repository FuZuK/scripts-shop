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
$title = 'Отзывы о товаре';
include(HEAD);
$q_add = null;
if (isset($_GET['only']) && in_array($_GET['only'], array('good', 'bad'))) {
	$only = $_GET['only'];
	if ($only == 'good')$q_add = " AND `type` = 'good'";
	elseif ($only == 'bad')$q_add = " AND `type` = 'bad'";
}
$count_results = $db -> res("SELECT COUNT(*) FROM `users_shop_goods_reviews` WHERE `id_good` = ?$q_add", array($good -> id));
if (!$count_results) {
	echo alerts::list_empty("Нет отзывов");
}
$navi = new navi($count_results, '?');
$q = $db -> q("SELECT * FROM `users_shop_goods_reviews` WHERE `id_good` = ?$q_add ORDER BY `time` DESC LIMIT ".$navi -> start.", ".$set -> results_on_page, array($good -> id));
$reviews = array();
while ($post = $q -> fetch()) {
	$us = new Users\User($post -> id_user);
	$actions = array();
	if (isset($u) && ($u -> id == $us -> id || adminka::access('shop_edit_reviews')))$actions[] = array(
		'link' => "/user/shop/?act=edit_review&review_id={$post -> id}", 
		'name' => "Редактировать"
	);
	$reviews[] = array(
		'data' => $post, 
		'us' => $us, 
		'actions' => $actions
	);
}
$smarty = new SMX();
$smarty -> assign("reviews", $reviews);
$smarty -> display("list.reviews.tpl");
echo $navi -> show;
if (isset($u) && !$db -> res("SELECT COUNT(*) FROM `users_shop_goods_reviews` WHERE `id_good` = ? AND `id_user` = ?", array($good -> id, $u -> id)) && $db -> res("SELECT COUNT(*) FROM `users_shop_goods_solds` WHERE `id_good` = ? AND `id_user` = ? AND `state` != ?", array($good -> id, $u -> id, 'return'))) {
	if (isset($_POST['sfsk']) && ussec::check_p()) {
		$mess = $_POST['mess'];
		$type = $_POST['type'];
		if (TextUtils::length(trim($mess)) < 1)$error = 'Введите отзыв';
		elseif (TextUtils::length($mess) > 1024)$error = 'Отзыв слишком длинный';
		elseif (!in_array($type, array('bad', 'good')))$error = 'Неверный тип отзыва';
		elseif (!Captcha::validate())$error = 'Вы ошиблись при вводе кода с картинки';
		else {
			$db -> q("INSERT INTO `users_shop_goods_reviews` (`id_good`, `id_user`, `mess`, `type`, `time`, `rating`) VALUES (?, ?, ?, ?, ?, ?)", array($good ->id, $u -> id, $mess, $type, time(), ($type == 'bad'?-$good -> price / 10:$good -> price / 100)));
			$rwid = $db -> lastInsertId();
			$sold_row = $db -> farr("SELECT * FROM `users_shop_goods_solds` WHERE `id_user` = ? AND `id_good` = ?", array($u -> id, $good -> id));
			if ($type == 'good') {
				// echo $sold_row -> time_output.'<br />';
				// echo (3600 * 24 * 2).'<br />';
				// echo $sold_row -> time_output - (3600 * 24 * 2).'<br />';
				$db -> q("UPDATE `users_shop_goods_solds` SET `time_output` = ? WHERE `id` = ?", array($sold_row -> time_output - (3600 * 24 * 2), $sold_row -> id));
				$db -> q("UPDATE `users_shop_goods_reviews` SET `effect` = ? WHERE `id` = ?", array(1, $rwid));
			} else {
				Users\Notifications::send('shop_good_bad_review', $seller -> id, 'Поступила притензия на ваш товар "'.$good -> name.'"');
				$db -> q("UPDATE `users_shop_goods_solds` SET `state` = ? WHERE `id_good` = ? AND `state` = 'wait'", array('in_block', $good -> id));
				$good -> block("Отрицательный отзыв пользователя [user]{$u -> login}[/user]", 0);
			}
			$good -> recountRating();
			$seller -> recountRating();
			alerts::msg_sess("Отзыв успешно добавлен");
			mailing::send_mess(0, $seller -> id, "[user]".$u -> login."[/user] оставил отзыв к товару \"[url=http://$_SERVER[HTTP_HOST]/user/shop/?act=good&good_id=".$good -> id."]".$good -> name."[/url]\"");
			header("Location: ?act=reviews&good_id=".$good -> id);
			exit();
		}
	}
	echo alerts::error();
	$el = array(
		array('type' => 'title', 'value' => 'Ваш отзыв:', 'br' => true), 
		array('type' => 'textarea', 'name' => 'mess', 'value' => '', 'br' => true), 
		array('type' => 'select', 'name' => 'type', 'options' => array('good' => 'Положительный', 'bad' => 'Отрицательный'), 'br' => true), 
		array('type' => 'captcha', 'br' => true), 
		array('type' => 'ussec'), 
		array('type' => 'submit', 'name' => 'sfsk', 'value' => 'Оставить отзыв')
	);
	new SMX(array('el' => $el), 'form.tpl');
}
Doc::back("Назад", "/user/shop/?act=good&good_id={$good -> id}");
include(FOOT);
?>