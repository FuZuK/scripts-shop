<?
$forum = $db -> farr("SELECT * FROM `forum_forums` WHERE `id` = ?", array(intval($_GET['forum_id'])));
if (!$forum -> id) {
	$title = 'Ой, ошибочка получилась...';
	include(HEAD);
	echo alerts::error("Подфорум не найден.");
	doc::back("Назад", "/forum");
	include(FOOT);
}
$title .= ' - '.TextUtils::escape($forum -> name);
include(HEAD);
if (isset($_GET['cat_up']) && ussec::check_g()) {
	adminka::accessCheck('forum_updown_cats');
	$up = $db -> farr("SELECT * FROM `forum_cats` WHERE `id` = ? AND `id_forum` = ?", array(intval($_GET['cat_up']), $forum -> id));
	if ($up -> id && $db -> res("SELECT COUNT(*) FROM `forum_cats` WHERE `pos` < ? AND `id_forum` = ?", array($up -> pos, $forum -> id))) {
		$db -> q("UPDATE `forum_cats` SET `pos` = ? WHERE `pos` = ? AND `id_forum` = ?", array($up -> pos, $up -> pos - 1, $forum -> id));
		$db -> q("UPDATE `forum_cats` SET `pos` = ? WHERE `id` = ? AND `id_forum` = ?", array($up -> pos - 1, $up -> id, $forum -> id));
		header("Location: /forum/f/".$forum -> id);
		exit();
	}
}
if (isset($_GET['cat_down']) && ussec::check_g()) {
	adminka::accessCheck('forum_updown_cats');
	$down = $db -> farr("SELECT * FROM `forum_cats` WHERE `id` = ? AND `id_forum` = ?", array(intval($_GET['cat_down']), $forum -> id));
	if ($down -> id && $db -> res("SELECT COUNT(*) FROM `forum_cats` WHERE `pos` > ? AND `id_forum` = ?", array($down -> pos, $forum -> id))) {
		$db -> q("UPDATE `forum_cats` SET `pos` = ? WHERE `pos` = ? AND `id_forum` = ?", array($down -> pos, $down -> pos + 1, $forum -> id));
		$db -> q("UPDATE `forum_cats` SET `pos` = ? WHERE `id` = ? AND `id_forum` = ?", array($down -> pos + 1, $down -> id, $forum -> id));
		header("Location: /forum/f/".$forum -> id);
		exit();
	}
}
$q = $db -> q("SELECT * FROM `forum_cats` WHERE `id_forum` = ? ORDER BY `pos` ASC", array($forum -> id));
if (!$q -> rowCount())doc::listEmpty("Нет разделов");
$pos = 0;
while ($post = $q -> fetch()) {
	$pos++;
	$db -> q("UPDATE `forum_cats` SET `pos`  = ? WHERE `id` = ? AND `id_forum` = ?", array($pos, $post -> id, $post -> id));
	$count_topics = $db -> res("SELECT COUNT(*) FROM `forum_topics` WHERE `id_cat` = ?", array($post -> id));
	?>
	<div class="content_mess">
		<? echo imgs::show("forum_cat.png", array('class' => ICON_CLASS, 'height' => ICON_WH, 'width' => ICON_WH));?> <a href="/forum/c/<? echo $post -> id?>"><? echo TextUtils::escape($post -> name);?></a> (<? echo $count_topics?>)
		<?
		if (adminka::access('forum_updown_cats')) {
			?>
			<span class="right"><a href="?cat_up=<? echo $post -> id?>&<? echo ussec::link()?>"><? echo imgs::show("arrow_up.png", array('class' => ICON_CLASS, 'height' => ICON_WH, 'width' => ICON_WH));?></a> <a href="?cat_down=<? echo $post -> id?>&<? echo ussec::link()?>"><? echo imgs::show("arrow_down.png", array('class' => ICON_CLASS, 'height' => ICON_WH, 'width' => ICON_WH));?></a></span>
			<?
		}
		if ($post -> desc) {
			?>
			<div>
				<? echo TextUtils::escape($post -> desc)?>
			</div>
			<?
		}
		?>
	</div>
	<?
}
if (adminka::access('forum_add_cat') || adminka::access('forum_edit_forum')) {
	?>
	<hr>
	<div class="mod">
		<?
		if (adminka::access('forum_add_cat')) {
			echo imgs::show("add1.png", array('class' => ICON_CLASS, 'height' => ICON_WH, 'width' => ICON_WH));?> <a href="/forum/create/cat/<? echo $forum -> id?>">Создать раздел</a><br />
			<?
		}
		if (adminka::access('forum_edit_forum')) {
			echo imgs::show("edit.png", array('class' => ICON_CLASS, 'height' => ICON_WH, 'width' => ICON_WH));?> <a href="/forum/edit/forum/<? echo $forum -> id?>">Редактировать подфорум</a><br />
			<?
		}
		?>
	</div>
	<?
}
doc::back("Назад", "/forum");
include(FOOT);
?>