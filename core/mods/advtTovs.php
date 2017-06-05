<? # by Killer
$q = $db -> q("SELECT * FROM `shop_advt_tovs` ORDER BY `time` DESC LIMIT 5");
if ($q -> rowCount()) {
	echo "<hr>\n";
	echo "<div class='content advt'>\n";
	while ($post = $q -> fetch()) {
		echo "<a href='/shop/tov/{$post -> id_tov}'>".TextUtils::escape($post -> name)."</a><br />\n";
	}
	echo "</div>\n";
}
?>