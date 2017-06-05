<?
$title = 'Логи входов';
include(HEAD);
$count_results = $db -> res("SELECT COUNT(*) FROM `loghist` WHERE `id_user` = ?", array($u -> id));
if (!$count_results)doc::listEmpty("Список логов пуст");
$navi = new navi($count_results, '?');
$q = $db -> q("SELECT * FROM `loghist` WHERE `id_user` = ? ORDER BY `time` DESC LIMIT ".$navi -> start.", ".$set -> results_on_page, array($u -> id));
$items = array();
while ($post = $q -> fetch()) {
	$content = "<span class='ank_q'>Дата входа:</span> <span class='ank_a'>".TimeUtils::show($post -> time)."</span><br />\n";
	$type = 'Ввод логина и пароля';
	if ($post -> type == 'cookies')$type = 'COOKIES';
	$content .= "<span class='ank_q'>Тип входа:</span> <span class='ank_a'>$type</span><br />\n";
	$content .= "<span class='ank_q'>Браузер:</span> <span class='ank_a'>".TextUtils::escape($post -> browser)."</span><br />\n";
	$content .= "<span class='ank_q'>IP:</span> <span class='ank_a'>".TextUtils::escape($post -> ip)."</span><br />\n";
	$items[] = array(
		'content' => $content
	);
}
$smarty = new SMX();
$smarty -> assign("list_items", $items);
$sets = array(
	'hr' => false, 
	'div' => 'content_mess'
);
$smarty -> assign("sets", $sets);
$smarty -> display("list.items.tpl");
echo $navi -> show;
doc::back("Назад", "/cab");
include(FOOT);
?>