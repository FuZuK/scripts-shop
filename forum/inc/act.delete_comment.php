<?
Users\User::if_user('is_reg');
adminka::accessCheck('forum_delete_comment');
$comment = $db -> farr("SELECT * FROM `forum_comms` WHERE `id` = ?", array(intval($_GET['comment_id'])));
if (!$comment -> id) {
	$title = 'Ой, ошибочка получилась...';
	include(HEAD);
	echo alerts::error("Комментарий не найден.")
	doc::back("Назад", "/forum");
	include(FOOT);
}
$topic = $db -> farr("SELECT * FROM `forum_topics` WHERE `id` = ?", array($comment -> id_topic));
$author = new Users\User($topic -> id_user);
$cat = $db -> farr("SELECT * FROM `forum_cats` WHERE `id` = ?", array($topic -> id_cat));
$forum = $db -> farr("SELECT * FROM `forum_forums` WHERE `id` = ?", array($cat -> id_forum));
if (ussec::check_g()) {
	adminka::adminsLog("Форум", "Комментарии", "Удален комментарий из топика \"[url=http://$_SERVER[HTTP_HOST]/forum/t/".$topic -> id."]".$topic -> them."[/url]\"");
	$db -> q("DELETE FROM `forum_comms` WHERE `id` = ?", array($comment -> id));
}
header("Location: /forum/t/".$topic -> id);
exit();
?>