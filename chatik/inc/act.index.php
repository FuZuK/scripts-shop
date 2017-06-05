<?
include(HEAD);
echo "<div class='mod'>\n";
echo "<a href='/chatik/?ref=" . rand(10000, 99999) . "'>Обновить</a><br />\n";
echo "</div>\n";
echo "<hr>\n";
$cr = $db -> res("SELECT COUNT(*) FROM `chatik_comms`");
$navi = new navi($cr, '?');
journal::update('chatik', 0, "/chatik/?page=".$navi -> page);
$posts = array();
$q = $db -> q("SELECT * FROM `chatik_comms` ORDER BY `time` DESC LIMIT ".$navi -> start.", ".$set -> results_on_page);
while ($post = $q -> fetch()) {
	$us = new Users\User($post -> id_user);
	$reply_us = new Users\User($post -> reply_id_user);
	$actions = array();
	$actions[] = array(
		'link' => "/chatik/reply_mess/{$post -> id}", 
		'name' => 'Ответить'
	);
	if (adminka::access('chatik_delete_message'))$actions[] = array(
		'link' => "/chatik/delete_mess/{$post -> id}/".ussec::get(), 
		'name' => 'Удалить'
	);
	$posts[] = array(
		'data' => $post, 
		'us' => $us, 
		'reply_us' => $reply_us, 
		'time_form' => TimeUtils::show($post -> time), 
		'msg_form' => TextUtils::show($post -> msg, $us -> id), 
		'actions' => $actions
	);
}
new SMX(array('posts' => $posts), 'list.comments.tpl');
echo $navi -> show;
if (isset($u)) {
	if (isset($_POST['sfsk']) && ussec::check_p()) {
		$msg = $_POST['msg'];
		if (TextUtils::length(trim($msg)) < 1)$error = 'Введите комментарий';
		elseif (TextUtils::length($msg) > 5000)$error = 'Комментарий слишком длинный';
		else {
			$db -> q("INSERT INTO `chatik_comms` (`id_user`, `time`, `msg`) VALUES (?, ?, ?)", array($u -> id, time(), $msg));
			$mid = $db -> lastInsertId();
			if (isset($_POST['reply_id_user']) && $db -> res("SELECT COUNT(*) FROM `users` WHERE `id` = ?", array(intval($_POST['reply_id_user']))) && isset($_POST['reply_id_comment']) && $db -> res("SELECT COUNT(*) FROM `chatik_comms` WHERE `id` = ?", array(intval($_POST['reply_id_comment'])))) {
				$db -> q("UPDATE `chatik_comms` SET `reply_id_user` = ?, `reply_id_comment` = ? WHERE `id` = ?", array(intval($_POST['reply_id_user']), intval($_POST['reply_id_comment']), $mid));
			}
			$all_users_commed = array();
			$q = $db -> q("SELECT * FROM `chatik_comms` ORDER BY `time` DESC");
			while ($comment = $q -> fetch()) {
				if (!in_array($comment -> id_user, $all_users_commed) && $comment -> id_user != $u -> id) {
					$all_users_commed[] = $comment -> id_user;
					journal::send($u -> id, $comment -> id_user, 'chatik', 'comment', 0, $comment -> id, $comment -> time);
				}
			}
			header("Location: ?");
			exit();
		}
	}
	echo alerts::error();
	$el = array();
	$el[] = array('type' => 'textarea', 'name' => 'msg', 'value' => '', 'br' => true);
	$el[] = array('type' => 'ussec');
	$el[] = array('type' => 'submit', 'name' => 'sfsk', 'value' => 'Отправить');
	$el[] = array('type' => 'hp_smiles');
	$el[] = array('type' => 'hp_tags');
	new SMX(array('el' => $el, 'fastSend' => true), 'form.tpl');
}
Doc::back('На главную', '/');
include(FOOT);
?>