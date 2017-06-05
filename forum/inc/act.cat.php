<?
$cat = $db -> farr("SELECT * FROM `forum_cats` WHERE `id` = ?", array(intval($_GET['cat_id'])));
if (!$cat -> id) {
	$title = 'Ой, ошибочка получилась...';
	include(HEAD);
	echo alerts::error("Раздел не найден.");
	doc::back("Назад", "/forum");
	include(FOOT);
}
$forum = $db -> farr("SELECT * FROM `forum_forums` WHERE `id` = ?", array($cat -> id_forum));
$forum_moders = array();
if ($forum -> moders)$forum_moders = explode(",", $forum -> moders);
$title .= ' - '.TextUtils::escape($cat -> name);
include(HEAD);
$q = $db -> q("SELECT * FROM `forum_topics` WHERE `id_cat` = ? ORDER BY `pin` DESC, `time` DESC", array($cat -> id));
if (!$q -> rowCount())doc::listEmpty("Нет топиков");
while ($post = $q -> fetch()) {
	$us = new Users\User($post -> id_user);
	$image = "forum_topic.png";
	if ($post -> poll)$image = "forum_topic_poll.png";
	if ($post -> lock)$image = "forum_topic_locked.png";
	if ($post -> pin)$image = "forum_topic_pinned.png";
	?>
	<div class="content_mess">
		<? echo imgs::show($image, array('class' => ICON_CLASS, 'height' => ICON_WH, 'width' => ICON_WH));?> <a href="/forum/t/<? echo $post -> id?>"><? echo TextUtils::escape($post -> them);?></a> (<? echo TimeUtils::show($post -> time)?>)<br />
		<? echo $us -> login?>
	</div>
	<?
}
if (isset($u)) {
	?>
	<hr>
	<div class="mod">
		<? echo imgs::show("add1.png", array('class' => ICON_CLASS, 'height' => ICON_WH, 'width' => ICON_WH));?> <a href="/forum/create/topic/<? echo $cat -> id?>">Создать топик</a><br />
		<?
		if (adminka::access('forum_edit_cat')) {
			echo imgs::show("edit.png", array('class' => ICON_CLASS, 'height' => ICON_WH, 'width' => ICON_WH));?> <a href="/forum/edit/cat/<? echo $cat -> id?>">Редактировать раздел</a><br />
			<?
		}
		?>
	</div>
	<?
}
doc::back("Назад", "/forum/f/{$forum -> id}");
include(FOOT);
?>