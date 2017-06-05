<?
Users\User::if_user('is_reg');
adminka::accessCheck('forum_delete_cat');
$cat = $db -> farr("SELECT * FROM `forum_cats` WHERE `id` = ?", array(intval($_GET['cat_id'])));
if (!$cat -> id) {
	$title = 'Ой, ошибочка получилась...';
	include(HEAD);
	echo alerts::error("Раздел не найден.");
	doc::back("Назад", "/forum");
	include(FOOT);
}
$forum = $db -> farr("SELECT * FROM `forum_forums` WHERE `id` = ?", array($cat -> id_forum));
$title .= ' - Удаление раздела';
include(HEAD);
if (isset($_POST['sfsk']) && ussec::check_p()) {
	$db -> q("DELETE FROM `forum_cats` WHERE `id` = ?", array($cat -> id));
	$select_topics = $db -> q("SELECT * FROM `forum_topics` WHERE `id_cat` = ?", array($cat -> id));
	while ($topic = $select_topics -> fetch()) {
		$db -> q("DELETE FROM `forum_topics` WHERE `id`  = ?", array($topic -> id));
		$db -> q("DELETE FROM `forum_comms` WHERE `id_topic`  = ?", array($topic -> id));
		if ($topic -> poll) {
			$db -> q("DELETE FROM `forum_poll_vars` WHERE `id_topic` = ?", array($topic -> id));
			$db -> q("DELETE FROM `forum_poll_votes` WHERE `id_topic` = ?", array($topic -> id));
		}
	}
	adminka::adminsLog("Форум", "Разделы", "Удален раздел \"".$cat -> name."\" из подфорума \"[url=http://$_SERVER[HTTP_HOST]/forum/f/".$forum -> id."]".$forum -> name."[/url]\"");
	alerts::msg_sess("Раздел успешно удален");
	header("Location: /forum/f/".$cat -> id_forum);
	exit();
}
echo alerts::error();
?>
<form action="" method="POST" class="content">
	<span class="form_q">Вы действительно хотите удалить этот раздел?</span><br />
	<? echo ussec::input();?>
	<input type="submit" name="sfsk" class="main_sub rad_tlr rad_blr" value="Да, хочу"> <a href="/forum/c/<? echo $cat -> id?>" class="hp_bb">Нет</a>
</form>
<?
doc::back("Назад", "/forum/c/{$cat -> id}");
include(FOOT);
?>