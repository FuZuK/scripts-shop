<?
if (adminka::access('tickets_read_ticket_to_adm') && !adminka::access('tickets_read_ticket_to_kons'))$tickets_q_where = " AND `type` = '0'";
if (adminka::access('tickets_read_ticket_to_kons') && !adminka::access('tickets_read_ticket_to_adm'))$tickets_q_where = " AND `type` = '1'";
elseif (adminka::access('tickets_read_ticket_to_adm') && adminka::access('tickets_read_ticket_to_kons'))$tickets_q_where = null;
if (!adminka::access('tickets_read_ticket_to_adm') && adminka::access('tickets_read_ticket_to_kons')) {
	$sys -> error_sess("Доступ закрыт");
	header("Location: /");
	exit();
}
$title .= ' - Новые тикеты';
include(HEAD);
$cr = $db -> res("SELECT COUNT(*) FROM `tickets` WHERE `opened` = ?$tickets_q_where", array(1));
$navi = new navi($cr, "?act=tickets&");
$q = $db -> q("SELECT * FROM `tickets` WHERE `opened` = ?$tickets_q_where ORDER BY `time` DESC LIMIT ".$navi -> start.", ".$set -> results_on_page, array(1));
$tickets = array();
while ($ticket = $q -> fetch()) {
	$us = new Users\User($ticket -> id_user);
	$tickets[] = array(
		'data' => $ticket, 
		'us' => $us
	);
}
$smarty = new SMX();
$smarty -> assign("tickets", $tickets);
$smarty -> display("list.tickets.tpl");
echo $navi -> show;
doc::back("В админку", "?act=index");
include(FOOT);
?>