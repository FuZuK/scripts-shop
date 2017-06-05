<? # by Killer
$stat = $db -> farr("SELECT * FROM `wiki_stats` WHERE `id` = ?", array(intval($_GET['stat_id'])));
if (!@$stat -> id) {
	$title = 'Ой, ошибочка получилась...';
	include(HEAD);
	echo alerts::error('Стаття не найдена');
	doc::back('Назад', '/wiki');
	include(FOOT);
}
$title .= ' - '.TextUtils::escape($stat -> title);
include(HEAD);
echo "<div class='content'>\n";
echo "<div class='wety'>\n";
echo TextUtils::escape($stat -> title);
echo "</div>\n";
echo TextUtils::show($stat -> text, 1);
echo "</div>\n";
CActions::setShowType(CActions::SHOW_ALL);
CActions::setSeparator("<br />\n");
if (adminka::access('wiki_stat_edit')) {
	CActions::addAction("/wiki/{$stat -> id}/edit", 'Редактировать', '/images/edit.png');
}
if (CActions::getCount() > 0)
	echo "<hr>\n";
echo CActions::showActions();
Doc::back('Назад', '/wiki/');
include(FOOT);