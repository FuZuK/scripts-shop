<?
include('../core/st.php');
$time_start_month = mktime(0, 0, 0, date("m"), 0, date("Y"));
include(DR.'core/libs/WebMoney/XmlI/header.php');
# награждаем лидеров месяца, ставим выше всего, что бы счетчики не обновились
if ($set -> time_award_leaders < $time_start_month) {
	$place = 1;
	$select_month_liders = $db -> q("SELECT * FROM `users` WHERE `month_solds` > 0 ORDER BY `month_solds` DESC LIMIT 3");
	while ($month_lider = $select_month_liders -> fetch()) {
		$us = new Users\User($month_lider -> id);
		switch ($place) {
			case 1:
				$price = 300;
			break;
			case 2:
				$price = 200;
			break;
			case 3:
				$price = 100;
			break;
		}
		if ($place <= 3) {
			$db -> q("INSERT INTO `webmoney_out` (`wmid`, `pursesrc`, `pursedest`, `amount`, `time`) VALUES (?, ?, ?, ?, ?)", array($set -> sys_wmid, $set -> sys_wmr, $us -> info -> wmr, $price, time()));
			$tranid = $db -> lastInsertId();
			$res = $wmxi -> X2(
				$tranid,                  # номер перевода
				$set -> sys_wmr,      # номер кошелька с которого выполняется перевод (отправитель)
				'R'.$u -> info -> wmr,      # номер кошелька, но который выполняется перевод (получатель)
				$price,                # переводимая сумма
				0,                  # срок протекции сделки в днях
				'',              # код протекции сделки
				'Вывод средств ('.$price.' WMR; #'.$tranid.')',  # описание оплачиваемого товара или услуги
				0,                  # номер счета (в системе WebMoney), по которому выполняется перевод
				1                   # учитывать разрешение получателя
			);
			$ress = $res -> toArray();
			$place++;
		}
	}
	$set -> time_award_leaders = time();
	$set -> save();
}

# обновляем инфу о местах продавцов в рейтинге
$q = $db -> q("SELECT * FROM `users` ORDER BY `rating` DESC");
$rating_place = 1;
while ($us = $q -> fetch()) {
	$us  = new Users\User($us -> id);
	$sum_solds = intval($db -> res("SELECT SUM(`price`) FROM `users_shop_goods_solds` WHERE `id_seller` = ? AND `state` = ?", array($us -> id, 'out')));
	$count_goods = intval($db -> res("SELECT COUNT(*) FROM `users_shop_goods` WHERE `id_user` = ?", array($us -> id)));
	$us -> setData('rating_place', $rating_place);
	$rating_place++;
	$users_array[$us -> id] = $sum_solds;
	$good_reviews = $db -> res("SELECT COUNT(`users_shop_goods_reviews`.`id`) FROM `users_shop_goods_reviews` LEFT JOIN `users_shop_goods` ON `users_shop_goods_reviews`.`id_good` = `users_shop_goods`.`id` WHERE `users_shop_goods`.`id_user` = ? AND `users_shop_goods_reviews`.`type` = ?", array($us -> id, 'good'));
	$bad_reviews = $db -> res("SELECT COUNT(`users_shop_goods_reviews`.`id`) FROM `users_shop_goods_reviews` LEFT JOIN `users_shop_goods` ON `users_shop_goods_reviews`.`id_good` = `users_shop_goods`.`id` WHERE `users_shop_goods`.`id_user` = ? AND `users_shop_goods_reviews`.`type` = ?", array($us -> id, 'bad'));
	$us -> setData('good_reviews', $good_reviews);
	$us -> setData('bad_reviews', $bad_reviews);
	$count_month_solds = intval($db -> res("SELECT COUNT(*) FROM `users_shop_goods_solds` WHERE `id_seller` = ? AND `time` > ?", array($us -> id, $time_start_month)));
	$us -> setData('month_solds', $count_month_solds);
}

$solds_place = 1;
arsort($users_array, SORT_NUMERIC);
foreach ($users_array as $us_id => $sum_solds) {
	$us  = new Users\User($us_id);
	$us -> setData('solds_place', $solds_place);
	$solds_place++;
}

?>