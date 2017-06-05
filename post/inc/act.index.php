<?
$title = 'Мои контакты';
include(HEAD);
$count_results = $db -> res("SELECT COUNT(*) FROM `mail_conts` WHERE `id_user` = ?", array($u -> id));
$navi = new navi($count_results, '?');
if ($count_results == 0) {
	doc::listEmpty("Список контактов пуст");
}
$query = $db -> q("SELECT * FROM `mail_conts` WHERE `id_user` = ? ORDER BY `date_last` DESC, `count` DESC LIMIT ".$navi -> start.", ".$set -> results_on_page, array($u -> id));
$conts = array();
while ($post = $query -> fetch()) {
	$post -> count_all = $db -> res("SELECT COUNT(*) FROM `mail` WHERE `id_cont` = ?", array($post -> id));
	$us = new Users\User($post -> id_ank);
	$content = null;
	if ($db -> res("SELECT COUNT(*) FROM `mail` WHERE `id_cont` = ?", array($post -> id))) {
		$last_msg = $db -> farr("SELECT * FROM `mail` WHERE `id_cont` = ? ORDER BY `date` DESC LIMIT 1", array($post -> id));
		if ($last_msg -> type == 'at') {
			$content .= "<span class='red'>Я</span> > \n";
		}
		$content .= $us -> login(1, 0).": ";
		$content .= TextUtils::show(TextUtils::cut($last_msg -> msg, 100));
		$content .= "<div>(".TimeUtils::show($last_msg -> date).")</div>\n";
	}
	$conts[] = array(
		'img' => $us -> ava_list(0, 'small'), 
		'link' => "/post/cont/{$post -> id}", 
		'name' => $us -> login, 
		'counter' => $post -> count_all, 
		'counter_new' => $post -> count, 
		'content' => $content
	);
}
$sets = array('img_left' => true, 'div' => 'content_mess');
$smarty = new SMX();
$smarty -> assign("sets", $sets);
$smarty -> assign("list_items", $conts);
$smarty -> display("list.items.tpl");
echo $navi -> show();
doc::back("Назад", "/cab");
include(FOOT);
?>