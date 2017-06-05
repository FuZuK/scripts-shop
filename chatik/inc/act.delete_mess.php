<?
Users\User::if_user('is_reg');
adminka::accessCheck('chatik_delete_message');
$mess = $db -> farr("SELECT * FROM `chatik_comms` WHERE `id` = ?", array(intval($_GET['mess_id'])));
if (!$mess -> id) {
	$title = 'Ой, ошибочка получилась...';
	include(HEAD);
	echo $sys -> error("Сообщение не найдено");
	doc::back("Назад", "<a href='/' class='back'>Назад</a>");
	include(FOOT);
}
$us = new Users\User($mess -> id_user);
if (ussec::check_g()) {
	adminka::adminsLog("Мини-чат", "Сообщения", "Удалено сообщение (ID: {$mess -> id})");
	$db -> q("DELETE FROM `chatik_comms` WHERE `id` = ?", array($mess -> id));
}
header("Location: /chatik");
exit();
?>