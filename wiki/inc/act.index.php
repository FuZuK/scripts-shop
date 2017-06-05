<? # by Killer
include(HEAD);
$cr = $db -> res("SELECT COUNT(*) FROM `wiki_stats`");
if (!$cr)doc::listEmpty("Список пуст");
$navi = new navi($cr, '?');
$q = $db -> q("SELECT * FROM `wiki_stats` ORDER BY `title` ASC LIMIT ".$navi -> start.", ".$set -> results_on_page);
$list = array();
while ($post = $q -> fetch()) {
	$list[] = array(
		'name' => TextUtils::escape($post -> title), 
		'link' => "/wiki/{$post -> id}/read"
	);
}
echo $navi -> show();
$sm = new SMX();
$sm -> assign('list_items', $list);
$sm -> assign('sets', array('hr' => true));
$sm -> display('list.items.tpl');
CActions::setShowType(CActions::SHOW_ALL);
CActions::setSeparator("<br />\n");
if (adminka::access('wiki_stat_add')) {
	CActions::addAction('/wiki/add', 'Добавить статтю', '/images/add1.png');
}
if (CActions::getCount() > 0)
	echo "<hr>\n";
echo CActions::showActions();
include(FOOT);
?>