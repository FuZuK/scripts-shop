<?
adminka::access('forum_delete_forum');
$forum = $db -> farr("SELECT * FROM `forum_forums` WHERE `id` = ?", array(intval($_GET['forum_id'])));
if (!$forum -> id) {
	$title = 'Ой, ошибочка получилась...';
	include(HEAD);
	echo alerts::error("Подфорум не найден.");
	doc::back("Назад", "/forum");
	include(FOOT);
}
$title .= ' - Удаление подфорума';
include(HEAD);
if (isset($_POST['sfsk']) && ussec::check_p()) {
	adminka::adminsLog("Форум", "Подфорумы", "Удален подфорум \"".$forum -> name."\"");
	$db -> q("DELETE FROM `forum_forums` WHERE `id` = ?", array($forum -> id));
	$select_cats = $db -> q("SELECT * FROM `forum_cats` WHERE `id_forum` = ?", array($forum -> id));
	while ($cat = $select_cats -> fetch()) {
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
	}
	alerts::msg_sess("Подфорум успешно удален");
	header("Location: /forum");
	exit();
}
echo alerts::error();
?>
<form action="" method="POST" class="content">
	<span class="form_q">Вы действительно хотите удалить этот подфорум?</span><br />
	<? echo ussec::input();?>
	<input type="submit" name="sfsk" class="main_sub rad_tlr rad_blr" value="Да, хочу"> <a href="/forum/f/<? echo $forum -> id?>" class="hp_bb">Нет</a>
</form>
<?
doc::back("Назад", "/forum/f/{$forum -> id}");
include(FOOT);
?>