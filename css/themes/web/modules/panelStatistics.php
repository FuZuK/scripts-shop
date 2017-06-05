<div class="boxWrapper">
	<div class="boxPanel">
		<div class="boxTitle">Статистика</div>
		<?
		echo "<div class='content_list'>\n";
		echo "<b>За все время:</b><br />\n";
		echo "Продавцов: " . $db -> res("SELECT COUNT(*) FROM `users` WHERE `prod` = ?", array(1)) . "<br />\n";
		echo "Колличество всех сделок: " . intval($db -> res("SELECT COUNT(*) FROM `users_shop_goods_solds`")) . "<br />\n";
		echo "Сумма сделок: <span class='wmr_blue'>" . intval($db -> res("SELECT SUM(`price`) FROM `users_shop_goods_solds`")) . " WMR</span><br />\n";
		$first_sold = $db -> farr("SELECT * FROM `users_shop_goods_solds` ORDER BY `time` ASC LIMIT 1");
		if (@$first_sold -> id) {
			echo "Первая продажа: " . TimeUtils::show($first_sold -> time) . "<br />\n";
		}
		$lats_sold = $db -> farr("SELECT * FROM `users_shop_goods_solds` ORDER BY `time` DESC LIMIT 1");
		if (@$lats_sold -> id) {
			echo "Последняя продажа: " . TimeUtils::show($lats_sold -> time) . "<br />\n";
		}
		$count_good_review = $db -> res("SELECT COUNT(*) FROM `users_shop_goods_reviews` WHERE `type` = ?", array('good'));
		$count_bad_review = $db -> res("SELECT COUNT(*) FROM `users_shop_goods_reviews` WHERE `type` = ?", array('bad'));
		echo "Положительных отзывов: " . $count_good_review . "<br />\n";
		echo "Отрицательных отзывов: " . $count_bad_review . "<br />\n";
		echo "</div>\n";
		echo "<hr>\n";
		$time_start_today = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
		echo "<div class='content_list'>\n";
		echo "<b>За сегодня:</b><br />\n";
		echo "Колличество сделок: " . intval($db -> res("SELECT COUNT(*) FROM `users_shop_goods_solds` WHERE `time` > ?", array($time_start_today))) . "<br />\n";
		echo "Сумма сделок: " . intval($db -> res("SELECT SUM(`price`) FROM `users_shop_goods_solds` WHERE `time` > ?", array($time_start_today))) . "<br />\n";
		$count_good_review_today = $db -> res("SELECT COUNT(*) FROM `users_shop_goods_reviews` WHERE `type` = ? AND `time` > ?", array('good', $time_start_today));
		$count_bad_review_today = $db -> res("SELECT COUNT(*) FROM `users_shop_goods_reviews` WHERE `type` = ? AND `time` > ?", array('bad', $time_start_today));
		echo "Положительных отзывов: " . $count_good_review_today . "<br />\n";
		echo "Отрицательных отзывов: " . $count_bad_review_today . "<br />\n";
		echo "</div>\n";
		?>
	</div>
</div>