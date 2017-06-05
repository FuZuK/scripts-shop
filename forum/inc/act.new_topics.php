<?
$title .= ' - Новые топикик';
include(HEAD);
$cr = $db -> res("SELECT COUNT(*) FROM `forum_topics` WHERE `time` > ?", array((time() - 3600 * 24)));
$navi = new navi($cr, '?');
$q =$db -> q("SELECT * FROM `forum_topics` WHERE `time` > ? ORDER BY `time` DESC LIMIT ".$navi ->start.", ".$set -> results_on_page, array((time() - 3600 * 24)));
if (!$cr)doc::listEmpty("Нет новых топиков");
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
?>
<?doc::back("Назад", "/forum")?>
<?
include(FOOT);
?>