<?
$pay_type = $_POST['pay_type'];
if ($_POST['LMI_PREREQUEST'] == 1) {
	switch ($pay_type):
		case 'user':
			$us = new Users\User(intval($_POST['user_id']));
			$wmd = $db -> farr("SELECT * FROM `webmoney_deals` WHERE `id` = ?", array(intval($_POST['LMI_PAYMENT_NO'])));
			if (!@$wmd -> id) {
				echo "Ошибка: Операция не найдена";
			} elseif ($_POST['LMI_PAYEE_PURSE'] != $set -> sys_wmr) {
				echo "Ошибка: Неверный кошелек получателя";
			} elseif (!$us -> id) {
				echo "Ошибка: Пользователь с ID#".intval($_POST['user_id'])." не найден";
			} elseif (TextUtils::escape($_POST['ussec']) != $us -> getSecCode()) {
				echo "Ошибка: Неверный защитный индентификатор ".TextUtils::DBFilter($_POST['ussec']);
			} else echo "YES";
			break;
		case 'good':
			$good = new UsersShop\Good(intval($_POST['good_id']));
			$seller = $good -> getSeller();
			$us = new Users\User(intval($_POST['user_id']));
			$new_price = $good -> getDiscountForUser($us -> id);
			$wmd = $db -> farr("SELECT * FROM `webmoney_deals` WHERE `id` = ?", array(intval($_POST['LMI_PAYMENT_NO'])));
			if ($new_price)$good -> price = $new_price;
			if (!@$wmd -> id) {
				echo "Ошибка: Операция не найдена";
			} elseif ($_POST['LMI_PAYEE_PURSE'] != $set -> sys_wmr) {
				echo "Ошибка: Неверный кошелек получателя";
			} elseif (!$us -> id) {
				echo "Ошибка: Пользователь с ID#".intval($_POST['user_id'])." не найден";
			} elseif (!$good -> id) {
				echo "Ошибка: Товар с ID#".intval($_POST['good_id'])." не найден";
			} elseif ($good -> price != $_POST['LMI_PAYMENT_AMOUNT']) {
				echo "Ошибка: Неверная цена товара";
			} elseif (blist::in($seller -> id, $_POST['LMI_PAYER_WM'], 2)) {
				echo "Ошибка: Владелец товара добавил Ваш WMID в свой Черный список";
			} else echo "YES";
			break;
	endswitch;
} else {
	$common_string = $_POST['LMI_PAYEE_PURSE'].$_POST['LMI_PAYMENT_AMOUNT'].$_POST['LMI_PAYMENT_NO'].$_POST['LMI_MODE'].$_POST['LMI_SYS_INVS_NO'].$_POST['LMI_SYS_TRANS_NO'].$_POST['LMI_SYS_TRANS_DATE'].$set -> merchant_secret_key.$_POST['LMI_PAYER_PURSE'].$_POST['LMI_PAYER_WM'];
	$hash = strtoupper(md5($common_string));
	if ($hash != $_POST['LMI_HASH']) {
		echo "Ошибка";
		exit();
	}
	switch ($pay_type):
		case 'user':
			$us = new Users\User(intval($_POST['user_id']));
			$wmd = $db -> farr("SELECT * FROM `webmoney_deals` WHERE `id` = ?", array(intval($_POST['LMI_PAYMENT_NO'])));
			if (!@$wmd -> id) {
				echo "Ошибка: Операция не найдена";
			} elseif ($_POST['LMI_PAYEE_PURSE'] != $set -> sys_wmr) {
				echo "Ошибка: Неверный кошелек получателя";
			} elseif (!$us -> id) {
				echo "Ошибка: Пользователь с ID#".intval($_POST['user_id'])." не найден";
			} elseif (TextUtils::escape($_POST['ussec']) != $us -> getSecCode()) {
				echo "Ошибка: Неверный защитный индентификатор ".TextUtils::DBFilter($_POST['ussec']);
			} else {
				$money = floatval($_POST['LMI_PAYMENT_AMOUNT']);
				$db -> q("UPDATE `users` SET `money` = ?, `money_personal` = ? WHERE `id` = ?", array($us -> money + $money, $us -> money_personal + $money, $us -> id));
				$db -> q("UPDATE `webmoney_deals` SET `trans_no` = ?, `ok` = ?, `payment_amount` = ?, `payer_purse` = ?, `payer_wm` = ?, `time` = ? WHERE `id` = ?", array(intval($_POST['LMI_SYS_TRANS_NO']), 1, $money, $_POST['LMI_PAYER_PURSE'], $_POST['LMI_PAYER_WM'], TimeUtils::currentTime(), $wmd -> id));
				$db -> q("INSERT INTO `moneylog` (`id_user`, `price`, `type`, `time`, `msg`) VALUES (?, ?, ?, ?, ?)", array($us -> id, $money, 'out', TimeUtils::currentTime(), "Ввод средств в систему через Webmoney"));
			}
			break;
		case 'good':
			$good = new UsersShop\Good(intval($_POST['good_id']));
			$seller = $good -> getSeller();
			$us = new Users\User(intval($_POST['user_id']));
			$new_price = $good -> getDiscountForUser($us -> id);
			$wmd = $db -> farr("SELECT * FROM `webmoney_deals` WHERE `id` = ?", array(intval($_POST['LMI_PAYMENT_NO'])));
			if ($new_price)$good -> price = $new_price;
			if (!@$wmd -> id) {
				echo "Ошибка: Операция не найдена";
			} elseif ($_POST['LMI_PAYEE_PURSE'] != $set -> sys_wmr) {
				echo "Ошибка: Неверный кошелек получателя";
			} elseif (!$us -> id) {
				echo "Ошибка: Пользователь с ID#".intval($_POST['user_id'])." не найден";
			} elseif (!$good -> id) {
				echo "Ошибка: Товар с ID#".intval($_POST['good_id'])." не найден";
			} elseif ($good -> price != $_POST['LMI_PAYMENT_AMOUNT']) {
				echo "Ошибка: Неверная цена товара";
			} elseif (blist::in($seller -> id, $_POST['LMI_PAYER_WM'], 2)) {
				echo "Ошибка: Владелец товара добавил Ваш WMID в свой Черный список";
			} else {
				$db -> q("INSERT INTO `users_shop_goods_solds` (`id_good`, `id_user`, `id_seller`, `time`, `time_output`, `price`, `price_with_percentage`, `deal_id`) VALUES(?, ?, ?, ?, ?, ?, ?, ?)", array($good -> id, $us -> id, $seller -> id, TimeUtils::currentTime(), TimeUtils::currentTime() + ($seller -> pro == true ? $set -> time_output : $set -> time_output_pro), $good -> price, $good -> getPriceWithPercents(), $wmd -> id));
				Shop\Basket::deleteGoodFromBasket($good -> id, $us -> id);
				$db -> q("UPDATE `users` SET `money_personal` = ? WHERE `id` = ?", array($seller -> money_personal + $good -> getPriceWithPercents(), $seller -> id));
				$db -> q("INSERT INTO `moneylog` (`id_user`, `price`, `type`, `time`, `msg`) VALUES (?, ?, ?, ?, ?)", array($us -> id, $good -> price, 'out', TimeUtils::currentTime(), "Ввод средств в систему через Webmoney Merchant"));
				$db -> q("INSERT INTO `moneylog` (`id_user`, `price`, `type`, `time`, `msg`) VALUES (?, ?, ?, ?, ?)", array($us -> id, -$good -> price, 'in', TimeUtils::currentTime(), "Покупка товара \"[url=http://$_SERVER[HTTP_HOST]/shop/good/{$good -> id}]{$good -> name}[/url]\""));
				mailing::send_mess(0, $seller -> id, "Добрый день!\r\nИзвещаем Вас, что выставленный Вами товар \"[url=http://$_SERVER[HTTP_HOST]/shop/good/{$good -> id}]{$good -> name}[/url]\" на торговой площадке, был куплен пользователем [user]{$us -> login}[/user].\r\nСпасибо!");
				$db -> q("UPDATE `webmoney_deals` SET `trans_no` = ?, `ok` = ?, `payer_purse` = ?, `payer_wm` = ?, `time` = ? WHERE `id` = ?", array(intval($_POST['LMI_SYS_TRANS_NO']), 1, $_POST['LMI_PAYER_PURSE'], $_POST['LMI_PAYER_WM'], TimeUtils::currentTime(), $wmd -> id));
				Users\Notifications::send('shop_good_buy', $seller -> id, 'У вас новая продажа на '.SITE_NAME);
			}
			break;
	endswitch;
}
exit();
?>