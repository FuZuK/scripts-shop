<?
Users\User::if_user('is_reg');
adminka::accessCheck('forum_delete_topic');
$topic = $db -> farr("SELECT * FROM `forum_topics` WHERE `id` = ?", array(intval($_GET['topic_id'])));
if (!$topic -> id) {
	$title = 'Ой, ошибочка получилась...';
	include(HEAD);
	echo alerts::error("Топик не найден.");
	doc::back("Назад", "/forum");
	include(FOOT);
}
$author = new Users\User($topic -> id_user);
$cat = $db -> farr("SELECT * FROM `forum_cats` WHERE `id` = ?", array($topic -> id_cat));
$forum = $db -> farr("SELECT * FROM `forum_forums` WHERE `id` = ?", array($cat -> id_forum));
$title .= ' - Удаление топика';
include(HEAD);
if (isset($_POST['sfsk']) && ussec::check_p()) {
	$db -> q("DELETE FROM `forum_topics` WHERE `id`  = ?", array($topic -> id));
	$db -> q("DELETE FROM `forum_comms` WHERE `id_topic`  = ?", array($topic -> id));
	if ($topic -> poll) {
		$db -> q("DELETE FROM `forum_poll_vars` WHERE `id_topic` = ?", array($topic -> id));
		$db -> q("DELETE FROM `forum_poll_votes` WHERE `id_topic` = ?", array($topic -> id));
	}
	adminka::adminsLog("Форум", "Топики", "Удален топик \"".$topic -> them."\" из раздела \"[url=http://$_SERVER[HTTP_HOST]/forum/c/".$cat -> id."]".$cat -> name."[/url]\"");
	alerts::msg_sess("Топик успешно удален");
	header("Location: /forum/c/".$topic -> id_cat);
	exit();
}
?>
<form action="" method="POST" class="content">
	<span class="form_q">Вы действительно хотите удалить этот топик?</span><br />
	<? echo ussec::input();?>
	<input type="submit" name="sfsk" class="main_sub rad_tlr rad_blr" value="Да, хочу"> <a href="/forum/t/<? echo $topic -> id?>" class="hp_bb">Нет</a>
</form>
<?
doc::back("Назад", "/forum/t/{$topic -> id}");
include(FOOT);
?>