<?
Users\User::if_user('is_reg');
$comment = $db -> farr("SELECT * FROM `news_comms` WHERE `id` = ?", array(intval($_GET['comment_id'])));
if (!$comment -> id) {
	$title = 'Ой, ошибочка получилась...';
	include(HEAD);
	echo alerts::error("Комментарий не найден");
	doc::back("Назад", "/");
	include(FOOT);
}
$us = new Users\User($comment -> id_user);
$new = $db -> farr("SELECT * FROM `news` WHERE `id` = ?", array($comment -> id_new));
$title .= ' - Ответ на комментарий';
include(HEAD);
$users[] = array(
	'us' => $us, 
	'info' => TextUtils::show($comment -> msg)
);
$sets = array('div' => 'content_mess');
new SMX(array('sets' => $sets, 'users' => $users), 'list.users.tpl');
echo alerts::error();
$el = array();
$el[] = array('type' => 'title', 'value' => 'Ваш ответ:', 'br' => true);
$el[] = array('type' => 'textarea', 'name' => 'msg', 'value' => '', 'br' => true);
$el[] = array('type' => 'ussec');
$el[] = array('type' => 'hidden', 'name' => 'reply_id_user', 'value' => $us -> id);
$el[] = array('type' => 'hidden', 'name' => 'reply_id_comment', 'value' => $comment -> id);
$el[] = array('type' => 'submit', 'name' => 'sfsk', 'value' => 'Отправить');
$el[] = array('type' => 'hp_smiles');
$el[] = array('type' => 'hp_tags');
new SMX(array('el' => $el, 'fastSend' => true, 'action' => '/news/read/' . $new -> id), 'form.tpl');
Doc::back("Назад", "/news/read/" . $new -> id);
include(FOOT);
?>