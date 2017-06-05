<?
include(HEAD);
$count_results = $db -> res("SELECT COUNT(*) FROM `news`");
$navi = new navi($count_results, '?');
if (!$count_results)doc::listEmpty("Нет новостей");
$q = $db -> q("SELECT * FROM `news` ORDER BY `time` DESC LIMIT ".$navi -> start.", ".$set -> results_on_page);
$items = array();
while ($post = $q -> fetch()) {
	$count_comms = $db -> res("SELECT COUNT(*) FROM `news_comms` WHERE `id_new` = ?", array($post -> id));
	$items[] = array(
		'link' => "/news/read/{$post -> id}", 
		'name' => TextUtils::escape($post -> title), 
		'content' => TextUtils::show(TextUtils::cut($post -> msg, 300)), 
		'counter' => $count_comms
	);
}
$sets = array(
	'img' => imgs::show("rss_b.png", array('height' => ($set -> wb?96:48), 'width' => ($set -> wb?96:48))), 
	'img_left' => true, 
	'hr' => true
);
$smarty = new SMX();
$smarty -> assign("list_items", $items);
$smarty -> assign("sets", $sets);
$smarty -> display("list.items.tpl");
echo $navi -> show;
?>
<?
if (adminka::access('news_add_new')) {
	?>
	<hr>
	<div class="mod">
		<? echo imgs::show("add1.png", array('class' => ICON_CLASS, 'height' => ICON_WH, 'width' => ICON_WH));?> <a href="/news/add">Добавить новость</a><br />
	</div>
	<?
}
doc::back("Назад", "/");
include(FOOT);
?>