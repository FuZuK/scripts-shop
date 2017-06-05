<?
echo "<div class='lst_h'>\n";
echo "<b>За все время:</b><br />\n";
echo "Продавцов: " . $db -> res("SELECT COUNT(*) FROM `users` u WHERE (SELECT COUNT(*) FROM `users_shop_goods` WHERE `id_user` = u.id) > 0") . "<br />\n";
echo "Колличество всех сделок: " . intval($db -> res("SELECT COUNT(*) FROM `users_shop_goods_solds`")) . "<br />\n";
echo "Сумма сделок: <span class='wmr_blue'>" . intval($db -> res("SELECT SUM(`price`) FROM `users_shop_goods_solds`")) . " WMR</span><br />\n";
$solds = $db -> farr("SELECT COUNT(`id`) count FROM `users_shop_goods_solds`");
if ($solds -> count != 0) {
	$primary_solds = $db -> farr("SELECT (SELECT `time` FROM `users_shop_goods_solds` ORDER BY `time` ASC LIMIT 1) `first`, (SELECT `time` FROM `users_shop_goods_solds` ORDER BY `time` DESC LIMIT 1) `last`");
	echo "Первая продажа: " . TimeUtils::show($primary_solds -> first) . "<br />\n";
	echo "Последняя продажа: " . TimeUtils::show($primary_solds -> last) . "<br />\n";
}
$count_review = $db -> farr("SELECT (SELECT COUNT(*) FROM `users_shop_goods_reviews` WHERE `type` = ?) `good`, (SELECT COUNT(*) FROM `users_shop_goods_reviews` WHERE `type` = ?) `bad`", array('good', 'bad'));
echo "Положительных отзывов: " . $count_review -> good . "<br />\n";
echo "Отрицательных отзывов: " . $count_review -> bad . "<br />\n";
echo "</div>\n";
$time_start_today = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
echo "<div class='lst_h'>\n";
echo "<b>За сегодня:</b><br />\n";
echo "Колличество сделок: " . intval($db -> res("SELECT COUNT(*) FROM `users_shop_goods_solds` WHERE `time` > ?", array($time_start_today))) . "<br />\n";
echo "Сумма сделок: " . intval($db -> res("SELECT SUM(`price`) FROM `users_shop_goods_solds` WHERE `time` > ?", array($time_start_today))) . "<br />\n";
$count_review_today = $db -> farr("SELECT (SELECT COUNT(*) FROM `users_shop_goods_reviews` WHERE `type` = ? AND `time` > ?) `good`, (SELECT COUNT(*) FROM `users_shop_goods_reviews` WHERE `type` = ? AND `time` > ?) `bad`", array('good', $time_start_today, 'bad', $time_start_today));
echo "Положительных отзывов: " . $count_review_today -> good . "<br />\n";
echo "Отрицательных отзывов: " . $count_review_today -> bad . "<br />\n";
echo "</div>\n";
?>