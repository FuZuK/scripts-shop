<?
Users\User::if_user('is_reg');
adminka::accessCheck('news_delete_comment');
$comment = $db -> farr("SELECT * FROM `news_comms` WHERE `id` = ?", array(intval($_GET['comment_id'])));
if (!@$comment -> id) {
	$title = 'Ой, ошибочка получилась...';
	include(HEAD);
	echo alerts::error("Комментарий не найден");
	doc::back("Назад", "/");
	include(FOOT);
}
$us = new Users\User($comment -> id_user);
$new = $db -> farr("SELECT * FROM `news` WHERE `id` = ?", array($comment -> id_new));
if (ussec::check_g()) {
	adminka::adminsLog("Новости", "Комментарии", "Удален комментарий к новости \"[url=http://$_SERVER[HTTP_HOST]/news/read/".$new -> id."]".$new -> title."[/url]\"");
	$db -> q("DELETE FROM `news_comms` WHERE `id` = ?", array($comment -> id));
}
header("Location: /news/read/".$new -> id);
exit();
?>