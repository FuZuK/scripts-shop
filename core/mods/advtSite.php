<? # by Killer
$q = $db -> q("SELECT * FROM `advt` WHERE `time_to` > '".time()."' ORDER BY rand() ASC LIMIT 5");
if ($q -> rowCount()) {
	echo "<hr>\n";
	echo "<div class='content advt'>\n";
	while ($post = $q -> fetch()) {
		echo "<a href='{$post -> link}'>".TextUtils::escape($post -> name)."</a><br />\n";
	}
	echo "</div>\n";
}
?>