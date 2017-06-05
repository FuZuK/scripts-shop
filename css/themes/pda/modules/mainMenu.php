<? # by Killer
?>
<div class="title"><a href="/shop">Магазин</a></div>
<?
$items = array();
$root = Shop\Shop::getRootCategory();
$q = $db -> q("SELECT * FROM `shop_categories` WHERE `id_category` = ? ORDER BY `pos` ASC", array($root -> id));
if (!$q -> rowCount())echo $doc -> error("Список категорий пуст");
while ($shop_cat = $q -> fetch()) {
	$items[] = array(
		'img' => Doc::showImage('/images/folder_blue.png', array('class' => ICON_CLASS, 'width' => ICON_WH, 'height' => ICON_WH)), 
		'link' => "/shop/category/{$shop_cat -> id}",
		'name' => TextUtils::DBFilter($shop_cat -> name)
	);
}
$items[] = array(
	'img' => Doc::showImage('/images/search.png', array('class' => ICON_CLASS, 'width' => ICON_WH, 'height' => ICON_WH)), 
	'link' => "/shop/search", 
	'name' => "Поиск товаров"
);
$items[] = array(
	'img' => Doc::showImage('/images/rating.png', array('class' => ICON_CLASS, 'width' => ICON_WH, 'height' => ICON_WH)), 
	'link' => "/shop/sellers_rating", 
	'name' => "Рейтинг продавцов"
);
$sets = array(
	'hr' => true
);
$smarty = new SMX();
$smarty -> assign("list_items", $items);
$smarty -> assign("sets", $sets);
$smarty -> display("list.items.tpl");
$smarty = null;
echo "<div class='title'>Общение</div>\n";
$items = array();
$count_news_all = $db -> res("SELECT COUNT(*) FROM `news`");
$count_news_new = $db -> res("SELECT COUNT(*) FROM `news` WHERE `time` > ?", array(time() - 86400));
$items[] = array(
	'img' => Doc::showImage('/images/news.png', array('class' => ICON_CLASS, 'width' => ICON_WH, 'height' => ICON_WH)), 
	'link' => "/news", 
	'name' => "Новости", 
	'counter' => $count_news_all, 
	'counter_new' => $count_news_new, 

);
$count_topics_all = $db -> res("SELECT COUNT(*) FROM `forum_topics`");
$count_topics_new = $db -> res("SELECT COUNT(*) FROM `forum_topics` WHERE `time` > ?", array(time() - 86400));
$items[] = array(
	'img' => Doc::showImage('/images/forum.png', array('class' => ICON_CLASS, 'width' => ICON_WH, 'height' => ICON_WH)), 
	'link' => "/forum", 
	'name' => "Форум", 
	'counter' => $count_topics_all, 
	'counter_new' => $count_topics_new
);
$count_chatik_mess_all = $db -> res("SELECT COUNT(*) FROM `chatik_comms`");
$count_chatik_mess_new = $db -> res("SELECT COUNT(*) FROM `chatik_comms` WHERE `time` > ?", array(time() - 86400));
$items[] = array(
	'img' => Doc::showImage('/images/chat.png', array('class' => ICON_CLASS, 'width' => ICON_WH, 'height' => ICON_WH)), 
	'link' => '/chatik', 
	'name' => "Мини-чат", 
	'counter' => $count_chatik_mess_all, 
	'counter_new' => $count_chatik_mess_new
);
$count_users_all = $db -> res("SELECT COUNT(*) FROM  `users_infos`");
$count_users_new = $db -> res("SELECT COUNT(*) FROM  `users_infos` WHERE `date_reg` > ?", array(time() - 86400));
$items[] = array(
	'img' => Doc::showImage('/images/prof.png', array('class' => ICON_CLASS, 'width' => ICON_WH, 'height' => ICON_WH)), 
	'link' => '/users/all', 
	'name' => "Пользователи", 
	'counter' => $count_users_all, 
	'counter_new' => $count_users_new
);
$items[] = array(
	'img' => Doc::showImage('/images/wiki.png', array('class' => ICON_CLASS, 'width' => ICON_WH, 'height' => ICON_WH)), 
	'link' => '/wiki', 
	'name' => "Справка"
);

$sets = array(
	'hr' => true, 
	'counter_class' => 'counter_main'
);
$smarty = new SMX();
$smarty -> assign("list_items", $items);
$smarty -> assign("sets", $sets);
$smarty -> display("list.items.tpl");
$smarty = null;
echo "<div class='title'>Статистика</div>\n";
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