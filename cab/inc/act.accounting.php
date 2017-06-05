<?
if (isset($_POST['sfsk']) && ussec::check_p()) {
	$out_price = floatval($_POST['out_price']);
	if ($out_price < 5)$error = 'Сума не должна быть меньше 5-ти WMR';
	elseif ($out_price > $u -> money)$error = 'Ваш личный счет не такой уж и велик, что бы выводить такие сумы :)';
	elseif (!$u -> info -> wmid)$error = 'Для вывода средств Вам нужно указать свой WMID и R кошелек';
	elseif (!$u -> info -> wmr)$error = 'Для вывода средств Вам нужно указать свой R кошелек';
	else {
		$out_price = floor($out_price);
		// if (true == false) {
			$db -> q("INSERT INTO `webmoney_out` (`wmid`, `pursesrc`, `pursedest`, `amount`, `time`) VALUES (?, ?, ?, ?, ?)", array($set -> sys_wmid, $set -> sys_wmr, $u -> info -> wmr, $out_price, time()));
			$tranid = $db -> lastInsertId();
			include(DR.'core/libs/WebMoney/XmlI/header.php');
			$res = $wmxi -> X2(
				$tranid,                  # номер перевода
				$set -> sys_wmr,      # номер кошелька с которого выполняется перевод (отправитель)
				'R'.$u -> info -> wmr,      # номер кошелька, но который выполняется перевод (получатель)
				$out_price,                # переводимая сумма
				0,                  # срок протекции сделки в днях
				'',              # код протекции сделки
				'Вывод средств ('.$out_price.' WMR; #'.$tranid.')',  # описание оплачиваемого товара или услуги
				0,                  # номер счета (в системе WebMoney), по которому выполняется перевод
				1                   # учитывать разрешение получателя
			);
			$ress = $res -> toArray();
			if (isset($ress['retval']) && $ress['retval'] == 0) {
				$db -> q("UPDATE `users` SET `money` = ?, `money_personal` = ? WHERE `id` = ?", array($u -> money - $out_price, $u -> money_personal - $out_price, $u -> id));
				$db -> q("INSERT INTO `moneylog` (`id_user`, `price`, `type`, `time`, `msg`) VALUES (?, ?, ?, ?, ?)", array($u -> id, -$out_price, 'in', time(), "Вывод средств на кошелек R".$u -> info -> wmr));
				alerts::msg_sess("Вывод средств успешно завершон");
			} else {
				alerts::error_sess("Произошла ошибка, попробуйте позже");
				$db -> q("DELETE FROM `webmoney_out` WHERE `id` = ?", array($tranid));
			}
		// } else {
		// 	$db -> q("INSERT INTO `moneylog` (`id_user`, `price`, `type`, `time`, `msg`) VALUES (?, ?, ?, ?, ?)", array($u -> id, -$out_price, 'in', time(), "Вывод средств на кошелек R".$u -> info -> wmr));
		// 	$db -> q("UPDATE `users` SET `money` = ?, `money_personal` = ? WHERE `id` = ?", array($u -> money - $out_price, $u -> money_personal - $out_price, $u -> id));
		// 	$db -> q('INSERT INTO `withdrawals` (`id_user`, `money`, `time_add`) VALUES (?, ?, ?)', array($u -> id, $out_price, TimeUtils::currentTime()));
		// 	alerts::msg_sess('Вывод средств добавлен в очередь');
		// } 
		header("Location: /cab/accounting");
		exit();
	}
}
$title = 'Бухгалтерия';
include(HEAD);
$count_results = $db -> res("SELECT COUNT(*) FROM `users_shop_goods_solds` WHERE `id_seller` = ?", array($u -> id));
if (!$count_results)echo alerts::list_empty("Никаких операций не производилось");
$navi = new navi($count_results, '?');
$items = array();
$q = $db -> q("SELECT * FROM `users_shop_goods_solds` WHERE `id_seller` = ? ORDER BY `time` DESC LIMIT ".$navi -> start.", ".$set -> results_on_page, array($u -> id));
while ($post = $q -> fetch()) {
	$good = new UsersShop\Good($post -> id_good);
	$buyer = new Users\User($post -> id_user);
	if ($post -> state == 'in_block')$post -> state_text = "<span class=\"red\">Заблокированы</span>";
	elseif ($post -> state == 'out')$post -> state_text = "<span class=\"green\">Перечислены на личный счет</span>";
	elseif ($post -> state == 'return')$post -> state_text = "<span style=\"color: blue;\">Возврат покупателю</span>";
	else $post -> state_text = "На проверке";
	$content = "<a href='/user/shop/?act=good&good_id={$good -> id}'>".TextUtils::DBFilter($good -> name)."</a><br />\n";
	$content .= "Цена: <span class='wmr_blue'>".$good -> price." WMR</span><br />\n";
	$content .= "К выплате:  <span class='wmr_blue'>{$post -> price_with_percentage} WMR</span><br />\n";
	$content .= "Покупатель: {$buyer -> login()}<br />\n";
	$content .= "Состояние: {$post -> state_text}<br />\n";
	$content .= "Дата покупки: ".TimeUtils::show($post -> time)."<br />\n";
	if ($post -> time_output > time() && $post -> state != 'out')$content .= "Доступны к снятию: ".TimeUtils::show($post -> time_output)."<br />\n";
	$items[] = array(
		'content' => $content
	);
}
$smarty = new SMX();
$smarty -> assign("list_items", $items);
$smarty -> assign("sets", array('hr' => false, 'div' => 'content_mess'));
$smarty -> display("list.items.tpl");
echo $navi -> show;
?>
<hr>
<div class="content hl2">
	Средств в системе: <span class="wmr_blue"><?=UsersShop\Shop::showMoneyFormed($u -> money_personal)?> WMR</span><br />
	Личный счет: <span class="wmr_blue"><?=UsersShop\Shop::showMoneyFormed($u -> money)?> WMR</span><br />
	<? if (!$u -> money) { ?><div class="alert" style="margin: 0;">Недостаточно средств для вывода</div><? } else { ?>
	<? echo alerts::error();?>
	<form action="" method="POST">
		<span class="form_q">Сума для вывода:</span><br />
		<input type="text" name="out_price" class="main_inp rad_tlr rad_blr" value="5.00"><br />
		<? echo ussec::input();?>
		<input type="submit" name="sfsk" class="main_sub rad_tlr rad_blr" value="Продолжить"><br />
	</form>
	<? } ?>
</div>
<hr>
<div class="mod">
	<? echo imgs::show("money_move.png", array('class' => ICON_CLASS, 'height' => ICON_WH, 'width' => ICON_WH));?> <a href="/cab/moneylog">Движение средств</a><br />
</div>
<?
doc::back("Назад", "/cab");
include(FOOT);
?>