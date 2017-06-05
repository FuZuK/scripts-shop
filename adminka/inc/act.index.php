<?
include(HEAD);
$items = array();
if (adminka::access('edit_users_groups'))$items[] = array(
	'link' => "/adminka/?act=users_groups", 
	'name' => "Настройки должностей"
);
if (adminka::access('edit_users_groups_accesses'))$items[] = array(
	'link' => "/adminka/?act=users_groups_accesses", 
	'name' => "Редактор привилегий"
);
if (adminka::access('adminka_withdrawals'))$items[] = array(
	'link' => "/adminka/?act=withdrawals", 
	'name' => "Вывод средств", 
	'counter' => $db -> res('SELECT COUNT(*) FROM `withdrawals` WHERE `acted` = 0')
);
if (adminka::access('adminka_system_settings'))$items[] = array(
	'link' => "/adminka/?act=system_settings", 
	'name' => "Настройки системы"
);
if (adminka::access('adminka_log_read'))$items[] = array(
	'link' => "/adminka/?act=admins_log", 
	'name' => "Действия администрации"
);
if (adminka::access('tickets_read_ticket_to_adm') || adminka::access('tickets_read_ticket_to_kons')) {
	$new_tickets = 0;
	if (adminka::access('tickets_read_ticket_to_adm') && !adminka::access('tickets_read_ticket_to_kons'))$tickets_q_where = " AND `type` = '0'";
	elseif (adminka::access('tickets_read_ticket_to_kons') && !adminka::access('tickets_read_ticket_to_adm'))$tickets_q_where = " AND `type` = '1'";
	elseif (adminka::access('tickets_read_ticket_to_adm') && adminka::access('tickets_read_ticket_to_kons'))$tickets_q_where = '';
	if (isset($tickets_q_where)) {
		$new_tickets = $db -> res("SELECT COUNT(*) FROM `tickets` WHERE `opened` = ?$tickets_q_where", array(1));
	}
	if ($new_tickets) {
		$items[] = array(
			'link' => "/adminka/?act=new_tickets", 
			'name' => "Новые тикеты", 
			'counter' => $new_tickets
		);
	}
}
if (adminka::access('adminka_advt_sets')) {
	$items[] = array(
		'link' => "/adminka/?act=advt", 
		'name' => "Настройки рекламы"
	);
}
if (adminka::access('adminka_smsc')) {
	$items[] = array(
		'link' => "/adminka/?act=smsc", 
		'name' => "Управление СМС Центром"
	);
}
$smarty = new SMX();
$sets = array(
	'img' => imgs::show("str.gif"), 
	'img_left' => true, 
	'hr' => true
);
$smarty -> assign("list_items", $items);
$smarty -> assign("sets", $sets);
$smarty -> display("list.items.tpl");
?>