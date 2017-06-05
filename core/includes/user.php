<?
$q = $db -> q("SELECT * FROM `users_shop_goods_solds` WHERE `id_seller` = ? AND `time_output` < ? AND `state` = ?", array($u -> id, time(), 'wait'));
$u -> money_new = 0;
while ($post = $q -> fetch()) {
	$good = new UsersShop\Good($post -> id_good);
	$u -> money_new += $post -> price_with_percentage;
	$db -> q("UPDATE `users_shop_goods_solds` SET `state` = ? WHERE `id` = ?", array('out', $post -> id));
	$db -> q("INSERT INTO `moneylog` (`id_user`, `price`, `type`, `time`, `msg`) VALUES (?, ?, ?, ?, ?)", array($u -> id, $post -> price_with_percentage, 'in', time(), "С продажи товара \"[url=http://".SITE_NAME."/user/shop/?act=good&good_id={$good -> id}]{$good -> name}[/url]\""));
}
$u -> money_new = floor($u -> money_new);
if ($u -> money_new > 0)$db -> q("UPDATE `users` SET `money` = ? WHERE `id` = ?", array($u -> money_new + $u -> money, $u -> id));
$db -> q("UPDATE `users_infos` SET `ip` = ?, `browser` = ?, `browser_full` = ?, `date_last` = ? WHERE `id` = ?", array($ip, $browser, $_SERVER['HTTP_USER_AGENT'], time(), $u -> info -> id));
Users\Notifications::readNotificaions();
if ($u -> wm_update_time < time() - (3600 * 24)) {
	$wm_info = new WM_Info();
	$wm_info -> getWMIDInfo($u -> info -> wmid);
	if ($wm_info -> wmid_new) {
		$u -> info -> setData('wm_bl', $wm_info -> bl);
		$u -> info -> setData('wm_attestat', $wm_info -> attestat_name);
		$u -> info -> setData('wm_posclaims', $wm_info -> posclaims);
		$u -> info -> setData('wm_negclaims', $wm_info -> negclaims);
		$u -> info -> setData('wm_last_update', TimeUtils::currentTime());
	}
}
?>