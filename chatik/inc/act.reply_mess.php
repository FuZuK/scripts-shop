<?
Users\User::if_user('is_reg');
$mess = $db -> farr("SELECT * FROM `chatik_comms` WHERE `id` = ?", array(intval($_GET['mess_id'])));
if (!$mess -> id) {
	$title = 'Ой, ошибочка получилась...';
	include(HEAD);
	echo alerts::error("Сообщение не найдено");
	doc::back("Назад", "/chatik");
	include(FOOT);
}
$us = new Users\User($mess -> id_user);
$title .= ' - Ответ на сообщение';
include(HEAD);
$users[] = array(
	'us' => $us, 
	'info' => TextUtils::show($mess -> msg)
);
$sets = array('div' => 'content_mess');
$smarty = new SMX();
$smarty -> assign("sets", $sets);
$smarty -> assign("users", $users);
$smarty -> display("list.users.tpl");
echo alerts::error();
$el = array();
$el[] = array('type' => 'title', 'value' => 'Ваш ответ:', 'br' => true);
$el[] = array('type' => 'textarea', 'name' => 'msg', 'value' => '', 'br' => true);
$el[] = array('type' => 'ussec');
$el[] = array('type' => 'hidden', 'name' => 'reply_id_user', 'value' => $us -> id);
$el[] = array('type' => 'hidden', 'name' => 'reply_id_comment', 'value' => $mess -> id);
$el[] = array('type' => 'submit', 'name' => 'sfsk', 'value' => 'Отправить');
$el[] = array('type' => 'hp_smiles');
$el[] = array('type' => 'hp_tags');
new SMX(array('el' => $el, 'fastSend' => true, 'action' => '/chatik/'), 'form.tpl');
Doc::back("Назад", "/chtik");
include_once(FOOT);
?>