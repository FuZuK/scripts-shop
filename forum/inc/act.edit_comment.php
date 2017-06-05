<?
Users\User::if_user('is_reg');
$comment = $db -> farr("SELECT * FROM `forum_comms` WHERE `id` = ?", array(intval($_GET['comment_id'])));
if (!$comment -> id) {
	$sys -> document();
	$title = 'Ой, ошибочка получилась...';
	$sys -> panel_up();
	$sys -> title();
	echo alerts::error("Комментарий не найден");
	Doc::back("Назад", "/forum");
	include(FOOT);
}
$us = new Users\User($comment -> id_user);
$topic = $db -> farr("SELECT * FROM `forum_topics` WHERE `id` = ?", array($comment -> id_topic));
$author = new Users\User($topic -> id_user);
$cat = $db -> farr("SELECT * FROM `forum_cats` WHERE `id` = ?", array($topic -> id_cat));
if (!($u -> id == $us -> id && $comment -> time + 600 > time())) {
	$sys -> document();
	$title = 'Ой, ошибочка получилась...';
	$sys -> panel_up();
	$sys -> title();
	echo alerts::error("У вас нет прав для редактирования этого комментария");
	doc::back("Назад", "/forum/t/{$topic -> id}");
	include(FOOT);
}
$title .= ' - Редактирование комментария';
include(HEAD);
if (isset($_POST['sfsk']) && ussec::check_p()) {
	$comment2 = $_POST['msg'];
	if (TextUtils::length(trim($comment2)) < 1)$error = 'Введите комментарий';
	elseif (TextUtils::length($comment2) > 1024)$error = 'Комментарий слишком длинный';
	else {
		$db -> q("UPDATE `forum_comms` SET `msg` = ? WHERE `id` = ?", array($comment2, $comment -> id));
		alerts::msg_sess("Комментарий успешно отредактирован");
		header("Location: /forum/t/".$topic -> id);
		exit();
	}
}
echo alerts::error();

$el = array();
$left_time = ($comment -> time + 600) - time();
$el[] = array('type' => 'title', 'value' => 'Осталось ' . TextUtils::declension($left_time, array('секунда', 'секунды', 'секунд')), 'br' => true);
$el[] = array('type' => 'textarea', 'name' => 'msg', 'value' => TextUtils::DBFilter($comment -> msg), 'br' => true);
$el[] = array('type' => 'ussec');
$el[] = array('type' => 'hidden', 'name' => 'reply_id_user', 'value' => $us -> id);
$el[] = array('type' => 'hidden', 'name' => 'reply_id_comment', 'value' => $comment -> id);
$el[] = array('type' => 'submit', 'name' => 'sfsk', 'value' => 'Отправить');
$el[] = array('type' => 'hp_smiles');
$el[] = array('type' => 'hp_tags');
new SMX(array('el' => $el, 'fastSend' => true, 'action' => '/forum/t/' . $topic -> id), 'form.tpl');
Doc::back("Назад", "/forum/t/{$topic -> id}");
include(FOOT);
?>