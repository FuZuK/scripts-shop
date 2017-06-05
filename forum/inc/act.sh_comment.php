<?
Users\User::if_user('is_reg');
adminka::accessCheck('forum_hideshow_comments');
$comment = $db -> farr("SELECT * FROM `forum_comms` WHERE `id` = ?", array(intval($_GET['comment_id'])));
if (!$comment -> id) {
	$title = 'Ой, ошибочка получилась...';
	include(HEAD);
	echo alerts::error("Комментарий не найден");
	doc::back("Назад", "/forum");
	include(FOOT);
}
$topic = $db -> farr("SELECT * FROM `forum_topics` WHERE `id` = ?", array($comment -> id_topic));
$author = new Users\User($topic -> id_user);
$cat = $db -> farr("SELECT * FROM `forum_cats` WHERE `id` = ?", array($topic -> id_cat));
$forum = $db -> farr("SELECT * FROM `forum_forums` WHERE `id` = ?", array($cat -> id_forum));
$hidden = 1;
if ($comment -> hidden)$hidden = 0;
if (ussec::check_g()) {
	adminka::adminsLog("Форум", "Комментарии", ($hidden?"Скрыт":"Показан")." комментарий в топике \"[url=http://$_SERVER[HTTP_HOST]/t/".$topic -> id."]".$topic -> them."[/url]\"");
	$db -> q("UPDATE `forum_comms` SET `hidden` = ?, `hidden_time` = ?, `hidden_id_user` = ? WHERE `id` = ?", array($hidden, time(), $u -> id, $comment -> id));
}
header("Location: /forum/t/".$topic -> id);
exit();
?>