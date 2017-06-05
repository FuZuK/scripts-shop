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
if (adminka::access('shop_set_buyers_money')) {
	if (isset($_GET['io_block']) && $db -> res("SELECT COUNT(*) FROM `users_shop_goods_solds` WHERE `id_good` = ? AND `id` = ?", array($good -> id, intval($_GET['io_block'])))) {
		$post = $db -> farr("SELECT * FROM `users_shop_goods_solds` WHERE `id_good` = ? AND `id` = ?", array($good -> id, intval($_GET['io_block'])));
		$buyer = new Users\User($post -> id_user);
		if ($post -> state == 'in_block') {
			adminka::adminsLog("Магазин", "Средства", "Разблокированы средства покупателя [user]".$buyer -> login."[/user] товара \"[url=http://$_SERVER[HTTP_HOST]/user/shop/?act=good&good_id=".$good -> id."]".$good -> name."[/url] (ID сделки: ".$post -> id.")\"");
			$db -> q("UPDATE `users_shop_goods_solds` SET `state` = ? WHERE `id` = ?", array('wait', $post -> id));
			alerts::msg_sess("Средства успешно разблокированы");
		} elseif ($post -> state == 'wait') {
			adminka::adminsLog("Магазин", "Средства", "Заблокированы средства покупателя [user]".$buyer -> login."[/user] товара \"[url=http://$_SERVER[HTTP_HOST]/user/shop/?act=good&good_id=".$good -> id."]".$good -> name."[/url] (ID сделки: ".$post -> id.")\"");
			$db -> q("UPDATE `users_shop_goods_solds` SET `state` = ? WHERE `id` = ?", array('in_block', $post -> id));
			alerts::msg_sess("Средства успешно заблокированы");
		}
		header("Location: ?act=buyers&good_id=".$good -> id);
		exit();
	}
	if (isset($_GET['to_seller']) && $db -> res("SELECT COUNT(*) FROM `users_shop_goods_solds` WHERE `id_good` = ? AND `id` = ?", array($good -> id, intval($_GET['to_seller'])))) {
		$post = $db -> farr("SELECT * FROM `users_shop_goods_solds` WHERE `id_good` = ? AND `id` = ?", array($good -> id, intval($_GET['to_seller'])));
		if ($post -> state == 'wait' && $post -> time_output > time()) {
			$db -> q("UPDATE `users_shop_goods_solds` SET `state` = ? WHERE `id` = ?", array('out', $post -> id));
			$db -> q("UPDATE `users` SET `money` = ? WHERE `id` = ?", array($seller -> money + $post -> price_with_percentage, $seller -> id));
			$db -> q("INSERT INTO `moneylog` (`id_user`, `price`, `type`, `time`, `msg`) VALUES (?, ?, ?, ?, ?)", array($seller -> id, $good -> price, 'in', time(), "С продажи товара \"[url=http://$_SERVER[HTTP_HOST]/shop/good/{$good -> id}]{$good -> name}[/url]\""));
			adminka::adminsLog("Магазин", "Средства", "Возвращены средства покупателя [user]".$buyer -> login."[/user] товара \"[url=http://$_SERVER[HTTP_HOST]/user/shop/?act=good&good_id=".$good -> id."]".$good -> name."[/url]\" (ID сделки: ".$post -> id.")");
			alerts::msg_sess("Средства успешно возвращены");
		}
		header("Location: ?act=buyers&good_id=".$good -> id);
		exit();
	}
}
$title = 'Покупетели';
include(HEAD);
$cr = $db -> res("SELECT COUNT(*) FROM `users_shop_goods_solds` WHERE `id_good` = ?", array($good -> id));
$navi = new navi($cr, '?');
$q2 = $db -> q("SELECT * FROM `users_shop_goods_solds` WHERE `id_good` = ? ORDER BY `time` DESC LIMIT ".$navi -> start.", ".$set -> results_on_page, array($good -> id));
if (!$cr)Doc::listEmpty("Никто еще не покупал этот товар");
$users = array();
while ($post = $q2 -> fetch()) {
	$us = new Users\User($post -> id_user);
	$actions = array();
	$infos = "Дата покупки: <span class='time_show'>".TimeUtils::show($post -> time)."</span><br />\n";
	if (adminka::access('shop_set_buyers_money') && $post -> state != 'out' && $post -> state != 'return')$actions[] = array(
		'link' => "?act=buyers&good_id=".$good -> id."&io_block={$post -> id}", 
		'name' => ($post -> state == 'in_block' ? "Разблокировать" : "Заблокировать")
	);
	if (adminka::access('shop_set_buyers_money') && $post -> state == 'wait' && $post -> time_output > time()) {
		$actions[] = array(
			'link' => "?act=buyers&good_id=".$good -> id."&to_seller={$post -> id}", 
			'name' => "Вернуть продавцу"
		);
	}
	$users[] = array(
		'us' => $us, 
		'info' => $infos, 
		'actions' => $actions
	);
}
$sets = array('rating' => true, 'hr' => true, 'rating' => true);
$smarty = new SMX();
$smarty -> assign("sets", $sets);
$smarty -> assign("users", $users);
$smarty -> display("list.users.tpl");
echo $navi -> show;
doc::back("Назад", "/user/shop/?act=good&good_id=".$good -> id);
include(FOOT);
?>