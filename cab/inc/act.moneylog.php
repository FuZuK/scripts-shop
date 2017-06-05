<?
$title = 'Движение средств';
include(HEAD);
$count_results = $db -> res("SELECT COUNT(*) FROM `moneylog` WHERE `id_user` = ?", array($u -> id));
if (!$count_results)doc::listEmpty("Никаких операций не производилось");
$navi = new navi($count_results, '?');
$q = $db -> q("SELECT * FROM `moneylog` WHERE `id_user` = ? ORDER BY `time` DESC LIMIT ".$navi -> start.", ".$set -> results_on_page, array($u -> id));
$items = array();
while ($post = $q -> fetch()) {
	$content = "Операция #{$post -> id}<br />\n";
	$content .= "Время операции: ".TimeUtils::show($post -> time)."<br />\n";
	$content .= "Расход: <span class='".($post -> price < 0?"red":"green")."'>{$post -> price} WMR</span><br />\n";
	$content .= "Тип операции: ".($post -> type == 'in'?"внутренняя":"внешняя")."<br />\n";
	$content .= "Комментарий: ".TextUtils::show($post -> msg, 1)."<br />\n";
	$items[] = array(
		'content' => $content
	);
}
$smarty = new SMX();
$smarty -> assign("list_items", $items);
$smarty -> assign("sets", array('hr' => false, 'div' => 'content_mess'));
$smarty -> display("list.items.tpl");
echo $navi -> show;
doc::back("Назад", "/cab/accounting");
include(FOOT);