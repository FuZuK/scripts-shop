<?
$title .= ' - Поиск топиков';
include(HEAD);
if (isset($_GET['q']))$search = $_GET['q'];
if (isset($_POST['q']))$search = $_POST['q'];
if (isset($search)) {
	$cr = $db -> res("SELECT COUNT(*) FROM `forum_topics` WHERE `them` LIKE ? OR `text` LIKE ?", array("%$search%", "%$search%"));
	$navi = new navi($cr, '?');
	$q =$db -> q("SELECT * FROM `forum_topics` WHERE `them` LIKE ? OR `text` LIKE ? ORDER BY `time` DESC LIMIT ".$navi ->start.", ".$set -> results_on_page, array("%$search%", "%$search%"));
	if (!$cr)doc::listEmpty("Ничего не найдено");
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
	echo $navi -> show;
}
?>
<hr>
<form action="?" method="POST" class="content">
	<input type="text" class="rad_tlr rad_blr main_inp" name="q" value=""> <input type="submit" class="rad_tlr rad_blr main_sub" name="sfsk" value="Поиск">
</form>
<?doc::back("Назад", "/forum")?>
<?
include(FOOT);
?>