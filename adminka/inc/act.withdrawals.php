<?
adminka::accessCheck('adminka_withdrawals');
$title .= ' - Вывод средств';
include(HEAD);
if (isset($_GET['ok']) && in_array($_GET['ok'], array(0, 1)) && isset($_GET['wid']) && $db -> res('SELECT COUNT(*) FROM `withdrawals` WHERE `id` = ?', array(intval($_GET['wid'])))) {
	$withdrawal = $db -> farr('SELECT * FROM `withdrawals` WHERE `id` = ?', array(intval($_GET['wid'])));
	$ok = intval($_GET['ok']);
	if (!$withdrawal -> acted) {
		$db -> q('UPDATE `withdrawals` SET `ok` = ?, `acted` = 1, `time_out` = ? WHERE `id` = ?', array($ok, TimeUtils::currentTIme(), $withdrawal -> id));
		if (!$ok) {
			mailing::send_mess(0, $withdrawal -> id_user, "Добрый день!\r\nВывод средств на суму {$withdrawal -> money} WMR был отклонен.\r\nСпасибо!");
		} else {
			mailing::send_mess(0, $withdrawal -> id_user, "Добрый день!\r\nВывод средств на суму {$withdrawal -> money} WMR был принят. Проверте свой кошелек.\r\nСпасибо!");
		}
	}
	header('Location: ?act=withdrawals');
	exit();
}
$count_results = $db -> res("SELECT COUNT(*) FROM `withdrawals`");
if (!$count_results)doc::listEmpty("Список выводов пуст");
$navi = new navi($count_results, '?act=withdrawals');
$q = $db -> q("SELECT * FROM `withdrawals`  ORDER BY `acted` ASC, `time_add` ASC LIMIT ".$navi -> start.", ".$set -> results_on_page);
$items = array();
while ($post = $q -> fetch()) {
	$ank = new Users\User($post -> id_user);
	$actions = array();
	if (!$post -> acted) $actions = array(
		array(
			'link' => "?act=withdrawals&ok=1&wid=" . $post -> id, 
			'name' => 'Выведено'
		), 
		array(
			'link' => "?act=withdrawals&ok=0&wid=" . $post -> id, 
			'name' => 'Запретить'
		)
	);
	$msg = "";
	$msg .= "Пользователь: " . $ank -> login() . " (<span class='time'>" . TimeUtils::show($post -> time_add) . "</span>)<br />\n";
	if ($post -> acted) $msg .= "<span style='color: " . ($post -> ok == 1 ? 'green' : 'red') . "'>" . ($post -> ok == 1 ? 'Выведено' : 'Отклонено') . "</span> (" . TimeUtils::show($post -> time_out) . ")<br />\n";
	$msg .= "R: " . $ank -> info -> wmr . "<br />\n";
	$msg .= "Сума: " . $post -> money . " WMR<br />\n";
	$items[] = array(
		'name' =>  $msg, 
		'actions' => $actions
	);
}
$sets = array(
	'hr' => true
);
$smarty = new SMX();
$smarty -> assign("sets", $sets);
$smarty -> assign("list_items", $items);
$smarty -> display("list.items.tpl");
echo $navi -> show;
Doc::back("Админка", "?act=index");
include(FOOT);
?>