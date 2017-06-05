<?
Users\User::if_user('is_reg');
$good = new UsersShop\Good(intval(@$_GET['good_id']));
if (!$good -> exists()) {
	$title = 'Ой, ошибочка получилась...';
	include(HEAD);
	echo alerts::error("Товар не найден");
	doc::back("Назад", "/");
	include(FOOT);
}
$seller = $good -> getSeller();
if ($good -> isDeleted()) {
	$title = 'Ой, ошибочка получилась...';
	include(HEAD);
	echo alerts::error("Товар удален");
	doc::back("Назад", "/shop/");
	include(FOOT);
}
if ($good -> isBlocked()) {
	$title = 'Ой, ошибочка получилась...';
	include(HEAD);
	echo alerts::error("Товар заблокирован");
	doc::back("Назад", "/shop/good/{$good -> id}");
	include(FOOT);
}
if ($seller -> id == $u -> id) {
	$title = 'Ой, ошибочка получилась...';
	include(HEAD);
	echo alerts::error("Вы являетесь владельцем этого товара. Вы можете его просто скачать.");
	doc::back("Назад", "/shop/good/{$good -> id}");
	include(FOOT);
}
if ($db -> res("SELECT COUNT(*) FROM `users_shop_goods_solds` WHERE `id_good` = ? AND `id_user` = ? AND `state` != ?", array($good -> id, $u -> id, 'return'))) {
	$title = 'Ой, ошибочка получилась...';
	include(HEAD);
	echo alerts::error("Вы уже покупали этот товар");
	doc::back("Назад", "/shop/good/{$good -> id}");
	include(FOOT);
}
if (!$good -> isAddedToShop()) {
	$title = 'Ой, ошибочка получилась...';
	include(HEAD);
	echo alerts::error("Товар не был добавлен в магазин");
	doc::back("Назад", "/shop/good/{$good -> id}");
	include(FOOT);
}
$title .= ' - Покупка товара';
include(HEAD);
$goods_show_configs['for'] = 'shop_category';
include_once(INCLUDS_DIR.'list_goods.php');
$new_price = $good -> getDiscountForUser($u -> id);
if ($new_price > 0) {
	echo "<div class='content_redi'>\n";
	echo "Вам предоставлена скидка в размере <span class='green'>{$good -> discount}%</span> на этот товар, так как сумма Ваших покупок товаров этого продавца больше <span class='wmr_blue'>{$good -> discount_price} WMR</span>.<br />\n";
	echo "Новая цена: <span class='wmr_blue'>$new_price WMR</span><br />\n";
	echo "</div>\n";
	$good -> price = $new_price;
}
if (isset($_GET['paytype']))
	switch ($_GET['paytype']) {
		case 'merchant':
		echo "<form method='POST' action='https://merchant.webmoney.ru/lmi/payment.asp'>\n";
		$db -> q("INSERT INTO `webmoney_deals` (`ok`, `payee_purse`, `payment_amount`) VALUES (?, ?, ?)", array(0, $set -> sys_wmr, $good -> price));
		$wmd_id = $db -> lastInsertId();
		echo "<div class='content lh2'>\n";
		echo "<input type='hidden' name='LMI_PAYMENT_DESC_BASE64' value='".base64_encode("Покупка товара \"".$good -> name."\"")."'>\n";
		echo "<input type='hidden' name='LMI_PAYEE_PURSE' value='{$set -> sys_wmr}'>\n";
		echo "<input type='hidden' name='LMI_PAYMENT_NO' value='$wmd_id'>\n";
		echo "<input type='hidden' name='pay_type' value='good'>\n";
		echo "<input type='hidden' name='user_id' value='{$u -> id}'>\n";
		echo "<input type='hidden' name='good_id' value='{$good -> id}'>\n";
		echo "<input type='hidden' name='LMI_PAYMENT_AMOUNT' value='{$good -> price}'>\n";
		echo ussec::input();
		echo "<input type='submit' class='main_sub rad_tlr rad_blr' value='Оплатить {$good -> price} WMR'><br />\n";
		echo "</div>\n";
		echo "</form>\n";
		Doc::back("Назад", "/shop/buy/{$good -> id}");
		include(FOOT);
		break;
		case 'personal_money':
		if (isset($_POST['pay']) && ussec::check_p()) {
			if ($good -> price > $u -> money_personal)
				$error = 'На Вашем личном счету не хватает средств для покупки этого товара';
			else {
				$db -> q("INSERT INTO `users_shop_goods_solds` (`id_good`, `id_user`, `id_seller`, `time`, `time_output`, `price`, `price_with_percentage`) VALUES(?, ?, ?, ?, ?, ?, ?)", array($good -> id, $u -> id, $seller -> id, TimeUtils::currentTime(), TimeUtils::currentTime() + ($seller -> pro == true ? $set -> time_output : $set -> time_output_pro), $good -> price, $good -> getPriceWithPercents()));
				$db -> q("UPDATE `users` SET `money_personal` = ? WHERE `id` = ?", array($seller -> money_personal + $good -> getPriceWithPercents(), $seller -> id));
				Shop\Basket::deleteGoodFromBasket($good -> id, $u -> id);
				$db -> q("INSERT INTO `moneylog` (`id_user`, `price`, `type`, `time`, `msg`) VALUES (?, ?, ?, ?, ?)", array($u -> id, -$good -> price, 'in', time(), "Покупка товара \"[url=http://$_SERVER[HTTP_HOST]/shop/good/{$good -> id}]{$good -> name}[/url]\""));
				mailing::send_mess(0, $seller -> id, "Добрый день!\r\nИзвещаем Вас, что выставленный Вами товар \"[url=http://$_SERVER[HTTP_HOST]/shop/good/{$good -> id}]{$good -> name}[/url]\" на торговой площадке, был куплен пользователем [user]{$u -> login}[/user].\r\nСпасибо!");
				$db -> q('UPDATE `users` SET `money` = ?, `money_personal` = ? WHERE `id` = ?', array($u -> money - $good -> price, $u -> money_personal - $good -> price, $u -> id));
				alerts::msg_sess('Товар успешно куплен');
				Users\Notifications::send('shop_good_buy', $seller -> id, 'У вас новая продажа на '.SITE_NAME);
				header("Location: /shop/good/{$good -> id}");
				exit();
			}
		}
		echo alerts::error();
		echo "<div class='content'>\n";
		echo "<span class='form_q'>Личный счет:</span> <span class='form_a'>{$u -> money_personal} <span class='wmr_blue'>WMR</span></span><br />\n";
		echo "</div>\n";
		echo "<hr />\n";
		echo "<form method='POST'>\n";
		echo "<div class='content'>\n";
		echo ussec::input();
		echo "<input type='submit' class='main_sub rad_tlr rad_blr' name='pay' value='Оплатить {$good -> price} WMR'><br />\n";
		echo "</div>\n";
		echo "</form>\n";
		Doc::back("Назад", "/shop/buy/{$good -> id}");
		include(FOOT);
		break;
	}
echo "<div class='content_list'>\n";
echo Doc::showImage('/images/restore.png', array('width' => ICON_WH, 'height' => ICON_WH, 'class' => ICON_CLASS)).' '.Doc::showLink('/shop/buy/'.$good -> id.'/merchant', 'Через Webmoney Merchant')."<br />\n";
echo "</div>\n";
echo "<hr />\n";
echo "<div class='content_list'>\n";
echo Doc::showImage('/images/restore.png', array('width' => ICON_WH, 'height' => ICON_WH, 'class' => ICON_CLASS)).' '.Doc::showLink('/shop/buy/'.$good -> id.'/personal_money', 'За деньги с личного счета')."<br />\n";
echo "</div>\n";
Doc::back("Назад", "/shop/good/{$good -> id}");
include(FOOT);
?>