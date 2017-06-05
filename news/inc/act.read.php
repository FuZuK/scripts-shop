<?
$new = $db -> farr("SELECT * FROM `news` WHERE `id` = ?", array(intval(@$_GET['new_id'])));
if (!$new -> id) {
	$title = 'Ой, ошибочка получилась...';
	$sys -> document();
	$sys -> panel_up();
	$sys -> title();
	echo alerts::error("Новость не найдена");
	doc::back("Назад", "/");
	include(FOOT);
}
$author = new Users\User($new -> id_user);
$title .= ' - '.TextUtils::escape($new -> title);
include(HEAD);
echo "<div class='content'>\n";
echo "<div class='wety'>\n";
echo "<div>" . $author -> icon().$author -> login() . "<br /></div>\n";
echo "</div>\n";
echo TextUtils::show($new -> msg, $author -> id);
echo "</div>\n";
if (adminka::access('news_edit_new')) {
	echo "<hr>\n";
	echo "<div class='mod'>\n";
	echo imgs::show("edit.png", array('class' => ICON_CLASS, 'height' => ICON_WH, 'width' => ICON_WH)) . " <a href='/news/edit/{$new -> id}'>Редактировать</a><br />\n";
	echo "</div>\n";
}
$count_results = $db -> res("SELECT COUNT(*) FROM `news_comms` WHERE `id_new` = ?", array($new -> id));
$navi = new navi($count_results, '?');
journal::update('news', $new -> id, "/news/read/".$new -> id."?page=".$navi -> page);
echo "<div class='panel_ud'>\n";
echo imgs::show("chat.png", array('class' => ICON_CLASS, 'height' => ICON_WH, 'width' => ICON_WH)) . " Комментарии ({$count_results})<br />\n";
echo "</div>\n";
echo "<hr>\n";
$posts = array();
$q = $db -> q("SELECT * FROM `news_comms` WHERE `id_new` = ? ORDER BY `time` DESC LIMIT ".$navi -> start.", ".$set -> results_on_page, array($new -> id));
while ($post = $q -> fetch()) {
	$us = new Users\User($post -> id_user);
	$reply_us = new Users\User($post -> reply_id_user);
	$actions = array();
	$actions[] = array(
		'link' => "/news/reply_comment/{$post -> id}", 
		'name' => 'Ответить'
	);
	if (adminka::access('news_delete_comment'))$actions[] = array(
		'link' => "/news/delete_comment/{$post -> id}/".ussec::get(), 
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
$smarty = new SMX();
$smarty -> assign("posts", $posts);
$smarty -> display("list.comments.tpl");
echo $navi -> show;
if (isset($u)) {
	if (isset($_POST['sfsk']) && ussec::check_p()) {
		$msg = $_POST['msg'];
		if (TextUtils::length(trim($msg)) < 1)$error = 'Введите комментарий';
		elseif (TextUtils::length($msg) > 1024)$error = 'Комментарий слишком длинный';
		else {
			$db -> q("INSERT INTO `news_comms` (`id_new`, `id_user`, `time`, `msg`) VALUES (?, ?, ?, ?)", array($new -> id, $u -> id, time(), $msg));
			$mid = $db -> lastInsertId();
			if (isset($_POST['reply_id_user']) && $db -> res("SELECT COUNT(*) FROM `users` WHERE `id` = ?", array(intval($_POST['reply_id_user']))) && isset($_POST['reply_id_comment']) && $db -> res("SELECT COUNT(*) FROM `news_comms` WHERE `id` = ? AND `id_new` = ?", array(intval($_POST['reply_id_comment']), $new -> id))) {
				$db -> q("UPDATE `news_comms` SET `reply_id_user` = ?, `reply_id_comment` = ? WHERE `id` = ?", array(intval($_POST['reply_id_user']), intval($_POST['reply_id_comment']), $mid));
			}
			$all_users_commed = array();
			$q = $db -> q("SELECT * FROM `news_comms` WHERE `id_new` = ? ORDER BY `time` DESC", array($new -> id));
			while ($comment = $q -> fetch()) {
				if (!in_array($comment -> id_user, $all_users_commed) && $comment -> id_user != $author -> id && $comment -> id_user != $u -> id) {
					$all_users_commed[] = $comment -> id_user;
					journal::send($u -> id, $comment -> id_user, 'news', 'comment', $new -> id, $comment -> id, $comment -> time);
				}
			}
			if ($u -> id != $author -> id) {
				journal::send($u -> id, $author -> id, 'news', 'object', $new -> id, 0, $new -> time);
			}
			header("Location: ?");
			exit();
		}
	}
	if (isset($_GET['delete_comm_id']) && $db -> res("SELECT COUNT(*) FROM `news_comms` WHERE `id` = ? AND `id_new` = ?", array(intval($_GET['delete_comm_id']), $new -> id)) && isset($admin) && ussec::check_g()) {
		$db -> q("DELETE FROM `news_comms` WHERE `id` = ? AND `id_new` = ?", array(intval($_GET['delete_comm_id']), $new -> id));
		header("Location: ?");
		exit();
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
Doc::back("Назад", "/news");
include(FOOT);
?>