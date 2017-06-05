<?
Users\User::if_user('is_reg');
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
$title .= ' - Создание топика';
include(HEAD);
$p = "forum_topic_new_".$cat -> id;
if (!isset($_SESSION[$p])) {
	$_SESSION[$p] = array(
		'them' => null,
		'text' => null,
	);
}
if (isset($_POST['pin_poll']) && ussec::check_p()) {
	adminka::accessCheck('forum_create_poll');
	$_SESSION[$p]['them'] = $_POST['them'];
	$_SESSION[$p]['text'] = $_POST['text'];
	header("Location: /forum/poll/new/".$cat -> id);
	exit();
}
if (isset($_POST['sfsk']) && ussec::check_p()) {
	$_SESSION[$p]['them'] = $_POST['them'];
	$_SESSION[$p]['text'] = $_POST['text'];
	$topic_new = $_SESSION[$p];
	if (TextUtils::length(trim($topic_new['them'])) < 1)$error = 'Введите тему топика';
	elseif (TextUtils::length($topic_new['them']) > 50)$error = 'Тема топика слишком длинная';
	elseif (TextUtils::length($topic_new['text']) < 1)$error = 'Введите текст топика';
	elseif (TextUtils::length($topic_new['text']) > 10000)$error = 'Текст топика слишком длинный';
	else {
		$db -> q("INSERT INTO `forum_topics` (`them`, `text`, `id_cat`, `time`, `id_user`) VALUES (?, ?, ?, ?, ?)", array($topic_new['them'], $topic_new['text'], $cat -> id, time(), $u -> id));
		$topic_id = $db -> lastInsertId();
		if (isset($_SESSION["new_poll_".$cat -> id]['pinned']) && adminka::access('forum_create_poll')) {
			$array_polltime = array('infin' => 'Бессрочное', 'day' => '1 День', '3days' => '3 Дня', 'week' => '1 Неделю', 'month' => '1 Месяц', '3months' => '3 Месяца');
			$array_polltime_num = array('infin' => 3600*24*31*12*3, 'day' => 3600*24, '3days' => 3600*24*3, 'week' => 3600*24*7, 'month' => 3600*24*31, '3months' => 3600*24*31*3);
			$db -> q("UPDATE `forum_topics` SET `poll` =?, `poll_timee` = ?, `poll_time` = ?, `poll_time_start` = ?, `poll_check` = ?, `poll_text` = ? WHERE `id` = ?", array(1, $_SESSION["new_poll_".$cat -> id]['polltime'], time() + $array_polltime_num[$_SESSION["new_poll_".$cat -> id]['polltime']], time(), $_SESSION["new_poll_".$cat -> id]['multi'], $_SESSION["new_poll_".$cat -> id]['text'], $topic_id));
			$num = 0;
			foreach ($_SESSION["new_poll_".$cat -> id]['vars'] as $key => $value) {
				if (TextUtils::length(trim($value))) {
					$num++;
					$db -> q("INSERT INTO `forum_poll_vars` (`id_topic`, `variant`, `num`) VALUES (?, ?, ?)", array($topic_id, $value, $num));
				}
			}
			unset($_SESSION["new_poll_".$cat -> id]);
			adminka::adminsLog("Форум", "Опросы", "Прикреплен опрос к топику \"[url=http://$_SERVER[HTTP_HOST]/forum/t/".$topic_id."]".$topic_new['them']."[/url]\"");
		}
		unset($_SESSION[$p]);
		alerts::msg_sess("Тема успешно создана");
		header("Location: /forum/t/".$topic_id);
		exit();
	}
}
echo alerts::error();
$topic_new = $_SESSION[$p];
?>
<form action="" method="POST" class="content">
	<span class="form_q">Тема:</span><br />
	<input type="text" class="main_inp rad_tlr rad_blr" name="them" value="<? echo TextUtils::DBFilter($topic_new['them']);?>">
	<span class="alert">Не больше 50-ти символов<br /></span>
	<span class="form_q">Текст:</span><br />
	<textarea name="text" class="main_inp rad_tlr rad_blr"><? echo TextUtils::DBFilter($topic_new['text']);?></textarea>
	<span class="alert">Не больше 10000 символов<br /></span>
	<?
	if (adminka::access('forum_create_poll')) {
		?>
		<div class="lh2">
			Прикрепить: <input type="submit" class="main_sub rad_tlr rad_blr" name="pin_poll" style="background: transparent; color: green; border: 0;" value="Опрос">
		</div>
		<?
	}
	echo ussec::input();?>
	<input type="submit" name="sfsk" class="main_sub rad_tlr rad_blr" value="Создать"> <a href="/smiles" class="hp_bb">Смайлы</a> <a href="/tags" class="hp_bb">Теги</a>
</form>
<?
if (isset($_SESSION["new_poll_".$cat -> id]['pinned']) && adminka::access('forum_create_poll')) {
	?>
	<hr>
	<div class="mod">
		<? echo imgs::show("poll_blue.png", array('class' => ICON_CLASS, 'height' => ICON_WH, 'width' => ICON_WH))?>Опрос прикреплен&nbsp;&nbsp;&nbsp;<a href="/forum/poll/new/<? echo $cat -> id?>?delete&<? echo ussec::link()?>"><? echo imgs::show("delete.png", array('class' => ICON_CLASS, 'height' => ICON_WH, 'width' => ICON_WH))?></a><br />
	</div>
	<?
}
doc::back("Назад", "/forum/c/{$cat -> id}");
include(FOOT);
?>