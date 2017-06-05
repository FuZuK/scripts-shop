<?
include(HEAD);
if (isset($_GET['forum_up']) && ussec::check_g()) {
	adminka::accessCheck('forum_updown_forums');
	$up = $db -> farr("SELECT * FROM `forum_forums` WHERE `id` = ?", array(intval($_GET['forum_up'])));
	if ($up -> id && $db -> res("SELECT COUNT(*) FROM `forum_forums` WHERE `pos` < ?", array($up -> pos))) {
		$db -> q("UPDATE `forum_forums` SET `pos` = ? WHERE `pos` = ?", array($up -> pos, $up -> pos - 1));
		$db -> q("UPDATE `forum_forums` SET `pos` = ? WHERE `id` = ?", array($up -> pos - 1, $up -> id));
		header("Location: /forum");
		exit();
	}
}
if (isset($_GET['forum_down']) && ussec::check_g()) {
	adminka::accessCheck('forum_updown_forums');
	$down = $db -> farr("SELECT * FROM `forum_forums` WHERE `id` = ?", array(intval($_GET['forum_down'])));
	if ($down -> id && $db -> res("SELECT COUNT(*) FROM `forum_forums` WHERE `pos` > ?", array($down -> pos))) {
		$db -> q("UPDATE `forum_forums` SET `pos` = ? WHERE `pos` = ?", array($down -> pos, $down -> pos + 1));
		$db -> q("UPDATE `forum_forums` SET `pos` = ? WHERE `id` = ?", array($down -> pos + 1, $down -> id));
		header("Location: /forum");
		exit();
	}
}
?>
<div class="content search_div">
	<? echo imgs::show("search.png", array('class' => ICON_CLASS, 'height' => ICON_WH, 'width' => ICON_WH))?> <a href="/forum/search">Поиск</a> | <a href="/forum/new_topics">Новые топики</a><? if (isset($u)) { ?> | <a href="/forum/my_topics">Мои топики</a><? } ?>
</div>
<?
$q = $db -> q("SELECT * FROM `forum_forums` ORDER BY `pos` ASC");
if (!$q -> rowCount())doc::listEmpty("Нет подфорумов");
$pos = 0;
while ($post = $q -> fetch()) {
	$pos++;
	$db -> q("UPDATE `forum_forums` SET `pos`  = ? WHERE `id` = ?", array($pos, $post -> id));
	$count_topics = 0;
	$select_cats = $db -> q("SELECT * FROM `forum_cats` WHERE `id_forum` = ?", array($post -> id));
	while ($cat = $select_cats -> fetch()) {
		$cat_count_topics = $db -> res("SELECT COUNT(*) FROM `forum_topics` WHERE `id_cat` = ?", array($cat -> id));
		$count_topics = $count_topics + $cat_count_topics;
	}
	?>
	<div class="content_mess">
		<? echo imgs::show("forum.png", array('class' => ICON_CLASS, 'height' => ICON_WH, 'width' => ICON_WH));?> <a href="/forum/f/<? echo $post -> id?>"><? echo TextUtils::escape($post -> name);?></a> (<? echo $count_topics?>)
		<?
		if (adminka::access('forum_updown_forums')) {
			?>
			<span class="right"><a href="?forum_up=<? echo $post -> id?>&<? echo ussec::link()?>"><? echo imgs::show("arrow_up.png", array('class' => ICON_CLASS, 'height' => ICON_WH, 'width' => ICON_WH));?></a> <a href="?forum_down=<? echo $post -> id?>&<? echo ussec::link()?>"><? echo imgs::show("arrow_down.png", array('class' => ICON_CLASS, 'height' => ICON_WH, 'width' => ICON_WH));?></a></span>
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
if (adminka::access('forum_add_forum')) {
	?>
	<hr>
	<div class="mod">
		<? echo imgs::show("add1.png", array('class' => ICON_CLASS, 'height' => ICON_WH, 'width' => ICON_WH));?> <a href="/forum/create/forum">Создать подфорум</a><br />
	</div>
	<?
}
include(FOOT);
?>